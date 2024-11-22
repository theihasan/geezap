<?php

namespace App\Constants;

class CoverLetterPrompt
{
    private static array $prompts = [
        "Hi! I'm [Name] and I'm really excited about the [JobTitle] role at [CompanyName]. I'd love to share a bit about myself -
        I've got [Experience] of experience and I'm skilled in [Skills]. I've read through the job description: [JobDescription],
        and it feels like a perfect match! Could you help me write a great cover letter that shows why I'd be a fantastic addition
        to the team? (Please make it ATS-friendly too!)",

        "Hey there! My name is [Name], and I can't wait to apply for the [JobTitle] position at [CompanyName]!
        The role sounds amazing: [JobDescription]. With my [Experience] of experience and background in [Skills],
        I think I could bring something special to the team. Would you help me create a friendly but professional
        cover letter that really shows what I'm all about? (Making it ATS-friendly would be great!)",

        "Hi [CompanyName] team! [Name] here, and I'm super interested in your [JobTitle] position!
        The job looks fantastic: [JobDescription]. I've spent [Experience] working in this field, and I've gotten pretty good at [Skills].
        Could you help me write a warm, engaging cover letter that shows my enthusiasm and qualifications?
        (Something that works well with ATS systems would be perfect!)",

        "Hello! I'm [Name], and I'm thrilled about the chance to apply for the [JobTitle] role at [CompanyName]!
        After [Experience] in the field and developing skills in [Skills], I feel ready to take on this exciting opportunity: [JobDescription].
        Would you help me craft a friendly, authentic cover letter that lets my personality shine while highlighting my experience?
        (ATS-optimized, of course!)",

        "Hey [CompanyName]! [Name] here - I saw your [JobTitle] position and got really excited! The role sounds incredible: [JobDescription].
        I've spent [Experience] honing my craft and becoming skilled in [Skills]. Could you help me write a personable yet
        professional cover letter that shows why I'd be a great fit? (And make sure it's ATS-friendly too!)",

        "Hi there! This is [Name], and I'm genuinely excited about the [JobTitle] opportunity at [CompanyName]!
        The position looks perfect: [JobDescription]. With [Experience] under my belt and expertise in [Skills],
        I'd love to show how I could contribute. Could you help create a warm, engaging cover letter that tells my story?
        (While keeping it ATS-optimized!)",

        "Hello [CompanyName]! I'm [Name], and I'm really passionate about the [JobTitle] role you're offering!
        It sounds exactly like what I've been looking for: [JobDescription]. I bring [Experience] of real-world experience
        and love working with [Skills]. Would you help me write a genuine, heartfelt cover letter that shows my enthusiasm?
        (Making it ATS-friendly is important too!)",

        "Hey! [Name] here, and I'm super excited to apply for the [JobTitle] position at [CompanyName]!
        The role looks amazing: [JobDescription]. Through my [Experience] in the field, I've become really good at [Skills].
        Could you help me write a friendly, conversational cover letter that shows who I am and what I can bring?
        (While ensuring it works well with ATS!)",

        "Hi [CompanyName] team! I'm [Name], and I'd love to join you as your new [JobTitle]!
        The role sounds perfect: [JobDescription]. With [Experience] of hands-on experience and a strong background in [Skills],
        I think we could do great things together. Would you help me write a warm, professional cover letter that shows my potential?
        (ATS-optimized, please!)",

        "Hello there! [Name] here, and I'm excited about the [JobTitle] role at [CompanyName]!
        After reading the description - [JobDescription] - I can't wait to apply! I've spent [Experience] developing my skills,
        especially in [Skills]. Could you help me create a friendly, engaging cover letter that shows my enthusiasm and expertise?
        (While keeping it ATS-friendly!)",

        "Hi! This is [Name], and I'm thrilled about the [JobTitle] opportunity at [CompanyName]!
        The role looks fantastic: [JobDescription]. With [Experience] in similar roles and strong skills in [Skills],
        I'd love to show how I could contribute. Would you help me write a warm, genuine cover letter that lets my personality shine?
        (Making it ATS-compliant too!)",

        "Hey [CompanyName]! [Name] here, and I'm really excited about your [JobTitle] position!
        What you're looking for sounds amazing: [JobDescription]. I bring [Experience] of experience and love working with [Skills].
        Could you help me craft a friendly, professional cover letter that shows why we'd be a great match?
        (While ensuring it works with ATS systems!)",

        "Hello! I'm [Name], and I can't wait to apply for the [JobTitle] role at [CompanyName]!
        The position sounds incredible: [JobDescription]. After [Experience] in the field and developing expertise in [Skills],
        I feel ready for this new challenge. Would you help me write a personable cover letter that tells my story?
        (ATS-optimized, of course!)",

        "Hi there! [Name] here - I'm really interested in the [JobTitle] position at [CompanyName]!
        The role looks perfect: [JobDescription]. I've spent [Experience] working in this area and really enjoy [Skills].
        Could you help me create a warm, engaging cover letter that shows my enthusiasm and qualifications?
        (While making it ATS-friendly!)",

        "Hey! I'm [Name], and I'm genuinely excited about the [JobTitle] opportunity at [CompanyName]!
        Everything about it sounds amazing: [JobDescription]. With [Experience] of experience and a passion for [Skills],
        I think I could bring something special. Would you help me write a friendly, authentic cover letter that shows who I am?
        (Keeping it ATS-optimized too!)",

        "Hi [CompanyName]! This is [Name], and I'm thrilled about your [JobTitle] position!
        The role sounds exactly like what I've been looking for: [JobDescription]. Throughout my [Experience] journey,
        I've become skilled at [Skills]. Could you help me craft a warm, professional cover letter that tells my story?
        (While ensuring it works well with ATS systems!)"
    ];

    public static function getRandomPrompt(): string
    {
        $prompts = self::$prompts;
        shuffle($prompts);

        return $prompts[array_rand($prompts)];
    }
}
