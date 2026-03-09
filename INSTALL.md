# SDO San Pedro City DTR System - Installation Guide

## Prerequisites

Before installing, ensure you have:
- XAMPP (Apache, MySQL, PHP) installed and running
- Web browser
- Text editor (optional)

## Installation Steps

### Step 1: Database Setup

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache and MySQL services

2. **Access phpMyAdmin**
   - Open your browser and go to: `http://localhost/phpmyadmin`
   - Login with default credentials (usually no password for root)

3. **Import Database**
   - Click on "New" in the left sidebar to create a new database
   - Or simply import the SQL file directly:
     - Click on "Import" tab
     - Click "Choose File" and select: `database/schema.sql`
     - Click "Go" to import the database

   **Alternatively, you can run the SQL manually:**
   - Copy the contents of `database/schema.sql`
   - Paste it in the SQL tab and click "Go"

### Step 2: Configure Database Connection

1. **Check Database Configuration**
   - Open `config/database.php`
   - Verify the database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'sdosanpedrocity_dtr');
     ```
   - Modify if your MySQL settings are different

### Step 3: Access the System

1. **Open the Landing Page**
   - Navigate to: `http://localhost/sdosanpedrocitydtr/`

2. **Login to the System**
   - Click on "Login" button
   - Use default admin credentials:
     - **Email:** `admin@sdosanpedrocity.edu.ph`
     - **Password:** `admin123`

3. **Change Default Password (Important!)**
   - After first login, change the default admin password immediately
   - Go to Settings/Profile and update your password

## Default Admin Account

- **Employee ID:** ADMIN001
- **Email:** admin@sdosanpedrocity.edu.ph
- **Password:** admin123
- **Role:** Administrator

⚠️ **Security Warning:** Change the default password immediately after first login!

## Troubleshooting

### Database Connection Error

If you see a database connection error:
1. Check if MySQL service is running in XAMPP
2. Verify database credentials in `config/database.php`
3. Ensure the database was created successfully

### Cannot Access the Site

If you can't access `http://localhost/sdosanpedrocitydtr/`:
1. Check if Apache is running in XAMPP
2. Verify the project folder is in `C:\xampp\htdocs\`
3. Try restarting Apache in XAMPP

### Page Not Found Error

If you get 404 errors:
1. Ensure all PHP files are in the correct location
2. Check file names are spelled correctly
3. Verify Apache is running

### Session Errors

If you see session-related errors:
1. Check if PHP has write permissions to the session directory
2. Restart Apache in XAMPP

## File Structure

```
sdosanpedrocitydtr/
├── config/
│   ├── database.php      # Database configuration and connection
│   └── session.php       # Session management functions
├── database/
│   └── schema.sql        # Database schema and initial data
├── img/
│   └── feat coming soon.png  # Landing page image
├── index.php             # Landing page
├── login.php             # Login page
├── logout.php            # Logout handler
├── dashboard.php         # User dashboard
├── record-dtr.php        # DTR recording page
├── README.md             # Project documentation
└── INSTALL.md            # This installation guide
```

## Next Steps

After installation:
1. Login with admin account
2. Change default password
3. Create user accounts for employees
4. Configure system settings
5. Start recording DTR entries

## Support

For technical issues or questions:
- Contact: SDO San Pedro City ICT Office
- Email: [insert ICT email here]

---

**Installation Date:** <?php echo date('F d, Y'); ?>  
**System Version:** 1.0.0
