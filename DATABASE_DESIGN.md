# Database Design - Employee Management System

## Overview
This document describes the database structure for the Employee Management System, including table schemas, relationships, constraints, and indexes.

**Database Type:** MySQL
**Character Set:** utf8mb4
**Collation:** utf8mb4_unicode_ci

---

## Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────────┐
│             USERS                   │
├─────────────────────────────────────┤
│ PK │ id          BIGINT UNSIGNED    │
│    │ name        VARCHAR(255)       │
│    │ email       VARCHAR(255) UNIQUE│
│    │ password    VARCHAR(255)       │
│    │ created_at  TIMESTAMP          │
│    │ updated_at  TIMESTAMP          │
└─────────────────────────────────────┘
                  │
                  │ (No direct FK relationship)
                  │ (Users authenticate to manage employees)
                  │
                  ▼
┌─────────────────────────────────────┐
│           EMPLOYEES                 │
├─────────────────────────────────────┤
│ PK │ id          BIGINT UNSIGNED    │
│    │ name        VARCHAR(255)       │
│    │ email       VARCHAR(255) UNIQUE│
│    │ position    VARCHAR(255)       │
│    │ salary      DECIMAL(10,2)      │
│    │ status      ENUM               │
│    │ created_at  TIMESTAMP          │
│    │ updated_at  TIMESTAMP          │
└─────────────────────────────────────┘


┌─────────────────────────────────────┐
│      PERSONAL_ACCESS_TOKENS         │
│      (Laravel Sanctum)              │
├─────────────────────────────────────┤
│ PK │ id              BIGINT UNSIGNED│
│ FK │ tokenable_type  VARCHAR(255)   │
│ FK │ tokenable_id    BIGINT UNSIGNED│
│    │ name            VARCHAR(255)   │
│    │ token           VARCHAR(64)    │
│    │ abilities       TEXT           │
│    │ last_used_at    TIMESTAMP      │
│    │ expires_at      TIMESTAMP      │
│    │ created_at      TIMESTAMP      │
│    │ updated_at      TIMESTAMP      │
└─────────────────────────────────────┘
                  ▲
                  │
                  │ Polymorphic Relationship
                  │
┌─────────────────┘
│
│ (tokenable_type = 'App\Models\User')
│ (tokenable_id = users.id)
```

---

## Table Schemas

### 1. Users Table

**Purpose:** Store authentication credentials for system administrators.

**Table Name:** `users`

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Columns:**

| Column            | Type              | Null | Default | Description                          |
|-------------------|-------------------|------|---------|--------------------------------------|
| id                | BIGINT UNSIGNED   | NO   | -       | Primary key, auto-increment          |
| name              | VARCHAR(255)      | NO   | -       | Full name of the user                |
| email             | VARCHAR(255)      | NO   | -       | Unique email address for login       |
| email_verified_at | TIMESTAMP         | YES  | NULL    | Email verification timestamp         |
| password          | VARCHAR(255)      | NO   | -       | Hashed password (bcrypt)             |
| remember_token    | VARCHAR(100)      | YES  | NULL    | Remember me token                    |
| created_at        | TIMESTAMP         | YES  | NULL    | Record creation timestamp            |
| updated_at        | TIMESTAMP         | YES  | NULL    | Record last update timestamp         |

**Constraints:**
- PRIMARY KEY: `id`
- UNIQUE: `email`

**Indexes:**
- PRIMARY: `id`
- UNIQUE: `email`
- INDEX: `idx_email` (for faster lookups)

**Sample Data:**
```sql
INSERT INTO users (name, email, password, created_at, updated_at) VALUES
('Admin User', 'admin@example.com', '$2y$10$hashed_password', NOW(), NOW()),
('John Doe', 'john@example.com', '$2y$10$hashed_password', NOW(), NOW());
```

---

### 2. Employees Table

**Purpose:** Store employee information for CRUD operations.

**Table Name:** `employees`

```sql
CREATE TABLE employees (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    position VARCHAR(255) NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_position (position),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Columns:**

| Column     | Type                       | Null | Default  | Description                         |
|------------|----------------------------|------|----------|-------------------------------------|
| id         | BIGINT UNSIGNED            | NO   | -        | Primary key, auto-increment         |
| name       | VARCHAR(255)               | NO   | -        | Full name of the employee           |
| email      | VARCHAR(255)               | NO   | -        | Unique email address                |
| position   | VARCHAR(255)               | NO   | -        | Job title/position                  |
| salary     | DECIMAL(10, 2)             | NO   | -        | Annual salary (supports decimals)   |
| status     | ENUM('active', 'inactive') | NO   | 'active' | Employment status                   |
| created_at | TIMESTAMP                  | YES  | NULL     | Record creation timestamp           |
| updated_at | TIMESTAMP                  | YES  | NULL     | Record last update timestamp        |

**Constraints:**
- PRIMARY KEY: `id`
- UNIQUE: `email`
- NOT NULL: `name`, `email`, `position`, `salary`, `status`
- CHECK: `salary` >= 0 (enforced at application level)

**Indexes:**
- PRIMARY: `id`
- UNIQUE: `email`
- INDEX: `idx_email` (for email lookups)
- INDEX: `idx_status` (for filtering by status)
- INDEX: `idx_position` (for filtering by position)
- INDEX: `idx_created_at` (for sorting by date)

**Sample Data:**
```sql
INSERT INTO employees (name, email, position, salary, status, created_at, updated_at) VALUES
('Alice Johnson', 'alice@company.com', 'Software Engineer', 85000.00, 'active', NOW(), NOW()),
('Bob Smith', 'bob@company.com', 'Product Manager', 95000.00, 'active', NOW(), NOW()),
('Carol White', 'carol@company.com', 'UX Designer', 75000.00, 'active', NOW(), NOW()),
('David Brown', 'david@company.com', 'DevOps Engineer', 90000.00, 'inactive', NOW(), NOW());
```

---

### 3. Personal Access Tokens Table (Laravel Sanctum)

**Purpose:** Store API authentication tokens for users.

**Table Name:** `personal_access_tokens`

```sql
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL DEFAULT NULL,
    expires_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_tokenable (tokenable_type, tokenable_id),
    UNIQUE INDEX idx_token (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Columns:**

| Column         | Type             | Null | Default | Description                          |
|----------------|------------------|------|---------|--------------------------------------|
| id             | BIGINT UNSIGNED  | NO   | -       | Primary key, auto-increment          |
| tokenable_type | VARCHAR(255)     | NO   | -       | Model type (e.g., App\Models\User)   |
| tokenable_id   | BIGINT UNSIGNED  | NO   | -       | Foreign key to users.id              |
| name           | VARCHAR(255)     | NO   | -       | Token name/description               |
| token          | VARCHAR(64)      | NO   | -       | Hashed token string (unique)         |
| abilities      | TEXT             | YES  | NULL    | JSON array of token abilities        |
| last_used_at   | TIMESTAMP        | YES  | NULL    | Last time token was used             |
| expires_at     | TIMESTAMP        | YES  | NULL    | Token expiration timestamp           |
| created_at     | TIMESTAMP        | YES  | NULL    | Record creation timestamp            |
| updated_at     | TIMESTAMP        | YES  | NULL    | Record last update timestamp         |

**Constraints:**
- PRIMARY KEY: `id`
- UNIQUE: `token`
- INDEX: `tokenable_type, tokenable_id` (polymorphic relationship)

**Relationships:**
- Polymorphic relationship to `users` table
- `tokenable_type` = 'App\Models\User'
- `tokenable_id` = `users.id`

**Sample Data:**
```sql
-- Token is automatically created by Laravel Sanctum during login
-- Example structure:
INSERT INTO personal_access_tokens
(tokenable_type, tokenable_id, name, token, abilities, last_used_at, created_at, updated_at)
VALUES
('App\\Models\\User', 1, 'auth-token', 'hashed_token_string', NULL, NOW(), NOW(), NOW());
```

---

## Relationships

### 1. Users to Personal Access Tokens
**Type:** One-to-Many (Polymorphic)
**Description:** A user can have multiple active tokens for different devices/sessions.

```
users (1) ──────< (many) personal_access_tokens
   │                          │
   └──────────────────────────┘
   (tokenable_type + tokenable_id)
```

**Laravel Eloquent Relationship:**
```php
// User Model
public function tokens()
{
    return $this->morphMany(PersonalAccessToken::class, 'tokenable');
}
```

### 2. Users to Employees
**Type:** No Direct Relationship
**Description:** Users authenticate to manage employees, but there's no foreign key relationship. This is an authorization-based relationship, not a data relationship.

```
users (authenticate) ──────> manage ──────> employees
  │                                              │
  └─────────── No FK Constraint ────────────────┘
```

---

## Data Types & Constraints

### Primary Keys
- **Type:** `BIGINT UNSIGNED AUTO_INCREMENT`
- **Reason:** Supports large number of records (up to 18,446,744,073,709,551,615)
- **Pattern:** All primary keys are named `id`

### String Fields
- **Type:** `VARCHAR(255)`
- **Character Set:** utf8mb4 (supports emoji and international characters)
- **Collation:** utf8mb4_unicode_ci (case-insensitive, Unicode-aware)

### Email Fields
- **Type:** `VARCHAR(255)`
- **Constraint:** UNIQUE
- **Index:** Yes (for faster lookups)
- **Validation:** Validated at application level (Laravel validation)

### Decimal Fields (Salary)
- **Type:** `DECIMAL(10, 2)`
- **Precision:** 10 digits total, 2 after decimal point
- **Range:** -99,999,999.99 to 99,999,999.99
- **Example:** 85000.00, 125500.50
- **Reason:** Exact precision for monetary values (no floating-point errors)

### ENUM Fields (Status)
- **Type:** `ENUM('active', 'inactive')`
- **Values:** Only 'active' or 'inactive' allowed
- **Default:** 'active'
- **Storage:** Efficient storage (1 byte)

### Timestamps
- **Type:** `TIMESTAMP`
- **Nullable:** YES
- **Laravel:** Automatically managed by Eloquent
- **Fields:** `created_at`, `updated_at`

---

## Indexes Strategy

### Primary Indexes
```sql
-- Automatically created with PRIMARY KEY
employees.id
users.id
personal_access_tokens.id
```

### Unique Indexes
```sql
-- Enforce uniqueness and fast lookups
employees.email
users.email
personal_access_tokens.token
```

### Regular Indexes
```sql
-- Speed up common queries
employees.status        -- Filter by active/inactive
employees.position      -- Filter/group by position
employees.created_at    -- Sort by date
users.email            -- Login lookups
personal_access_tokens.tokenable_type, tokenable_id -- Polymorphic queries
```

### Index Usage Examples
```sql
-- Fast query with index on status
SELECT * FROM employees WHERE status = 'active';

-- Fast query with index on email
SELECT * FROM employees WHERE email = 'alice@company.com';

-- Fast query with index on position
SELECT * FROM employees WHERE position = 'Software Engineer';

-- Fast query with index on created_at
SELECT * FROM employees ORDER BY created_at DESC LIMIT 10;
```

---

## Migration Files

### Migration Order
1. `2024_01_01_000000_create_users_table.php` (Laravel default)
2. `2024_01_01_000001_create_personal_access_tokens_table.php` (Sanctum)
3. `2024_01_01_000002_create_employees_table.php` (Custom)

### Laravel Migration: Users Table
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Additional indexes
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### Laravel Migration: Employees Table
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('position');
            $table->decimal('salary', 10, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Indexes for better query performance
            $table->index('email');
            $table->index('status');
            $table->index('position');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
```

### Laravel Migration: Personal Access Tokens (Sanctum)
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
```

---

## Seeder Files

### User Seeder
```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
```

### Employee Seeder
```php
<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@company.com',
                'position' => 'Software Engineer',
                'salary' => 85000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob.smith@company.com',
                'position' => 'Product Manager',
                'salary' => 95000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Carol White',
                'email' => 'carol.white@company.com',
                'position' => 'UX Designer',
                'salary' => 75000.00,
                'status' => 'active',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@company.com',
                'position' => 'DevOps Engineer',
                'salary' => 90000.00,
                'status' => 'inactive',
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma.davis@company.com',
                'position' => 'Frontend Developer',
                'salary' => 80000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Frank Miller',
                'email' => 'frank.miller@company.com',
                'position' => 'Backend Developer',
                'salary' => 88000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Grace Lee',
                'email' => 'grace.lee@company.com',
                'position' => 'QA Engineer',
                'salary' => 70000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Henry Wilson',
                'email' => 'henry.wilson@company.com',
                'position' => 'Data Analyst',
                'salary' => 78000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Isabel Martinez',
                'email' => 'isabel.martinez@company.com',
                'position' => 'HR Manager',
                'salary' => 82000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Jack Taylor',
                'email' => 'jack.taylor@company.com',
                'position' => 'Sales Representative',
                'salary' => 65000.00,
                'status' => 'inactive',
            ],
            [
                'name' => 'Karen Anderson',
                'email' => 'karen.anderson@company.com',
                'position' => 'Marketing Specialist',
                'salary' => 72000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Liam Thomas',
                'email' => 'liam.thomas@company.com',
                'position' => 'System Administrator',
                'salary' => 84000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Mia Jackson',
                'email' => 'mia.jackson@company.com',
                'position' => 'Business Analyst',
                'salary' => 79000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Noah Harris',
                'email' => 'noah.harris@company.com',
                'position' => 'Mobile Developer',
                'salary' => 87000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Olivia Clark',
                'email' => 'olivia.clark@company.com',
                'position' => 'Scrum Master',
                'salary' => 92000.00,
                'status' => 'inactive',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
```

### Database Seeder (Main)
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
```

---

## Laravel Eloquent Models

### User Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

### Employee Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'position',
        'salary',
        'status',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'status' => 'string',
    ];
}
```

---

## Database Queries Examples

### Common SELECT Queries
```sql
-- Get all active employees
SELECT * FROM employees WHERE status = 'active';

-- Get employees by position
SELECT * FROM employees WHERE position = 'Software Engineer';

-- Get employees with salary above 80000
SELECT * FROM employees WHERE salary > 80000.00;

-- Get recently added employees
SELECT * FROM employees ORDER BY created_at DESC LIMIT 10;

-- Get employee count by status
SELECT status, COUNT(*) as count
FROM employees
GROUP BY status;

-- Get average salary by position
SELECT position, AVG(salary) as avg_salary
FROM employees
GROUP BY position
ORDER BY avg_salary DESC;

-- Search employees by name or email
SELECT * FROM employees
WHERE name LIKE '%john%' OR email LIKE '%john%';
```

### Common INSERT Queries
```sql
-- Insert new employee
INSERT INTO employees (name, email, position, salary, status, created_at, updated_at)
VALUES ('John Doe', 'john@company.com', 'Developer', 75000.00, 'active', NOW(), NOW());
```

### Common UPDATE Queries
```sql
-- Update employee information
UPDATE employees
SET name = 'John Smith',
    salary = 80000.00,
    updated_at = NOW()
WHERE id = 1;

-- Deactivate employee
UPDATE employees
SET status = 'inactive',
    updated_at = NOW()
WHERE id = 5;
```

### Common DELETE Queries
```sql
-- Delete employee by ID
DELETE FROM employees WHERE id = 10;

-- Delete inactive employees (cleanup)
DELETE FROM employees WHERE status = 'inactive';
```

---

## Database Optimization

### Query Optimization Tips
1. **Use Indexes:** All frequently queried columns have indexes
2. **Limit Results:** Use LIMIT for paginated queries
3. **Avoid SELECT *:** Select only needed columns
4. **Use Prepared Statements:** Laravel Eloquent handles this automatically

### Index Maintenance
```sql
-- Check index usage
SHOW INDEX FROM employees;

-- Analyze table performance
ANALYZE TABLE employees;

-- Optimize table (defragment)
OPTIMIZE TABLE employees;
```

### Performance Considerations
- **Small Dataset:** Current design handles 1M+ employees efficiently
- **Indexes:** Properly indexed for common queries
- **Data Types:** Optimized for storage and performance
- **No Over-indexing:** Only necessary indexes to avoid write overhead

---

## Backup & Restore

### Backup Database
```bash
# Full database backup
mysqldump -u username -p database_name > backup.sql

# Specific tables backup
mysqldump -u username -p database_name users employees > backup.sql
```

### Restore Database
```bash
# Restore from backup
mysql -u username -p database_name < backup.sql
```

### Laravel Artisan Commands
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan db:wipe

# Seed database
php artisan db:seed
```

---

## Security Considerations

### 1. SQL Injection Prevention
- ✅ Use Laravel Eloquent ORM (parameterized queries)
- ✅ Never concatenate user input into SQL
- ✅ Validate all input at application level

### 2. Password Security
- ✅ Hash passwords using bcrypt (Laravel default)
- ✅ Never store plain-text passwords
- ✅ Use strong password validation rules

### 3. Email Security
- ✅ Validate email format
- ✅ Enforce uniqueness constraint
- ✅ Use lowercase for storage (optional)

### 4. Data Integrity
- ✅ Use transactions for critical operations
- ✅ Implement proper constraints
- ✅ Validate at both database and application level

### 5. Token Security
- ✅ Tokens are hashed in database
- ✅ Set appropriate expiration times
- ✅ Revoke tokens on logout
- ✅ Use HTTPS for token transmission

---

## Testing Data

### Test User Credentials
```
Email: admin@example.com
Password: password
```

### Test Employees
- 15 sample employees with varied data
- Mix of active (12) and inactive (3) statuses
- Positions: Developer, Manager, Designer, QA, HR, Sales, etc.
- Salary range: $65,000 - $95,000

---

## Schema Version History

| Version | Date       | Changes                                      |
|---------|------------|----------------------------------------------|
| 1.0     | 2026-01-08 | Initial schema design with users & employees |

---

## Appendix

### Full Database Schema Visualization

```
┌──────────────────────────────────────────────────────────────┐
│                     DATABASE: employee_management            │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌─────────────────────┐       ┌──────────────────────┐    │
│  │       USERS         │       │     EMPLOYEES        │    │
│  ├─────────────────────┤       ├──────────────────────┤    │
│  │ • id (PK)           │       │ • id (PK)            │    │
│  │ • name              │       │ • name               │    │
│  │ • email (UNIQUE)    │       │ • email (UNIQUE)     │    │
│  │ • password          │       │ • position           │    │
│  │ • created_at        │       │ • salary             │    │
│  │ • updated_at        │       │ • status (ENUM)      │    │
│  └─────────────────────┘       │ • created_at         │    │
│           │                    │ • updated_at         │    │
│           │                    └──────────────────────┘    │
│           │                                                │
│           │ (Polymorphic)                                  │
│           │                                                │
│           ▼                                                │
│  ┌────────────────────────────┐                           │
│  │ PERSONAL_ACCESS_TOKENS     │                           │
│  ├────────────────────────────┤                           │
│  │ • id (PK)                  │                           │
│  │ • tokenable_type (FK)      │                           │
│  │ • tokenable_id (FK)        │                           │
│  │ • name                     │                           │
│  │ • token (UNIQUE)           │                           │
│  │ • abilities                │                           │
│  │ • last_used_at             │                           │
│  │ • expires_at               │                           │
│  │ • created_at               │                           │
│  │ • updated_at               │                           │
│  └────────────────────────────┘                           │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

---

**Document Version:** 1.0
**Last Updated:** 2026-01-08
**Database Version:** MySQL 8.0+
**Laravel Version:** 10.x / 11.x
