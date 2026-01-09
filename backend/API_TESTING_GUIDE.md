# API Testing Guide

Complete guide for testing the Employee Management System API.

---

## Prerequisites

1. **Start the Laravel development server:**
```bash
cd backend
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

**Note:** If port 8000 is already in use, Laravel will automatically use port 8001 or the next available port. Update the URLs in the commands below accordingly.

To use a specific port:
```bash
php artisan serve --port=8080
```

2. **Ensure database is seeded:**
```bash
php artisan migrate:fresh --seed
```

Default credentials:
- Email: `admin@example.com`
- Password: `password`

---

## Method 1: Testing with cURL (Command Line)

### Step 1: Login and Get Token

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

**Expected Response (200 OK):**
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

**Copy the token** from the response. You'll need it for subsequent requests.

---

### Step 2: Test Employee Endpoints

Replace `YOUR_TOKEN_HERE` with the actual token from Step 1.

#### Get All Employees
```bash
curl -X GET http://127.0.0.1:8000/api/employees \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Expected Response (200 OK):**
```json
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
    // ... more employees
  ]
}
```

---

#### Get Single Employee
```bash
curl -X GET http://127.0.0.1:8000/api/employees/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Expected Response (200 OK):**
```json
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

---

#### Create New Employee
```bash
curl -X POST http://127.0.0.1:8000/api/employees \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@company.com",
    "position": "Backend Developer",
    "salary": 90000,
    "status": "active"
  }'
```

**Expected Response (201 Created):**
```json
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

---

#### Update Employee
```bash
curl -X PUT http://127.0.0.1:8000/api/employees/16 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@company.com",
    "position": "Senior Backend Developer",
    "salary": 105000,
    "status": "active"
  }'
```

**Expected Response (200 OK):**
```json
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

---

#### Delete Employee
```bash
curl -X DELETE http://127.0.0.1:8000/api/employees/16 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Expected Response (204 No Content):**
```
(Empty response body with HTTP 204 status)
```

---

#### Logout
```bash
curl -X POST http://127.0.0.1:8000/api/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Expected Response (200 OK):**
```json
{
  "message": "Logged out successfully"
}
```

---

## Method 2: Testing with Postman

### Setup Postman Collection

1. **Create a new Collection** named "Employee Management API"

2. **Set Collection Variables:**
   - `base_url`: `http://127.0.0.1:8000/api`
   - `token`: (will be set automatically after login)

3. **Create Requests:**

#### 1. Login
- **Method:** POST
- **URL:** `{{base_url}}/login`
- **Headers:**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
- **Body (raw JSON):**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```
- **Tests Script:**
```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    pm.collectionVariables.set("token", jsonData.token);
}
```

#### 2. Get All Employees
- **Method:** GET
- **URL:** `{{base_url}}/employees`
- **Headers:**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
  - `Authorization`: `Bearer {{token}}`

#### 3. Get Single Employee
- **Method:** GET
- **URL:** `{{base_url}}/employees/1`
- **Headers:**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
  - `Authorization`: `Bearer {{token}}`

#### 4. Create Employee
- **Method:** POST
- **URL:** `{{base_url}}/employees`
- **Headers:**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
  - `Authorization`: `Bearer {{token}}`
- **Body (raw JSON):**
```json
{
  "name": "Jane Smith",
  "email": "jane.smith@company.com",
  "position": "Frontend Developer",
  "salary": 85000,
  "status": "active"
}
```

#### 5. Update Employee
- **Method:** PUT
- **URL:** `{{base_url}}/employees/1`
- **Headers:**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
  - `Authorization`: `Bearer {{token}}`
- **Body (raw JSON):**
```json
{
  "name": "Alice Johnson",
  "email": "alice.johnson@company.com",
  "position": "Senior Software Engineer",
  "salary": 95000,
  "status": "active"
}
```

#### 6. Delete Employee
- **Method:** DELETE
- **URL:** `{{base_url}}/employees/15`
- **Headers:**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
  - `Authorization`: `Bearer {{token}}`

#### 7. Logout
- **Method:** POST
- **URL:** `{{base_url}}/logout`
- **Headers:**
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
  - `Authorization`: `Bearer {{token}}`

---

## Method 3: Testing with Insomnia

Similar setup to Postman:

1. **Create a new Request Collection**
2. **Use Environment Variables:**
   - `base_url`: `http://127.0.0.1:8000/api`
   - `token`: (set from login response)
3. **Create the same 7 requests as above**

---

## Method 4: Automated Test Script

An automated test script is available at [test-api.sh](test-api.sh) that:

- Automatically finds an available port (8000-8004)
- Starts the Laravel development server
- Tests all 7 API endpoints (login, logout, get all, get single, create, update, delete)
- Displays colored output with test results
- Stops the server and releases the port when complete

**Run the automated tests:**
```bash
cd backend
chmod +x test-api.sh
./test-api.sh
```

The script requires no external dependencies and handles all server management automatically.

---

## Testing Error Scenarios

### 1. Invalid Login Credentials
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"wrong@example.com","password":"wrongpass"}'
```

**Expected Response (401 Unauthorized):**
```json
{
  "message": "Invalid credentials"
}
```

---

### 2. Rate Limiting (Brute Force Protection)
Try logging in 6 times rapidly with wrong credentials:

```bash
for i in {1..6}; do
  echo "Attempt $i:"
  curl -X POST http://127.0.0.1:8000/api/login \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"email":"admin@example.com","password":"wrong"}'
  echo
done
```

**Expected Response on 6th attempt (429 Too Many Requests):**
```json
{
  "message": "Too Many Attempts."
}
```

---

### 3. Validation Errors
```bash
curl -X POST http://127.0.0.1:8000/api/employees \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "X",
    "email": "invalid-email",
    "position": "Dev",
    "salary": 99999999999,
    "status": "active"
  }'
```

**Expected Response (422 Unprocessable Entity):**
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

### 4. XSS Attack Protection
```bash
curl -X POST http://127.0.0.1:8000/api/employees \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "<script>alert(1)</script>",
    "email": "test@company.com",
    "position": "Developer",
    "salary": 85000,
    "status": "active"
  }'
```

**Expected Response (422 Unprocessable Entity):**
```json
{
  "message": "The name may only contain letters, spaces, hyphens, dots, and apostrophes.",
  "errors": {
    "name": ["The name may only contain letters, spaces, hyphens, dots, and apostrophes."]
  }
}
```

---

### 5. SQL Injection Protection
```bash
curl -X POST http://127.0.0.1:8000/api/employees \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "John Doe",
    "email": "test@company.com",
    "position": "DROP TABLE employees;",
    "salary": 85000,
    "status": "active"
  }'
```

**Expected Response (422 Unprocessable Entity):**
```json
{
  "message": "The position may only contain letters, spaces, hyphens, dots, and apostrophes.",
  "errors": {
    "position": ["The position may only contain letters, spaces, hyphens, dots, and apostrophes."]
  }
}
```

---

### 6. Unauthorized Access (No Token)
```bash
curl -X GET http://127.0.0.1:8000/api/employees \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

**Expected Response (401 Unauthorized):**
```json
{
  "message": "Unauthenticated."
}
```

---

### 7. Employee Not Found
```bash
curl -X GET http://127.0.0.1:8000/api/employees/9999 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Expected Response (404 Not Found):**
```json
{
  "message": "Employee not found"
}
```

---

### 8. Duplicate Email
```bash
# First create an employee
curl -X POST http://127.0.0.1:8000/api/employees \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "Test User",
    "email": "duplicate@company.com",
    "position": "Developer",
    "salary": 85000,
    "status": "active"
  }'

# Try to create another with same email
curl -X POST http://127.0.0.1:8000/api/employees \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "Another User",
    "email": "duplicate@company.com",
    "position": "Designer",
    "salary": 75000,
    "status": "active"
  }'
```

**Expected Response (422 Unprocessable Entity):**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

## HTTP Status Code Reference

| Code | Meaning | When You'll See It |
|------|---------|-------------------|
| 200 | OK | Successful GET, PUT, logout |
| 201 | Created | Successful POST (employee created) |
| 204 | No Content | Successful DELETE |
| 401 | Unauthorized | Invalid/missing token, wrong credentials |
| 404 | Not Found | Employee ID doesn't exist |
| 422 | Unprocessable Entity | Validation errors |
| 429 | Too Many Requests | Rate limit exceeded (>5 login attempts/min) |

---

## Troubleshooting

### Server Not Running
**Error:** `Failed to connect to 127.0.0.1 port 8000`

**Solution:**
```bash
cd backend
php artisan serve
```

---

### Token Expired
**Error:** `{"message":"Unauthenticated."}`

**Solution:** Login again to get a fresh token.

---

### Database Empty
**Error:** Returns empty employee list

**Solution:**
```bash
php artisan migrate:fresh --seed
```

---

### CORS Errors (from frontend)
**Solution:** Configure CORS in `config/cors.php` when building frontend.

---

## Next Steps

After manually testing the API:
1. All endpoints should return expected status codes
2. All validation should work correctly
3. Security measures (rate limiting, XSS, SQL injection) should be functional
4. Ready to integrate with React frontend

---

## Summary

✅ **8 Endpoints Tested:**
- POST /api/login
- POST /api/logout
- GET /api/employees
- GET /api/employees/{id}
- POST /api/employees
- PUT /api/employees/{id}
- DELETE /api/employees/{id}
- GET /api/user

✅ **Security Tests:**
- Rate limiting
- XSS protection
- SQL injection protection
- Overflow protection
- Authentication required
- Validation errors

✅ **All Status Codes:**
- 200 OK
- 201 Created
- 204 No Content
- 401 Unauthorized
- 404 Not Found
- 422 Unprocessable Entity
- 429 Too Many Requests

---

**Ready for Frontend Integration!**
