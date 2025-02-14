<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Geezap Weekly Digest</title>
</head>
<body style="background-color: #f3f4f6; margin: 0; padding: 32px 0; font-family: system-ui, -apple-system, sans-serif;">
<div style="max-width: 672px; margin: 0 auto; background-color: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;">
    <!-- Header -->
    <div style="background-color: #12122b; padding: 32px 24px; text-align: center;">
        <h2 style="margin: 0; color: white; font-size: 24px; font-weight: 700;">Hello, {{$user->name}}</h2>
        <h4 style="margin: 8px 0; color: white; font-size: 20px; font-weight: 500;">Weekly Job Digest</h4>
        <p style="margin: 8px 0 0; color: #d1d5db;">{{ $jobCount }} New Opportunities This Week</p>
    </div>

    <!-- Content -->
    <div style="padding: 32px 24px;">
        <div style="display: flex; flex-direction: column; gap: 24px;">
            @foreach($jobs->take(5) as $job)
                <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        @if($job->employer_logo)
                            <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover;">
                        @endif
                        <div>
                            <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111827;">{{ $job->job_title }}</h3>
                            <p style="margin: 4px 0 0; color: #4b5563;">{{ $job->employer_name }}</p>
                        </div>
                    </div>

                    <div style="margin-top: 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i style="color: #ec4899;">📍</i>
                            <span style="color: #4b5563;">{{ $job->is_remote ? 'Remote' : $job->city }}</span>
                        </div>

                        @if($job->min_salary && $job->max_salary)
                            <div style="color: #ec4899; font-weight: 500;">
                                ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} -
                                ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- CTA Button -->
        <div style="margin-top: 32px; text-align: center;">
            <a href="{{ route('job.index') }}" style="display: inline-block; padding: 12px 24px; background-color: #ec4899; color: white; font-weight: 500; text-decoration: none; border-radius: 8px;">
                View All Jobs
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div style="background-color: #f9fafb; padding: 16px 24px; text-align: center; color: #4b5563; border-top: 1px solid #e5e7eb;">
        <p style="margin: 0;">Best regards,</p>
        <p style="margin: 4px 0 0; font-weight: 500;">The {{ config('app.name') }} Team</p>
    </div>
</div>
</body>
</html>
