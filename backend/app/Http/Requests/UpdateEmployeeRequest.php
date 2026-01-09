<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee');

        return [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\-\.\']+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email,' . $employeeId],
            'position' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\-\.\']+$/u'],
            'salary' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->email ?? '')),
            'name' => trim($this->name ?? ''),
            'position' => trim($this->position ?? ''),
        ]);
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'The name may only contain letters, spaces, hyphens, dots, and apostrophes.',
            'position.regex' => 'The position may only contain letters, spaces, hyphens, dots, and apostrophes.',
            'salary.max' => 'The salary must not exceed 9,999,999.99.',
        ];
    }
}
