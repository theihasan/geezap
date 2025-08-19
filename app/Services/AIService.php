<?php

namespace App\Services;

use App\Exceptions\DailyChatLimitExceededException;
use App\Exceptions\OpenAPICreditExceedException;
use App\Exceptions\OpenAIApiKeyInvalidException;
use App\Models\Airesponse;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    const DAILY_LIMIT = 20;

    private function checkUserLimit(User $user): bool
    {
        $todayResponses = Airesponse::query()
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        return $todayResponses < self::DAILY_LIMIT;
    }

    /**
     * Generate cover letter with streaming response
     * @throws \Throwable
     */
    public function generateCoverLetter(User $user, array $jobData, ?string $feedback = null, ?string $previousLetter = null): \Generator
    {
        throw_if(!$this->checkUserLimit($user), new DailyChatLimitExceededException("You've reached your daily limit of " . self::DAILY_LIMIT . " cover letter generations"));

        // Use template-based approach for reliability
        $coverLetter = $this->generateTemplateCoverLetter($user, $jobData, $feedback, $previousLetter);

        Airesponse::create([
            'user_id' => $user->id,
            'response' => $coverLetter,
            'job_id' => $jobData['id'] ?? null
        ]);

        // Simulate streaming by yielding word by word for UI effect
        $words = explode(' ', $coverLetter);

        foreach ($words as $word) {
            yield $word . ' ';
            usleep(50000);
        }
    }

    private function buildMessages(User $user, array $jobData, ?string $feedback, ?string $previousLetter): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are an expert professional business writer with 20+ years of experience writing executive-level cover letters for Fortune 500 companies. Your writing must be flawless, sophisticated, and compelling. CRITICAL REQUIREMENTS: 1) Every sentence must be grammatically perfect and complete 2) Use sophisticated business vocabulary 3) Ensure perfect spelling and punctuation 4) Create compelling, professional narratives 5) Zero tolerance for incomplete sentences, missing words, or grammatical errors. Write at the level of a Harvard Business Review article.'
            ]
        ];

        if ($feedback && $previousLetter) {
            // Add conversation context for feedback
            $messages[] = ['role' => 'user', 'content' => $this->buildPrompt($user, $jobData)];
            $messages[] = ['role' => 'assistant', 'content' => $previousLetter];
            $messages[] = ['role' => 'user', 'content' => "Please improve this cover letter based on this feedback: {$feedback}. Keep the same professional tone but incorporate the requested changes."];
        } else {
            $messages[] = ['role' => 'user', 'content' => $this->buildPrompt($user, $jobData)];
        }

        return $messages;
    }

    private function buildPrompt(User $user, array $jobData): string
    {
        $skills = $this->formatSkills($user->skills);
        $experience = $this->formatExperience($user->experience);

        return <<<PROMPT
        TASK: Write a flawless, professional cover letter that will impress any hiring manager.

        JOB DETAILS:
        Position: {$jobData['job_title']}
        Company: {$jobData['employer_name']}
        Job Description: {$jobData['description']}

        CANDIDATE DETAILS:
        Name: {$user->name}
        Current Position: {$user->occupation}
        Technical Skills: {$skills}
        Work Experience: {$experience}
        Professional Background: {$user->bio}

        MANDATORY REQUIREMENTS FOR YOUR WRITING:
        ✓ Start with "Dear Hiring Manager,"
        ✓ Every sentence must be grammatically perfect and complete
        ✓ Use sophisticated, professional business language
        ✓ Mention the exact job title and company name
        ✓ Highlight 2-3 most relevant skills that match the job requirements
        ✓ Write exactly 4 paragraphs with perfect structure
        ✓ End with "Sincerely," followed by the candidate's name
        ✓ Zero incomplete sentences or missing words
        ✓ Zero grammatical errors or typos
        ✓ Ready to submit to any Fortune 500 company

        EXAMPLE OF PERFECT STRUCTURE:
        "Dear Hiring Manager,

        I am writing to express my strong interest in the [exact position title] position at [exact company name]. As a [current role] with [number] years of experience in [relevant field], I am excited about the opportunity to contribute my expertise to your distinguished team.

        In my current role as [position] at [company], I have developed comprehensive expertise in [skill 1], [skill 2], and [skill 3]. My hands-on experience with [specific technologies mentioned in job] has equipped me with the technical proficiency and problem-solving abilities essential for success in this position.

        I am particularly drawn to [company name]'s commitment to [something specific from job description]. My proven track record in [relevant achievement] and deep understanding of [relevant technology/field] would enable me to make immediate and meaningful contributions to your development initiatives.

        Thank you for considering my application. I would welcome the opportunity to discuss how my technical expertise and professional experience can benefit [company name]. I look forward to hearing from you.

        Sincerely,
        [Full Name]"

        NOW WRITE THE ACTUAL COVER LETTER using the candidate's real information. Make every word perfect:
        PROMPT;
    }

    private function formatSkills($skills): string
    {
        if (is_string($skills)) {
            return $skills;
        }

        if (is_array($skills) && isset($skills['skill'])) {
            // Handle the specific format: {"skill":["PHP","Laravel"],"skill_level":["proficient","proficient"]}
            return implode(', ', $skills['skill']);
        }

        if (is_array($skills)) {
            return implode(', ', $skills);
        }

        return $skills ?? 'Various technical skills';
    }

    private function formatExperience($experience): string
    {
        if (is_string($experience)) {
            return $experience;
        }

        if (!is_array($experience)) {
            return $experience ?? '';
        }

        // Handle the specific format: {"company_name":["FIGLAB","CodeThinker"],"position":["Software Engineer","Software Engineer"]}
        if (isset($experience['company_name'], $experience['position'])) {
            $formatted = [];
            $companies = $experience['company_name'];
            $positions = $experience['position'];

            for ($i = 0; $i < count($companies); $i++) {
                if (isset($companies[$i], $positions[$i])) {
                    $formatted[] = $positions[$i] . ' at ' . $companies[$i];
                }
            }
            return implode(', ', $formatted);
        }

        // Handle traditional array format
        $formatted = [];
        foreach ($experience as $exp) {
            if (isset($exp['title'], $exp['company'])) {
                $duration = '';
                if (isset($exp['start_date'])) {
                    $duration = $exp['start_date'];
                    if (isset($exp['end_date'])) {
                        $duration .= " - " . $exp['end_date'];
                    }
                }
                $formatted[] = "{$exp['title']} at {$exp['company']}" . ($duration ? " ({$duration})" : '');
            }
        }
        return implode(', ', $formatted);
    }

    private function cleanCoverLetter(string $text): string
    {
        // Basic cleanup
        $text = trim($text);

        // Fix obvious spacing issues
        $text = preg_replace('/\s+/', ' ', $text); // Multiple spaces to single
        $text = preg_replace('/([a-z])([A-Z])/', '$1 $2', $text); // CamelCase spacing
        $text = preg_replace('/(\w)([.,;:!?])([A-Z])/', '$1$2 $3', $text); // Space after punctuation

        // Fix common AI errors
        $text = preg_replace('/\bI ([a-z])/', 'I am $1', $text); // "I passionate" → "I am passionate"
        $text = preg_replace('/\bwith(\d)/', 'with $1', $text); // "with3" → "with 3"
        $text = preg_replace('/\b([A-Z])([a-z]+)\)/', '$1$2)', $text); // Fix parentheses

        // Ensure proper greeting
        if (!str_starts_with($text, 'Dear')) {
            $text = 'Dear Hiring Manager,

' . $text;
        }

        // Ensure proper closing
        if (!str_contains($text, 'Sincerely,')) {
            $text .= '

Sincerely,
[Name]';
        }

        // Validate quality - if it's still broken, return a fallback
        if ($this->isTextBroken($text)) {
            return $this->getFallbackCoverLetter();
        }

        return $text;
    }

    private function isTextBroken(string $text): bool
    {
        // Check for obvious signs of broken text
        $brokenPatterns = [
            '/\b[A-Z][a-z]*\)/', // Words ending with parentheses incorrectly
            '/\b[A-Z]{2,}[a-z]/', // Weird capitalization like "PHPLaravel"
            '/\s{3,}/', // Multiple spaces
            '/[a-z][A-Z]/', // Missing spaces between words
            '/\b[a-z]+[A-Z]/', // CamelCase words
        ];

        foreach ($brokenPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        // Check for incomplete sentences (very basic)
        $sentences = explode('.', $text);
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($sentence) > 10 && !preg_match('/\b(I|The|My|In|This|Thank)\b/', $sentence)) {
                return true; // Likely incomplete sentence
            }
        }

        return false;
    }

    private function generateTemplateCoverLetter(User $user, array $jobData, ?string $feedback = null, ?string $previousLetter = null): string
    {
        // Extract relevant information
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $currentRole = $user->occupation ?? 'Software Developer';
        $skills = $this->formatSkills($user->skills);

        // Determine years of experience
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');

        // Handle feedback and generate variations
        if ($feedback && $previousLetter) {
            return $this->generateVariationBasedOnFeedback($user, $jobData, $feedback, $previousLetter);
        }

        // Generate different variations for initial requests
        $variations = $this->getCoverLetterVariations($user, $jobData);
        
        // Use a simple hash of user ID and job ID to get consistent but different variations
        $variationIndex = (int)($user->id + ($jobData['id'] ?? 0)) % count($variations);
        
        return $variations[$variationIndex];
    }

    private function generateVariationBasedOnFeedback(User $user, array $jobData, string $feedback, string $previousLetter): string
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $currentRole = $user->occupation ?? 'Software Developer';
        $skills = $this->formatSkills($user->skills);
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');

        // Analyze feedback to determine what changes to make
        $feedbackLower = strtolower($feedback);
        
        if (str_contains($feedbackLower, 'friendly') || str_contains($feedbackLower, 'casual')) {
            return $this->generateFriendlyTone($user, $jobData);
        }
        
        if (str_contains($feedbackLower, 'professional') || str_contains($feedbackLower, 'formal')) {
            return $this->generateProfessionalTone($user, $jobData);
        }
        
        if (str_contains($feedbackLower, 'shorter') || str_contains($feedbackLower, 'brief')) {
            return $this->generateShorterVersion($user, $jobData);
        }
        
        if (str_contains($feedbackLower, 'longer') || str_contains($feedbackLower, 'detailed')) {
            return $this->generateDetailedVersion($user, $jobData);
        }
        
        if (str_contains($feedbackLower, 'skills') || str_contains($feedbackLower, 'technical')) {
            return $this->generateSkillsFocused($user, $jobData);
        }
        
        if (str_contains($feedbackLower, 'experience') || str_contains($feedbackLower, 'background')) {
            return $this->generateExperienceFocused($user, $jobData);
        }

        // Default: generate a different variation
        return $this->generateAlternativeVersion($user, $jobData);
    }

    private function getCoverLetterVariations(User $user, array $jobData): array
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $currentRole = $user->occupation ?? 'Software Developer';
        $skills = $this->formatSkills($user->skills);
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');

        return [
            // Variation 1: Standard professional
            "Dear Hiring Manager,

I am writing to express my strong interest in the {$jobTitle} position at {$companyName}. As a {$currentRole} with {$experienceYears} years of experience in software development, I am excited about the opportunity to contribute to your innovative team.

In my current role, I have developed comprehensive expertise in {$skills}. My hands-on experience with web application development and modern technologies has equipped me with the technical proficiency and problem-solving abilities essential for success in this position.

I am particularly drawn to {$companyName}'s commitment to technological excellence and innovation. My proven track record in software development and deep understanding of modern web technologies would enable me to make immediate and meaningful contributions to your development initiatives.

Thank you for considering my application. I would welcome the opportunity to discuss how my technical expertise and professional experience can benefit {$companyName}. I look forward to hearing from you.

Sincerely,
{$candidateName}",

            // Variation 2: More personal approach
            "Dear Hiring Manager,

I am excited to submit my application for the {$jobTitle} position at {$companyName}. With {$experienceYears} years of experience as a {$currentRole}, I am confident that my technical skills and passion for innovation make me an ideal candidate for your team.

Throughout my career, I have cultivated strong expertise in {$skills}, consistently delivering high-quality solutions that drive business success. My experience in developing scalable applications and collaborating with cross-functional teams has prepared me to excel in this role.

What particularly attracts me to {$companyName} is your reputation for fostering innovation and technological advancement. I am eager to contribute my skills and fresh perspective to help achieve your development goals and continue your tradition of excellence.

I would be thrilled to discuss how my background and enthusiasm can contribute to {$companyName}'s continued success. Thank you for your time and consideration.

Sincerely,
{$candidateName}",

            // Variation 3: Results-focused
            "Dear Hiring Manager,

I am writing to apply for the {$jobTitle} position at {$companyName}. As an experienced {$currentRole} with {$experienceYears} years in the field, I have consistently delivered impactful solutions and driven meaningful results for my employers.

My technical expertise spans {$skills}, and I have successfully applied these skills to develop robust applications and improve system performance. My track record includes optimizing workflows, implementing best practices, and mentoring junior developers to achieve team objectives.

{$companyName}'s commitment to innovation and excellence aligns perfectly with my professional values and career aspirations. I am confident that my proven ability to deliver results and adapt to new technologies would make me a valuable addition to your development team.

I look forward to the opportunity to discuss how my experience and results-driven approach can contribute to {$companyName}'s success. Thank you for considering my application.

Sincerely,
{$candidateName}"
        ];
    }

    private function generateFriendlyTone(User $user, array $jobData): string
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $currentRole = $user->occupation ?? 'Software Developer';
        $skills = $this->formatSkills($user->skills);
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');

        return "Dear Hiring Team,

I hope this message finds you well! I'm reaching out to express my enthusiasm for the {$jobTitle} position at {$companyName}. As a passionate {$currentRole} with {$experienceYears} years of experience, I'm genuinely excited about the possibility of joining your team.

I've been fortunate to work with technologies like {$skills} throughout my career, and I truly enjoy the challenge of creating innovative solutions. My experience has taught me that great software comes from collaboration, creativity, and a genuine passion for problem-solving.

What draws me to {$companyName} is your company's reputation for innovation and the positive impact you're making in the industry. I'd love the opportunity to bring my skills and enthusiasm to contribute to your team's success.

I'd be delighted to chat more about how my experience and passion for technology can add value to {$companyName}. Thank you so much for your consideration!

Best regards,
{$candidateName}";
    }

    private function generateProfessionalTone(User $user, array $jobData): string
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $currentRole = $user->occupation ?? 'Software Developer';
        $skills = $this->formatSkills($user->skills);
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');

        return "Dear Hiring Manager,

I am writing to formally express my interest in the {$jobTitle} position at {$companyName}. With {$experienceYears} years of progressive experience as a {$currentRole}, I possess the technical acumen and professional dedication required to excel in this role.

My comprehensive expertise encompasses {$skills}, with a demonstrated history of delivering enterprise-level solutions that align with organizational objectives. I have consistently maintained the highest standards of code quality, system architecture, and project delivery throughout my professional tenure.

{$companyName}'s distinguished reputation for technological leadership and commitment to excellence represents an ideal environment for my continued professional growth. I am prepared to leverage my extensive experience to contribute meaningfully to your organization's strategic initiatives.

I would welcome the opportunity to discuss how my qualifications align with your requirements. Thank you for your consideration of my candidacy.

Respectfully,
{$candidateName}";
    }

    private function generateShorterVersion(User $user, array $jobData): string
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $skills = $this->formatSkills($user->skills);

        return "Dear Hiring Manager,

I am excited to apply for the {$jobTitle} position at {$companyName}. My expertise in {$skills} and passion for innovative technology solutions make me an ideal candidate for your team.

I am drawn to {$companyName}'s reputation for excellence and would love to contribute my skills to your continued success.

Thank you for your consideration. I look forward to discussing this opportunity further.

Sincerely,
{$candidateName}";
    }

    private function generateDetailedVersion(User $user, array $jobData): string
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $currentRole = $user->occupation ?? 'Software Developer';
        $skills = $this->formatSkills($user->skills);
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');
        $experience = $this->formatExperience($user->experience);

        return "Dear Hiring Manager,

I am writing to express my sincere interest in the {$jobTitle} position at {$companyName}. As a dedicated {$currentRole} with {$experienceYears} years of comprehensive experience in software development, I am excited about the opportunity to bring my expertise and passion to your esteemed organization.

Throughout my career, I have developed extensive proficiency in {$skills}, consistently applying these technologies to create robust, scalable solutions that drive business success. My professional journey includes {$experience}, where I have honed my ability to work effectively in diverse environments and tackle complex technical challenges.

In my current role, I have successfully led multiple projects from conception to deployment, consistently delivering high-quality results within budget and timeline constraints. My experience includes architecting systems, optimizing database performance, implementing security best practices, and mentoring junior developers to help them reach their full potential.

What particularly attracts me to {$companyName} is your organization's commitment to innovation, technical excellence, and positive impact in the industry. I am impressed by your recent achievements and would be thrilled to contribute to your continued growth and success. My approach combines technical expertise with strong problem-solving skills and a collaborative mindset that I believe would be valuable to your team.

I am confident that my technical skills, professional experience, and enthusiasm for continuous learning make me a strong candidate for this position. I would welcome the opportunity to discuss in detail how my background and expertise can contribute to {$companyName}'s objectives and help drive your technical initiatives forward.

Thank you for your time and consideration. I look forward to the possibility of joining your team and contributing to your continued success.

Sincerely,
{$candidateName}";
    }

    private function generateSkillsFocused(User $user, array $jobData): string
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $skills = $this->formatSkills($user->skills);
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');

        return "Dear Hiring Manager,

I am excited to apply for the {$jobTitle} position at {$companyName}. My {$experienceYears} years of hands-on experience with {$skills} have equipped me with the technical expertise necessary to excel in this role.

My technical proficiency spans full-stack development, with particular strength in {$skills}. I have successfully implemented these technologies to build scalable web applications, optimize system performance, and deliver innovative solutions that meet complex business requirements.

Beyond technical skills, I bring a strong foundation in software engineering principles, including test-driven development, code review processes, and agile methodologies. My ability to quickly adapt to new technologies and frameworks has consistently enabled me to contribute effectively to diverse projects.

I am particularly excited about the opportunity to apply my technical skills at {$companyName} and contribute to your innovative development initiatives. Thank you for considering my application.

Sincerely,
{$candidateName}";
    }

    private function generateExperienceFocused(User $user, array $jobData): string
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $currentRole = $user->occupation ?? 'Software Developer';
        $experience = $this->formatExperience($user->experience);
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');

        return "Dear Hiring Manager,

I am writing to express my interest in the {$jobTitle} position at {$companyName}. With {$experienceYears} years of progressive experience as a {$currentRole}, I have built a strong foundation in software development and project leadership.

My professional journey has taken me through various roles including {$experience}, where I have gained valuable insights into different aspects of software development, from initial planning and architecture to deployment and maintenance. This diverse experience has taught me the importance of writing clean, maintainable code and collaborating effectively with cross-functional teams.

Throughout my career, I have consistently taken on increasing responsibilities, leading projects, mentoring team members, and driving technical decisions that have resulted in successful product deliveries. My experience has taught me to balance technical excellence with business objectives, ensuring that solutions are both robust and practical.

I am excited about the opportunity to bring this wealth of experience to {$companyName} and contribute to your team's success. Thank you for your consideration.

Sincerely,
{$candidateName}";
    }

    private function generateAlternativeVersion(User $user, array $jobData): string
    {
        $jobTitle = $jobData['job_title'] ?? 'the position';
        $companyName = $jobData['employer_name'] ?? 'your company';
        $candidateName = $user->name ?? 'Candidate';
        $currentRole = $user->occupation ?? 'Software Developer';
        $skills = $this->formatSkills($user->skills);
        $experienceYears = $this->extractExperienceYears($user->bio ?? '');

        return "Dear Hiring Manager,

I was thrilled to discover the {$jobTitle} opening at {$companyName}. As a {$currentRole} with {$experienceYears} years of experience, I am confident that my technical background and passion for creating exceptional software solutions align perfectly with your team's needs.

My expertise in {$skills} has been instrumental in developing applications that not only meet functional requirements but also provide excellent user experiences. I thrive in collaborative environments where I can contribute to both technical discussions and creative problem-solving.

{$companyName}'s innovative approach to technology and commitment to quality resonates with my professional values. I am eager to bring my technical skills and fresh perspective to contribute to your continued success and growth.

I would be honored to discuss how my background and enthusiasm can add value to your team. Thank you for your time and consideration.

Sincerely,
{$candidateName}";
    }

    private function extractExperienceYears(string $bio): string
    {
        // Look for patterns like "3 years", "5+ years", etc.
        if (preg_match('/(\d+)\+?\s*years?/i', $bio, $matches)) {
            return $matches[1];
        }

        // Default fallback
        return '3';
    }

    private function getFallbackCoverLetter(): string
    {
        return "Dear Hiring Manager,

I am writing to express my interest in the position at your company. With my background in software development and technical expertise, I am excited about the opportunity to contribute to your team.

In my current role, I have developed strong skills in web development technologies and gained valuable experience in creating scalable applications. My technical proficiency and problem-solving abilities would be valuable assets to your organization.

I am particularly interested in your company's innovative approach to technology solutions. My experience and dedication to professional excellence would enable me to make meaningful contributions to your projects.

Thank you for considering my application. I look forward to discussing how my skills and experience can benefit your organization.

Sincerely,
[Candidate Name]";
    }
}
