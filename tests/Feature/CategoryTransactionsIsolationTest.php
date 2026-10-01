<?php

use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;

test('category transactions only include the authenticated user transactions', function () {
    // Las categorías son un catálogo compartido: ambos usuarios usan la misma.
    $category = Category::create(['name' => 'Food & Groceries', 'type' => 'expense', 'icon' => '🛒']);
    $paymentMethod = PaymentMethod::create(['name' => 'Cash', 'icon' => '💵']);

    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $userA->categories()->attach($category->id);
    $userB->categories()->attach($category->id);

    $transactionA = Transaction::create([
        'user_id' => $userA->id,
        'category_id' => $category->id,
        'payment_method_id' => $paymentMethod->id,
        'type' => 'expense',
        'amount' => 10,
        'description' => 'Compra de A',
        'date' => now(),
    ]);

    $transactionB = Transaction::create([
        'user_id' => $userB->id,
        'category_id' => $category->id,
        'payment_method_id' => $paymentMethod->id,
        'type' => 'expense',
        'amount' => 20,
        'description' => 'Compra de B',
        'date' => now(),
    ]);

    $response = $this->actingAs($userA)->getJson('/category-transactions/'.$category->id);

    $response->assertOk();

    $ids = collect($response->json('transactions'))->pluck('id');

    expect($ids)->toContain($transactionA->id)
        ->and($ids)->not->toContain($transactionB->id);
});
