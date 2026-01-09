# Employee Management System

Full-stack Employee Management System with Laravel REST API backend and React frontend, featuring authentication and complete CRUD operations for employee management.

## Technical Assessment Project

This project is a technical assessment demonstrating:
- RESTful API development with Laravel 12
- Token-based authentication using Laravel Sanctum
- Complete CRUD operations for employee management
- Form Request validation with comprehensive security measures
- React frontend consuming the API (coming soon)
- Clean code practices following Single Responsibility Principle
- Professional Git workflow

---

## Technologies Used

### Backend
- **Framework:** Laravel 12.46.0
- **Authentication:** Laravel Sanctum 4.0
- **Database:** MySQL 9.5.0
- **PHP Version:** 8.4.16
- **Key Packages:** Laravel Sanctum, Eloquent ORM

### Frontend (Coming Soon)
- **Framework:** React
- **Build Tool:** Vite
- **HTTP Client:** Axios
- **Routing:** React Router

---

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP 8.2 or higher (developed with PHP 8.4.16)
- Composer
- MySQL 8.0 or higher (developed with MySQL 9.5.0)
- Node.js 18+ and npm (for frontend)
- Git

---

## Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/m-alhamry/employee-management-system.git
cd employee-management-system
```

### 2. Backend Setup

For detailed backend installation instructions, see [Backend Installation Guide](backend/INSTALLATION_GUIDE.md).

```bash
cd backend
composer install
cp .env.example .env
# Configure your database credentials in .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

### 3. Default Login Credentials

```
Email: admin@example.com
Password: password
```

---

## API Documentation

### Base URL
```
http://127.0.0.1:8000/api
```

### Authentication Endpoints

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}

Response: 200 OK
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com"
  }
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}

Response: 200 OK
{
  "message": "Logged out successfully"
}
```

#### Get Authenticated User
```http
GET /api/user
Authorization: Bearer {token}

Response: 200 OK
{
  "id": 1,
  "name": "Admin User",
  "email": "admin@example.com"
}
```

### Employee CRUD Endpoints

All employee endpoints require authentication via Bearer token.

#### Get All Employees
```http
GET /api/employees
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "name": "Alice Johnson",
      "email": "alice.johnson@company.com",
      "position": "Software Engineer",
      "salary": "85000.00",
      "status": "active",
      "created_at": "2026-01-09T10:19:22.000000Z",
      "updated_at": "2026-01-09T10:19:22.000000Z"
    }
  ]
}
```

#### Get Single Employee
```http
GET /api/employees/{id}
Authorization: Bearer {token}

Response: 200 OK
{
  "data": {
    "id": 1,
    "name": "Alice Johnson",
    "email": "alice.johnson@company.com",
    "position": "Software Engineer",
    "salary": "85000.00",
    "status": "active",
    "created_at": "2026-01-09T10:19:22.000000Z",
    "updated_at": "2026-01-09T10:19:22.000000Z"
  }
}
```

#### Create Employee
```http
POST /api/employees
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john.doe@company.com",
  "position": "Backend Developer",
  "salary": 90000,
  "status": "active"
}

Response: 201 Created
{
  "data": {
    "id": 16,
    "name": "John Doe",
    "email": "john.doe@company.com",
    "position": "Backend Developer",
    "salary": "90000.00",
    "status": "active",
    "created_at": "2026-01-09T10:46:12.000000Z",
    "updated_at": "2026-01-09T10:46:12.000000Z"
  }
}
```

#### Update Employee
```http
PUT /api/employees/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john.doe@company.com",
  "position": "Senior Backend Developer",
  "salary": 105000,
  "status": "active"
}

Response: 200 OK
{
  "data": {
    "id": 16,
    "name": "John Doe",
    "email": "john.doe@company.com",
    "position": "Senior Backend Developer",
    "salary": "105000.00",
    "status": "active",
    "created_at": "2026-01-09T10:46:12.000000Z",
    "updated_at": "2026-01-09T10:46:52.000000Z"
  }
}
```

#### Delete Employee
```http
DELETE /api/employees/{id}
Authorization: Bearer {token}

Response: 204 No Content
```

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200  | OK - Request successful |
| 201  | Created - Resource created successfully |
| 204  | No Content - Resource deleted successfully |
| 401  | Unauthorized - Invalid or missing token |
| 404  | Not Found - Resource not found |
| 422  | Unprocessable Entity - Validation errors |
| 429  | Too Many Requests - Rate limit exceeded (5 login attempts/minute) |

---

## Authentication Flow

This project uses **Laravel Sanctum** for token-based authentication:

1. **Login:** User submits email and password to `/api/login`
2. **Token Generation:** Server validates credentials and returns a Bearer token
3. **Token Storage:** Frontend stores token in localStorage
4. **Authenticated Requests:** Frontend includes token in Authorization header: `Authorization: Bearer {token}`
5. **Token Validation:** Laravel Sanctum middleware validates token on protected routes
6. **Logout:** User calls `/api/logout` to revoke token from database

### Security Features

- Rate limiting: 5 login attempts per minute to prevent brute force attacks
- Token-based authentication (stateless)
- Tokens stored securely in database (hashed)
- Token revocation on logout
- All employee endpoints protected with `auth:sanctum` middleware

For comprehensive security documentation, see [Security Documentation](backend/SECURITY.md).

---

## Validation Rules

### Employee Fields

| Field    | Validation Rules |
|----------|------------------|
| name     | Required, string, min 2 chars, max 255 chars, letters/spaces/hyphens/dots/apostrophes only |
| email    | Required, valid email format, max 255 chars, unique in database |
| position | Required, string, min 2 chars, max 255 chars, letters/spaces/hyphens/dots/apostrophes only |
| salary   | Required, numeric, min 0, max 9,999,999.99 |
| status   | Required, must be 'active' or 'inactive' |

### Validation Error Response Example
```json
{
  "message": "The name field must be at least 2 characters. (and 2 more errors)",
  "errors": {
    "name": ["The name field must be at least 2 characters."],
    "email": ["The email field must be a valid email address."],
    "salary": ["The salary must not exceed 9,999,999.99."]
  }
}
```

---

## Project Structure

```
employee-management-system/
├── backend/                    # Laravel API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   └── Api/
│   │   │   │       ├── AuthController.php
│   │   │   │       └── EmployeeController.php
│   │   │   └── Requests/
│   │   │       ├── LoginRequest.php
│   │   │       ├── StoreEmployeeRequest.php
│   │   │       └── UpdateEmployeeRequest.php
│   │   └── Models/
│   │       ├── User.php
│   │       └── Employee.php
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │       ├── UserSeeder.php
│   │       ├── EmployeeSeeder.php
│   │       └── DatabaseSeeder.php
│   ├── routes/
│   │   └── api.php
│   ├── API_TESTING_GUIDE.md
│   ├── INSTALLATION_GUIDE.md
│   ├── SECURITY.md
│   └── COMPLIANCE_CHECKLIST.md
├── frontend/                   # React App (Coming Soon)
├── PROJECT_PLAN.md            # Implementation roadmap
├── USER_STORIES.md            # User stories and acceptance criteria
├── DATABASE_DESIGN.md         # Database schema and ERD
├── BEST_PRACTICES.md          # Coding standards and guidelines
└── README.md                  # This file
```

---

## Documentation

### Planning & Design
- [Project Plan](PROJECT_PLAN.md) - Complete implementation roadmap with timeline
- [User Stories](USER_STORIES.md) - Detailed user stories and acceptance criteria
- [Database Design](DATABASE_DESIGN.md) - Database schema, ERD, and relationships
- [Best Practices](BEST_PRACTICES.md) - Coding standards and SRP guidelines

### Backend Documentation
- [Installation Guide](backend/INSTALLATION_GUIDE.md) - Detailed backend setup instructions
- [API Testing Guide](backend/API_TESTING_GUIDE.md) - Complete guide for testing all endpoints
- [Security Documentation](backend/SECURITY.md) - Security measures and testing results
- [Compliance Checklist](backend/COMPLIANCE_CHECKLIST.md) - Requirements verification

---

## Features Implemented

### Backend (Laravel API)
- ✅ RESTful API with 8 endpoints
- ✅ Laravel Sanctum token-based authentication
- ✅ Complete CRUD operations for employees
- ✅ Form Request validation with comprehensive security
- ✅ Rate limiting (brute force protection)
- ✅ XSS and SQL injection protection
- ✅ Overflow protection with max constraints
- ✅ Data sanitization (trim, lowercase)
- ✅ Proper HTTP status codes (200, 201, 204, 401, 404, 422, 429)
- ✅ Database seeders with 15 sample employees
- ✅ Clean code following Single Responsibility Principle
- ✅ Small, focused functions (3-10 lines each)

### Frontend (React) - Coming Soon
- ⏳ Login page with authentication
- ⏳ Employees list page
- ⏳ Add/Edit employee forms
- ⏳ Delete confirmation dialog
- ⏳ Error handling and loading states
- ⏳ Responsive design

---

## Testing

### Manual API Testing

All endpoints have been tested using curl:

- ✅ Login with valid credentials → 200 + token
- ✅ Login with invalid credentials → 401
- ✅ Login rate limiting → 429 after 5 attempts
- ✅ Logout → 200 + token revoked
- ✅ Get all employees → 200 + 15 employees
- ✅ Get single employee → 200 + employee data
- ✅ Create employee → 201 + created data
- ✅ Update employee → 200 + updated data
- ✅ Delete employee → 204
- ✅ Validation errors → 422 + field errors
- ✅ XSS attempt → 422 + regex error
- ✅ SQL injection attempt → 422 + regex error
- ✅ Overflow attempt → 422 + max error
- ✅ Unauthorized access → 401

### How to Test the API

For complete testing instructions including cURL commands, Postman setup, automated scripts, and security testing, see the [API Testing Guide](backend/API_TESTING_GUIDE.md).

**Quick Test:**
```bash
# 1. Login and get token
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# 2. Get all employees (replace TOKEN with actual token from step 1)
curl -X GET http://127.0.0.1:8000/api/employees \
  -H "Authorization: Bearer TOKEN"
```

The testing guide includes:
- ✅ cURL commands for all endpoints
- ✅ Postman/Insomnia collection setup
- ✅ Automated test script
- ✅ Security testing scenarios
- ✅ Error handling examples

---

## Code Quality

### Best Practices Followed
- ✅ **Single Responsibility Principle:** Each function does one thing (3-10 lines)
- ✅ **KISS:** Keep it simple, no over-engineering
- ✅ **DRY:** Don't repeat yourself, reusable helper methods
- ✅ **Clean Code:** Meaningful names, proper formatting
- ✅ **Security First:** Comprehensive validation and sanitization
- ✅ **PSR-12:** Laravel coding standards

### Example: Small, Focused Functions
```php
public function store(StoreEmployeeRequest $request): JsonResponse
{
    $employee = $this->createEmployee($request->validated());
    return $this->createdResponse($employee);
}

private function createEmployee(array $data): Employee
{
    return Employee::create($data);
}
```

---

## Development Status

### Completed ✅
- [x] Laravel backend setup
- [x] MySQL database configuration
- [x] Laravel Sanctum authentication
- [x] Database migrations and seeders
- [x] User and Employee models
- [x] Authentication API (login/logout)
- [x] Employee CRUD API (full CRUD)
- [x] Form Request validation
- [x] Security enhancements (XSS, SQL injection, rate limiting)
- [x] API testing and verification
- [x] Documentation (planning, security, installation)

### In Progress 🚧
- [ ] React frontend development
- [ ] Frontend-backend integration
- [ ] UI/UX implementation

### Planned 📋
- [ ] Deployment configuration
- [ ] Final testing and bug fixes
- [ ] Project submission

---

## Contributing

This is a technical assessment project and is not open for contributions.

---

## License

This project is created for assessment purposes only.

---

## Contact

**Author:** Mohamed Alhamry
**Repository:** [https://github.com/m-alhamry/employee-management-system](https://github.com/m-alhamry/employee-management-system)
**Assessment Deadline:** January 12th, 2026

---

## Acknowledgments

- Laravel Framework
- Laravel Sanctum
- React (upcoming)
- Technical Assessment provided by Tasneem Alrababah
