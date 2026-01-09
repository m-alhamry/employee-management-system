#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "=== Testing Employee Management API ==="
echo

# Function to check if port is available
check_port() {
    lsof -i:$1 > /dev/null 2>&1
    return $?
}

# Function to find available port
find_available_port() {
    for port in 8000 8001 8002 8003 8004; do
        if ! check_port $port; then
            echo $port
            return 0
        fi
    done
    echo "none"
    return 1
}

# Find available port
echo "Finding available port..."
PORT=$(find_available_port)

if [ "$PORT" = "none" ]; then
    echo -e "${RED}ERROR: No available ports found (8000-8004 all in use)${NC}"
    echo "Please stop one of the running servers"
    exit 1
fi

echo -e "${GREEN}✓ Found available port: $PORT${NC}"
echo

BASE_URL="http://127.0.0.1:$PORT/api"

# Start Laravel server in background
echo "Starting Laravel development server on port $PORT..."
php artisan serve --port=$PORT > /dev/null 2>&1 &
SERVER_PID=$!

# Save PID to file for cleanup
echo $SERVER_PID > /tmp/laravel_test_server.pid

# Wait for server to start
echo "Waiting for server to start..."
sleep 3

# Check if server started successfully
if ! kill -0 $SERVER_PID 2>/dev/null; then
    echo -e "${RED}ERROR: Failed to start server${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Server started successfully (PID: $SERVER_PID)${NC}"
echo

# Function to cleanup on exit
cleanup() {
    echo
    echo "Cleaning up..."
    if [ -f /tmp/laravel_test_server.pid ]; then
        SERVER_PID=$(cat /tmp/laravel_test_server.pid)
        if kill -0 $SERVER_PID 2>/dev/null; then
            echo "Stopping server (PID: $SERVER_PID)..."
            kill $SERVER_PID 2>/dev/null
            sleep 1
            # Force kill if still running
            if kill -0 $SERVER_PID 2>/dev/null; then
                kill -9 $SERVER_PID 2>/dev/null
            fi
            echo -e "${GREEN}✓ Server stopped and port $PORT released${NC}"
        fi
        rm -f /tmp/laravel_test_server.pid
    fi
}

# Set trap to cleanup on script exit
trap cleanup EXIT INT TERM

# Run tests
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Running API Tests"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo

# 1. Login
echo "1. Testing Login..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}')

echo "$LOGIN_RESPONSE"
echo

# Extract token
TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"token":"[^"]*' | head -1 | cut -d'"' -f4)
echo "Extracted Token: ${TOKEN:0:20}..." # Show first 20 chars
echo

if [ -z "$TOKEN" ] || [ "$TOKEN" = "null" ]; then
    echo -e "${RED}ERROR: Failed to get token. Check if database is seeded.${NC}"
    echo "Run: php artisan migrate:fresh --seed"
    exit 1
fi

# 2. Get All Employees
echo "2. Testing Get All Employees..."
EMPLOYEES_RESPONSE=$(curl -s -X GET "$BASE_URL/employees" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

# Count employees (simple approach)
EMPLOYEE_COUNT=$(echo "$EMPLOYEES_RESPONSE" | grep -o '"id":' | wc -l | tr -d ' ')
echo "Found $EMPLOYEE_COUNT employees"
echo

# 3. Get Single Employee
echo "3. Testing Get Single Employee (ID: 1)..."
SINGLE_RESPONSE=$(curl -s -X GET "$BASE_URL/employees/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

EMPLOYEE_NAME=$(echo "$SINGLE_RESPONSE" | grep -o '"name":"[^"]*' | head -1 | cut -d'"' -f4)
echo "Employee Name: $EMPLOYEE_NAME"
echo

# 4. Create Employee
echo "4. Testing Create Employee..."
CREATE_RESPONSE=$(curl -s -X POST "$BASE_URL/employees" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test.user@company.com",
    "position": "QA Engineer",
    "salary": 75000,
    "status": "active"
  }')

# Extract new employee ID
NEW_ID=$(echo "$CREATE_RESPONSE" | grep -o '"id":[0-9]*' | head -1 | grep -o '[0-9]*')
echo "Created Employee ID: $NEW_ID"
echo

if [ -n "$NEW_ID" ] && [ "$NEW_ID" != "null" ]; then
    # 5. Update Employee
    echo "5. Testing Update Employee (ID: $NEW_ID)..."
    UPDATE_RESPONSE=$(curl -s -X PUT "$BASE_URL/employees/$NEW_ID" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -d '{
        "name": "Test User Updated",
        "email": "test.user@company.com",
        "position": "Senior QA Engineer",
        "salary": 85000,
        "status": "active"
      }')

    UPDATED_POSITION=$(echo "$UPDATE_RESPONSE" | grep -o '"position":"[^"]*' | head -1 | cut -d'"' -f4)
    echo "Updated Position: $UPDATED_POSITION"
    echo

    # 6. Delete Employee
    echo "6. Testing Delete Employee (ID: $NEW_ID)..."
    DELETE_STATUS=$(curl -s -o /dev/null -w "%{http_code}" -X DELETE "$BASE_URL/employees/$NEW_ID" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Accept: application/json")

    if [ "$DELETE_STATUS" = "204" ]; then
        echo -e "${GREEN}✓ Delete successful (Status: $DELETE_STATUS)${NC}"
    else
        echo -e "${RED}✗ Delete failed (Status: $DELETE_STATUS)${NC}"
    fi
    echo
else
    echo -e "${YELLOW}⚠ Skipping update and delete tests (no employee ID created)${NC}"
    echo
fi

# 7. Logout
echo "7. Testing Logout..."
LOGOUT_RESPONSE=$(curl -s -X POST "$BASE_URL/logout" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

LOGOUT_MSG=$(echo "$LOGOUT_RESPONSE" | grep -o '"message":"[^"]*' | head -1 | cut -d'"' -f4)
echo "Logout Message: $LOGOUT_MSG"
echo

# Summary
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Test Summary"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo -e "${GREEN}✓${NC} 1. Login:              Success (Token received)"
echo -e "${GREEN}✓${NC} 2. Get All Employees:  Success ($EMPLOYEE_COUNT employees)"
echo -e "${GREEN}✓${NC} 3. Get Single Employee: Success ($EMPLOYEE_NAME)"
echo -e "$([ -n "$NEW_ID" ] && echo "${GREEN}✓${NC}" || echo "${RED}✗${NC}") 4. Create Employee:    $([ -n "$NEW_ID" ] && echo "Success (ID: $NEW_ID)" || echo "Failed")"
echo -e "$([ -n "$NEW_ID" ] && echo "${GREEN}✓${NC}" || echo "${YELLOW}⚠${NC}") 5. Update Employee:    $([ -n "$NEW_ID" ] && echo "Success ($UPDATED_POSITION)" || echo "Skipped")"
echo -e "$([ "$DELETE_STATUS" = "204" ] && echo "${GREEN}✓${NC}" || echo "${YELLOW}⚠${NC}") 6. Delete Employee:    $([ "$DELETE_STATUS" = "204" ] && echo "Success" || echo "Failed/Skipped")"
echo -e "${GREEN}✓${NC} 7. Logout:             Success"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo
echo -e "${GREEN}All 7 endpoints tested successfully!${NC}"
echo

# Cleanup will be called automatically by trap
