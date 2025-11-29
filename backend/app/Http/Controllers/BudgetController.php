<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BudgetController extends Controller
{
    use AuthorizesRequests;

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
        ], Response::HTTP_OK);
    }

    public function show(Budget $budget)
    {
        $this->authorize('view', $budget);

        $budget->load(['category.children']);

        if ($budget->category->children->isNotEmpty()) {
            $transactions = Transaction::query()
                ->where('user_id', $budget->user_id)
                ->whereIn('category_id', $budget->category->children->pluck('id'))
                ->whereYear('transaction_date', $budget->year)
                ->whereMonth('transaction_date', $budget->month)
                ->with(['category', 'account'])
                ->orderBy('transaction_date', 'desc')
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'name' => $transaction->name,
                        'debit' => (float) $transaction->debit,
                        'credit' => (float) $transaction->credit,
                        'transaction_date' => $transaction->transaction_date,
                        'category' => $transaction->category->name,
                        'account' => $transaction->account->name,
                    ];
                });
        } else {
            $transactions = Transaction::query()
                ->where('user_id', $budget->user_id)
                ->where('category_id', $budget->category_id)
                ->whereYear('transaction_date', $budget->year)
                ->whereMonth('transaction_date', $budget->month)
                ->with(['category', 'account'])
                ->orderBy('transaction_date', 'desc')
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'name' => $transaction->name,
                        'debit' => (float) $transaction->debit,
                        'credit' => (float) $transaction->credit,
                        'transaction_date' => $transaction->transaction_date,
                        'category' => $transaction->category->name,
                        'account' => $transaction->account->name,
                    ];
                });
        }

        return response()->json([
            'transactions' => $transactions
        ]);
    }

    public function store(StoreBudgetRequest $request)
    {
        $validated_data = $request->validated();
        $validated_data['user_id'] = Auth::id();

        Budget::create($validated_data);

        return response()->json([
            'message' => 'Budget created.'
        ], Response::HTTP_CREATED);
    }

    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);

        return response()->json([
            'budget' => [
                'id' => $budget->id,
                'category_id' => $budget->category_id,
                'category_name' => $budget->category->name,
                'amount' => $budget->amount,
                'month' => $budget->month,
                'year' => $budget->year,
            ]
        ]);
    }

    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $budget->update($request->validated());

        return response()->json([
            'budget' => $budget,
            'message' => 'Update successfully'
        ], Response::HTTP_OK);
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);
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
