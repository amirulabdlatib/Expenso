<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
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
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
                Rule::unique('budgets')
                    ->where('user_id', $this->user()->id)
                    ->where('month', $this->month)
                    ->where('year', $this->year),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:99999999.99',
            ],
            'month' => [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],
            'year' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'category_id.unique' => 'A budget for this category already exists for the selected month and year.',
            'amount.required' => 'Please enter a budget amount.',
            'amount.min' => 'The budget amount must be at least 0.01.',
            'amount.max' => 'The budget amount cannot exceed 99,999,999.99.',
            'month.required' => 'Please select a month.',
            'month.min' => 'Invalid month selected.',
            'month.max' => 'Invalid month selected.',
            'year.required' => 'Please select a year.',
            'year.min' => 'Year must be 2000 or later.',
            'year.max' => 'Year must be 2100 or earlier.',
        ];
    }
}
