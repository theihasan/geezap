<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Geezap Weekly Digest</title>
    <!--[if mso]>
    <style type="text/css">
        table {border-collapse: collapse;}
        .mso-button {padding: 12px 24px !important;}
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f4f5f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased; font-size: 16px; line-height: 1.4; color: #333333; width: 100%;">
    <!-- Preheader text (hidden) -->
    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        {{ $jobCount }} new job opportunities waiting for you this week - Geezap Weekly Digest
    </div>
    
    <!-- Email container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px; margin: 0 auto;">
        <!-- Header section -->
        <tr>
            <td style="padding: 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="background-color: #12122b; padding: 30px 20px; text-align: center; border-radius: 8px 8px 0 0;">
                            <img src="{{ asset('images/logo.png') }}" alt="Geezap" width="120" style="max-width: 120px; height: auto; margin-bottom: 15px;">
                            <h1 style="margin: 0; color: white; font-size: 24px; font-weight: 600;">Hello, {{$user->name}}!</h1>
                            <p style="margin: 10px 0 0; color: #d1d5db; font-size: 16px;">{{ $jobCount }} New Opportunities This Week</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Intro section -->
        <tr>
            <td style="background-color: white; padding: 25px 20px 15px; text-align: center; border-bottom: 1px solid #e5e7eb;">
                <h2 style="margin: 0; color: #12122b; font-size: 20px; font-weight: 600;">Your Weekly Job Digest</h2>
                <p style="margin: 10px 0 0; color: #4b5563;">We've found some great opportunities that match your preferences.</p>
            </td>
        </tr>
        
        <!-- Job listings -->
        <tr>
            <td style="background-color: white; padding: 0 20px;">
                @foreach($jobs->take(20) as $job)
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 20px; border-bottom: 1px solid #e5e7eb; padding-bottom: 20px;">
                    <tr>
                        <td width="60" style="vertical-align: top; padding-right: 15px;">
                            @if($job->employer_logo)
                            <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" width="50" height="50" style="border-radius: 6px; object-fit: cover; border: 1px solid #e5e7eb;">
                            @else
                            <div style="width: 50px; height: 50px; background-color: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: 1px solid #e5e7eb;">
                                <span style="color: #9ca3af; font-size: 18px;">{{ substr($job->employer_name, 0, 1) }}</span>
                            </div>
                            @endif
                        </td>
                        <td style="vertical-align: top;">
                            @if($job->slug)
                            <a href="{{route('job.show', ['slug' => $job->slug])}}" style="text-decoration: none; color: #12122b; font-weight: 600; font-size: 18px; display: inline-block; margin-bottom: 5px;">{{ $job->job_title }}</a>
                            @else
                            <span style="color: #12122b; font-weight: 600; font-size: 18px; display: inline-block; margin-bottom: 5px;">{{ $job->job_title }}</span>
                            @endif
                            <p style="margin: 0 0 8px; color: #4b5563; font-size: 15px;">{{ $job->employer_name }}</p>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 0;">
                                        <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                            <span style="display: inline-block; margin-right: 15px;">
                                                <img src="{{ asset('assets/images/location-icon.png') }}" alt="Location" width="12" height="12" style="vertical-align: middle; margin-right: 5px;">{{ $job->is_remote ? 'Remote' : $job->city }}
                                            </span>
                                            @if($job->min_salary && $job->max_salary)
                                            <span style="display: inline-block; color: #ec4899;">
                                                <img src="{{ asset('assets/images/salary-icon.png') }}" alt="Salary" width="12" height="12" style="vertical-align: middle; margin-right: 5px;">${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} - ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }}
                                            </span>
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                @endforeach
            </td>
        </tr>
        
        <!-- CTA section -->
        <tr>
            <td style="background-color: white; padding: 25px 20px 30px; text-align: center; border-radius: 0 0 8px 8px;">
                <!--[if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ route('job.index') }}" style="height:45px;v-text-anchor:middle;width:200px;" arcsize="10%" stroke="f" fillcolor="#ec4899">
                <w:anchorlock/>
                <center>
                <![endif]-->
                <a href="{{ route('job.index') }}" class="mso-button" style="background-color: #ec4899; border-radius: 8px; color: #ffffff; display: inline-block; font-size: 16px; font-weight: 500; line-height: 45px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none;">View All Jobs</a>
                <!--[if mso]>
                </center>
                </v:roundrect>
                <![endif]-->
                
                <p style="margin: 20px 0 0; color: #6b7280; font-size: 14px;">Not seeing the right opportunities? <a href="{{ route('profile.preferences') }}" style="color: #ec4899; text-decoration: none;">Update your preferences</a></p>
            </td>
        </tr>
        
        <!-- Footer section -->
        <tr>
            <td style="padding: 20px; text-align: center;">
                <p style="margin: 0; color: #6b7280; font-size: 14px;">Best regards,<br>The {{ config('app.name') }} Team</p>
                <p style="margin: 15px 0 0; color: #9ca3af; font-size: 12px;">
                    You're receiving this email because you signed up for job alerts.<br>
                    <a href="{{ route('profile.preferences') }}" style="color: #ec4899; text-decoration: none;">Manage email preferences</a> or <a href="{{ route('profile.preferences') }}" style="color: #ec4899; text-decoration: none;">Unsubscribe</a>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
