# Laravel Office Management System

A comprehensive Laravel-based Office Management System converted from Node.js/Express with MySQL database and DataTables integration.

## Installation & Setup

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM

### Installation Steps

1. **Create Laravel Project**
```bash
composer create-project laravel/laravel office-management-system
cd office-management-system
```

2. **Install Dependencies**
```bash
composer install
npm install

# Install additional packages
composer require yajra/laravel-datatables-oracle
npm install datatables.net-bs5 datatables.net-buttons-bs5
```
3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=office_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. **Database Setup**
```bash
php artisan migrate
php artisan db:seed
```

5. **Serve Application**
```bash
php artisan serve
```

## Key Features

### DataTables Integration
- Server-side processing for large datasets
- Advanced search and filtering
- Sortable columns
- Pagination
- Export capabilities

### Employee Management
- CRUD operations for employees
- Hierarchical supervisor-subordinate relationships
- Department assignments
- Location tracking with country/state/city
- Status management (active/inactive/terminated)
- Salary tracking

### Department Management  
- Department CRUD operations
- Budget tracking
- Employee count per department
- Prevent deletion of departments with employees

### Search & Filter Features
- Global search across all employee fields
- Filter by department
- Filter by job title/position
- Filter by status
- Combined filtering capabilities
- Real-time search results

### Validation & Security
- Form request validation
- Email uniqueness enforcement
- Referential integrity constraints
- Prevention of circular supervisor references
- XSS protection through escaped output

### Responsive Design
- Bootstrap 5 integration
- Mobile-friendly interface
- Modern UI components
- Toast notifications for user feedback

