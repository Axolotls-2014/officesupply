# System Installation Guide

This guide will walk you through the steps required to install and set up the system using CodeIgniter 3. Follow the instructions carefully to ensure a successful installation.

## Prerequisites

Before you begin, make sure you have the following installed on your server:

- **Web Server**: Apache, Nginx, or another compatible web server
- **PHP**: Version 8.0 
- **MySQL**: Version 5.1 or higher (or a compatible database system)
- **CodeIgniter 3**: Ensure you have the system files uploaded to your server

## Installation Steps

### 1. Create a Database and User via cPanel

If you're installing on a server with cPanel access, you can create a new database and user through the cPanel interface.

#### Steps to Create a Database and User:

1. **Log in to cPanel**:
   
   Access your cPanel dashboard by navigating to `http://your-domain.com/cpanel` and entering your credentials.

2. **Navigate to MySQL® Databases**:
   
   In the **Databases** section, click on **MySQL® Databases**.

3. **Create a New Database**:
   
   - **Add a New Database**:
     - Under **Create New Database**, enter a name for your database (e.g., `system_db`).
     - Click **Create Database**.
   
   - **Note**: Make a note of the exact database name as cPanel may prefix it with your cPanel username (e.g., `cpuser_system_db`).

4. **Create a New Database User**:
   
   - **Add a New User**:
     - Scroll down to the **MySQL Users** section.
     - Under **Add New User**, enter a **Username** (e.g., `system_user`) and a **Password**. Make sure to use a strong password.
     - Click **Create User**.
   
   - **Note**: Similar to databases, cPanel may prefix the username with your cPanel username (e.g., `cpuser_system_user`).

5. **Assign User to Database**:
   
   - **Add User to Database**:
     - Scroll to the **Add User To Database** section.
     - Select the user you just created from the **User** dropdown.
     - Select the database you created from the **Database** dropdown.
     - Click **Add**.
   
   - **Set Privileges**:
     - On the next screen, check **All Privileges** to grant the user full access to the database.
     - Click **Make Changes**.

6. **Note Your Database Credentials**:
   
   - **Database Name**: e.g., `cpuser_system_db`
   - **Database Username**: e.g., `cpuser_system_user`
   - **Password**: The password you set for the user

### 2. Configure `database.php`

1. **Navigate to the Config File**:
   
   Open the `application/config/database.php` file in your CodeIgniter project.

2. **Set Database Connection Settings**:
   
   Replace the default settings with your database credentials:

   ```php
   $db['default'] = array(
       'dsn'      => '',
       'hostname' => 'localhost',
       'username' => 'cpuser_system_user',   // Your database username
       'password' => 'your_password',        // Your database password
       'database' => 'cpuser_system_db',     // Your database name
       'dbdriver' => 'mysqli',
       'dbprefix' => '',
       'pconnect' => FALSE,
       'db_debug' => (ENVIRONMENT !== 'production'),
       'cache_on' => FALSE,
       'cachedir' => '',
       'char_set' => 'utf8',
       'dbcollat' => 'utf8_general_ci',
       'swap_pre' => '',
       'encrypt'  => FALSE,
       'compress' => FALSE,
       'stricton' => FALSE,
       'failover' => array(),
       'save_queries' => TRUE
   );
