# Installation Guide - Development Environment Setup

## Prerequisites Installation

### 1. Install Laravel Herd (Free)

**Download and Install:**
1. Visit: https://herd.laravel.com/
2. Download the free version for macOS
3. Install the application
4. Launch Herd from Applications

**What Herd Provides:**
- PHP (multiple versions)
- Composer
- Nginx web server
- Node.js & npm
- Easy Laravel project management

**Verify Installation:**
```bash
# After Herd installation, restart your terminal and verify:
php --version
composer --version
node --version
npm --version
```

### 2. Install MySQL via Homebrew

**Install Homebrew (if not already installed):**
```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

**Install MySQL:**
```bash
brew install mysql
```

**Start MySQL Service:**
```bash
brew services start mysql
```

**Secure MySQL Installation (Optional but Recommended):**
```bash
mysql_secure_installation
```
- Set root password (or press Enter to skip)
- Follow prompts (recommended: Y for all)

**Verify MySQL is Running:**
```bash
mysql --version
brew services list | grep mysql
```

**Create Database:**
```bash
# Login to MySQL
mysql -u root -p

# Create database (press Enter if no password was set)
CREATE DATABASE employee_management;

# Exit MySQL
exit;
```

### 3. Verify All Tools

Run these commands to ensure everything is installed:
```bash
php --version          # Should show PHP 8.x
composer --version     # Should show Composer 2.x
node --version         # Should show Node.js 18.x or higher
npm --version          # Should show npm 9.x or higher
mysql --version        # Should show MySQL 8.x
```

---

## Ready to Start Development!

Once all tools are installed and verified, you're ready to create the Laravel backend project.

**Next Steps:**
1. Create Laravel project
2. Configure database connection
3. Install Laravel Sanctum
4. Start building the API

---

**Installation Status:**
- [ ] Laravel Herd installed
- [ ] PHP working
- [ ] Composer working
- [ ] Node.js & npm working
- [ ] MySQL installed
- [ ] MySQL service running
- [ ] Database created

---

**Troubleshooting:**

**If `php` command not found after Herd installation:**
- Restart your terminal
- Or run: `eval "$(/opt/homebrew/bin/brew shellenv)"`

**If MySQL won't start:**
```bash
brew services stop mysql
brew services start mysql
```

**If you need to reset MySQL:**
```bash
brew services stop mysql
brew uninstall mysql
brew install mysql
brew services start mysql
```

---

**Document Version:** 1.0
**Last Updated:** 2026-01-09
