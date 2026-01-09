<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(): JsonResponse
    {
        $employees = $this->getAllEmployees();

        return $this->successResponse($employees);
    }

    /**
     * Store a newly created employee.
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->createEmployee($request->validated());

        return $this->createdResponse($employee);
    }

    /**
     * Display the specified employee.
     */
    public function show(string $id): JsonResponse
    {
        $employee = $this->findEmployee($id);

        if (!$employee) {
            return $this->notFoundResponse();
        }

        return $this->successResponse($employee);
    }

    /**
     * Update the specified employee.
     */
    public function update(UpdateEmployeeRequest $request, string $id): JsonResponse
    {
        $employee = $this->findEmployee($id);

        if (!$employee) {
            return $this->notFoundResponse();
        }

        $this->updateEmployee($employee, $request->validated());

        return $this->successResponse($employee);
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(string $id): JsonResponse
    {
        $employee = $this->findEmployee($id);

        if (!$employee) {
            return $this->notFoundResponse();
        }

        $this->deleteEmployee($employee);

        return $this->deletedResponse();
    }

    /**
     * Get all employees from database.
     */
    private function getAllEmployees()
    {
        return Employee::all();
    }

    /**
     * Find employee by ID.
     */
    private function findEmployee(string $id): ?Employee
    {
        return Employee::find($id);
    }

    /**
     * Create new employee.
     */
    private function createEmployee(array $data): Employee
    {
        return Employee::create($data);
    }

    /**
     * Update existing employee.
     */
    private function updateEmployee(Employee $employee, array $data): void
    {
        $employee->update($data);
    }

    /**
     * Delete employee.
     */
    private function deleteEmployee(Employee $employee): void
    {
        $employee->delete();
    }

    /**
     * Return success response with data.
     */
    private function successResponse($data): JsonResponse
    {
        return response()->json(['data' => $data], 200);
    }

    /**
     * Return created response.
     */
    private function createdResponse(Employee $employee): JsonResponse
    {
        return response()->json(['data' => $employee], 201);
    }

    /**
     * Return not found response.
     */
    private function notFoundResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    /**
     * Return deleted response.
     */
    private function deletedResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }
}
