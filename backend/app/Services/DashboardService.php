<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Account;
use App\Enums\CategoryType;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    /**
     * Get all dashboard data at once
     */
    public function getDashboardData(): array
    {
        return [
            'total_balance' => $this->getTotalBalance(),
            'monthly_income' => $this->getMonthlyIncome(),
            'monthly_expenses' => $this->getMonthlyExpenses(),
            'transactions' => $this->getRecentTransactions(),
            'budgets' => $this->getBudgetOverview(),
        ];
    }

    public function getTotalBalance()
    {
        return Account::forCurrentUser()
            ->where('is_active', true)
            ->sum('current_balance');
    }

    public function getMonthlyIncome()
    {
        return Transaction::forCurrentUser()
            ->whereHas('category', function ($q) {
                $q->where('type', CategoryType::Income);
            })
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('credit');
    }

    public function getMonthlyExpenses()
    {
        return Transaction::forCurrentUser()
            ->whereHas('category', function ($q) {
                $q->where('type', CategoryType::Expense);
            })
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('debit');
    }

    public function getRecentTransactions()
    {
        return Transaction::where('user_id', Auth::id())
            ->with('category:id,name,type,icon,color')
            ->latest('transaction_date')
            ->whereHas('category', function ($query) {
                $query->whereIn('type', [CategoryType::Income, CategoryType::Expense]);
            })
            ->select(['id', 'name', 'debit', 'credit', 'transaction_date', 'category_id'])
            ->limit(5)
            ->get();
    }

    public function getBudgetOverview()
    {
        $now = now();

        return Budget::forCurrentUser()
            ->where('month', $now->month)
            ->where('year', $now->year)
            ->get()
            ->map(function ($budget) {
                $spent = $this->calculateSpent($budget);
                $percentage = $budget->amount > 0 ? round(($spent / $budget->amount) * 100) : 0;

                return [
                    'id' => $budget->id,
                    'spent' => (float) $spent,
                    'budget_category' => $budget->category->name,
                    'budget_amount' => (float) $budget->amount,
                    'percentage' => $percentage,
                ];
            });
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
}
