# TPL Tinapa CMS - Setup Instructions

## Prerequisites
- PHP 7.0+
- MySQL Server
- Git (optional)

## Setup Steps

### 1. Start MySQL Server
On Windows, start MySQL using:
```bash
# Using MySQL Command Line Client
mysql -u root

# Or if MySQL is installed as a service, ensure it's running in Services
```

### 2. Create Database
Run the SQL setup script:

**Option A: Using MySQL Command Line**
```bash
mysql -u root < database_setup.sql
```

**Option B: Using phpMyAdmin**
- Open phpMyAdmin (usually at http://localhost/phpmyadmin)
- Click "New" database named `tinapa_cms`
- Go to Import tab
- Upload `database_setup.sql`
- Click Import

### 3. Start PHP Development Server
Open PowerShell and navigate to the project folder:
```powershell
cd "c:\Users\Christian16\OneDrive\Dokumen\GitHub\TPL-Best-Tinapa-in-Bulacan-"
php -S localhost:8000
```

### 4. Access the Website
- **Frontend**: http://localhost:8000
- **Admin Dashboard**: http://localhost:8000/admin/
- **Admin Login**: 
  - Email: `admin@tinapa.com`
  - Password: `12345`

## Database Credentials
The application uses:
- Host: `localhost`
- User: `root`
- Password: (empty)
- Database: `tinapa_cms`

To change these, edit [includes/config.php](includes/config.php)

## Folder Structure
- `index.php` - Homepage
- `about.php` - About page
- `services.php` - Services page
- `contact.php` - Contact page
- `admin/` - Admin dashboard and management
- `includes/` - Shared PHP files (config, functions)
- `uploads/` - User-uploaded images
- `assets/css/` - Stylesheets

## Initial Test Data
The database setup creates:
- 1 Admin user (admin@tinapa.com / 12345)
- 4 Content sections (home, about, services, contact)
- 3 Sample products (different types of tinapa)

## Troubleshooting

### "Connection failed" error
- Make sure MySQL is running
- Check credentials in [includes/config.php](includes/config.php)

### Page looks broken
- Admin styles are loaded from `assets/css/admin_styles.css`
- This has been created for you

### Can't start PHP server
- Make sure PHP is installed and in your PATH
- Run from the project root directory
