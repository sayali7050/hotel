# Production Deployment Guide - Hotel Management System v2.0

## 🔒 Security-First Production Deployment

This guide provides step-by-step instructions for deploying the Hotel Management System in a production environment with enterprise-level security.

## 📋 Pre-Deployment Checklist

### System Requirements
- **PHP**: 7.4+ (8.0+ recommended)
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **SSL Certificate**: Required for production
- **Memory**: Minimum 512MB RAM (2GB+ recommended)
- **Storage**: Minimum 1GB free space

### Security Requirements
- [ ] SSL/TLS certificate installed
- [ ] Firewall configured
- [ ] Database server secured
- [ ] File permissions properly set
- [ ] Error reporting disabled in production
- [ ] Debug mode disabled

## 🛠 Deployment Steps

### 1. Server Preparation

#### 1.1 Update System
```bash
sudo apt update && sudo apt upgrade -y
```

#### 1.2 Install Required Software
```bash
# Install PHP and extensions
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-gd php8.1-json php8.1-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install MySQL/MariaDB
sudo apt install mysql-server

# Install Nginx
sudo apt install nginx
```

### 2. Database Setup

#### 2.1 Secure MySQL Installation
```bash
sudo mysql_secure_installation
```

#### 2.2 Create Database and User
```sql
-- Login to MySQL as root
sudo mysql -u root -p

-- Create database
CREATE DATABASE hotel_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create dedicated user
CREATE USER 'hotel_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';

-- Grant specific permissions
GRANT SELECT, INSERT, UPDATE, DELETE ON hotel_management.* TO 'hotel_user'@'localhost';

-- Remove unnecessary privileges
REVOKE ALL PRIVILEGES ON mysql.* FROM 'hotel_user'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

#### 2.3 Import Database Schema
```bash
mysql -u hotel_user -p hotel_management < database_production.sql
```

### 3. Application Deployment

#### 3.1 Clone/Upload Application
```bash
# Clone repository (if using Git)
git clone https://github.com/your-repo/hotel-management.git /var/www/hotel

# Or upload files to /var/www/hotel
```

#### 3.2 Install Dependencies
```bash
cd /var/www/hotel
composer install --no-dev --optimize-autoloader
```

#### 3.3 Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Edit environment file
nano .env
```

**Critical .env settings for production:**
```bash
CI_ENV=production
DB_HOST=localhost
DB_USER=hotel_user
DB_PASS=your_strong_password
DB_NAME=hotel_management
BASE_URL=https://yourdomain.com/
ENCRYPTION_KEY=generate-32-character-random-key
ADMIN_2FA_ENABLED=true
MAINTENANCE_MODE=false
```

#### 3.4 Generate Encryption Key
```bash
# Generate secure encryption key
php -r "echo bin2hex(random_bytes(16)) . PHP_EOL;"
```

### 4. File Permissions & Security

#### 4.1 Set Proper Ownership
```bash
sudo chown -R www-data:www-data /var/www/hotel
```

#### 4.2 Set Secure Permissions
```bash
# Application files
find /var/www/hotel -type f -exec chmod 644 {} \;
find /var/www/hotel -type d -exec chmod 755 {} \;

# Writable directories
chmod 775 /var/www/hotel/application/logs
chmod 775 /var/www/hotel/application/cache
chmod 775 /var/www/hotel/uploads

# Protect sensitive files
chmod 600 /var/www/hotel/.env
chmod 600 /var/www/hotel/application/config/database.php

# Remove write permissions from config files
chmod 644 /var/www/hotel/application/config/*.php
```

#### 4.3 Secure Sensitive Directories
```bash
# Create .htaccess for application directory
cat > /var/www/hotel/application/.htaccess << 'EOF'
<RequireAll>
    Require all denied
</RequireAll>
EOF

# Create .htaccess for system directory
cat > /var/www/hotel/system/.htaccess << 'EOF'
<RequireAll>
    Require all denied
</RequireAll>
EOF
```

### 5. Web Server Configuration

#### 5.1 Nginx Configuration
```nginx
# /etc/nginx/sites-available/hotel
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    
    root /var/www/hotel;
    index index.php index.html;
    
    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    
    # Security Headers
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    
    # Hide server information
    server_tokens off;
    
    # PHP Configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    
    # URL Rewriting for CodeIgniter
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }
    
    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }
    
    location ~ /(application|system)/ {
        deny all;
    }
    
    location ~ \.(env|log)$ {
        deny all;
    }
    
    # Rate limiting
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
    location /auth/ {
        limit_req zone=login burst=10 nodelay;
        try_files $uri $uri/ /index.php$is_args$args;
    }
}
```

#### 5.2 Enable Site
```bash
sudo ln -s /etc/nginx/sites-available/hotel /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 6. PHP-FPM Security Configuration

#### 6.1 Edit PHP-FPM Pool
```bash
sudo nano /etc/php/8.1/fpm/pool.d/hotel.conf
```

```ini
[hotel]
user = www-data
group = www-data
listen = /var/run/php/php8.1-fpm-hotel.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3

; Security
php_admin_value[disable_functions] = exec,passthru,shell_exec,system,proc_open,popen
php_admin_flag[allow_url_fopen] = off
php_admin_flag[allow_url_include] = off
```

### 7. SSL/TLS Setup (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### 8. Firewall Configuration

```bash
# Enable UFW
sudo ufw enable

# Allow SSH (adjust port if needed)
sudo ufw allow 22/tcp

# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Allow MySQL only from localhost
sudo ufw allow from 127.0.0.1 to any port 3306

# Check status
sudo ufw status
```

### 9. Monitoring & Logging

#### 9.1 Setup Log Rotation
```bash
sudo nano /etc/logrotate.d/hotel
```

```
/var/www/hotel/application/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
}
```

#### 9.2 Setup Monitoring
```bash
# Install system monitoring
sudo apt install htop iotop

# Setup log monitoring
sudo tail -f /var/www/hotel/application/logs/log-*.php
```

### 10. Performance Optimization

#### 10.1 Enable OPCache
```bash
sudo nano /etc/php/8.1/fpm/conf.d/10-opcache.ini
```

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.enable_file_override=1
```

#### 10.2 Database Optimization
```sql
-- Optimize database tables
OPTIMIZE TABLE users, bookings, rooms, payments;

-- Add indexes if needed
CREATE INDEX idx_bookings_dates ON bookings(check_in_date, check_out_date);
CREATE INDEX idx_bookings_status ON bookings(status);
```

### 11. Backup Strategy

#### 11.1 Database Backup Script
```bash
#!/bin/bash
# /usr/local/bin/backup-hotel-db.sh

DB_NAME="hotel_management"
DB_USER="hotel_user"
DB_PASS="your_password"
BACKUP_DIR="/backups/hotel"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/hotel_db_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -name "hotel_db_*.sql.gz" -mtime +30 -delete
```

#### 11.2 Setup Cron Job
```bash
# Add to crontab
0 2 * * * /usr/local/bin/backup-hotel-db.sh
```

## 🔐 Security Hardening Checklist

### Application Level
- [ ] Environment set to 'production'
- [ ] Debug mode disabled
- [ ] Error reporting disabled
- [ ] CSRF protection enabled
- [ ] Input validation implemented
- [ ] SQL injection protection
- [ ] XSS protection enabled
- [ ] File upload restrictions
- [ ] Session security configured
- [ ] 2FA enabled for admin accounts

### Server Level
- [ ] SSL/TLS certificate installed
- [ ] Security headers configured
- [ ] File permissions set correctly
- [ ] Unnecessary services disabled
- [ ] Firewall configured
- [ ] Regular security updates
- [ ] Log monitoring enabled
- [ ] Intrusion detection configured

### Database Level
- [ ] Dedicated database user created
- [ ] Minimal privileges assigned
- [ ] Regular backups scheduled
- [ ] Database server secured
- [ ] Connection encryption enabled

## 🚨 Post-Deployment Verification

### 1. Functionality Tests
```bash
# Test application access
curl -I https://yourdomain.com

# Test admin login
curl -X POST https://yourdomain.com/auth/admin_login

# Test database connection
php -r "echo 'DB Connected' . PHP_EOL;" /var/www/hotel/test_db.php
```

### 2. Security Tests
```bash
# Test SSL configuration
openssl s_client -connect yourdomain.com:443

# Test security headers
curl -I https://yourdomain.com

# Test file access restrictions
curl https://yourdomain.com/.env
curl https://yourdomain.com/application/
```

### 3. Performance Tests
```bash
# Test response time
curl -w "@curl-format.txt" -o /dev/null https://yourdomain.com

# Monitor server resources
htop
```

## 📞 Emergency Procedures

### Maintenance Mode
```bash
# Enable maintenance mode
echo "MAINTENANCE_MODE=true" >> /var/www/hotel/.env

# Disable maintenance mode
sed -i 's/MAINTENANCE_MODE=true/MAINTENANCE_MODE=false/' /var/www/hotel/.env
```

### Security Incident Response
1. **Immediate Actions:**
   - Enable maintenance mode
   - Review access logs
   - Check for unauthorized changes
   - Backup current state

2. **Investigation:**
   - Analyze audit logs
   - Check database integrity
   - Review user activities
   - Identify attack vectors

3. **Recovery:**
   - Restore from clean backup if needed
   - Update security measures
   - Reset compromised credentials
   - Document incident

## 📈 Monitoring & Maintenance

### Daily Tasks
- [ ] Check error logs
- [ ] Monitor disk space
- [ ] Review access logs
- [ ] Check backup status

### Weekly Tasks
- [ ] Update security patches
- [ ] Review user accounts
- [ ] Check performance metrics
- [ ] Test backup restoration

### Monthly Tasks
- [ ] Security audit
- [ ] Database optimization
- [ ] Review access controls
- [ ] Update documentation

## ⚠️ Important Notes

1. **Never store sensitive information in version control**
2. **Always test changes in staging environment first**
3. **Keep regular backups and test restoration procedures**
4. **Monitor logs for suspicious activities**
5. **Stay updated with security patches**
6. **Use strong, unique passwords for all accounts**
7. **Regularly review and update security measures**

## 📞 Support

For production support and security issues:
- Email: security@hotel.com
- Documentation: [Internal Wiki]
- Emergency: [Emergency Contact Number]

---

**Last Updated:** 2025-01-XX  
**Version:** 2.0  
**Review Date:** [Next Review Date]