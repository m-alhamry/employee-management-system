# Coding Best Practices

A concise guide for maintaining code quality throughout this project.

---

## General Principles

### KISS - Keep It Simple, Stupid
- Write simple, readable code
- Avoid over-engineering
- Don't add features not in requirements

### DRY - Don't Repeat Yourself
- Reuse code through functions/components
- Extract common logic into utilities
- Avoid copy-paste programming

### YAGNI - You Aren't Gonna Need It
- Only implement what's required now
- Don't build for hypothetical future needs
- Focus on current user stories

### Single Responsibility Principle (SRP)
- Each function/method should do ONE thing only
- If you use "and" to describe it, it's doing too much
- Keep functions small (ideally 5-15 lines)
- Extract complex logic into separate functions
- A function should be readable in one glance

---

## Laravel Backend

### Naming Conventions
```php
// Controllers - Singular, PascalCase
EmployeeController, AuthController

// Models - Singular, PascalCase
Employee, User

// Methods - camelCase, descriptive verbs
public function getActiveEmployees()

// Variables - camelCase, descriptive nouns
$employeeData, $validatedInput

// Routes - plural, kebab-case
/api/employees, /api/auth/login
```

### Controller Best Practices
```php
// ✅ GOOD - Single responsibility, uses Form Request
public function store(StoreEmployeeRequest $request)
{
    $employee = Employee::create($request->validated());
    return response()->json($employee, 201);
}

// ❌ BAD - Validation in controller, no type hints
public function store($request)
{
    $this->validate($request, [...]);
    $employee = Employee::create($request->all());
    return $employee;
}
```

### Function Length & Single Responsibility
```php
// ❌ BAD - Function doing too many things (validation, creation, email, logging)
public function createEmployee($request)
{
    // Validate input
    if (empty($request->name)) {
        return response()->json(['error' => 'Name required'], 422);
    }
    if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['error' => 'Invalid email'], 422);
    }

    // Create employee
    $employee = new Employee();
    $employee->name = $request->name;
    $employee->email = $request->email;
    $employee->position = $request->position;
    $employee->salary = $request->salary;
    $employee->status = $request->status;
    $employee->save();

    // Send welcome email
    Mail::to($employee->email)->send(new WelcomeEmail($employee));

    // Log activity
    Log::info('Employee created: ' . $employee->id);

    return response()->json($employee, 201);
}

// ✅ GOOD - Small, focused functions, each doing ONE thing
public function store(StoreEmployeeRequest $request)
{
    $employee = $this->createEmployee($request->validated());
    $this->notifyNewEmployee($employee);

    return response()->json($employee, 201);
}

private function createEmployee(array $data): Employee
{
    return Employee::create($data);
}

private function notifyNewEmployee(Employee $employee): void
{
    Mail::to($employee->email)->send(new WelcomeEmail($employee));
    Log::info('Employee created: ' . $employee->id);
}
```

**Key Points:**
- Each function has ONE clear purpose
- Function names clearly describe what they do
- Easy to test, maintain, and debug
- If a function is > 20 lines, consider breaking it down

### Validation
- Always use Form Request classes
- Keep validation logic out of controllers
- Return proper HTTP status codes (422 for validation errors)
- Provide clear error messages

```php
// StoreEmployeeRequest
public function rules()
{
    return [
        'email' => 'required|email|unique:employees',
        'salary' => 'required|numeric|min:0',
    ];
}
```

### Database
- Use migrations for schema changes
- Never edit old migrations - create new ones
- Use descriptive migration names: `create_employees_table`
- Add indexes for frequently queried columns
- Use appropriate data types (DECIMAL for money, ENUM for fixed values)

### Security
```php
// ✅ GOOD - Use Eloquent (prevents SQL injection)
Employee::where('status', $status)->get();

// ❌ BAD - Raw queries with user input
DB::select("SELECT * FROM employees WHERE status = '$status'");

// ✅ GOOD - Hash passwords
Hash::make($password);

// ✅ GOOD - Use middleware for auth
Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});
```

### API Responses
```php
// ✅ GOOD - Consistent structure, proper status codes
return response()->json([
    'data' => $employee
], 201);

return response()->json([
    'message' => 'Employee not found'
], 404);

// ❌ BAD - Inconsistent, wrong status codes
return $employee; // Defaults to 200 always
```

---

## React Frontend

### Naming Conventions
```javascript
// Components - PascalCase
LoginPage, EmployeeForm, EmployeeList

// Files - Match component name
LoginPage.jsx, EmployeeForm.jsx

// Functions - camelCase
handleSubmit, fetchEmployees

// Constants - UPPER_SNAKE_CASE
const API_BASE_URL = 'http://localhost:8000/api';

// Hooks - prefix with 'use'
useAuth, useEmployees
```

### Component Structure
```javascript
// ✅ GOOD - Clean, organized, small functions
function EmployeeList() {
    const [employees, setEmployees] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchEmployees();
    }, []);

    const fetchEmployees = async () => {
        // API call logic
    };

    if (loading) return <LoadingSpinner />;
    if (error) return <ErrorMessage message={error} />;

    return (
        <div>
            {/* JSX */}
        </div>
    );
}

// ❌ BAD - Logic in JSX, no loading/error states
function EmployeeList() {
    const [employees, setEmployees] = useState([]);

    return (
        <div>
            {axios.get('/api/employees').then(res => {
                // Don't do this!
            })}
        </div>
    );
}
```

### Keep Functions Small (React)
```javascript
// ❌ BAD - One large function doing everything
function EmployeeForm() {
    const handleSubmit = async (e) => {
        e.preventDefault();

        // Validate
        if (!name || !email || !position || !salary) {
            setError('All fields required');
            return;
        }
        if (!email.includes('@')) {
            setError('Invalid email');
            return;
        }
        if (salary < 0) {
            setError('Salary must be positive');
            return;
        }

        // Prepare data
        const data = {
            name: name.trim(),
            email: email.toLowerCase().trim(),
            position: position.trim(),
            salary: parseFloat(salary),
            status: status
        };

        // Submit
        try {
            setLoading(true);
            const response = await axios.post('/api/employees', data);
            setSuccess('Employee created!');
            setEmployees([...employees, response.data]);
            resetForm();
            navigate('/employees');
        } catch (err) {
            setError(err.response?.data?.message || 'Failed');
        } finally {
            setLoading(false);
        }
    };
}

// ✅ GOOD - Small, focused functions
function EmployeeForm() {
    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!validateForm()) return;

        const data = prepareFormData();
        await submitEmployee(data);
    };

    const validateForm = () => {
        if (!name || !email || !position || !salary) {
            setError('All fields required');
            return false;
        }
        return true;
    };

    const prepareFormData = () => ({
        name: name.trim(),
        email: email.toLowerCase().trim(),
        position: position.trim(),
        salary: parseFloat(salary),
        status
    });

    const submitEmployee = async (data) => {
        try {
            setLoading(true);
            const response = await axios.post('/api/employees', data);
            handleSuccess(response.data);
        } catch (err) {
            handleError(err);
        } finally {
            setLoading(false);
        }
    };

    const handleSuccess = (employee) => {
        setSuccess('Employee created!');
        setEmployees(prev => [...prev, employee]);
        resetForm();
        navigate('/employees');
    };

    const handleError = (err) => {
        setError(err.response?.data?.message || 'Failed');
    };
}
```

**Benefits:**
- Each function is 3-10 lines
- Easy to understand at a glance
- Easy to test individual functions
- Easy to reuse logic
- Easy to debug

### State Management
```javascript
// ✅ GOOD - Appropriate state updates
setEmployees(prevEmployees =>
    prevEmployees.filter(emp => emp.id !== deletedId)
);

// ❌ BAD - Mutating state directly
employees.push(newEmployee); // Never mutate state!
setEmployees(employees);
```

### API Calls
```javascript
// ✅ GOOD - Error handling, loading states
const fetchEmployees = async () => {
    setLoading(true);
    setError(null);

    try {
        const response = await axios.get('/api/employees');
        setEmployees(response.data);
    } catch (err) {
        setError(err.response?.data?.message || 'Failed to fetch employees');
    } finally {
        setLoading(false);
    }
};

// ❌ BAD - No error handling
const fetchEmployees = async () => {
    const response = await axios.get('/api/employees');
    setEmployees(response.data);
};
```

### Authentication
```javascript
// ✅ GOOD - Token in header via interceptor
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// ❌ BAD - Manual token on each request
axios.get('/api/employees', {
    headers: { Authorization: `Bearer ${token}` }
});
```

---

## Git Best Practices

### Commit Messages
```bash
# ✅ GOOD - Clear, descriptive
git commit -m "Add employee creation form with validation"
git commit -m "Fix authentication token expiration handling"
git commit -m "Implement employee delete confirmation modal"

# ❌ BAD - Vague, unclear
git commit -m "updates"
git commit -m "fix bug"
git commit -m "wip"
```

### Commit Structure
```
Present tense, imperative mood

Add feature X
Fix bug in Y
Update documentation for Z
Refactor component A
Remove unused code from B
```

### What to Commit
```bash
# ✅ DO commit
- Source code
- Configuration files (.env.example)
- Documentation
- .gitignore

# ❌ DON'T commit
- .env files with secrets
- node_modules/
- vendor/
- IDE settings (.vscode, .idea)
- OS files (.DS_Store)
- Build artifacts
```

### Commit Frequency
- Commit after completing a logical unit of work
- Don't commit broken/non-working code
- Commit before switching tasks
- Small, focused commits are better than large ones

---

## Code Style

### PHP (PSR-12)
```php
// ✅ GOOD
namespace App\Http\Controllers;

class EmployeeController extends Controller
{
    public function index(): JsonResponse
    {
        $employees = Employee::all();

        return response()->json([
            'data' => $employees
        ]);
    }
}

// Formatting
- 4 spaces for indentation
- Opening braces on same line for methods
- Type hints for parameters and return types
- One blank line between methods
```

### JavaScript
```javascript
// ✅ GOOD
const fetchEmployees = async () => {
    try {
        const response = await axios.get('/api/employees');
        return response.data;
    } catch (error) {
        console.error('Failed to fetch employees:', error);
        throw error;
    }
};

// Formatting
- 2 spaces for indentation
- Use const/let, not var
- Arrow functions for callbacks
- Async/await over promises
- Destructuring when appropriate
```

---

## Error Handling

### Backend
```php
// ✅ GOOD - Graceful error handling
public function show($id)
{
    $employee = Employee::find($id);

    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    return response()->json(['data' => $employee]);
}
```

### Frontend
```javascript
// ✅ GOOD - User-friendly messages
try {
    await axios.post('/api/employees', data);
    toast.success('Employee created successfully!');
} catch (error) {
    if (error.response?.status === 422) {
        // Show validation errors
        setErrors(error.response.data.errors);
    } else {
        toast.error('Failed to create employee. Please try again.');
    }
}
```

---

## Testing Before Commit

### Checklist
- [ ] Code runs without errors
- [ ] All new features work as expected
- [ ] No console errors or warnings
- [ ] Validation works correctly
- [ ] Error handling works
- [ ] Code follows naming conventions
- [ ] No commented-out code
- [ ] No debug console.log statements
- [ ] .env values not hardcoded

---

## Performance

### Backend
```php
// ✅ GOOD - Only select needed columns
Employee::select('id', 'name', 'email')->get();

// ❌ BAD - Select all when not needed
Employee::all(); // Returns all columns
```

### Frontend
```javascript
// ✅ GOOD - Memoize expensive calculations
const sortedEmployees = useMemo(() =>
    employees.sort((a, b) => a.name.localeCompare(b.name)),
    [employees]
);

// ✅ GOOD - Prevent unnecessary re-renders
const handleDelete = useCallback((id) => {
    deleteEmployee(id);
}, []);
```

---

## Documentation

### Code Comments
```php
// ✅ GOOD - Explain WHY, not WHAT
// Calculate salary with 10% bonus for senior positions
$finalSalary = $baseSalary * 1.1;

// ❌ BAD - Obvious comments
// Set name to employee name
$name = $employee->name;
```

### When to Comment
- Complex business logic
- Non-obvious workarounds
- Important constraints or limitations
- **Don't comment** obvious code - write self-explanatory code instead

---

## Security Checklist

### Backend
- [ ] All passwords hashed (bcrypt)
- [ ] SQL injection prevention (use Eloquent)
- [ ] CSRF protection enabled
- [ ] API routes use auth middleware
- [ ] Input validation on all endpoints
- [ ] Proper CORS configuration

### Frontend
- [ ] No sensitive data in localStorage except tokens
- [ ] XSS prevention (React handles by default)
- [ ] Validate input before sending to API
- [ ] Clear tokens on logout
- [ ] Use HTTPS in production

---

## Quick Reference

### HTTP Status Codes
```
200 - OK (GET, PUT)
201 - Created (POST)
204 - No Content (DELETE)
400 - Bad Request
401 - Unauthorized (not logged in)
403 - Forbidden (logged in but no permission)
404 - Not Found
422 - Unprocessable Entity (validation errors)
500 - Internal Server Error
```

### Laravel Artisan Commands
```bash
php artisan make:controller EmployeeController --api
php artisan make:request StoreEmployeeRequest
php artisan make:model Employee
php artisan make:migration create_employees_table
php artisan make:seeder EmployeeSeeder

php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed
```

### React Best Practices
```javascript
// Always use keys in lists
{employees.map(emp => (
    <EmployeeRow key={emp.id} employee={emp} />
))}

// Conditional rendering
{loading ? <Spinner /> : <EmployeeList />}
{error && <ErrorMessage message={error} />}

// Event handlers
<button onClick={handleClick}>Click</button>
// Not: onClick={handleClick()}
```

---

## Final Reminders

1. **Read before you write** - Understand existing code patterns
2. **Test before you commit** - Ensure everything works
3. **Keep it simple** - Simplest solution is often the best
4. **Be consistent** - Follow existing patterns in the codebase
5. **Clean as you go** - Don't leave TODO comments or dead code
6. **Document the why** - Code shows what, comments explain why

---

**Remember:** Clean code is not about being clever, it's about being clear!

**Document Version:** 1.0
**Last Updated:** 2026-01-09
