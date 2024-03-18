<?php

namespace Modules\Donor\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [ 
            'type' => 'required|string|in:member,caregiver,partner,volunteer,donor',
            'email' => ['required', 'email'],
            'user_name' => 'required',
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'min:8'],
            //'first_name' => 'required',
            //'last_name'=> 'required',
            //'gender' => 'required',
             'phone_number' => 'required',
            //'date_of_birth' => ['required'],
             'address' => 'required',
             'image'=> 'required',
             'first_name' => ['required_if:type,donor|string'],
             'last_name' => ['required_if:type,donor|string'],
             'gender' => ['required_if:type,donor|string'],
            'date_of_birth' => ['required_if:type,donor|date', 'date'],

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
