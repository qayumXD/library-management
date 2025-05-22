# Library Management System

A modern library management system built with Laravel and Tailwind CSS.

## Features

- User Authentication (Admin & Student roles)
- Book Management
- Author Management
- Category Management
- Borrowing System
- Dashboard with Statistics
- Responsive Design

## Requirements

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/library-management.git
cd library-management
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in .env file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations and seeders:
```bash
php artisan migrate:fresh --seed
```

8. Start the development server:
```bash
php artisan serve
```

9. In a separate terminal, start Vite:
```bash
npm run dev
```

## Default Users

After running the seeders, you can log in with these credentials:

### Admin
- Email: admin@library.com
- Password: password

### Student
- Email: student@library.com
- Password: password

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
