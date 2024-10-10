# Test App

A demo application to illustrate how Test App works.

## Requirements

## XAMPP

-   **Version:** XAMPP 7.x or newer
-   **Included Components:**
    -   Apache (Web server)
    -   MySQL / MariaDB (Database server)
    -   PHP (Server-side programming language)
    -   phpMyAdmin (Tool for managing MySQL/MariaDB databases)

### Installing XAMPP

1. Download the XAMPP installer package from the [official Apache Friends website](https://www.apachefriends.org/index.html).
2. Run the installer and activate the Apache and MySQL modules via the XAMPP Control Panel.
3. Then, run the xampp and start apache & mysql.
4. Create the new database, see [Rumah Web Tutorial](https://www.rumahweb.com/journal/membuat-database-di-xampp/)

## Composer Package Manager

-   **Version:** Composer 2.x or newer
-   **PHP Requirements:** Minimum version 7.2.5
-   **Required PHP Extensions:**
    -   intl

### Installing Composer

1. Download the Composer installer from the [official Composer website](https://getcomposer.org/download/).
2. Run the installer and select the PHP version installed with XAMPP.
3. Verify the Composer installation by running:

```bash
composer --version
```

## Installation

Clone the repo or download locally:

```sh
git clone https://github.com/mframadann/test-app.git && cd test-app
```

Install PHP dependencies:

```sh
composer install
```

Setup configuration:

```sh
cp .env.example .env
```

Then, opened the `.env` file and you will see this block:

```sh
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

Generate application key:

```sh
php artisan key:generate
```

Run database migrations:

```sh
php artisan migrate
```

Create an admin user:

```sh
php artisan make:filament-user
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```
