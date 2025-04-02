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
                    ->where('transactions.user_id', '=', $user->id)
                    ->whereRaw('MONTH(transactions.date) = MONTH(CURRENT_DATE()) AND YEAR(transactions.date) = YEAR(CURRENT_DATE())');
            })
            ->where('category_user.user_id', $user->id)
            ->groupBy('categories.id', 'categories.name', 'categories.icon', 'categories.type')
            ->select(
                'categories.id',
                'categories.name',
                'categories.icon',
                'categories.type',
                DB::raw('COALESCE(SUM(transactions.amount), 0) as total_amount')
            )
            ->get();
    }

    public function getMonthlySpending(User $user)
    {
        return Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    public function invalidateUserCache(User $user)
    {
        Cache::forget("user_{$user->id}_category_stats");
        Cache::forget("user_{$user->id}_monthly_spending");
    }

    public function updateTotalBalance($user, $transaction)
    {
        if ($transaction->type === 'expense') {
            $user->total_balance -= $transaction->amount;
        } else {
            $user->total_balance += $transaction->amount;
        }
        
        $user->save();
        return $user->total_balance;
    }  

    public function getTransactionsByPeriod(User $user, $period)
    {
        $query = Transaction::where('user_id', $user->id);

        switch ($period) {
            case 'day':
                $query->whereDate('created_at', now());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
                break;
            case 'all':
                break;
        }

        return $query->orderBy('created_at', 'desc')
                    ->with(['category', 'paymentMethod'])
                    ->get();
    }
}