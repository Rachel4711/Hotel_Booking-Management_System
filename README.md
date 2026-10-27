# Hotel Booking & Management System

A full-stack hotel booking and administration platform built with Laravel. Customers can discover hotels across Malaysia, check room availability, make payments, and manage their bookings, while administrators can manage hotels, rooms, customers, and reservations through a dedicated dashboard.

## Features

### Customer

- Register, sign in, sign out, and reset a forgotten password
- Search for hotels by name or Malaysian state
- View hotel details, room types, prices, capacity, and policies
- Check room availability for selected dates
- Calculate booking prices before confirmation
- Book one or more rooms for a selected stay
- Complete simulated payments using supported payment methods
- View booking history and booking details
- Cancel eligible bookings
- View and update profile information
- Receive hotel recommendations based on popularity

### Administrator

- Secure administrator authentication
- Dashboard for monitoring the system
- Create, view, edit, and delete hotels
- Create, view, edit, and delete rooms
- View and manage customer accounts
- View and manage bookings
- Update administrator profile information

## Technology Stack

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** Blade, Tailwind CSS 4, JavaScript
- **Build tool:** Vite 7
- **Database:** MySQL
- **Testing:** PHPUnit
- **Package managers:** Composer and npm

## Requirements

Install the following software before setting up the project:

- PHP 8.2 or newer
- Composer
- Node.js and npm
- MySQL or MariaDB
- Git

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Rachel4711/Hotel_Booking-Management_System.git
   cd Hotel_Booking-Management_System
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Install frontend dependencies:

   ```bash
   npm install
   ```

4. Create the environment configuration:

   **Windows Command Prompt:**

   ```cmd
   copy .env.example .env
   ```

   **macOS/Linux:**

   ```bash
   cp .env.example .env
   ```

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Create a MySQL database named `Hotel_Management`.

7. Update the database settings in `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=Hotel_Management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

8. Create the tables and load sample data:

   ```bash
   php artisan migrate --seed
   ```

9. Create the public storage link:

   ```bash
   php artisan storage:link
   ```

## Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

In a second terminal, start Vite:

```bash
npm run dev
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

Alternatively, run the combined Laravel development command:

```bash
composer run dev
```

## Demo Accounts

The database seeder creates the following accounts for local demonstration purposes:

| Role | Email | Password |
| --- | --- | --- |
| Administrator | `admin@example.com` | `password` |
| Customer | `user@example.com` | `password` |

The administrator login page is available at `/admin/login`.

> These credentials are intended only for local development. Change or remove them before deploying the application.

## Testing

Run the automated test suite with:

```bash
php artisan test
```

## Production Build

Compile optimized frontend assets with:

```bash
npm run build
```

Before deploying, set `APP_ENV=production` and `APP_DEBUG=false`, use secure credentials, and configure the web server to serve the Laravel `public` directory.

## Project Structure

```text
app/             Application models, controllers, policies, and services
database/        Migrations, factories, and sample-data seeders
public/          Public entry point and static assets
resources/       Blade templates, CSS, and JavaScript source files
routes/          Web route definitions
storage/         Logs, cache, sessions, and user-uploaded files
tests/           Automated tests
```

## Security Notes

- Never commit `.env`, database files, API keys, or production credentials.
- Keep `vendor/`, `node_modules/`, logs, caches, and generated files out of Git.
- Treat the included payment workflow as a demonstration unless it is connected to a secure payment provider and reviewed for production use.

## License

This project is intended for educational use. Add a `LICENSE` file if you want to distribute it under a specific open-source license.
