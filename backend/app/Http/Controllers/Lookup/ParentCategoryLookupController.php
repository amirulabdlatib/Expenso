<?php

namespace App\Http\Controllers\Lookup;

use App\Models\Category;
use App\Enums\CategoryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParentCategoryLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $categories = Category::query()
            ->forCurrentUser()
            ->whereNull('parent_id')
            ->where('type', CategoryType::Expense->value)
            ->when($month && $year, function ($query) use ($month, $year) {
                return $query->whereDoesntHave('budgets', function ($q) use ($month, $year) {
                    $q->where('month', $month)
                        ->where('year', $year);
                });
            })
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories
        ]);
    }
}
