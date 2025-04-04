# Visual Sentinel - Website Monitoring Tool

<p align="center">
  <img src="public/images/logo.svg" width="150" alt="Visual Sentinel Logo">
</p>

<p align="center">
  A personal tool for monitoring your own and client websites, with a focus on accuracy, beautiful UI, and comprehensive monitoring features.
</p>

## Features

### Website Monitoring
- **Advanced Monitoring**
  - Uptime monitoring with CDN awareness
  - Response time tracking
  - SSL certificate monitoring
  - Content change detection
  - Custom monitoring intervals

- **Screenshot Comparison**
  - Automatic screenshots at configurable intervals
  - Visual diff comparison
  - Change history tracking
  - Screenshot gallery

### Modern UI
- Clean, professional design
- Dark/Light mode toggle
- Responsive layout
- Website organization with tags and filtering
- Status-based filtering (UP, Changed, Down, Not Monitored)

### Notifications
- Email notifications for:
  - Website downtime (with CDN-aware status)
  - SSL certificate expiration
  - Significant content changes
  - High response times
  - Recovery notifications

## Technical Stack

- **Backend:** Laravel 12.6
- **Database:** MySQL 8.0+
- **Frontend:** Blade templates with Tailwind CSS
- **JS Libraries:** Alpine.js for interactivity

## Getting Started

### Requirements
- PHP 8.2+
- MySQL 8.0+
- Composer

### Installation

1. Clone the repository
```bash
git clone https://github.com/RaiAnsar/VisualSentinel-mini.git
cd visual-sentinel-mini
```

2. Install dependencies
```bash
composer install
npm install
```

3. Set up environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env`

5. Run migrations
```bash
php artisan migrate
```

6. Compile assets
```bash
npm run dev
```

7. Start the development server
```bash
php artisan serve
```

## Usage

1. Create an account or log in
2. Add websites to monitor
3. Set monitoring preferences
4. View dashboard for site status

## License

[MIT License](LICENSE)

## Acknowledgements

- This project is built with [Laravel](https://laravel.com)
- UI components powered by [Tailwind CSS](https://tailwindcss.com)
- Interactive elements with [Alpine.js](https://alpinejs.dev)
