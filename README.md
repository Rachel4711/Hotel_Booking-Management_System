# Hotel Management System

## Hello Fellow Teammates!

Follow the steps below to set up and run this project on your local machine.

---

## Project Setup

### 1. Clone the Repository

Clone this repository using **GitHub Desktop** or Git:

```
git clone <repository-url>
```

---

### 2. Install Dependencies

Install the required Node.js packages:

```
npm install
```

---

### 3. Run Vite (Frontend Assets)

Run the following command to compile CSS and JavaScript files:

```
npm run dev
```

Make sure this command is running during development so that assets update automatically.

---

### 4. Create Database

Create a database named:

```
Hotel_Management
```

You can create it using **phpMyAdmin**, **MySQL Workbench**, or any other database management tool.

---

### 5. Run Migrations and Seeders

Run the following command to create all database tables and insert sample data:

```
php artisan migrate --seed
```

---

## Importing CSS or JavaScript

If you want to import any **CSS** or **JavaScript** file, use the following Vite format:

```
@vite([
    'resources/css/Admin/admin.css',
    'resources/js/Admin/admin.js'
])
```

You can also refer to the example located at:

```
resources/views/Layouts/admin.blade.php
```

---

## Notes

* Ensure that `npm run dev` is running when developing the project.
* If you encounter database issues, check your `.env` file to ensure the database configuration is correct.

