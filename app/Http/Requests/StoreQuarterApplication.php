<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuarterApplication extends FormRequest
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
        return [
            'quarter_type' => 'required|in:Family,Scheduled',
            'officer_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'nic' => 'required|string|max:20',
            'designation' => 'required|string|max:100',
            'service_grade' => 'required|in:1,2,3,4,5,5A',
            'permanent_address' => 'required|string',
            'temporary_address' => 'required|string',
            'monthly_salary' => 'required|numeric',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'date_of_assumption_of_duties' => 'required|date',
        ];
    }
}
