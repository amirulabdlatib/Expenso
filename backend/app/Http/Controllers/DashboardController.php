<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    private int $currentUser;

    public function __construct()
    {
        $this->currentUser = Auth::id();
    }

    public function index()
    {
        $user = $this->currentUser;
        return response()->json([]);
    }


    private function getTotalBalance() {}

    private function getMonthlyIncome() {}

    private function getMonthlyExpenses() {}

    private function getTransactions() {}

    private function getMonthlySavingGoals() {}

    private function getBudgetOverview() {}

    private function getActiveSavingsGoals() {}
}
