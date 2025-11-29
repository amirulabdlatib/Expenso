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
        $budgetId = $request->input('budget_id');

        $categories = Category::query()
            ->forCurrentUser()
            ->whereNull('parent_id')
            ->where('type', CategoryType::Expense->value)
            ->when($month && $year, function ($query) use ($month, $year, $budgetId) {
                return $query->where(function ($q) use ($month, $year, $budgetId) {
                    $q->whereDoesntHave('budgets', function ($sub) use ($month, $year) {
                        $sub->where('month', $month)
                            ->where('year', $year);
                    });

                    if ($budgetId) {
                        $q->orWhereHas('budgets', function ($sub) use ($budgetId) {
                            $sub->where('id', $budgetId);
                        });
                    }
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
