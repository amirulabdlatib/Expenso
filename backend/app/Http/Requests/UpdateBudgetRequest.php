<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateBudgetRequest extends StoreBudgetRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $budgetId = $this->route('budget')->id;

        $rules['category_id'] = [
            'required',
            'integer',
            'exists:categories,id',
            Rule::unique('budgets')
                ->where('user_id', $this->user()->id)
                ->where('month', $this->month)
                ->where('year', $this->year)
                ->ignore($budgetId),
        ];

        return $rules;
    }
}
