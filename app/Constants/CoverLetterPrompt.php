<?php

namespace App\Constants;

class CoverLetterPrompt
{
    private static array $prompts = [
        "Hello, my name is [Name]. I am excited to apply for the [JobTitle] position at [CompanyName].
        The job description provided is as follows: [JobDescription].
        I have [Experience] of experience in related roles, and my skill set includes [Skills]. Please create an ATS-friendly cover letter for me.",

        "Dear Hiring Manager at [CompanyName], my name is [Name], and I am writing to express my interest in the [JobTitle] role.
        Based on the job description [JobDescription], I believe my [Experience] of experience and skills in [Skills] make me a strong candidate.
        Could you assist in drafting an ATS-compliant cover letter for this position?",

        "Greetings [CompanyName] team, I am [Name], eager to apply for the [JobTitle] position.
        The job details include [JobDescription], and I bring [Experience] years of experience and skills in [Skills] to the table.
        I need an ATS-optimized cover letter to support my application. Thank you!",

        "Hi [CompanyName], I am [Name], applying for the [JobTitle] role. With [Experience] of relevant experience and expertise in [Skills],
        I am confident in my ability to contribute effectively. The job description is as follows: [JobDescription].
        Please help me create an ATS-friendly cover letter.",

        "Dear [CompanyName], I am [Name], and I wish to apply for the [JobTitle] position.
        My professional journey includes [Experience] years of experience and proficiency in [Skills].
        The job description is [JobDescription]. Could you generate an ATS-friendly cover letter for my application?",

        "Hello [CompanyName] recruitment team, my name is [Name], interested in the [JobTitle] position.
        According to the job description [JobDescription], I have [Experience] years of relevant experience and skills in [Skills].
        I seek your help in crafting an ATS-compliant cover letter."
    ];

    public static function getRandomPrompt()
    {
        return self::$prompts[array_rand(self::$prompts)];
    }

}
