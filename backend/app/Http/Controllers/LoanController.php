<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Enums\LoanType;
use App\Models\Account;
use App\Models\LoanPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enums\LoanPaymentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreLoanRequest;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();
        $data['description'] = $data['description'] ?? null;
        $data['total_balance'] = $data['total_amount'] - ($data['initial_paid_amount'] ?? 0);

        DB::transaction(function () use ($data) {
            $loan = Loan::create($data);

            $account = Account::find($data['account_id']);

            if ($data['type'] === LoanType::Borrow->value) {
                $account->increment('current_balance', $data['total_amount']);
            } elseif ($data['type'] === LoanType::Lent->value) {  // ✅ Fixed
                $account->decrement('current_balance', $data['total_amount']);
            }

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'account_id' => $data['account_id'],
                'category_id' => null,
                'name' => $data['name'],
                'description' => $data['description'],
                'debit' => $data['type'] === LoanType::Lent->value ? $data['total_amount'] : null,
                'credit' => $data['type'] === LoanType::Borrow->value ? $data['total_amount'] : null,
                'transaction_date' => $data['start_date'],
                'loan_id' => $loan->id,
            ]);


            LoanPayment::create([
                'loan_id' => $loan->id,
                'transaction_id' => $transaction->id,
                'amount' => $data['total_amount'],
                'direction' => $data['type'] === LoanType::Borrow->value ? LoanPaymentType::IN->value : LoanPaymentType::OUT->value,
                'date' => $data['start_date'],
            ]);
        });

        return response()->json([
            'message' => 'Loan created successfully'
        ]);
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
