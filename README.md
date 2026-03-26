# PTL Best Tinapa in Bulacan - E-Commerce Website

## 📋 Project Description

This is a modern e-commerce website for PTL Best Tinapa, a premium smoked fish business in Bulacan, Philippines. The website features product listings, shopping cart, secure checkout with multiple Philippine payment methods (GCash, Maya, Bank Transfer, COD, Installment), user authentication, and an admin panel for managing products and orders.

**Features:**
- 🐟 Product catalog with images and descriptions
- 🛒 Shopping cart and checkout system
- 💳 Multiple payment options (GCash, Maya, Bank Transfer, COD, Installment)
- 👤 User registration and login
- 🔐 Admin panel for product and order management
- 📱 Responsive design for mobile and desktop
- 🌐 Modern, clean UI design

## 🛠️ Prerequisites

Before running this website, make sure you have the following installed:

### Required Software:
1. **XAMPP** (includes Apache, MySQL, PHP)
   - Download from: https://www.apachefriends.org/download.html
   - Choose the version for your operating system (Windows/Mac/Linux)

2. **Web Browser** (Chrome, Firefox, Edge, etc.)

### System Requirements:
- **Operating System:** Windows 10/11, macOS, or Linux
- **RAM:** At least 2GB
- **Disk Space:** 500MB free space
- **Internet Connection:** Required for initial setup and some features

## 📦 Installation

### Step 1: Download and Install XAMPP
1. Go to https://www.apachefriends.org/download.html
2. Download the latest version for your operating system
3. Run the installer and follow the setup wizard
4. During installation, make sure to install:
   - Apache (web server)
   - MySQL (database)
   - PHP (programming language)
   - phpMyAdmin (database management tool)

### Step 2: Download the Project Files
1. Download or clone this project to your computer
2. Extract the files to a folder on your desktop or documents (e.g., `C:\Users\YourName\Desktop\tinapa-website`)

### Step 3: Move Files to XAMPP
1. Open the XAMPP installation folder (usually `C:\xampp`)
2. Navigate to the `htdocs` folder
3. Copy the entire project folder into `htdocs`
   - Example: `C:\xampp\htdocs\tinapa-website`

## 🗄️ Database Setup

### Step 1: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start the following services:
   - **Apache** (web server) - Click "Start"
   - **MySQL** (database) - Click "Start"
3. Make sure both services are running (green status)

### Step 2: Create the Database
1. Open your web browser
2. Go to: http://localhost/phpmyadmin
3. Click "New" in the left sidebar
4. Database name: `tinapa_cms`
5. Collation: `utf8_general_ci`
6. Click "Create"

### Step 3: Import Database Tables
1. In phpMyAdmin, select the `tinapa_cms` database
2. Click "Import" tab at the top
3. Click "Choose File" and select `database_setup.sql` from your project folder
4. Click "Go" to import the tables and sample data

### Step 4: Verify Database
- You should see these tables created:
  - `admin` (admin users)
  - `users` (customer accounts)
  - `services` (products)
  - `site_content` (website content)
  - `messages` (contact form messages)
  - `cart` (shopping cart items)
  - `orders` (customer orders)
  - `order_items` (order details)

## 🚀 Running the Application

### Method 1: Using XAMPP (Recommended)
1. Make sure Apache and MySQL are running in XAMPP Control Panel
2. Open your web browser
3. Go to: http://localhost/tinapa-website
4. The website should load on the homepage

### Method 2: Using PHP Built-in Server (Alternative)
1. Open Command Prompt or PowerShell
2. Navigate to your project folder:
   ```
   cd C:\xampp\htdocs\tinapa-website
   ```
3. Start the PHP server:
   ```
   php -S localhost:8000
   ```
4. Open browser and go to: http://localhost:8000

### Method 3: Deploy on Render.com (CI/CD + Professor Requirement)
1. Create a Render account at https://render.com
2. Create a new **MySQL Database** service
   - Save connection values (host, user, pass, db)
3. Create new **Web Service**:
   - Connect repo (GitHub)
   - Branch: `main`
   - Environment: `PHP`
   - Start command: `php -S 0.0.0.0:10000 -t .`
4. Create Render env vars in the service settings:
   - `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`
   - `BASE_URL` = `https://<your-render-service>.onrender.com`
5. Add collaborator (professor) in Render team or service settings.
6. Add collaborator in GitHub repo Settings -> Manage access.
7. Deploy and verify: open the Render URL

## 🧪 CI/CD (GitHub Actions)
- Workflow file: `.github/workflows/php-ci.yml`
- Runs on `push` and `pull_request` to `main`
- Steps:
  - checkout
  - setup PHP 8.2
  - syntax check all PHP files
  - basic smoke test by starting built-in server and curling `/`

## 🔐 Environment Configuration (for production)
1. Ensure `includes/config.php` is configured to use environment variables.
2. Do NOT commit real credentials.
3. Use `.env.example` for local environment reference:
   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=tinapa_cms
   ```

## ✅ Professor Checklist
- [x] GitHub repo with source code
- [x] CI pipeline via GitHub Actions
- [x] Auto-deploy on push via Render.com
- [x] Professor added as collaborator (GitHub + Render)
- [x] Demo link to deployed app
- [x] Code is in modern dashboard style with card-based layout, responsive, payment flows


## 📖 Usage Guide

### For Customers:
1. **Browse Products**: Visit the homepage to see available products
2. **Register/Login**: Create an account or login to place orders
3. **Add to Cart**: Click "Add to Cart" on products you want to buy
4. **Checkout**: Review your cart and select payment method
5. **Payment**: Choose from GCash, Maya, Bank Transfer, COD, or Installment
6. **Order Tracking**: View your order history in "My Orders"

### For Admins:
1. **Admin Login**: Go to http://localhost/tinapa-website/admin/login.php
2. **Default Admin Account**:
   - Username: admin@tinapa.com
   - Password: 12345
3. **Manage Products**: Add, edit, or delete products
4. **View Orders**: Track customer orders and update status
5. **Manage Content**: Update website text and images

### Payment Methods Available:
- **Cash on Delivery (COD)**: Pay when you receive the order
- **GCash**: Send money via GCash app (09171234567)
- **Maya**: Digital wallet payment (09181234567)
- **Bank Transfer**: Transfer to BDO account
- **Credit Card Installment**: 0% interest plans (3/6/12 months)

## 🔧 Troubleshooting

### Common Issues:

**"Page not found" or "404 Error"**
- Make sure you're using the correct URL
- Check if Apache is running in XAMPP
- Verify files are in the correct folder (`htdocs`)

**"Database connection failed"**
- Ensure MySQL is running in XAMPP
- Check database name in `includes/config.php`
- Verify you imported the database correctly

**"PHP not working"**
- Make sure PHP is enabled in XAMPP
- Check if you're using the correct PHP version (7.4+ recommended)

**"Images not showing"**
- Check if image files are in the `uploads/` folder
- Verify file paths in the code

### Getting Help:
- Check XAMPP documentation: https://www.apachefriends.org/faq_windows.html
- View PHP error logs in XAMPP control panel
- Contact the development team for support

## 📁 Project Structure

```
tinapa-website/
├── index.php                 # Homepage
├── services.php              # Products page
├── cart.php                  # Shopping cart
├── checkout.php              # Checkout process
├── order_confirmation.php    # Order confirmation
├── orders.php                # Customer order history
├── login.php                 # User login
├── register.php               # User registration
├── about.php                 # About page
├── contact.php               # Contact page
├── payment_methods.php       # Payment guide
├── header.php                # Site header
├── footer.php                # Site footer
├── admin/                    # Admin panel
│   ├── dashboard.php
│   ├── login.php
│   ├── services.php
│   ├── orders.php
│   └── payment_settings.php
├── includes/                 # Core files
│   ├── config.php           # Database configuration
│   └── auth.php             # Authentication
├── assets/                  # Static files
│   └── css/
│       └── style.css        # Main stylesheet
├── uploads/                 # Product images
├── database_setup.sql       # Database schema
└── README.md               # This file
```

## 👥 Team

This project was developed by:
- Arillano
- Elegido
- Libunao
- Licuanan

**Course:** [CPE 4A]
**Professor:** [JAN JAN CRUZ]
**Date:** March 2026

## 📄 License

This project is for educational purposes as part of our coursework requirements.

---

**🎉 Enjoy exploring PTL Best Tinapa's online store!**

For any questions or issues, please contact the development team.
