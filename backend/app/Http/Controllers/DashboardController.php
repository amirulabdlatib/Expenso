<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Enums\CategoryType;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBalance = $this->getTotalBalance();
        $monthlyIncome = $this->getMonthlyIncome();
        $monthlyExpenses = $this->getMonthlyExpenses();
        $transactions = $this->getTransactions();

        return response()->json([
            'total_balance' => $totalBalance,
            'monthly_income' => $monthlyIncome,
            'monthly_expenses' => $monthlyExpenses,
            'transactions' => $transactions,
        ]);
    }


    private function getTotalBalance()
    {
        return Account::where('user_id', Auth::id())
            ->where('is_active', true)
            ->sum('current_balance');
    }

    private function getMonthlyIncome()
    {
        return Transaction::where('user_id', Auth::id())
            ->whereHas('category', function ($q) {
                $q->where('type', CategoryType::Income);
            })
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('credit');
    }

    private function getMonthlyExpenses()
    {
        return Transaction::where('user_id', Auth::id())
            ->whereHas('category', function ($q) {
                $q->where('type', CategoryType::Expense);
            })
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('debit');
    }

    private function getTransactions()
    {
        return Transaction::with('category:id,name,type,icon,color')
            ->latest('transaction_date')
            ->whereHas('category', function ($query) {
                $query->whereIn('type', [CategoryType::Income, CategoryType::Expense]);
            })
            ->select(['id', 'name', 'debit', 'credit', 'transaction_date', 'category_id'])
            ->limit(5)
            ->get();
    }

    private function getMonthlySavingGoals() {}

    private function getBudgetOverview() {}

    private function getActiveSavingsGoals() {}
}
