<?php

namespace Modules\Caregiver\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaregiverRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'min:8'],
            'type' => 'required|string|in:member,caregiver,partner,volunteer,donor',
            'image' => ['required'],
            'address' => ['required'],
            'phone_number' => ['required'],
            'first_name' => ['required_if:type,caregiver|string'],
            'last_name' => ['required_if:type,caregiver|string'],
            'gender' => ['required_if:type,caregiver|string'],
            'date_of_birth' => ['required_if:type,caregiver|date', 'date'],
            'relationship_with_member' => 'required_if:type,caregiver|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return true;
    // }
}
