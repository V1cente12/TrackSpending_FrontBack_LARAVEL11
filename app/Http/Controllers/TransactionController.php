<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TransactionStatsRepository;

class TransactionController extends Controller
{
    protected $statsRepository;

    public function __construct(TransactionStatsRepository $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:expense,income'
        ]);

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'category_id' => $validated['category_id'],
            'type' => $validated['type']
        ]);

        // Obtener estadísticas actualizadas
        $user = Auth::user();
        $categories = $this->statsRepository->getCategoryStats($user);
        $totalBalance = $user->total_balance;

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'totalBalance' => $totalBalance
        ]);
    }
}