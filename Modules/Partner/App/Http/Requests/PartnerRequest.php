<?php

namespace Modules\Partner\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:volunteers,email,' . $this->partner,
            'user_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'confirm_password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'first_name' => 'required',
            'last_name' => 'required',
            'shop_name'=>'required',
            'shop_address'=>'required',

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
