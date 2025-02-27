<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransactionStatsRepository
{
    public function getCategoryStats(User $user)
    {
        return DB::table('categories')
            ->join('category_user', 'categories.id', '=', 'category_user.category_id')
            ->leftJoin('transactions', function($join) use ($user) {
                $join->on('categories.id', '=', 'transactions.category_id')
                     ->where('transactions.user_id', '=', $user->id);
            })
            ->where('category_user.user_id', $user->id)
            ->groupBy('categories.id', 'categories.name')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COALESCE(SUM(transactions.amount), 0) as total_amount')
            )
            ->get();
    }

    public function getMonthlySpending(User $user)
    {
        $cacheKey = "user_{$user->id}_monthly_spending";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user) {
            return Transaction::where('user_id', $user->id)
                ->where('type', 'expense')
                ->whereMonth('created_at', now()->month)
                ->sum('amount');
        });
    }

    public function invalidateUserCache(User $user)
    {
        Cache::forget("user_{$user->id}_category_stats");
        Cache::forget("user_{$user->id}_monthly_spending");
    }

    public function updateTotalBalance(User $user, Transaction $transaction)
    {
        $amount = $transaction->amount;
        if ($transaction->type === 'expense') {
            $amount = -$amount;
        }
        
        $user->total_balance += $amount;
        $user->save();
        
        return $user->total_balance;
    }
}