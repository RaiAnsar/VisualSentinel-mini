# Visual Sentinel - Personal Website Monitoring Tool

## Overview

A personal tool for monitoring your own and client websites, with a focus on accuracy, beautiful UI, and comprehensive monitoring features.

## Core Features

### 1. Website Monitoring

- **Advanced Monitoring**

  - Uptime monitoring with CDN awareness (Cloudflare error detection)
  - Response time tracking
  - SSL certificate monitoring
  - Content change detection
  - Custom monitoring intervals
- **Screenshot Comparison**

  - Automatic screenshots at configurable intervals
  - Visual diff comparison
  - Change history tracking
  - Screenshot gallery

### 2. Dashboard & UI

- **Modern Interface**

  - Clean, professional design
  - Dark/Light mode toggle
  - Responsive layout
  - Beautiful landing page
  - Status-based filtering (UP, Changed, Down, Not Monitored)
- **Website Organization**

  - Tags and labels for categorization
  - Custom status indicators
  - Grouping by status
  - Search and filtering
- **Visualizations**

  - Response time graphs
  - Uptime statistics
  - Status timeline
  - Change history

### 3. Notifications

- Email notifications for:
  - Website downtime (with CDN-aware status)
  - SSL certificate expiration
  - Significant content changes
  - High response times
  - Recovery notifications

### 4. Technical Features

- **CDN Awareness**

  - Detect actual server status behind CDN
  - Identify Cloudflare error pages
  - Accurate uptime reporting
- **Data Management**

  - Export functionality
  - Data retention policies
  - Backup options

## Technical Implementation

### Backend

- Laravel 12.6
- MySQL 8.0+
- Redis for caching and queues (if available on Hostinger, fallback to database queues)
- Scheduled tasks for monitoring
- API endpoints for future mobile app

### Frontend

- Blade templates
- Pre-compiled assets (CSS/JS)
- Alpine.js for interactivity
- Dark/Light mode implementation
- Responsive design

### Monitoring

- HTTP status checks with CDN detection
- Response time measurement
- SSL certificate validation
- Screenshot capture and comparison
- Custom monitoring intervals

## Development Phases

### Phase 1: Foundation (2-3 weeks)

1. Project Setup

   - Laravel Latest* installation
   - Database configuration
   - Basic authentication
   - Dark/Light mode implementation
   - Asset compilation setup (local development)
2. Core Models & Database

   - Website model with tags
   - Monitoring logs
   - Screenshot storage
   - Status tracking

### Phase 2: Monitoring Core (2-3 weeks)

1. Basic Monitoring

   - HTTP status checks
   - CDN error detection
   - Response time tracking
   - Basic dashboard
2. Screenshot System

   - Screenshot capture
   - Storage implementation
   - Basic comparison
   - Gallery view

### Phase 3: UI & Features (2-3 weeks)

1. Dashboard Development

   - Status cards
   - Filtering system
   - Tag management
   - Response graphs
2. Website Management

   - CRUD operations
   - Tag system
   - Status management
   - Monitoring controls

### Phase 4: Advanced Features (2-3 weeks)

1. Enhanced Monitoring

   - SSL certificate checks
   - Content change detection
   - Custom intervals
   - Detailed statistics
2. Notification System

   - Email notifications
   - Alert configuration
   - Status history
   - Recovery alerts

### Phase 5: Polish & Optimization (1-2 weeks)

1. UI Refinement

   - Landing page
   - Dashboard enhancements
   - Mobile optimization
   - Performance improvements
2. Final Touches

   - Export functionality
   - Backup system
   - Documentation
   - Deployment setup

## Setup Requirements

- PHP 8.2+
- MySQL 8.0+
- Redis (optional, fallback to database queues)
- Chrome/Chromium (for screenshots)
- SMTP server

## Development Workflow

1. Local Development

   - Use Node.js locally for asset compilation
   - Pre-compile assets before deployment
   - Test all features locally
2. Deployment to Hostinger

   - Upload pre-compiled assets
   - Configure cron jobs for monitoring
   - Set up email notifications
   - Configure database

## Security & Maintenance

- HTTPS required
- Regular backups
- Log monitoring
- Performance optimization
- Security updates

## Getting Started

1. Clone repository
2. Install dependencies (composer only)
3. Configure environment
4. Set up database
5. Configure monitoring
6. Add websites

## Future Enhancements

- Mobile app
- API integrations
- Advanced analytics
- Custom monitoring rules
- Team collaboration (optional)

This plan is optimized for Hostinger deployment while maintaining all the core features. We'll handle asset compilation locally and deploy pre-compiled assets to the server.
