<?php

namespace Geezap\ContentFormatter\Jobs;

use App\Models\JobListing;
use App\Models\JobCategory;
use Geezap\ContentFormatter\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\Schema\IntegerSchema;
use Prism\Prism\Schema\BooleanSchema;
use Prism\Prism\Schema\ArraySchema;

class FormatContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Batchable;
    
    public $tries = 3;
    public $timeout = 60; 
    public $maxExceptions = 3;
    public $backoff = [10, 30, 60];
    
    public function __construct(
        public int $packageId
    ) {
        Log::info('FormatContentJob: Job created', [
            'package_id' => $this->packageId
        ]);
    }

    public function handle(): void
    {
        Log::info('FormatContentJob: Starting job processing', [
            'package_id' => $this->packageId,
            'attempt' => $this->attempts()
        ]);

        try {
            $package = Package::query()->findOrFail($this->packageId);
            $package->update(['status' => 'processing']);
            Log::info('FormatContentJob: Updated package status to processing', [
                'package_id' => $package->id
            ]);

            $prompt = $this->buildFallbackPrompt($package->content, $package->apply_link);
            Log::info('FormatContentJob: Prompt built', [
                'prompt_length' => strlen($prompt),
                'content_preview' => substr($package->content, 0, 100) . '...',
                'apply_link' => $package->apply_link
            ]);

            Log::info('FormatContentJob: Calling Prism API with text-based approach');
            
            $textResponse = Prism::text()
                ->using(Provider::DeepSeek, 'deepseek-chat')
                ->withPrompt($prompt)
                ->asText();
            
            Log::info('FormatContentJob: Text response received', [
                'response_length' => strlen($textResponse->text),
                'response_preview' => substr($textResponse->text, 0, 200)
            ]);
            
            $structuredData = $this->parseJsonResponse($textResponse->text);
            $response = (object) ['structured' => $structuredData];

            $jobData = $this->processStructuredResponse($response->structured);
            Log::info('FormatContentJob: Structured data processed', [
                'job_data_fields' => array_keys($jobData),
                'job_title' => $jobData['job_title'] ?? 'N/A'
            ]);

            $jobListing = JobListing::query()->create($jobData);
            Log::info('FormatContentJob: JobListing created', [
                'job_listing_id' => $jobListing->id,
                'job_title' => $jobListing->job_title
            ]);

            // Update package status to completed
            $package->update([
                'status' => 'completed',
                'formatted_content' => json_encode($response->structured, JSON_PRETTY_PRINT),
                'metadata' => [
                    'job_listing_id' => $jobListing->id,
                    'processed_at' => now(),
                    'api_provider' => 'deepseek-chat',
                    'schema_version' => '1.0'
                ],
            ]);

            Log::info('FormatContentJob: Job completed successfully', [
                'package_id' => $package->id,
                'job_listing_id' => $jobListing->id,
                'processing_time' => now()->diffInSeconds($package->updated_at)
            ]);

        } catch (\Exception $e) {
            Log::error('FormatContentJob: Job failed', [
                'package_id' => $this->packageId,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'attempt' => $this->attempts()
            ]);

            Package::query()->where('id', $this->packageId)->update([
                'status' => 'failed',
                'metadata' => [
                    'error' => $e->getMessage(),
                    'failed_at' => now(),
                    'attempt' => $this->attempts(),
                    'error_trace' => $e->getTraceAsString()
                ],
            ]);

            throw $e;
        }
    }

    private function createJobListingSchema(): ObjectSchema
    {
        Log::info('FormatContentJob: Creating job listing schema');
        
        return new ObjectSchema(
            name: 'job_listing',
            description: 'A structured job listing with all required fields',
            properties: [
                new StringSchema(
                    name: 'job_title',
                    description: 'The job title or position name'
                ),
                new StringSchema(
                    name: 'employer_name',
                    description: 'The name of the hiring company or organization'
                ),
                new StringSchema(
                    name: 'description',
                    description: 'Detailed job description and requirements'
                ),
                new StringSchema(
                    name: 'employment_type',
                    description: 'Type of employment (full-time, part-time, contract, internship, etc.)'
                ),
                new StringSchema(
                    name: 'city',
                    description: 'City where the job is located (if not remote)'
                ),
                new StringSchema(
                    name: 'state',
                    description: 'State or province where the job is located'
                ),
                new StringSchema(
                    name: 'country',
                    description: 'Country code where the job is located. Default BD'
                ),
                new BooleanSchema(
                    name: 'is_remote',
                    description: 'Whether this is a remote work position'
                ),
                new ArraySchema(
                    name: 'benefits',
                    description: 'List of job benefits and perks',
                    items: new StringSchema('benefit', 'A single benefit or perk')
                ),
                new ArraySchema(
                    name: 'qualifications',
                    description: 'Required qualifications and skills',
                    items: new StringSchema('qualification', 'A single qualification requirement')
                ),
                new ArraySchema(
                    name: 'responsibilities',
                    description: 'Job responsibilities and duties',
                    items: new StringSchema('responsibility', 'A single job responsibility')
                ),
                new IntegerSchema(
                    name: 'min_salary',
                    description: 'Minimum salary amount (optional, 0 if not specified)'
                ),
                new IntegerSchema(
                    name: 'max_salary',
                    description: 'Maximum salary amount (optional, 0 if not specified)'
                ),
                new StringSchema(
                    name: 'salary_currency',
                    description: 'Currency code (USD, EUR, etc.) or "N/A" if not specified'
                ),
                new StringSchema(
                    name: 'salary_period',
                    description: 'Salary period (yearly, monthly, hourly, etc.) or "N/A" if not specified'
                ),
                new IntegerSchema(
                    name: 'job_category',
                    description: 'Job category ID (1-15, use best judgment based on role type)'
                )
            ],
            requiredFields: [
                'job_title', 
                'employer_name', 
                'description', 
                'employment_type', 
                'city', 
                'state', 
                'country', 
                'is_remote',
                'job_category'
            ]
        );
    }

    private function buildPrompt(string $content): string
    {
        Log::info('FormatContentJob: Building structured prompt');
        
        return "Extract job information from the following content and structure it according to the provided schema. 

IMPORTANT INSTRUCTIONS:
- Extract all available information from the job posting
- For missing information, use reasonable defaults:
  - If location is not specified but mentions remote, set is_remote=true and use 'Remote' for city
  - If no salary mentioned, set min_salary=0, max_salary=0, currency='N/A', period='N/A'
  - If no benefits mentioned, return empty array
  - Choose appropriate job_category (1-15): 1=Tech, 2=Marketing, 3=Sales, 4=HR, 5=Finance, 6=Operations, 7=Design, 8=Customer Service, 9=Healthcare, 10=Education, 11=Legal, 12=Construction, 13=Retail, 14=Manufacturing, 15=Other
- Break down responsibilities and qualifications into separate array items
- Ensure all required fields are populated
-If country not found then default country code will be BD
- If no direct apply link found then look for an email address or contact information and add it to description and apply link

JOB CONTENT TO EXTRACT:
{$content}";
    }
    
    private function processStructuredResponse(array $structuredData): array
    {
        Log::info('FormatContentJob: Processing structured response', [
            'structured_fields' => array_keys($structuredData)
        ]);

        $requiredFields = ['job_title', 'employer_name', 'description', 'employment_type'];
        foreach ($requiredFields as $field) {
            if (empty($structuredData[$field])) {
                Log::warning("FormatContentJob: Missing required field: {$field}");
                throw new \Exception("Missing required field: {$field}");
            }
        }

        // Handle apply link - use provided apply link or fallback to extracted contact info
        $applyLink = $package->apply_link ?? '#';
        if ($applyLink === '#' || empty($applyLink)) {
            if (!empty($structuredData['application_url'])) {
                $applyLink = $structuredData['application_url'];
                Log::info('FormatContentJob: Using extracted application URL', ['url' => $applyLink]);
            } elseif (!empty($structuredData['contact_email'])) {
                $applyLink = 'mailto:' . $structuredData['contact_email'];
                Log::info('FormatContentJob: Using extracted contact email', ['email' => $structuredData['contact_email']]);
            } else {
                Log::warning('FormatContentJob: No contact information found, using default #');
            }
        } else {
            Log::info('FormatContentJob: Using provided apply link', ['apply_link' => $applyLink]);
        }

        // Apply defaults and normalize data
        $defaults = [
            'publisher' => 'Content Formatter',
            'job_id' => uniqid('cf_'),
            'posted_at' => now(),
            'expired_at' => now()->addDays(30),
            'employer_company_type' => 'Unknown',
            'apply_link' => $applyLink,
            'required_experience' => 0,
        ];

        // Normalize arrays - ensure they are arrays even if empty
        $arrayFields = ['benefits', 'qualifications', 'responsibilities'];
        foreach ($arrayFields as $field) {
            if (!isset($structuredData[$field]) || !is_array($structuredData[$field])) {
                $structuredData[$field] = [];
                Log::info("FormatContentJob: Normalized {$field} to empty array");
            }
        }

        // Handle salary fields
        if (!isset($structuredData['min_salary']) || $structuredData['min_salary'] === 'N/A') {
            $structuredData['min_salary'] = null;
        }
        if (!isset($structuredData['max_salary']) || $structuredData['max_salary'] === 'N/A') {
            $structuredData['max_salary'] = null;
        }
        if (!isset($structuredData['salary_currency']) || $structuredData['salary_currency'] === 'N/A') {
            $structuredData['salary_currency'] = null;
        }
        if (!isset($structuredData['salary_period']) || $structuredData['salary_period'] === 'N/A') {
            $structuredData['salary_period'] = null;
        }

        // Ensure boolean fields are proper booleans
        $structuredData['is_remote'] = (bool) ($structuredData['is_remote'] ?? false);

        // Merge with defaults
        $finalData = array_merge($defaults, $structuredData);

        Log::info('FormatContentJob: Final processed data', [
            'job_title' => $finalData['job_title'],
            'employer_name' => $finalData['employer_name'],
            'employment_type' => $finalData['employment_type'],
            'is_remote' => $finalData['is_remote'],
            'job_category' => $finalData['job_category'],
            'benefits_count' => count($finalData['benefits']),
            'qualifications_count' => count($finalData['qualifications']),
            'responsibilities_count' => count($finalData['responsibilities'])
        ]);

        return $finalData;
    }

    private function buildFallbackPrompt(string $content, ?string $applyLink = null): string
    {
        Log::info('FormatContentJob: Building fallback JSON prompt');
        
        // Get available job categories from database
        $categories = JobCategory::all(['id', 'name'])->map(function ($category) {
            return $category->id . '=' . $category->name;
        })->implode(', ');
        
        Log::info('FormatContentJob: Retrieved job categories', [
            'categories_count' => JobCategory::count(),
            'categories' => $categories
        ]);
        
        $applyLinkInstruction = '';
        if (!empty($applyLink) && $applyLink !== '#') {
            $applyLinkInstruction = "\n\nAPPLY LINK PROVIDED: {$applyLink} - Use this as the primary application method.";
        }
        
        return "Extract job information from the following content and return ONLY a valid JSON object with these exact fields:

{
  \"job_title\": \"string - the job title\",
  \"employer_name\": \"string - company name\",
  \"description\": \"string - job description\",
  \"employment_type\": \"string - full-time/part-time/contract/etc\",
  \"city\": \"string - city name or Remote\",
  \"state\": \"string - state/province\",
  \"country\": \"string - country name\",
  \"is_remote\": true/false,
  \"benefits\": [\"string array of benefits\"],
  \"qualifications\": [\"string array of requirements\"],
  \"responsibilities\": [\"string array of duties\"],
  \"min_salary\": 0,
  \"max_salary\": 0,
  \"salary_currency\": \"USD\",
  \"salary_period\": \"yearly\",
  \"job_category\": 1,
  \"contact_email\": \"string - application email address if found\",
  \"application_url\": \"string - application URL if found\",
  \"contact_phone\": \"string - phone number if found\"
}

AVAILABLE JOB CATEGORIES - Select the most appropriate ID based on job content:
{$categories}

INSTRUCTIONS:
- Analyze the job content carefully and select the most appropriate job_category ID
- Extract ALL contact information including emails, URLs, and phone numbers
- If no salary information is available, use 0 for salary amounts
- If country is not specified, default to 'BD'
- If employer_name is not found, use 'Not Specified' as default
- If employment_type is not clear, use 'full-time' as default
- Break down responsibilities and qualifications into separate array items
- Ensure ALL required fields are populated{$applyLinkInstruction}

JOB CONTENT:
{$content}

Return ONLY the JSON object, no other text.";
    }

    private function parseJsonResponse(string $response): array
    {
        Log::info('FormatContentJob: Parsing JSON response', [
            'response_length' => strlen($response),
            'response_start' => substr($response, 0, 100)
        ]);

        $response = trim($response);
        
        $jsonStart = strpos($response, '{');
        $jsonEnd = strrpos($response, '}');
        
        if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
            $jsonString = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
            Log::info('FormatContentJob: Extracted JSON string', [
                'json_length' => strlen($jsonString),
                'json_preview' => substr($jsonString, 0, 200)
            ]);
        } else {
            $jsonString = $response;
        }

        try {
            $data = json_decode($jsonString, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('FormatContentJob: JSON decode failed', [
                    'json_error' => json_last_error_msg(),
                    'json_string' => $jsonString
                ]);
                throw new \Exception('Invalid JSON response: ' . json_last_error_msg());
            }

            Log::info('FormatContentJob: JSON parsed successfully', [
                'parsed_fields' => array_keys($data)
            ]);

            return $data;
            
        } catch (\Exception $e) {
            Log::error('FormatContentJob: Failed to parse JSON response', [
                'error' => $e->getMessage(),
                'response' => $response
            ]);
            throw $e;
        }
    }
    
    public function middleware(): array
    {
        return [
            new ThrottlesExceptions(
                maxAttempts: 2,
                decaySeconds: 10
            )
        ];
    }
}
