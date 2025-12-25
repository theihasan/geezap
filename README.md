# Geezap Job Aggregator

A comprehensive job aggregation platform that consolidates job listings from multiple sources (LinkedIn, Upwork, Indeed, ZipRecruiter) into one unified search interface with AI-powered features.

## ⚡ Ultra-Fast Search Performance

**NEW**: Revolutionary search experience with **sub-200ms response times** - that's 4000%+ faster than traditional APIs!

- **Direct Client Integration**: Browser connects directly to Typesense server
- **Secure Architecture**: Scoped API keys with automatic rotation  
- **Zero Server Load**: Search operations bypass Laravel entirely
- **Real-time Suggestions**: Instant search-as-you-type functionality

**Architecture Comparison:**

```
Traditional (SLOW - 8+ seconds):
User → Laravel API → Database Query → Response

Ultra-Fast (NEW - <200ms):
User → Direct Typesense Client → Instant Results ⚡
```

---

## Quick Start Installation

### Prerequisites

Ensure your system has:
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+
- Redis server
- Typesense 27.1+ server (for ultra-fast search functionality)

### Step 1: Download and Setup

```bash
git clone https://github.com/theihasan/geezap.git
cd geezap
composer install
npm install
```

### Step 2: Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

### Step 3: Database Setup

Create a MySQL database and update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=geezap
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run database migrations:

```bash
php artisan migrate
php artisan db:seed
```

### Step 4: Build Assets

```bash
npm run build
```

### Step 5: Start Background Services

The application requires multiple background services to function properly:

```bash
# Terminal 1 - Main application
php artisan serve

# Terminal 2 - Typesense server (required for ultra-fast search)
# If using Docker:
docker run -d --name typesense -p 8108:8108 -v typesense-data:/data typesense/typesense:27.1 --data-dir /data --api-key=your-api-key --enable-cors
# Or if installed directly:
./typesense-server --data-dir=/tmp/typesense-data --api-key=your-api-key

# Terminal 3 - Queue worker (required for job processing)
php artisan queue:work

# Terminal 4 - WebSocket server (required for real-time features)
php artisan reverb:start (### Currently Not Running)

# Terminal 5 - Scheduler (required for automated job fetching)
php artisan schedule:work
```

**After starting Typesense, index your job listings:**
```bash
# Import job listings to Typesense for search functionality
php artisan scout:import "App\Models\JobListing"
```

### Step 6: Initial Admin Setup

1. Access admin panel at: `http://localhost:8000/geezap`
    -`Admin Email: admin@geezap.com`
    -`password: password`
2. Default admin credentials are created by the seeder
3. Add job categories via: `/geezap/job-categories`
4. Configure API keys via: `/geezap/api-keys`

## Required API Credentials

### Essential Services

**AI Service (Choose One):**
```env
# OpenAI (Recommended)
OPENAI_API_KEY=sk-your-openai-key

# Or Gemini
GEMINI_API_KEY=your-gemini-key

# Or DeepSeek
DEEPSEEK_API_KEY=your-deepseek-key
```

**Bot Protection (Required):**
```env
CLOUDFLARE_TURNSTILE_SITE_KEY=your-site-key
CLOUDFLARE_TURNSTILE_SECRET_KEY=your-secret-key
```

Get Cloudflare Turnstile keys from: https://dash.cloudflare.com/

### Email Configuration

Configure email service for user notifications:

```env
# Primary email service (Brevo/Sendinblue)
BREVO_API_KEY=your-brevo-key
BREVO_SMTP_HOST=smtp-relay.brevo.com
BREVO_SMTP_PORT=587
BREVO_SMTP_ENCRYPTION=tls
BREVO_SMTP_USERNAME=your-brevo-username
BREVO_SMTP_PASSWORD=your-brevo-password

# Backup email service (Resend)
RESEND_KEY=your-resend-key

# Or use ZeptoMail
ZEPTO_SMTP_HOST=smtp.zeptomail.com
ZEPTO_SMTP_PORT=587
ZEPTO_SMTP_ENCRYPTION=tls
ZEPTO_SMTP_USERNAME=your-zepto-username
ZEPTO_SMTP_PASSWORD=your-zepto-password
```

### Typesense Search Configuration

**Ultra-Fast Search Engine**: Typesense provides instant search results with <200ms response times.

```env
# Typesense Configuration (Required for search functionality)
TYPESENSE_API_KEY=your-typesense-admin-key
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http

# Optional: Search-only key for enhanced security
TYPESENSE_SEARCH_ONLY_API_KEY=your-read-only-key
```

**Typesense Installation:**

1. **Install Typesense Server:**
   ```bash
   # Using Docker (Recommended)
   docker run -d \
     --name typesense \
     -p 8108:8108 \
     -v typesense-data:/data \
     typesense/typesense:27.1 \
     --data-dir /data \
     --api-key=your-api-key \
     --enable-cors
   
   # Or install directly
   curl -O https://dl.typesense.org/releases/27.1/typesense-server-27.1-linux-amd64.tar.gz
   tar -xzf typesense-server-27.1-linux-amd64.tar.gz
   ./typesense-server --data-dir=/tmp/typesense-data --api-key=your-api-key
   ```

2. **Configure Laravel Scout:**
   ```bash
   # Install required packages
   composer require laravel/scout typesense/typesense-php
   
   # Publish Scout configuration
   php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
   ```

3. **Index Job Listings:**
   ```bash
   # Create the search index and import all job listings
   php artisan scout:import "App\Models\JobListing"
   
   # Check indexing status
   php artisan tinker
   >>> \App\Models\JobListing::search('*')->count()
   ```

**Security Features:**

- **Scoped API Keys**: Automatic generation of search-only keys with 24-hour TTL
- **Admin Key Protection**: Admin API keys are never exposed to frontend clients
- **Role-Based Access**: Admin-only endpoints for key management
- **Secure Caching**: Search keys cached for optimal performance with security

**Search Performance:**

- **Direct Client Integration**: Frontend bypasses slow server APIs for instant results
- **Response Times**: Sub-200ms search responses (4000%+ faster than traditional APIs)
- **Real-time Suggestions**: Ultra-fast search-as-you-type functionality
- **Grouped Results**: Organized search results with faceting support

**API Endpoints:**

- `GET /api/typesense/config` - Public endpoint returning secure search configuration
- `POST /api/typesense/refresh-key` - Admin-only endpoint for manual key refresh

**Frontend Integration:**

The application includes a TypesenseSearch JavaScript class that automatically:
1. Fetches secure configuration from `/api/typesense/config`
2. Initializes direct Typesense client connection
3. Provides ultra-fast search suggestions on the homepage
4. Handles errors gracefully with fallback mechanisms

### Social Authentication (Optional)

```env
# GitHub OAuth
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret

```

### Redis Configuration

For production environments with separate Redis instances:

```env
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_SCHEME=tls
```

## Production Deployment

### Server Requirements

- **Web Server**: Nginx with Laravel configuration
- **PHP**: 8.2+ with extensions: PDO, MySQL, Curl, OpenSSL, JSON, Tokenizer, XML, Ctype, BCMath
- **Database**: MySQL 8.0+ or MariaDB 10.3+
- **Cache**: Redis 6.0+
- **Memory**: Minimum 2GB RAM (4GB+ recommended)
- **Storage**: 10GB+ with room for growth

### Production Installation

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Configure environment
cp .env.example .env
# Edit .env with production values
php artisan key:generate

# Database setup
php artisan migrate --force

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### File Permissions Setup

**Critical**: Proper file permissions are essential for the application to function correctly. Run these commands on your production server:

```bash
# Set ownership of the application directory
sudo chown -R deploy:www-data /var/www/html/geezap

# Set base permissions for the entire application
sudo chmod -R 755 /var/www/html/geezap

# Set writable permissions for storage and cache directories
sudo chmod -R 775 /var/www/html/geezap/storage
sudo chmod -R 775 /var/www/html/geezap/bootstrap/cache

# Set permissions for public assets
sudo chmod -R 755 /var/www/html/geezap/public

# Ensure web server owns critical directories for write operations
sudo chown -R www-data:www-data /var/www/html/geezap/storage
sudo chown -R www-data:www-data /var/www/html/geezap/bootstrap/cache
```

**Permission Structure Explained:**
- **755** (rwxr-xr-x): Owner can read/write/execute, group and others can read/execute
- **775** (rwxrwxr-x): Owner and group can read/write/execute, others can read/execute
- **www-data**: Web server user that needs write access to logs, cache, and uploaded files
- **deploy**: Deployment user that owns the application files

**Required Directories with Write Permissions:**
- `storage/` - For logs, cache, sessions, and uploaded files
- `storage/logs/` - Application and error logs
- `storage/framework/cache/` - Application cache files
- `storage/framework/sessions/` - User session data
- `storage/framework/views/` - Compiled Blade templates
- `bootstrap/cache/` - Configuration and route cache files

**Verification:**
After setting permissions, verify they are correct:

```bash
# Check storage directory permissions
ls -la /var/www/html/geezap/storage

# Check bootstrap cache permissions  
ls -la /var/www/html/geezap/bootstrap/cache

# Test write permissions
sudo -u www-data touch /var/www/html/geezap/storage/logs/test.log
sudo rm /var/www/html/geezap/storage/logs/test.log
```

**Common Permission Issues:**
- **500 Internal Server Error**: Often caused by incorrect file ownership
- **Permission Denied**: Web server cannot write to storage or cache directories
- **Failed to open stream**: Log files or cache files cannot be created or accessed

**Security Notes:**
- Never set 777 permissions on any directory
- Ensure only necessary directories have write permissions
- Web server should not own the entire application directory
- Keep sensitive files (like `.env`) readable only by owner

### Background Services Setup

Use a process manager like Supervisor to manage background services:

**Queue Worker Configuration:**
```ini
[program:geezap-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/geezap/artisan queue:work --sleep=3 --tries=3
directory=/var/www/html/geezap
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/geezap/storage/logs/worker.log
```

**Scheduler Cron Job:**
```bash
# Add to crontab
* * * * * cd /var/www/html/geezap && php artisan schedule:run >> /dev/null 2>&1
```

## Common Errors and Solutions

### Installation Errors

**Error**: `Class 'PDO' not found`
**Solution**: Install PHP PDO extension: `sudo apt-get install php8.2-mysql`

**Error**: `composer: command not found`
**Solution**: Install Composer: https://getcomposer.org/download/

**Error**: `npm: command not found`
**Solution**: Install Node.js: https://nodejs.org/

### Database Errors

**Error**: `SQLSTATE[HY000] [2002] No such file or directory`
**Solution**: Ensure MySQL service is running: `sudo systemctl start mysql`

**Error**: `Access denied for user 'root'@'localhost'`
**Solution**: Check database credentials in `.env` file and ensure user has proper permissions

**Error**: `Base table or view not found`
**Solution**: Run migrations: `php artisan migrate`

### Application Errors

**Error**: `419 Page Expired`
**Solution**: Clear cache and regenerate app key:
```bash
php artisan cache:clear
php artisan config:clear
php artisan key:generate
```

**Error**: `Class 'Redis' not found`
**Solution**: Install Redis PHP extension: `sudo apt-get install php8.2-redis`

**Error**: Jobs not processing
**Solution**: Ensure queue worker is running: `php artisan queue:work`

**Error**: Real-time features not working
**Solution**: Ensure Reverb WebSocket server is running: `php artisan reverb:start`

### Typesense Search Errors

**Error**: `Connection refused to Typesense server`
**Solution**: 
1. Ensure Typesense server is running on configured host/port
2. Check Typesense API key is correct in `.env`
3. Verify firewall allows connections to Typesense port (default 8108)

**Error**: `Search not working or returning empty results`
**Solution**: 
1. Re-index job listings: `php artisan scout:import "App\Models\JobListing"`
2. Check search index exists: Access Typesense dashboard at `http://localhost:8108`
3. Verify facet configuration is correct in `config/scout.php`

**Error**: `Unable to generate secure search key`
**Solution**: 
1. Check admin API key permissions in Typesense
2. Verify Typesense server supports scoped key generation
3. Clear cache: `php artisan cache:clear`

**Error**: `Slow search performance`
**Solution**: 
1. Ensure direct client connection is working (check browser network tab)
2. Verify Typesense server has sufficient resources (CPU/RAM)
3. Check if search keys are being cached properly

**Error**: `API key exposed security warning`
**Solution**: 
1. Verify scoped key system is working: `curl -H "Accept: application/json" http://localhost:8000/api/typesense/config`
2. Ensure returned API key is different from admin key in `.env`
3. Check security tests pass: `php artisan test tests/Feature/TypesenseConfigSecurityTest.php`

### API Integration Errors

**Error**: Cover letter generation fails
**Solution**: Verify AI service API key is set correctly in `.env`

**Error**: Bot protection not working
**Solution**: 
1. Verify Cloudflare Turnstile keys are correct
2. Ensure domain is properly configured in Cloudflare dashboard

**Error**: Social login not working
**Solution**: 
1. Check OAuth credentials in `.env`
2. Verify callback URLs are configured correctly in OAuth providers
3. Ensure SSL is enabled for production domains

### Performance Issues

**Error**: Slow page loading
**Solution**: 
```bash
# Enable caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear old cache if needed
php artisan cache:clear
```

**Error**: High memory usage
**Solution**: 
1. Monitor queue worker memory with `php artisan horizon` (if using Horizon)
2. Restart workers periodically: `php artisan queue:restart`

## How to Use the Application

### For Job Seekers

**Ultra-Fast Job Search:**
1. Visit the homepage and start typing in the search box
2. Experience instant search suggestions in <200ms
3. Results are fetched directly from Typesense server (bypassing Laravel API)
4. Search results are grouped intelligently by job title and employer

**Browse Jobs:**
1. Visit the homepage to see latest job listings
2. Use search filters to narrow down results by location, category, and keywords
3. Click on job titles to view detailed descriptions

**Save and Apply:**
1. Create an account or log in
2. Click "Save for Later" on interesting jobs
3. Use "Apply" button to track your applications
4. Access saved jobs from your dashboard

**AI Cover Letter Generation:**
1. Open any job listing detail page
2. Click "Generate Cover Letter" button
3. AI will create a customized cover letter based on job requirements
4. Edit and download the generated cover letter

**Email Notifications:**
1. Set email preferences in your account settings
2. Receive weekly job digest emails with personalized recommendations
3. Get notifications about saved job deadlines

### For Administrators

**Admin Panel Access:**
- Navigate to `/geezap` for admin dashboard
- Requires admin credentials (created during seeding)

**Manage Job Categories:**
1. Go to `/geezap/job-categories`
2. Add, edit, or delete job categories
3. Set category images and descriptions

**Configure API Keys:**
1. Visit `/geezap/api-keys`
2. Add API keys for different job platforms
3. Monitor API usage and rate limits

**Monitor System:**
1. Check queue jobs at `/horizon`
2. View application logs in `storage/logs/`
3. Monitor user analytics and search patterns

## Key Features

**Ultra-Fast Search:**
- **Instant Results**: Sub-200ms search responses with direct Typesense integration
- **Real-time Suggestions**: Search-as-you-type with 300ms debouncing
- **Secure Architecture**: Scoped API keys with automatic 24-hour rotation
- **Performance Breakthrough**: 4000%+ faster than traditional server-side search APIs
- **Grouped Results**: Intelligent result grouping by job title and employer

**Job Aggregation:**
- Automatically collects jobs from LinkedIn, Upwork, Indeed, and ZipRecruiter
- Standardizes job data format for consistent display
- Real-time updates via WebSocket connections
- Advanced filtering and search capabilities

**AI-Powered Tools:**
- Intelligent cover letter generation based on job descriptions
- Personalized job recommendations
- Smart matching algorithms

**User Management:**
- Social authentication via GitHub, Google, and Facebook
- User preferences and notification settings
- Application tracking and history

**Security Features:**
- Bot protection using Cloudflare Turnstile
- Rate limiting and CSRF protection
- Secure API key management

**Administrative Tools:**
- Comprehensive admin dashboard using Filament
- Real-time monitoring with Laravel Horizon
- Analytics and reporting features
- Backup and maintenance tools

## Technical Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Livewire 3, Alpine.js, TailwindCSS
- **Database**: MySQL 8.0+, Redis
- **Search Engine**: Typesense 27.1+ (ultra-fast search with <200ms responses)
- **Queue**: Laravel Horizon with Redis
- **WebSockets**: Laravel Reverb
- **AI Integration**: OpenAI/Gemini/DeepSeek APIs
- **Admin Panel**: Filament v3

## Support and Troubleshooting

**Log Files:**
- Application logs: `storage/logs/laravel.log`
- Queue logs: Check Horizon dashboard
- Web server logs: Check your web server configuration

**Debug Mode:**
For development only, enable debug mode in `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

**Cache Issues:**
Clear all caches when experiencing unexpected behavior:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Database Issues:**
Reset database if needed (development only):
```bash
php artisan migrate:fresh --seed
```

## Contributing

Contributions are welcome. Please ensure all tests pass before submitting pull requests:

```bash
php artisan test
```

## License

This project is licensed under the MIT License.