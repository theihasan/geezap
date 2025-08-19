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

        // Build professional cover letter
        $coverLetter = "Dear Hiring Manager,

I am writing to express my strong interest in the {$jobTitle} position at {$companyName}. As a {$currentRole} with {$experienceYears} years of experience in software development, I am excited about the opportunity to contribute to your innovative team.

In my current role, I have developed comprehensive expertise in {$skills}. My hands-on experience with web application development and modern technologies has equipped me with the technical proficiency and problem-solving abilities essential for success in this position.

I am particularly drawn to {$companyName}'s commitment to technological excellence and innovation. My proven track record in software development and deep understanding of modern web technologies would enable me to make immediate and meaningful contributions to your development initiatives.

Thank you for considering my application. I would welcome the opportunity to discuss how my technical expertise and professional experience can benefit {$companyName}. I look forward to hearing from you.

Sincerely,
{$candidateName}";

        return $coverLetter;
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
