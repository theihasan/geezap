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
```

5. Set up Laravel Reverb for WebSocket:
```bash
php artisan reverb:install
php artisan reverb:start
```

6. Run migrations
```bash
php artisan migrate
```

7. Start the development server
```bash
php artisan serve
npm run dev
```

## 💻 Technologies Used

- Laravel 11.x
- Laravel Reverb for WebSocket
- OpenAI API
- MySQL
- Livewire (Frontend)
- TailwindCSS

## 🔜 Upcoming Features (Version 2.0.1)

### Interview Preparation Module
- **Quiz System**
    - Generate relevant interview questions based on job descriptions
    - Practice mode with instant feedback
    - Track quiz performance and progress
    - Customized question sets based on job requirements

### Additional Planned Features
- **Enhanced Analytics**
    - Job market trends analysis
    - Skill gap analysis

- **Advanced Job Matching**
    - AI-powered job recommendations
    - Skill compatibility scoring

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
