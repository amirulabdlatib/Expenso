<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBudgetRequest;

class BudgetController extends Controller
{

    public function index()
    {
        $budgets = Budget::forCurrentUser()
            ->with(['category.children'])
            ->get()
            ->map(function ($budget) {
                $spent = $this->calculateSpent($budget);
                $transactionCount = $this->calculateTransactionCount($budget);
                $percentage = $budget->amount > 0 ? round(($spent / $budget->amount) * 100) : 0;
                $remaining = $budget->amount - $spent;

                return [
                    'id' => $budget->id,
                    'category' => $budget->category->name,
                    'limit' => (float) $budget->amount,
                    'spent' => (float) $spent,
                    'percentage' => $percentage,
                    'remaining' => (float) $remaining,
                    'transactionCount' => $transactionCount,
                ];
            });

        return response()->json([
            'budgets' => $budgets,
        ]);
    }

    public function show() {}

    public function store(StoreBudgetRequest $request)
    {
        $validated_data = $request->validated();
        $validated_data['user_id'] = Auth::id();

        Budget::create($validated_data);

        return response()->json([
            'message' => 'Budget created.'
        ]);
    }

    public function update() {}

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return response()->noContent();
    }


    private function calculateSpent($budget)
    {
        if (!$budget->category) {
            return 0;
        }
        if ($budget->category->children->isNotEmpty()) {
            return Transaction::query()
                ->where('user_id', $budget->user_id)
                ->whereIn('category_id', $budget->category->children->pluck('id'))
                ->whereYear('transaction_date', $budget->year)
                ->whereMonth('transaction_date', $budget->month)
                ->sum('debit');
        }

        return Transaction::query()
            ->where('user_id', $budget->user_id)
            ->where('category_id', $budget->category_id)
            ->whereYear('transaction_date', $budget->year)
            ->whereMonth('transaction_date', $budget->month)
            ->sum('debit');
    }

    private function calculateTransactionCount($budget)
    {
        if (!$budget->category) {
            return 0;
        }

        if ($budget->category->children->isNotEmpty()) {
            return Transaction::query()
                ->where('user_id', $budget->user_id)
                ->whereIn('category_id', $budget->category->children->pluck('id'))
                ->whereYear('transaction_date', $budget->year)
                ->whereMonth('transaction_date', $budget->month)
                ->count();
        }

        return Transaction::query()
            ->where('user_id', $budget->user_id)
            ->where('category_id', $budget->category_id)
            ->whereYear('transaction_date', $budget->year)
            ->whereMonth('transaction_date', $budget->month)
            ->count();
    }
}
