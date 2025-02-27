<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TransactionStatsRepository;

class DashboardController extends Controller
{
    protected $statsRepository;

    public function __construct(TransactionStatsRepository $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->currency_id === null) {
           return redirect()->route('select-currency');
        }

        if ($user->total_balance === null || $user->total_balance == 0) {
            return redirect()->route('initial-balance');
        }
       
        if (!$user->categories()->exists()) {
            return redirect()->route('select-category');
        }
            
        return $this->show();
    }

    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        $currencySymbol = $user->currency()->first()->symbol;
        $totalBalance = $user->total_balance;
        $categories = $this->statsRepository->getCategoryStats($user);

        return Inertia::render('Dashboard', [
            'currencySymbol' => $currencySymbol,
            'totalBalance' => $totalBalance,
            'categories' => $categories
        ]);
    }
}