<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'type' => 'required|in:expense,income',
            'transaction_date' => 'required|date'
        ]);
    
        $date = \Carbon\Carbon::parse($validated['transaction_date'])
            ->setTimeFromTimeString(now()->format('H:i:s'));
    
        [$user, $totalBalance] = DB::transaction(function () use ($validated, $date) {
            // Bloquear al usuario antes del INSERT evita perder actualizaciones concurrentes del saldo
            // y el deadlock causado por el bloqueo de la foreign key al insertar en transactions.
            $user = User::whereKey(Auth::id())->lockForUpdate()->firstOrFail();

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'date' => $date,
            ]);

            // Usar el monto tal como lo guardó MySQL (DECIMAL(10,2)).
            $transaction->refresh();

            // Actualizar el balance total
            return [$user, $this->statsRepository->updateTotalBalance($user, $transaction)];
        });

        $categories = $this->statsRepository->getCategoryStats($user);
        $monthlySpending = $this->statsRepository->getMonthlySpending($user);

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'totalBalance' => $totalBalance,
            'monthlySpending' => $monthlySpending
        ]);
    }

    public function getCategoryTransactions($categoryId)
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('category_id', $categoryId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'description' => $transaction->description,
                    'amount' => $transaction->amount,
                    'type' => $transaction->type,
                    'date' => $transaction->date->format('d M, Y')
                ];
            });
        return response()->json([
            'transactions' => $transactions
        ]);
    }

    public function getTransactionsByPeriod($period)
    {
        $user = Auth::user();
        $transactions = $this->statsRepository->getTransactionsByPeriod($user, $period);

        return response()->json([
            'transactions' => $transactions
        ]);
    }
}