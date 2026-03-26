# Simple PHP Inventory Login (LAMP)

This is a minimal PHP + MySQLi login system for an inventory app starter.

## Features

- Login page with HTML/CSS/PHP
- MySQL `users` table
- Username/password validation from database
- Redirect to dashboard on successful login
- Error message on invalid login
- Logout functionality
- Category management (add/list categories)
- Product management (add/list products)
- Assign each product to a category

## Default Credentials

- Username: `admin`
- Password: `admin123`

## File Structure

- `config.php` - MySQL connection (MySQLi)
- `login.php` - login form + authentication
- `dashboard.php` - protected page after login
- `logout.php` - destroys session and redirects
- `categories.php` - add and list categories
- `products.php` - add and list products with category assignment
- `style.css` - basic styling
- `database.sql` - database/table/data setup

## Ubuntu (LAMP) Deployment Steps

1. Install Apache, MySQL, and PHP:
   - `sudo apt update`
   - `sudo apt install -y apache2 mysql-server php libapache2-mod-php php-mysql`

2. Copy project into Apache web root:
   - `sudo cp -r inventory /var/www/html/`

3. Create database and seed admin user:
   - `sudo mysql -u root -p < /var/www/html/inventory/database.sql`

   If this project already exists on your server, run the SQL file again to add the
   new `categories` and `products` tables.

4. Update DB credentials if needed in `config.php`:
   - `$db_user`, `$db_pass`, `$db_name`

5. Set permissions:
   - `sudo chown -R www-data:www-data /var/www/html/inventory`

6. Access in browser:
   - `http://YOUR_SERVER_IP/inventory/login.php`
