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
- **Caching**: Symfony Cache
- **Validation**: Custom validation components
- **Testing**: PHPUnit

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP 8.1 or higher
- Composer (PHP dependency manager)
- MySQL 5.7+ or MariaDB 10.2+
- Apache or Nginx web server with PHP support
- Git (for cloning the repository)

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/kta-pcta-backend.git
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

4. **Configure the application**:
   - Review and update `config/app.conf` with your specific settings:
     - `secret`: Application secret key for JWT signing
     - `dev_mode`: Set to `false` for production
     - `member_image`: Directory for storing member images
     - `member_qrcode_image`: Directory for storing member QR codes

5. **Set up web server**:
   - Configure your web server to point to the project's root directory
   - Ensure the `public` directory is set as the document root
   - Configure URL rewriting to direct all requests to `index.php`

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

## API Endpoints

### Member Endpoints

- `GET /member` - Retrieve all members with pagination
- `GET /member/{id}` - Retrieve a specific member by ID
- `POST /province/{pid}/municipal/{mid}/district/{did}/subdistrict/{sid}/member` - Create a new member
- `PUT /member/{id}` - Update an existing member
- `DELETE /member/{id}` - Delete a member
- `PATCH /member/{id}/upload-image` - Upload member image

### Location Endpoints

- `GET /province` - Retrieve all provinces
- `GET /province/{id}` - Retrieve a specific province
- `GET /province/{pid}/municipal` - Retrieve municipalities in a province
- `GET /province/{pid}/municipal/{mid}/district` - Retrieve districts in a municipality
- `GET /province/{pid}/municipal/{mid}/district/{did}/subdistrict` - Retrieve subdistricts in a district

## Running the Application

### Development Server

For development purposes, you can use PHP's built-in server:

```bash
php -S localhost:8000 -t .
```

Then access the API at `http://localhost:8000`.

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

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, contact the development team or open an issue in the repository.

## Authors

- **Paulus Gandung Prakosa** - *Initial work* - [gandung](mailto:gandung@infradead.org)

## Acknowledgments

- Thanks to the Slim Framework team for providing an excellent PHP micro-framework
- Thanks to the Doctrine project for the ORM implementation
- Thanks to all contributors who have helped with the development of this project