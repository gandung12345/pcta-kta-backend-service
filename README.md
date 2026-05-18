# PCTA KTA Backend Service

This is the repository for the member registration backend service for PCTA (Persaudaraan Cinta Tanah Air) Indonesia. The service provides a RESTful API for managing member registrations, including personal information, contact details, and administrative data.

## Project Overview

The PCTA KTA Backend Service is a PHP-based API built with modern frameworks and follows best practices for web application development. It provides endpoints for managing members, provinces, municipalities, districts, and subdistricts with full CRUD operations.

### Key Features

- Member management (registration, updates, deletion)
- Hierarchical location data management (provinces, municipalities, districts, subdistricts)
- RESTful API design with JSON responses
- Database integration with MySQL via Doctrine ORM
- API documentation with OpenAPI/Swagger annotations
- JWT-based authentication support
- QR code generation for members
- Image upload functionality
- Pagination and filtering capabilities

## Technical Stack

- **Language**: PHP 8.1+
- **Framework**: Slim Framework 4.x
- **ORM**: Doctrine ORM 3.x
- **Database**: MySQL/MariaDB
- **API Documentation**: OpenAPI (Swagger) annotations
- **Authentication**: JWT tokens
- **Caching**: Custom caching module based on Symfony caching component
- **Validation**: Custom validation components
- **Testing**: PHPUnit

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP 8.1 or higher
- Composer (PHP dependency manager)
- MySQL 5.7+ or MariaDB 10.2+
- Nginx (web server)
- Git (for cloning the repository)

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/gandung12345/kta-pcta-backend.git
   cd kta-pcta-backend
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Database Setup**:
   - Create a MySQL database for the application:
     ```sql
     CREATE DATABASE kta_pcta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   - Update the database configuration in `config/database.conf` with your database credentials.

4. **Database Migration**
   - Enter ```bin``` directory and run:
     ```bash
     $ ./doctrine orm:clear-cache:metadata
     $ ./doctrine orm:clear-cache:query
     $ ./doctrine orm:schema-tool:create
     ```

5. **Configure the application**:
   - Review and update `config/app.conf` with your specific settings:
     - `secret`: Application secret key for JWT signing
     - `dev_mode`: Set to `false` for production
     - `member_image`: Directory for storing member images
     - `member_qrcode_image`: Directory for storing member QR codes

6. **Directory permissions**:
   - Ensure the web server has write permissions to:
     - Member image directory (default: `/home/pwn/.local/share/member-image`)
     - Member QR code directory (default: `/home/pwn/.local/share/member-qrcode-image`)
     - `var` directory for cache files

## Configuration

### Database Configuration

The database configuration is located in `config/database.conf`:

```ini
[database]
driver = 'pdo_mysql'
host = '127.0.0.1'
port = 3306
schema = 'kta_pcta'
user = 'your_database_user'
password = 'your_database_password'
charset = 'utf8'
```

### Application Configuration

The application configuration is located in `config/app.conf`:

```ini
[app]
secret = 'your_secret_key_here'
dev_mode = true
member_image = '/path/to/member/images'
member_qrcode_image = '/path/to/qr/codes'
```

## Running the Application

### Development Server

For development purposes, you can use PHP's built-in server:

```bash
php -S localhost:31337 -t .
```

Then access the API at `http://localhost:31337`.

### Production Deployment

1. **Web Server Configuration**:
   - For Apache, ensure mod_rewrite is enabled and use the provided `.htaccess` rules
   - For Nginx, configure the server block to direct all requests to `index.php`

2. **Environment Variables**:
   - Set `dev_mode` to `false` in `config/app.conf`
   - Update the database credentials in `config/database.conf`
   - Ensure proper file permissions for directories

3. **Caching**:
   - In production, ensure the `var` directory is writable for cache files
   - Consider using a more robust caching solution like Redis or Memcached

## API Documentation

API documentation is available through Swagger UI. The documentation is generated from OpenAPI annotations in the code.

To access the documentation:
1. Ensure the application is running
2. Navigate to `/api/docs` (or the configured documentation path)
3. Explore the available endpoints and test them directly in the UI

## Testing

To run the test suite:

```bash
composer test
```

Or directly with PHPUnit:

```bash
./vendor/bin/phpunit
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Authors

- **Paulus Gandung Prakosa** - *Initial work* - [gandung](mailto:gandung@infradead.org)
