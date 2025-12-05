<?php

namespace App\Http\Controllers\Lookup;

use App\Models\Account;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;


class AccountLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $accounts = Account::getActiveAccounts();

        return response()->json([
            'accounts' => $accounts,
        ], Response::HTTP_OK);
    }
}
