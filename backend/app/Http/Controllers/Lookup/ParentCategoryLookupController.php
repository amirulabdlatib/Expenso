<?php

namespace App\Http\Controllers\Lookup;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ParentCategoryLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $categories = Category::query()
            ->forCurrentUser()
            ->whereNull('parent_id')
            ->where('type', CategoryType::Expense->value)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories,
        ], Response::HTTP_OK);
    }
}
