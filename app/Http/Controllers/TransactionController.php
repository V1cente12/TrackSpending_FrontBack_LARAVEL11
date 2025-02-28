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
            'payment_method_id' => 'required|exists:payment_methods,id',
            'type' => 'required|in:expense,income'
        ]);

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'payment_method_id' => $validated['payment_method_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'date' => now(), 
        ]);

        $user = Auth::user();
        // Actualizar el balance total
        $totalBalance = $this->statsRepository->updateTotalBalance($user, $transaction);
        $categories = $this->statsRepository->getCategoryStats($user);
        $monthlySpending = $this->statsRepository->getMonthlySpending($user);

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'totalBalance' => $totalBalance,
            'monthlySpending' => $monthlySpending
        ]);
    }
}