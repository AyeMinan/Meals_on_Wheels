<?php

namespace Modules\Volunteer\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VolunteerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:volunteers,email,' . $this->volunteer,
            'user_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'address' => 'required|string',
            'image' => 'nullable|image|max:2048', 
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
