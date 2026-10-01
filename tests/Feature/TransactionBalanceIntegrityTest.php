<?php

use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionStatsRepository;
use Illuminate\Support\Facades\DB;

// A2: comportamiento esperado del saldo al registrar transacciones.

function balanceFixtures(string $balance = '100.00'): array
{
    $category = Category::create(['name' => 'Food & Groceries', 'type' => 'expense', 'icon' => '🛒']);
    $paymentMethod = PaymentMethod::create(['name' => 'Cash', 'icon' => '💵']);
    $user = User::factory()->create(['total_balance' => $balance]);

    return [$user, $category, $paymentMethod];
}

function postBalanceTransaction($test, User $user, Category $category, PaymentMethod $paymentMethod, string $type, string $amount)
{
    return $test->actingAs($user)->postJson('/transactions', [
        'description' => 'Test',
        'amount' => $amount,
        'category_id' => $category->id,
        'payment_method_id' => $paymentMethod->id,
        'type' => $type,
        'transaction_date' => now()->toDateString(),
    ]);
}

test('an expense subtracts its amount from the balance', function () {
    [$user, $category, $paymentMethod] = balanceFixtures('100.00');

    $response = postBalanceTransaction($this, $user, $category, $paymentMethod, 'expense', '30');

    $response->assertOk();
    expect($response->json('totalBalance'))->toEqual(70)
        ->and($user->fresh()->total_balance)->toBe('70.00');
});

test('an income adds its amount to the balance', function () {
    [$user, $category, $paymentMethod] = balanceFixtures('100.00');

    $response = postBalanceTransaction($this, $user, $category, $paymentMethod, 'income', '30');

    $response->assertOk();
    expect($response->json('totalBalance'))->toEqual(130)
        ->and($user->fresh()->total_balance)->toBe('130.00');
});

test('a failure while updating the balance does not persist the transaction', function () {
    [$user, $category, $paymentMethod] = balanceFixtures('100.00');

    // Simula un error justo después de actualizar el saldo del usuario.
    DB::listen(function ($query) {
        if (str_starts_with($query->sql, 'update `users`') && str_contains($query->sql, 'total_balance')) {
            throw new RuntimeException('Fallo simulado al actualizar el saldo');
        }
    });

    $response = postBalanceTransaction($this, $user, $category, $paymentMethod, 'expense', '30');

    $response->assertStatus(500);
    expect(Transaction::count())->toBe(0)
        ->and($user->fresh()->total_balance)->toBe('100.00');
});

test('the balance stays consistent with the stored amount when it has more than two decimals', function () {
    [$user, $category, $paymentMethod] = balanceFixtures('100.00');

    postBalanceTransaction($this, $user, $category, $paymentMethod, 'expense', '10.005')->assertOk();

    $storedAmount = Transaction::first()->amount;

    expect($user->fresh()->total_balance)->toBe(bcsub('100.00', $storedAmount, 2));
});

test('the returned balance has no floating point artifacts', function () {
    [$user, $category, $paymentMethod] = balanceFixtures('0.10');

    $response = postBalanceTransaction($this, $user, $category, $paymentMethod, 'income', '0.20');

    $response->assertOk();
    expect($response->json('totalBalance'))->toBe(0.3);
});

test('the user row is locked before the transaction is inserted', function () {
    [$user, $category, $paymentMethod] = balanceFixtures('100.00');

    $queries = [];
    DB::listen(function ($query) use (&$queries) {
        $queries[] = strtolower($query->sql);
    });

    postBalanceTransaction($this, $user, $category, $paymentMethod, 'expense', '30')->assertOk();

    $lockIndex = collect($queries)->search(fn ($sql) => str_starts_with($sql, 'select') && str_contains($sql, 'from `users`') && str_ends_with($sql, 'for update'));
    $insertIndex = collect($queries)->search(fn ($sql) => str_starts_with($sql, 'insert into `transactions`'));

    expect($lockIndex)->not->toBeFalse()
        ->and($insertIndex)->not->toBeFalse()
        ->and($lockIndex)->toBeLessThan($insertIndex);
});

test('interleaved balance updates for the same user are not lost', function () {
    [$user, $category, $paymentMethod] = balanceFixtures('100.00');

    // Dos peticiones simultáneas: cada una cargó al usuario con saldo 100 antes de que la otra guardara.
    $requestA = User::find($user->id);
    $requestB = User::find($user->id);

    $attributes = ['user_id' => $user->id, 'category_id' => $category->id, 'payment_method_id' => $paymentMethod->id, 'type' => 'expense', 'description' => 'Test', 'date' => now()];
    $expenseA = Transaction::create($attributes + ['amount' => '30']);
    $expenseB = Transaction::create($attributes + ['amount' => '20']);

    $repository = app(TransactionStatsRepository::class);
    $repository->updateTotalBalance($requestA, $expenseA);
    $repository->updateTotalBalance($requestB, $expenseB);

    expect($user->fresh()->total_balance)->toBe('50.00');
});
