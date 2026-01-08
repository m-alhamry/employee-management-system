# Technical Assessment Project Plan
## Laravel Backend & React Frontend - Employee Management System

**Deadline:** January 12th, 2026 (before midnight)
**Duration:** 3 days from receipt
**Date Received:** Based on email date

---

## Overview
Build a full-stack Employee Management System with Laravel REST API backend and React frontend, featuring authentication and CRUD operations.

---

## Project Structure
```
technical-assessment/
├── backend/              # Laravel API
│   ├── app/
│   ├── database/
│   ├── routes/
│   └── ...
├── frontend/             # React Application
│   ├── src/
│   ├── public/
│   └── ...
└── README.md            # Main documentation
```

---

## Phase 1: Backend Development (Laravel API)

### 1.1 Project Setup
- [ ] Install Laravel (latest stable version)
- [ ] Configure MySQL database connection
- [ ] Install Laravel Sanctum for authentication
- [ ] Set up Git repository with proper .gitignore
- [ ] Configure CORS for React frontend

### 1.2 Database Design

#### Users Table (for Authentication)
```sql
- id (bigint, primary key)
- name (string)
- email (string, unique)
- password (string, hashed)
- created_at (timestamp)
- updated_at (timestamp)
```

#### Employees Table
```sql
- id (bigint, primary key)
- name (string, required)
- email (string, unique, required)
- position (string, required)
- salary (decimal(10,2), required)
- status (enum: 'active', 'inactive', default: 'active')
- created_at (timestamp)
- updated_at (timestamp)
```

### 1.3 Migrations & Seeders
- [ ] Create migration for `users` table
- [ ] Create migration for `employees` table
- [ ] Create UserSeeder (minimum 1 admin user for login)
- [ ] Create EmployeeSeeder (10-15 sample employees)
- [ ] Run migrations and seeders

### 1.4 Models
- [ ] Create `Employee` model with fillable fields
- [ ] Create `User` model (extend default Laravel User)
- [ ] Add appropriate casts (salary as decimal, status as string)

### 1.5 Authentication (Laravel Sanctum)
- [ ] Configure Sanctum middleware
- [ ] Create AuthController with methods:
  - `login()` - authenticate user and return token
  - `logout()` - revoke token
  - `me()` - get authenticated user details (optional but recommended)
- [ ] Create LoginRequest for validation
- [ ] Return proper HTTP status codes (200, 401, 422)

### 1.6 Employee CRUD Operations
- [ ] Create EmployeeController with methods:
  - `index()` - list all employees (200)
  - `store()` - create new employee (201)
  - `show($id)` - get single employee (200, 404)
  - `update($id)` - update employee (200, 404, 422)
  - `destroy($id)` - delete employee (204, 404)

### 1.7 Form Request Validation
- [ ] Create StoreEmployeeRequest with validation rules:
  - name: required, string, max:255
  - email: required, email, unique:employees
  - position: required, string, max:255
  - salary: required, numeric, min:0
  - status: required, in:active,inactive
- [ ] Create UpdateEmployeeRequest (similar rules, email unique except current)

### 1.8 API Routes
```php
// routes/api.php
POST   /api/login          # AuthController@login
POST   /api/logout         # AuthController@logout (protected)
GET    /api/me             # AuthController@me (protected)

GET    /api/employees      # EmployeeController@index (protected)
POST   /api/employees      # EmployeeController@store (protected)
GET    /api/employees/{id} # EmployeeController@show (protected)
PUT    /api/employees/{id} # EmployeeController@update (protected)
DELETE /api/employees/{id} # EmployeeController@destroy (protected)
```

### 1.9 Backend Testing
- [ ] Test authentication endpoints with Postman/Insomnia
- [ ] Test all CRUD operations
- [ ] Verify validation errors return 422
- [ ] Verify proper status codes
- [ ] Test with invalid tokens

---

## Phase 2: Frontend Development (React)

### 2.1 Project Setup
- [ ] Create React app using Vite (recommended) or CRA
- [ ] Install dependencies:
  - axios (API calls)
  - react-router-dom (routing)
  - Optional: tailwindcss, bootstrap, or material-ui (styling)
- [ ] Configure axios base URL and interceptors
- [ ] Set up folder structure:
  ```
  src/
  ├── components/
  ├── pages/
  ├── services/
  ├── context/
  └── utils/
  ```

### 2.2 Authentication Setup
- [ ] Create AuthContext for managing auth state
- [ ] Create auth service (login, logout, token storage)
- [ ] Implement axios interceptor to add Bearer token to requests
- [ ] Create ProtectedRoute component
- [ ] Handle token expiration and redirect to login

### 2.3 Pages & Components

#### Login Page
- [ ] Create LoginPage component
- [ ] Email and password input fields
- [ ] Form validation (client-side)
- [ ] Handle login API call
- [ ] Store token in localStorage
- [ ] Redirect to employees list on success
- [ ] Display error messages

#### Employees List Page
- [ ] Create EmployeesListPage component
- [ ] Fetch and display employees in table format
- [ ] Show: name, email, position, salary, status
- [ ] Add "Edit" and "Delete" buttons for each row
- [ ] Add "Add Employee" button
- [ ] Handle loading state
- [ ] Handle empty state

#### Add/Edit Employee Form
- [ ] Create EmployeeForm component (reusable for add/edit)
- [ ] Input fields: name, email, position, salary, status
- [ ] Client-side validation
- [ ] Handle create/update API calls
- [ ] Display success/error messages
- [ ] Redirect to list on success

#### Delete Confirmation
- [ ] Create confirmation modal/dialog
- [ ] Show employee name in confirmation message
- [ ] Handle delete API call
- [ ] Refresh list after deletion

### 2.4 API Integration (Axios)
- [ ] Create employee service with methods:
  - `getEmployees()`
  - `getEmployee(id)`
  - `createEmployee(data)`
  - `updateEmployee(id, data)`
  - `deleteEmployee(id)`
- [ ] Create auth service with methods:
  - `login(email, password)`
  - `logout()`

### 2.5 Error Handling
- [ ] Handle network errors
- [ ] Handle 401 (redirect to login)
- [ ] Handle 404 (show not found message)
- [ ] Handle 422 (display validation errors)
- [ ] Handle 500 (show generic error message)
- [ ] Display user-friendly error messages

### 2.6 UI/UX Enhancements
- [ ] Add loading spinners
- [ ] Add success/error toast notifications
- [ ] Responsive design (mobile-friendly)
- [ ] Basic styling (clean and professional)
- [ ] Form field validation feedback

### 2.7 Frontend Testing
- [ ] Test login flow
- [ ] Test protected routes
- [ ] Test CRUD operations
- [ ] Test error handling
- [ ] Test logout functionality

---

## Phase 3: Documentation

### 3.1 README.md
Create comprehensive documentation including:

#### Project Overview
- [ ] Brief description of the project
- [ ] Technologies used

#### Prerequisites
- [ ] PHP version
- [ ] Composer
- [ ] Node.js & npm
- [ ] MySQL

#### Backend Setup Instructions
- [ ] Clone repository
- [ ] Install dependencies: `composer install`
- [ ] Copy .env.example to .env
- [ ] Configure database credentials
- [ ] Generate application key: `php artisan key:generate`
- [ ] Run migrations: `php artisan migrate`
- [ ] Run seeders: `php artisan db:seed`
- [ ] Start server: `php artisan serve`

#### Frontend Setup Instructions
- [ ] Navigate to frontend directory
- [ ] Install dependencies: `npm install`
- [ ] Configure API URL in .env or config
- [ ] Start development server: `npm run dev`

#### Default Login Credentials
- [ ] Provide seeded user credentials

#### Authentication Flow
- [ ] Explain Laravel Sanctum token-based authentication
- [ ] Describe login process
- [ ] Describe token storage and usage
- [ ] Describe logout process

#### API Endpoints Documentation
```
POST   /api/login
  Request: { email, password }
  Response: { token, user }

POST   /api/logout
  Headers: Authorization: Bearer {token}
  Response: { message }

GET    /api/employees
  Headers: Authorization: Bearer {token}
  Response: [{ id, name, email, position, salary, status }]

POST   /api/employees
  Headers: Authorization: Bearer {token}
  Request: { name, email, position, salary, status }
  Response: { employee }

GET    /api/employees/{id}
  Headers: Authorization: Bearer {token}
  Response: { employee }

PUT    /api/employees/{id}
  Headers: Authorization: Bearer {token}
  Request: { name, email, position, salary, status }
  Response: { employee }

DELETE /api/employees/{id}
  Headers: Authorization: Bearer {token}
  Response: { message }
```

#### Project Structure
- [ ] Document folder organization

#### Testing
- [ ] How to run backend tests (if added)
- [ ] How to test API endpoints

---

## Phase 4: Final Review & Submission

### 4.1 Code Quality
- [ ] Follow PSR-12 coding standards (Laravel)
- [ ] Follow React best practices
- [ ] Remove commented-out code
- [ ] Remove console.log statements
- [ ] Add meaningful comments where needed
- [ ] Ensure consistent naming conventions

### 4.2 Git Best Practices
- [ ] Initialize git repository
- [ ] Create meaningful commit messages
- [ ] Commit regularly with logical chunks
- [ ] Create .gitignore for both frontend and backend
- [ ] Don't commit .env files or node_modules

### 4.3 Testing Checklist
- [ ] Fresh install test (clone and setup from scratch)
- [ ] Test all API endpoints
- [ ] Test all frontend features
- [ ] Test authentication flow
- [ ] Test error scenarios
- [ ] Test on different browsers (Chrome, Firefox)

### 4.4 Submission Package
- [ ] Ensure README.md is complete and clear
- [ ] Verify all seeders work correctly
- [ ] Verify .env.example files are present
- [ ] Double-check deadline: January 12th before midnight
- [ ] Create zip file or push to GitHub repository
- [ ] Send to: Tasneem Alrababah

---

## Evaluation Criteria Checklist

### Laravel API Quality & Structure (25%)
- [ ] RESTful API design
- [ ] Clean code organization
- [ ] Proper use of controllers, models, and requests
- [ ] Form Request validation implemented
- [ ] Proper HTTP status codes
- [ ] Sanctum authentication working

### React Integration & API Consumption (25%)
- [ ] Clean component structure
- [ ] Proper state management
- [ ] Axios integration
- [ ] All required pages implemented
- [ ] UI is functional and user-friendly

### Authentication & Error Handling (25%)
- [ ] Sanctum token authentication working
- [ ] Protected routes implemented
- [ ] Login/logout flow working
- [ ] Token stored and sent correctly
- [ ] Comprehensive error handling
- [ ] User-friendly error messages

### Code Cleanliness & Git Usage (25%)
- [ ] Clean, readable code
- [ ] Consistent coding style
- [ ] Meaningful variable/function names
- [ ] Proper commit history
- [ ] Good commit messages
- [ ] Proper .gitignore usage

---

## Time Management (3 Days)

### Day 1: Backend Development
- Morning: Laravel setup, database, migrations, seeders
- Afternoon: Authentication with Sanctum
- Evening: Employee CRUD API endpoints and validation

### Day 2: Frontend Development
- Morning: React setup, authentication, routing
- Afternoon: Employee list and forms
- Evening: Delete functionality, error handling, styling

### Day 3: Testing & Documentation
- Morning: Complete any remaining features
- Afternoon: Testing, bug fixes, code cleanup
- Evening: README.md, final review, submission

---

## Notes & Tips

### Backend
- Use API Resources for consistent JSON responses (optional but recommended)
- Consider adding pagination for employees list
- Use database transactions for data integrity
- Log errors appropriately

### Frontend
- Use environment variables for API URL
- Implement loading states for better UX
- Consider using React hooks (useState, useEffect, useContext)
- Validate forms before API calls to reduce unnecessary requests

### Security
- Never commit .env files
- Ensure CORS is configured correctly
- Validate all input on backend
- Sanitize user input
- Use HTTPS in production

### Optional Enhancements (If Time Permits)
- [ ] Add search/filter functionality for employees
- [ ] Add pagination for employee list
- [ ] Add sorting capability
- [ ] Add API rate limiting
- [ ] Add unit/feature tests
- [ ] Add field-level validation errors in UI
- [ ] Add confirmation before logout

---

## Quick Reference

### Default Seeded User
```
Email: admin@example.com
Password: password
```

### Common Laravel Commands
```bash
php artisan migrate:fresh --seed  # Fresh migrations with seeders
php artisan route:list            # List all routes
php artisan tinker                # Laravel REPL
```

### Common Git Commands
```bash
git init
git add .
git commit -m "Initial commit"
git log --oneline
```

---

**Good luck with your assessment!**
