<?php

namespace App\Http\Requests;

use App\Enums\AccountType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:accounts,name,NULL,id,user_id,' . Auth::id()
            ],
            'type' => ['required', new Enum(AccountType::class)],
            'icon' => 'required|string',
            'initial_balance' => 'required|numeric',
            'currency' => 'required|string|max:7',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'You already have an account with this name.',
        ];
    }
}
