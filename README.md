# Employee Management System

A full-stack web application for managing employee records with authentication, built with Laravel (backend API) and React (frontend).

> **Note:** This repository contains both backend and frontend in a monorepo structure for simplicity and assessment purposes. In production environments, it's best practice to maintain separate repositories for backend and frontend to enable independent deployment, versioning, and team workflows.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Prerequisites](#prerequisites)
- [Quick Start](#quick-start)
- [Project Structure](#project-structure)
- [Authentication Flow](#authentication-flow)
- [API Endpoints](#api-endpoints)
- [Default Credentials](#default-credentials)
- [Documentation](#documentation)
- [Security](#security)
- [Testing](#testing)
- [Code Quality](#code-quality)
- [Assessment Compliance](#assessment-compliance)
- [License](#license)

---

## Overview

This Employee Management System is a RESTful API-based application that demonstrates:
- **Backend:** Laravel 12 REST API with Sanctum authentication
- **Frontend:** React 19 SPA with Vite
- **Database:** MySQL with migrations and seeders
- **Security:** Comprehensive input validation, XSS protection, SQL injection prevention
- **Code Quality:** Single Responsibility Principle, clean code practices

Built as a technical assessment to showcase full-stack development skills with Laravel and React.

---

## Features

### Authentication
- ✅ Token-based authentication using Laravel Sanctum
- ✅ Secure login/logout functionality
- ✅ Protected routes on both backend and frontend
- ✅ Automatic token persistence and restoration

### Employee Management (CRUD)
- ✅ View all employees in a table
- ✅ Add new employees with comprehensive validation
- ✅ Edit existing employee information
- ✅ Delete employees with confirmation dialog
- ✅ Real-time form validation

### User Experience
- ✅ Loading states during API operations
- ✅ Error handling for all scenarios (401, 404, 422, 500, network errors)
- ✅ Responsive design (mobile-friendly)
- ✅ Clean, professional UI

### Data Management
- ✅ Database migrations with proper schema design
- ✅ Seeders with sample data (15 employees, 2 users)
- ✅ Comprehensive validation rules matching database constraints

---

## Technologies Used

### Backend
- **Framework:** Laravel 12
- **Authentication:** Laravel Sanctum 4.0
- **Database:** MySQL 8.x
- **PHP:** 8.2+
- **Validation:** Form Request classes
- **Code Quality:** Laravel Pint (PSR-12)

### Frontend
- **Framework:** React 19.2.0
- **Build Tool:** Vite 7.2.4
- **Routing:** React Router DOM 7.12.0
- **HTTP Client:** Axios 1.13.2
- **State Management:** Context API
- **Code Quality:** ESLint

### Development Tools
- **Version Control:** Git & GitHub
- **Package Managers:** Composer (backend), npm (frontend)
- **Code Formatting:** Laravel Pint, ESLint

---

## Prerequisites

Before you begin, ensure you have the following installed:

### Required
- **PHP:** 8.2 or higher
- **Composer:** 2.x
- **Node.js:** 18.x or higher
- **npm:** 9.x or higher
- **MySQL:** 8.x

### Recommended
- **Laravel Herd** (includes PHP, Composer, MySQL, Node.js) - [Download](https://herd.laravel.com/)
- **Git:** For version control

### Verify Installation
```bash
php --version        # Should show PHP 8.2+
composer --version   # Should show Composer 2.x
node --version       # Should show Node 18.x+
npm --version        # Should show npm 9.x+
mysql --version      # Should show MySQL 8.x
```

**Detailed Installation Guides:**
- [Backend Installation Guide](backend/INSTALLATION_GUIDE.md)
- [Frontend Installation Guide](frontend/INSTALLATION_GUIDE.md)

---

## Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/m-alhamry/employee-management-system.git
cd employee-management-system
```

### 2. Backend Setup

```bash
# Navigate to backend directory
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=employee_management
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Generate application key
php artisan key:generate

# Create database
mysql -u root -p
CREATE DATABASE employee_management;
exit;

# Run migrations and seeders
php artisan migrate --seed

# Start the server
php artisan serve
# Backend API will run at: http://127.0.0.1:8000
```

### 3. Frontend Setup

```bash
# Open new terminal, navigate to frontend directory
cd frontend

# Install dependencies
npm install

# Start development server
npm run dev
# Frontend will run at: http://localhost:5173
```

### 4. Access the Application

1. Open your browser and navigate to: `http://localhost:5173`
2. Login with default credentials (see below)
3. Start managing employees!

---

## Project Structure

```
employee-management-system/
├── backend/                        # Laravel API
│   ├── app/Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php      # Authentication
│   │   │   └── EmployeeController.php  # Employee CRUD
│   │   └── Requests/                   # Form validations
│   ├── database/
│   │   ├── migrations/                 # Database schema
│   │   └── seeders/                    # Sample data
│   ├── routes/api.php                  # API routes
│   ├── INSTALLATION_GUIDE.md
│   ├── COMPLIANCE_CHECKLIST.md
│   ├── API_TESTING_GUIDE.md
│   └── SECURITY.md
│
├── frontend/                       # React Application
│   ├── src/
│   │   ├── components/                 # Reusable components
│   │   ├── contexts/                   # Global state
│   │   ├── hooks/                      # Custom hooks
│   │   ├── pages/                      # Page components
│   │   ├── services/                   # API calls
│   │   └── App.jsx                     # Main app
│   ├── INSTALLATION_GUIDE.md
│   └── COMPLIANCE_CHECKLIST.md
│
├── USER_STORIES.md                 # User stories
├── PROJECT_PLAN.md                 # Development plan
├── DATABASE_DESIGN.md              # Database schema
├── BEST_PRACTICES.md               # Coding standards
└── README.md                       # This file
```

---

## Authentication Flow

### How Authentication Works

1. **User Login:**
   - User submits email and password via React login form
   - Frontend sends POST request to `/api/login`
   - Backend validates credentials using Laravel Sanctum
   - On success, backend returns authentication token and user data
   - Frontend stores token in `localStorage` and sets it in Axios headers

2. **Authenticated Requests:**
   - Frontend automatically includes token in all API requests via Axios interceptor
   - Backend validates token using Sanctum middleware
   - If valid, request proceeds; if invalid, returns 401 Unauthorized

3. **Token Persistence:**
   - Token stored in browser's `localStorage`
   - On page reload, frontend checks for existing token
   - If found, automatically authenticates user

4. **User Logout:**
   - User clicks logout button
   - Frontend sends POST request to `/api/logout`
   - Backend revokes token from database
   - Frontend removes token from `localStorage` and redirects to login

5. **Protected Routes:**
   - Frontend uses `ProtectedRoute` component to guard routes
   - Unauthenticated users redirected to login page
   - Backend uses `auth:sanctum` middleware on API routes

**For detailed authentication flow, see:** [USER_STORIES.md - Epic 1](USER_STORIES.md#epic-1-authentication--authorization)

---

## API Endpoints

### Base URL
```
http://127.0.0.1:8000/api
```

### Authentication Endpoints

#### POST /api/login
Authenticate user and receive token.

**Request:**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

**Response (200 OK):**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com"
  }
}
```

---

#### POST /api/logout
Revoke authentication token.

**Headers:** `Authorization: Bearer {token}`

**Response (200 OK):**
```json
{
  "message": "Logged out successfully"
}
```

---

### Employee Endpoints

All employee endpoints require authentication via Bearer token.

#### GET /api/employees
Get all employees.

**Response (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "position": "Software Engineer",
      "salary": "75000.00",
      "status": "active"
    }
  ]
}
```

#### POST /api/employees
Create new employee.

**Request:**
```json
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "position": "Product Manager",
  "salary": 85000,
  "status": "active"
}
```

**Validation Rules:**
- `name`: required, min:2, max:255
- `email`: required, email, unique
- `position`: required, min:2, max:255
- `salary`: required, numeric, min:0, max:9999999.99
- `status`: required, 'active' or 'inactive'

**Response (201 Created)**

---

#### PUT /api/employees/{id}
Update existing employee.

**Response (200 OK)**

---

#### DELETE /api/employees/{id}
Delete employee.

**Response (204 No Content)**

---

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK |
| 201 | Created |
| 204 | No Content |
| 401 | Unauthorized |
| 404 | Not Found |
| 422 | Validation Errors |
| 429 | Too Many Requests |

**For detailed API documentation, see:** [backend/API_TESTING_GUIDE.md](backend/API_TESTING_GUIDE.md)

---

## Default Credentials

The application comes pre-seeded with test users and sample employees.

### Login Credentials

| Email | Password | Name |
|-------|----------|------|
| admin@example.com | password | Admin User |
| john@example.com | password | John Doe |

### Sample Data
- **15 employees** with various positions (Developer, Manager, Designer, HR, etc.)
- **Salary range:** $30,000 - $150,000
- **Status mix:** 12 active, 3 inactive

---

## Documentation

Comprehensive documentation is provided for all aspects of the project:

### Planning & Design
- **[USER_STORIES.md](USER_STORIES.md)** - User stories with acceptance criteria
- **[PROJECT_PLAN.md](PROJECT_PLAN.md)** - Development plan and task breakdown
- **[DATABASE_DESIGN.md](DATABASE_DESIGN.md)** - Database schema, ERD, and design decisions
- **[BEST_PRACTICES.md](BEST_PRACTICES.md)** - Coding standards and Single Responsibility Principle

### Backend Documentation
- **[backend/INSTALLATION_GUIDE.md](backend/INSTALLATION_GUIDE.md)** - Setup instructions
- **[backend/COMPLIANCE_CHECKLIST.md](backend/COMPLIANCE_CHECKLIST.md)** - Requirements verification
- **[backend/API_TESTING_GUIDE.md](backend/API_TESTING_GUIDE.md)** - API endpoint testing
- **[backend/SECURITY.md](backend/SECURITY.md)** - Security measures

### Frontend Documentation
- **[frontend/INSTALLATION_GUIDE.md](frontend/INSTALLATION_GUIDE.md)** - React app setup
- **[frontend/COMPLIANCE_CHECKLIST.md](frontend/COMPLIANCE_CHECKLIST.md)** - Requirements verification

---

## Security

This application implements comprehensive security measures:

### Input Validation & Sanitization
- ✅ All inputs validated using Laravel Form Requests
- ✅ Email addresses auto-lowercased and trimmed
- ✅ Name and position fields validated with regex patterns
- ✅ Salary capped at 9,999,999.99 to prevent overflow
- ✅ Status field restricted to enum values

### Attack Prevention
- ✅ **SQL Injection:** Prevented via Eloquent ORM with parameterized queries
- ✅ **XSS (Cross-Site Scripting):** Blocked via regex validation on backend, React escaping on frontend
- ✅ **Brute Force:** Rate limiting (5 attempts/minute) on login endpoint
- ✅ **CSRF:** Laravel handles automatically
- ✅ **Mass Assignment:** Fillable whitelist in models

### Authentication & Authorization
- ✅ Token-based authentication with Laravel Sanctum
- ✅ All employee routes protected with `auth:sanctum` middleware
- ✅ Tokens invalidated on logout
- ✅ Automatic 401 responses for unauthorized access

**For detailed security documentation, see:** [backend/SECURITY.md](backend/SECURITY.md)

---

## Testing

### Manual Testing

#### Backend API Testing
Test the API using cURL, Postman, or Insomnia.

**Example: Login and fetch employees**
```bash
# 1. Login to get token
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# 2. Use token to fetch employees
curl -X GET http://127.0.0.1:8000/api/employees \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**See full testing guide:** [backend/API_TESTING_GUIDE.md](backend/API_TESTING_GUIDE.md)

#### Frontend Testing
1. Start both backend and frontend servers
2. Navigate to `http://localhost:5173`
3. Test the complete flow:
   - Login with default credentials
   - View employee list
   - Create new employee
   - Edit existing employee
   - Delete employee
   - Logout

---

## Code Quality

### Backend (Laravel)
- **Standards:** PSR-12 coding standards
- **Principles:** Single Responsibility Principle (3-10 line functions)
- **Validation:** Form Request classes for all inputs
- **Security:** Comprehensive input validation and sanitization

### Frontend (React)
- **Structure:** Component-based architecture
- **State Management:** Context API for global state
- **Hooks:** Custom hooks for reusable logic
- **Validation:** Client-side validation matching backend rules

**See coding guidelines:** [BEST_PRACTICES.md](BEST_PRACTICES.md)

---

## Assessment Compliance

### Requirements Met ✅

**Backend Requirements:**
- ✅ RESTful API using Laravel 12
- ✅ Authentication using Laravel Sanctum 4.0
- ✅ CRUD operations for Employees
- ✅ Form Request validation
- ✅ Proper HTTP status codes (200, 201, 204, 401, 404, 422, 429)
- ✅ Seeders for sample data

**Frontend Requirements:**
- ✅ React 19 with Vite
- ✅ API consumption using Axios
- ✅ Login page with authentication
- ✅ Employees list page
- ✅ Add/Edit employee forms
- ✅ Delete with confirmation
- ✅ Error handling for API responses

**Database Requirements:**
- ✅ MySQL database
- ✅ Clean migrations with proper naming
- ✅ Correct data types and constraints

**Documentation:**
- ✅ README.md with setup instructions (this file)
- ✅ Authentication flow explained
- ✅ API endpoints documented

**Code Quality:**
- ✅ Clean, maintainable code
- ✅ Meaningful commit messages
- ✅ Proper Git usage

**Verification Documents:**
- [backend/COMPLIANCE_CHECKLIST.md](backend/COMPLIANCE_CHECKLIST.md)
- [frontend/COMPLIANCE_CHECKLIST.md](frontend/COMPLIANCE_CHECKLIST.md)

---

## Git Workflow

### Commit Message Convention
```
type: Brief description

Detailed explanation of what changed and why.
```

**Example Commits:**
```bash
git log --oneline
071d422 chore: Remove unused files and dependencies
36a743e feat: Implement complete React frontend
c367f96 chore: Initialize React frontend with Vite
6fbe2ba feat: Implement complete Laravel backend API
```

---

## Troubleshooting

### Backend Issues

**Database Connection Error:**
```bash
# Verify MySQL is running
brew services list | grep mysql

# Check .env database credentials
```

**Port 8000 Already in Use:**
```bash
# Stop existing process
lsof -ti:8000 | xargs kill -9
```

### Frontend Issues

**Port 5173 Already in Use:**
```bash
# Kill process
lsof -ti:5173 | xargs kill -9
```

**Cannot Connect to Backend:**
- Ensure backend is running on `http://127.0.0.1:8000`
- Check API base URL in `frontend/src/services/api.js`

**For more troubleshooting, see:**
- [backend/INSTALLATION_GUIDE.md](backend/INSTALLATION_GUIDE.md)
- [frontend/INSTALLATION_GUIDE.md](frontend/INSTALLATION_GUIDE.md)

---

## License

This project is open-source and available under the MIT License.

---

## Contact & Support

**Developer:** Mohamed Ali Mohamed Alhamry
**Repository:** [https://github.com/m-alhamry/employee-management-system](https://github.com/m-alhamry/employee-management-system)
**Assessment Deadline:** January 12, 2026

---

## Acknowledgments

- **Laravel Framework** - Backend framework
- **React Team** - Frontend library
- **Vite** - Build tool
- **Laravel Sanctum** - Authentication package

---

**Built with ❤️ for Technical Assessment**

**Last Updated:** 2026-01-10
