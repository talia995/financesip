<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_display_transactions_index_page(): void
    {
        $account = Account::factory()->create(['name' => 'BCA Test']);
        $category = Category::factory()->create(['name' => 'Makan Enak', 'type' => 'expense']);
        
        Transaction::factory()->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 50000,
        ]);

        $response = $this->get(route('transactions.index'));

        $response->assertOk();
        $response->assertSee('BCA Test');
        $response->assertSee('Makan Enak');
        $response->assertSee('50.000');
    }

    public function test_can_store_income_transaction_and_increments_account_balance(): void
    {
        $account = Account::factory()->create([
            'current_balance' => 1000000,
        ]);
        $category = Category::factory()->create(['type' => 'income']);

        $payload = [
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => 500000,
            'transaction_date' => now()->format('Y-m-d'),
            'notes' => 'Gaji bulanan',
        ];

        $response = $this->post(route('transactions.store'), $payload);

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseHas('transactions', [
            'account_id' => $account->id,
            'amount' => 500000,
            'type' => 'income',
        ]);

        // Saldo akun harus bertambah: 1.000.000 + 500.000 = 1.500.000
        $this->assertEquals(1500000, $account->fresh()->current_balance);
    }

    public function test_can_store_expense_transaction_and_decrements_account_balance(): void
    {
        $account = Account::factory()->create([
            'current_balance' => 1000000,
        ]);
        $category = Category::factory()->create(['type' => 'expense']);

        $payload = [
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 200000,
            'transaction_date' => now()->format('Y-m-d'),
            'notes' => 'Beli bensin dan belanja',
        ];

        $response = $this->post(route('transactions.store'), $payload);

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseHas('transactions', [
            'account_id' => $account->id,
            'amount' => 200000,
            'type' => 'expense',
        ]);

        // Saldo akun harus berkurang: 1.000.000 - 200.000 = 800.000
        $this->assertEquals(800000, $account->fresh()->current_balance);
    }

    public function test_can_update_transaction_and_adjusts_account_balances(): void
    {
        $account = Account::factory()->create([
            'current_balance' => 800000, // setelah expense 200.000 dari saldo awal 1.000.000
        ]);
        $category = Category::factory()->create(['type' => 'expense']);

        $transaction = Transaction::factory()->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 200000,
        ]);

        // Ubah nominal dari 200.000 menjadi 300.000
        $response = $this->put(route('transactions.update', $transaction), [
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 300000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('transactions.index'));
        // Saldo akun: revert 200.000 (800k + 200k = 1jt), lalu kurangi 300.000 = 700.000
        $this->assertEquals(700000, $account->fresh()->current_balance);
    }

    public function test_can_delete_transaction_and_reverts_account_balance(): void
    {
        $account = Account::factory()->create([
            'current_balance' => 800000,
        ]);
        $category = Category::factory()->create(['type' => 'expense']);

        $transaction = Transaction::factory()->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 200000,
        ]);

        $response = $this->delete(route('transactions.destroy', $transaction));

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id,
        ]);

        // Saldo pengeluaran dikembalikan: 800.000 + 200.000 = 1.000.000
        $this->assertEquals(1000000, $account->fresh()->current_balance);
    }

    public function test_validates_required_fields_when_storing_transaction(): void
    {
        $response = $this->post(route('transactions.store'), []);

        $response->assertSessionHasErrors(['account_id', 'category_id', 'type', 'amount', 'transaction_date']);
    }
}

