# Frontend Installation Guide - React Application Setup

## Prerequisites

### Required Software
- **Node.js**: Version 18.x or higher
- **npm**: Version 9.x or higher (comes with Node.js)
- **Backend API**: Laravel backend must be running on `http://127.0.0.1:8000`

### Verify Prerequisites
```bash
node --version    # Should show v18.x or higher
npm --version     # Should show 9.x or higher
```

If Node.js is not installed, download from: https://nodejs.org/

---

## Installation Steps

### 1. Navigate to Frontend Directory
```bash
cd frontend
```

### 2. Install Dependencies
```bash
npm install
```

This will install:
- React 19.2.0
- React Router DOM 7.12.0
- Axios 1.13.2
- Vite 7.2.4

### 3. Configure API Base URL

The API base URL is configured in `src/services/api.js`:
```javascript
const API_BASE_URL = 'http://127.0.0.1:8000/api';
```

If your backend runs on a different URL, update this value.

### 4. Start Development Server
```bash
npm run dev
```

The application will start on: `http://localhost:5173`

---

## Default Login Credentials

Use these credentials from the backend seeder:

**Email:** `admin@example.com`
**Password:** `password`

---

## Available Scripts

### Development
```bash
npm run dev
```
Starts the development server with hot-reload.

### Build for Production
```bash
npm run build
```
Creates an optimized production build in the `dist` folder.

### Preview Production Build
```bash
npm run preview
```
Previews the production build locally.

### Lint Code
```bash
npm run lint
```
Checks code quality using ESLint.

---

## Project Structure

```
frontend/
├── src/
│   ├── components/         # Reusable components
│   │   └── ProtectedRoute.jsx
│   ├── contexts/          # React contexts
│   │   └── AuthContext.jsx
│   ├── hooks/             # Custom React hooks
│   │   └── useAuth.js
│   ├── pages/             # Page components
│   │   ├── Login.jsx
│   │   ├── Employees.jsx
│   │   └── EmployeeForm.jsx
│   ├── services/          # API services
│   │   ├── api.js
│   │   ├── authService.js
│   │   └── employeeService.js
│   ├── App.jsx            # Main application component
│   ├── App.css            # Global application styles
│   ├── index.css          # Root styles
│   └── main.jsx           # Application entry point
├── public/                # Static assets
├── package.json           # Dependencies and scripts
└── vite.config.js         # Vite configuration
```

---

## Features

### Authentication
- Token-based authentication using Laravel Sanctum
- Automatic token persistence in localStorage
- Protected routes requiring authentication
- Automatic redirect to login if not authenticated

### Employee Management
- View all employees in a table
- Add new employees with validation
- Edit existing employees
- Delete employees with confirmation
- Search and filter capabilities

### Form Validation
- Name: Required, minimum 2 characters
- Email: Required, valid email format
- Position: Required, minimum 2 characters
- Salary: Required, positive number, maximum 9,999,999.99
- Status: Required, active or inactive

---

## Troubleshooting

### Port Already in Use
If port 5173 is already in use:
```bash
# Kill the process using the port
lsof -ti:5173 | xargs kill -9

# Or specify a different port
npm run dev -- --port 3000
```

### Cannot Connect to Backend
**Error:** Network errors or 404 responses

**Solutions:**
1. Ensure backend is running:
   ```bash
   cd ../backend
   php artisan serve
   ```

2. Verify backend URL in `src/services/api.js`

3. Check CORS configuration in backend `config/cors.php`

### Build Errors
**Error:** Missing dependencies or import errors

**Solutions:**
```bash
# Clear node_modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Clear Vite cache
rm -rf node_modules/.vite
npm run dev
```

### Authentication Issues
**Problem:** Cannot login or token not persisting

**Solutions:**
1. Clear browser localStorage:
   - Open DevTools (F12)
   - Go to Application > Local Storage
   - Clear all entries

2. Verify backend Sanctum configuration

3. Check browser console for errors

### Styling Issues
**Problem:** Pages not filling the screen

**Solution:**
Ensure `src/index.css` has proper height settings:
```css
html, body {
  height: 100%;
  width: 100%;
}

#root {
  min-height: 100vh;
  width: 100%;
}
```

---

## Development Workflow

### 1. Start Backend
```bash
cd backend
php artisan serve
```

### 2. Start Frontend
```bash
cd frontend
npm run dev
```

### 3. Access Application
Open browser to: `http://localhost:5173`

### 4. Login
Use default credentials:
- Email: `admin@example.com`
- Password: `password`

---

## Production Deployment

### 1. Build Application
```bash
npm run build
```

### 2. Deploy `dist` Folder
The `dist` folder contains the production-ready application.

### 3. Configure Environment
Update API base URL for production environment in `src/services/api.js`.

### 4. Serve Static Files
Use any static file server:
```bash
# Using serve (install globally)
npm install -g serve
serve -s dist -p 5173
```

Or deploy to:
- Vercel
- Netlify
- AWS S3 + CloudFront
- Any web server (Apache, Nginx)

---

## Installation Checklist

- [ ] Node.js and npm installed
- [ ] Dependencies installed (`npm install`)
- [ ] Backend API running on `http://127.0.0.1:8000`
- [ ] Development server started (`npm run dev`)
- [ ] Application accessible at `http://localhost:5173`
- [ ] Successfully logged in with default credentials
- [ ] Can view employees list
- [ ] Can add new employee
- [ ] Can edit employee
- [ ] Can delete employee

---

## Additional Resources

- [React Documentation](https://react.dev/)
- [Vite Documentation](https://vitejs.dev/)
- [React Router Documentation](https://reactrouter.com/)
- [Axios Documentation](https://axios-http.com/)

---

**Document Version:** 1.0
**Last Updated:** 2026-01-09
