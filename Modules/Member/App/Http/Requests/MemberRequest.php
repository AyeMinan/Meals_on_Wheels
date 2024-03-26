<?php

namespace Modules\Member\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => ['required'],
            'phone_number' => ['required'],
            'first_name' => ['required_if:type,member|string'],
            'last_name' => ['required_if:type,member|string'],
            'gender' => ['required_if:type,member|string'],
            'date_of_birth' => ['required_if:type,member|date', 'date'],
            'age' => 'required_if:type,member|integer',
            'emergency_contact_number' => 'required_if:type,member|string',
            'dietary_restriction' => 'required_if:type,member|string',

             // 'user_id' => ['required'],
            // 'first_name' => ['required'],
            // 'last_name' => ['required'],
            // 'gender' => ['required'],
            // 'date_of_birth' => ['required'],
            // 'age' => 'required',
            // 'emergency_contact_number' => 'required',
            // 'dietary_restriction' => 'required',
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
