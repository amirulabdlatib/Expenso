<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;

class ProfileController extends Controller
{
    public function index()
    {
        $total_transactions = Transaction::forCurrentUser()
            ->count();

        $total_accounts = Account::forCurrentUser()
            ->count();

        return response()->json([
            'total_transactions' => $total_transactions,
            'total_accounts' => $total_accounts,
            'total_income' => Transaction::totalIncome(),
            'total_expenses' => Transaction::totalExpenses(),
            'net_balance' => Transaction::totalIncome() - Transaction::totalExpenses(),
        ]);
    }

    public function update() {}

    public function deleteData() {}

    public function destroy() {}
}
