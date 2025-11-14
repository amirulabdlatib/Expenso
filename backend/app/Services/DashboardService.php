<?php

namespace App\Services;

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

    public function getMonthlySavingGoals() {}

    public function getBudgetOverview() {}

    public function getActiveSavingsGoals() {}
}
