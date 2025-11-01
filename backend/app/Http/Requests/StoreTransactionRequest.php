<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{

    protected $types = [
        'income',
        'expense',
        'transfer',
    ];

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
            'type' => ['required', 'string', Rule::in($this->types)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0.00',],
            'transaction_date' => ['required', 'date_format:Y-m-d H:i:s'],
            'account_id' => [
                'required',
                'integer',
                Rule::exists('accounts', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ],
            'category_id' => [
                'nullable',
                'required_if:type,income,expense',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ],
            'related_account_id' => [
                'nullable',
                'required_if:type,transfer',
                'integer',
                'different:account_id',
                Rule::exists('accounts', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Transaction type is required.',
            'type.in' => 'Transaction type must be income, expense, or transfer.',
            'name.required' => 'Transaction name is required.',
            'name.max' => 'Transaction name cannot exceed 255 characters.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be at least 0.01.',
            'account_id.required' => 'Account is required.',
            'account_id.exists' => 'The selected account does not exist or does not belong to you.',
            'category_id.required_if' => 'Category is required for income or expense transactions.',
            'category_id.exists' => 'The selected category does not exist or does not belong to you.',
            'related_account_id.required_if' => 'Destination account is required for transfers.',
            'related_account_id.different' => 'Destination account must be different from the source account.',
            'related_account_id.exists' => 'The selected destination account does not exist or does not belong to you.',
        ];
    }
}
