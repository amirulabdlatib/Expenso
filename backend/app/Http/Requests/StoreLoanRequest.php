<?php

namespace App\Http\Requests;

use App\Enums\LoanType;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
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
            'account_id' => [
                'required',
                'exists:accounts,id',
                function ($attribute, $value, $fail) {
                    $account = Account::find($value);
                    if (!$account || $account->user_id !== Auth::id()) {
                        $fail('The selected account does not belong to you.');
                    }
                },
            ],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100', new Enum(LoanType::class)],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'initial_paid_amount' => ['nullable', 'numeric', 'min:0', 'lt:total_amount'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'Please select an account.',
            'account_id.exists' => 'The selected account does not exist.',
            'initial_paid_amount.lte' => 'The initial paid amount must be less than or equal to the total amount.',
        ];
    }
}
