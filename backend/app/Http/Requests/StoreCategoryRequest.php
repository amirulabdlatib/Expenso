<?php

namespace App\Http\Requests;

use App\Enums\CategoryType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreCategoryRequest extends FormRequest
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
            'name' => 'required|max:255',
            'type' => ['required', new Enum(CategoryType::class)],
            'icon' => 'required',
            'color' => 'required',
            'parent_id' => ['nullable',
                            Rule::exists('categories','id')
                                ->whereNull('parent_id')
                                ->where('user_id',Auth::id()),
                        ],
        ];
    }
}
