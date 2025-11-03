<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InitialBalanceController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        $currency = $user->currency()->first();
        return Inertia::render('InitialBalance', [
            'currencySymbol' => $currency ? $currency->symbol : ''
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'total_balance' => 'required|numeric|min:0'
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'total_balance' => $validated['total_balance']
        ]);

        return redirect()->route('dashboard');
    }
}