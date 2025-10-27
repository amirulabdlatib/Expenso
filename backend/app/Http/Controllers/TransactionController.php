<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionRequest;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with([
                'accounts:name,icon',
                'categories:name,icon,color,type'
            ])->get();

        $total_income = Transaction::totalIncome();
        $total_expenses = Transaction::totalExpenses();
        $total_transaction_this_month = Transaction::where('user_id', Auth::id())
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return response()->json([
            'transactions' => $transactions,
            'total_income' => $total_income,
            'total_expenses' => $total_expenses,
            'total_transaction_this_month' => $total_transaction_this_month,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        Log::info($data);

        if ($data['type'] == TransactionType::Income) {
        } elseif ($data['type'] == TransactionType::Expense) {
        } else {
        }

        // Transaction::create($request->validated());

        return response()->json([
            'message' => ' Transaction created.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
