    # SDO San Pedro City Daily Time Record (DTR) System

## Overview

This project is a Daily Time Record (DTR) management system designed for the Schools Division Office (SDO) of San Pedro City. The system facilitates efficient tracking and monitoring of employee attendance, particularly for work-from-home arrangements and other flexible work setups.

## Background

This system was developed in response to **DepEd Memo 19 s.2026**, which provides guidelines for attendance monitoring and time record management for Department of Education personnel.

## Purpose

The SDO San Pedro City DTR System aims to:

- Streamline the process of recording daily time records
- Support work-from-home and hybrid work arrangements
- Ensure compliance with DepEd policies and regulations
- Provide accurate attendance tracking and reporting
- Facilitate administrative oversight and HR management

## Features

- Daily time record logging
- Support for various work arrangements (office-based, work-from-home, hybrid)
- User authentication and role-based access control
- Attendance reports and analytics
- Administrative dashboard for HR and management
- Compliance with DepEd Memo 19 s.2026 requirements

## Technology Stack

- **Server Environment**: XAMPP (Apache, MySQL, PHP)
- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript

## Installation

For detailed installation instructions, please see [INSTALL.md](INSTALL.md)

### Quick Start

1. **Prerequisites**
   - XAMPP installed and running
   - Apache and MySQL services started

2. **Database Setup**
   ```bash
   # Import database schema using phpMyAdmin
   # Navigate to: http://localhost/phpmyadmin
   # Import: database/schema.sql
   ```

3. **Configure Database**
   - Verify settings in `config/database.php`
   - Default: host=localhost, user=root, pass=(empty)

4. **Access the System**
   - Landing page: `http://localhost/sdosanpedrocitydtr/`
   - Login page: `http://localhost/sdosanpedrocitydtr/login.php`
   
5. **Default Login Credentials**
   - Email: `admin@sdosanpedrocity.edu.ph`
   - Password: `admin123`
   - **⚠️ Change default password after first login!**

## Usage

1. Log in with your credentials
2. Record your daily time (time in/time out)
3. Specify your work arrangement (office/work-from-home)
4. Submit your DTR for approval
5. View reports and attendance history

## Target Users

- DepEd teaching and non-teaching personnel
- HR administrators
- School division supervisors
- Office heads and managers

## Compliance

This system is developed in accordance with:
- **DepEd Memo 19 s.2026** - Guidelines on attendance and time record management
- Civil Service Commission regulations
- Department of Education policies

## Project Structure

```
sdosanpedrocitydtr/
├── config/
│   ├── database.php       # Database configuration & PDO connection
│   └── session.php        # Session management & authentication
├── database/
│   └── schema.sql         # Database schema with tables & default data
├── img/
│   └── feat coming soon.png
├── index.php              # Landing page
├── login.php              # User authentication
├── logout.php             # Logout handler
├── dashboard.php          # User dashboard with DTR overview
├── record-dtr.php         # DTR recording form
├── README.md              # Project documentation
└── INSTALL.md             # Installation guide
```

## Database Schema

The system uses MySQL with the following main tables:

- **users** - Employee accounts and authentication
- **dtr_records** - Daily time record entries
- **leave_requests** - Leave application management
- **activity_logs** - System activity tracking
- **settings** - System configuration

See `database/schema.sql` for complete schema details.

## Support

For issues, questions, or support, please contact:
- SDO San Pedro City ICT Office
- Email: [contact email]
- Phone: [contact number]

## License

This project is developed for the exclusive use of the Schools Division Office of San Pedro City, Department of Education.

## Acknowledgments

- Schools Division Office of San Pedro City
- Department of Education
- All stakeholders and contributors to this project

---

**Version**: 1.0.0  
**Last Updated**: March 9, 2026  
**Developed for**: SDO San Pedro City, DepEd
