<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Currency;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function show()
    {
        $currencies = Currency::all();
        return Inertia::render('SelectCurrency', [
            'currencies' => $currencies
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency_id' => 'required|exists:currencies,id'
        ]);

         /** @var User $user */
        $user = Auth::user();
        $user->update([
            'currency_id' => $validated['currency_id']
        ]);

        return redirect()->route('dashboard');
    }
}