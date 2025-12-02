<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class UpdateAccountRequest extends StoreAccountRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Update the name rule to ignore the current account being updated
        $rules['name'] = [
            'required',
            'string',
            'max:255',
            'unique:accounts,name,' . $this->route('account')->id . ',id,user_id,' . Auth::id()
        ];

        return $rules;
    }
}
