<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Geezap</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        /* Base styles */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #1f2937;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        /* Responsive styles */
        @media (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 10px !important;
            }

            .header {
                padding: 15px !important;
            }

            .content {
                padding: 15px !important;
            }

            .footer {
                padding: 15px !important;
            }

            .button {
                width: 100% !important;
                text-align: center !important;
                display: block !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f9fafb;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table class="container" width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td class="header" style="background-color: #3b82f6; padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700;">🎯 Geezap Job Aggregator</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content" style="padding: 30px;">
                            <h2 style="margin-top: 0; color: #1f2937; font-size: 20px; font-weight: 600;">Welcome to Geezap!</h2>

                            <p style="margin-bottom: 20px; color: #4b5563; line-height: 1.6;">
                                Hi {{ $user->name ?? 'there' }},
                            </p>

                            <p style="margin-bottom: 20px; color: #4b5563; line-height: 1.6;">
                                Thank you for joining Geezap - your comprehensive job aggregation platform that brings opportunities from multiple sources into one place.
                            </p>

                            <p style="margin-bottom: 20px; color: #4b5563; line-height: 1.6;">
                                With Geezap, you can:
                            </p>

                            <ul style="margin-bottom: 25px; color: #4b5563; line-height: 1.6; padding-left: 20px;">
                                <li style="margin-bottom: 10px;">Search for jobs across multiple platforms including LinkedIn, Upwork, Indeed, and more</li>
                                <li style="margin-bottom: 10px;">Track your application status and save jobs for later</li>
                                <li style="margin-bottom: 10px;">Generate AI-powered cover letters tailored to job descriptions</li>
                                <li style="margin-bottom: 10px;">Access detailed job information in a standardized format</li>
                            </ul>

                            <p style="margin-bottom: 20px; color: #4b5563; line-height: 1.6;">
                                If you have any questions or need assistance, feel free to reply to this email.
                            </p>

                            <p style="color: #4b5563; line-height: 1.6;">
                                Happy job hunting!<br>
                                The Geezap Team
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer" style="background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 14px; color: #6b7280;">
                            <p style="margin: 0 0 10px 0;">
                                © {{ date('Y') }} Geezap Job Aggregator. All rights reserved.
                            </p>
                            <p style="margin: 0;">
                                <a href="{{route('privacy-policy')}}" style="color: #3b82f6; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
                                <a href="{{route('terms')}}" style="color: #3b82f6; text-decoration: none; margin: 0 10px;">Terms of Service</a>
                                <a href="{{route('contact')}}" style="color: #3b82f6; text-decoration: none; margin: 0 10px;">Contact Us</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
