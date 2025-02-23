<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->currency_id === null) {
           return redirect()->route('select-currency');
        }

        if ($user->total_balance === null || $user->total_balance == 0) {
            return redirect()->route('initial-balance');
        }
            
        return $this->show();
    }

    public function show(){
        /** @var User $user */
        $user = Auth::user();
        $currencySymbol = $user->currency()->first()->symbol;
        $totalBalance = $user->total_balance;

        return Inertia::render('Dashboard', compact('currencySymbol', 'totalBalance'));
    }
}