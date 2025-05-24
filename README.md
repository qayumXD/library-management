# Library Management System

A modern web application for managing library resources, built with Laravel and Tailwind CSS.

## Features

- Book management (add, edit, delete, view)
- Member management
- Borrowing system
- Category management
- Author management
- Dashboard with statistics
- User authentication
- Responsive design

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js 16.x or higher
- NPM (comes with Node.js)
- Git

## Installation Steps for Windows

1. **Install Required Software**
   - Install [XAMPP](https://www.apachefriends.org/download.html) (includes PHP and MySQL)
   - Install [Composer](https://getcomposer.org/download/)
   - Install [Node.js](https://nodejs.org/) (LTS version recommended)
   - Install [Git](https://git-scm.com/download/win)

2. **Clone the Repository**
   ```bash
   git clone https://github.com/qayumXD/library-management.git
   cd library-management
   ```

3. **Install PHP Dependencies**
   ```bash
   composer install
   ```

4. **Install Node.js Dependencies**
   ```bash
   npm install
   ```

5. **Environment Setup**
   - Copy `.env.example` to `.env`
   ```bash
   copy .env.example .env
   ```
   - Generate application key
   ```bash
   php artisan key:generate
   ```

6. **Database Configuration**
   - Start XAMPP Control Panel
   - Start Apache and MySQL services
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `library_management`
   - Update `.env` file with database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=library_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

8. **Build Assets**
   ```bash
   npm run build
   ```

9. **Start the Development Server**
   ```bash
   php artisan serve
   ```

10. **Access the Application**
    - Open your browser and visit: http://localhost:8000
    - Register a new account to get started

## Default Login Credentials

After running migrations, you can register a new account through the registration page.

## Troubleshooting

1. **Port Already in Use**
   - If port 8000 is already in use, you can specify a different port:
   ```bash
   php artisan serve --port=8001
   ```

2. **Permission Issues**
   - Ensure the `storage` and `bootstrap/cache` directories are writable
   ```bash
   php artisan storage:link
   ```

3. **Database Connection Issues**
   - Verify MySQL service is running in XAMPP
   - Check database credentials in `.env` file
   - Ensure database `library_management` exists

4. **Composer Issues**
   - If you encounter SSL certificate issues, run:
   ```bash
   composer config -g disable-tls true
   ```

## Development

- Run development server with hot reload:
  ```bash
  npm run dev
  ```

- Watch for changes:
  ```bash
  npm run watch
  ```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
