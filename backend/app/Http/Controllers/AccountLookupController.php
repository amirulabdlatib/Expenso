<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AccountLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $accounts = Account::where('user_id', Auth::id())
            ->where('is_active', true)
            ->select(['id', 'name', 'balance'])
            ->get();

        return response()->json([
            'accounts' => $accounts,
        ], Response::HTTP_OK);
    }
}
