![Free Palestine](https://github.com/user-attachments/assets/2b796609-c819-4cf6-b454-993e47a6e0f2)


<div align="center">
  <h1>🎯 Geezap-Job Aggregator</h1>
  <p>A comprehensive job aggregation platform that brings opportunities from multiple sources into one place.</p>
</div>

## 📌 Project Overview

Geezap-Job Aggregator is a Laravel-based application that simplifies the job search process by aggregating job listings from various platforms including:
- LinkedIn
- Upwork
- Indeed
- ZipRecruiter
- And more...

The platform not only consolidates job listings but also provides tools to enhance the job application process and preparation.

## 🚀 Key Features

- **Job Aggregation**
    - Unified search across multiple job platforms
    - Real-time job updates
    - Detailed job information in a standardized format

- **Application Management**
    - Track application status (Applied, Saved)
    - Save jobs for later application
    - Application history dashboard

- **Cover Letter Generation**
    - AI-powered cover letter generation based on job details
    - Customizable templates
    - Export options

## 🛠️ Installation

1. Clone the repository
```bash
git clone https://github.com/theihasan/geezap.git
cd geezap
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment variables
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up required API keys in `.env`:
```bash
OPENAI_API_KEY=your_openai_api_key

# Cloudflare Turnstile
CLOUDFLARE_TURNSTILE_SITE_KEY=your_site_key
CLOUDFLARE_TURNSTILE_SECRET_KEY=your_secret_key
```
> Also you may need to set turnstile widget from cloudflare dashboard

5. Run migrations and generate application key
```bash
php artisan migrate
```

6. Set up Laravel Reverb for WebSocket:
```bash
php artisan reverb:install
php artisan reverb:start
```

7. Start the development server
```bash
php artisan serve
npm run dev
```

8. Add Job Category
- Add a job category via the admin panel: `/geezap/job-categories`.
- Admin credential are available in the seeder class

9. Add API-Key
- Add API Keys for job search via admin panel: `/geezap/api-keys`.

10. Run the Scheduler
```bash
php artisan schedule:run
```

1.   Run the queue worker
```bash
php artisan queue:work
```

**Notes**
- If you don't get expected behavior check `laravel.log` file
- Following command might be helpful in some cases
```bash
php artisan cache:clear
```

## 💻 Technologies Used

- Laravel 11.x
- Laravel Reverb for WebSocket
- OpenAI API
- MySQL
- Livewire (Frontend)
- TailwindCSS

## 📧 Email Communications

Geezap sends emails to users at various touchpoints throughout their journey:

1. **User Registration**
    - Welcome email when a new user signs up

2. **Password Management**
    - Password reset links when requested

3. **Job Alerts**
    - Weekly job digest with personalized job recommendations

All emails are sent using a failover configuration that ensures reliable delivery through multiple providers. Users can manage their email preferences through their account settings.

## Current Feature
  - It collect techical jobs from Bangladesh, India, Australia, UK, USA, Thailand and show this portal
  - User can generate a darft cover letter from job details page.
  - User get weekly digest in their email.
  - User get push notification.
  - Apply button click history.
  - Save job for letter apply.
  - Social Authentication via Github, Google. Facebook ongoing.
  - Bot protection using Cloudflare turnstile.
  - Better filtering options in browse job page.
## 🔜 Upcoming Features
- **Personalized Job Recommendations**
    - AI-driven suggestions based on user activity, preferences, and saved searches.
    - AI-powered job recommendations
    - Skill compatibility scoring

- **Social Media Sharing**
    - Share job listings on platforms like LinkedIn, Twitter, and Facebook.
    - Auto posting to facebook

- **Reminder Notification for Saved for Latter Job**
    - When user saved a job for letter application then he will get a email notification before 2days of deadline of this job
    - Job preference


## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
