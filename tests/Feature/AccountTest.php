<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_display_accounts_index_page(): void
    {
        Account::factory()->create([
            'name' => 'BCA Utama',
            'current_balance' => 1500000,
        ]);

        $response = $this->get(route('accounts.index'));

        $response->assertOk();
        $response->assertSee('BCA Utama');
        $response->assertSee('1.500.000');
    }

    public function test_can_store_new_bank_account(): void
    {
        $payload = [
            'name' => 'Mandiri Operasional',
            'type' => 'bank',
            'account_number' => '1400012345678',
            'initial_balance' => 2500000,
            'notes' => 'Rekening belanja operasional',
        ];

        $response = $this->post(route('accounts.store'), $payload);

        $response->assertRedirect(route('accounts.index'));
        $this->assertDatabaseHas('accounts', [
            'name' => 'Mandiri Operasional',
            'type' => 'bank',
            'initial_balance' => 2500000,
            'current_balance' => 2500000,
            'is_active' => true,
        ]);
    }

    public function test_validates_required_fields_when_storing_account(): void
    {
        $response = $this->post(route('accounts.store'), []);

        $response->assertSessionHasErrors(['name', 'type', 'initial_balance']);
    }

    public function test_can_update_bank_account(): void
    {
        $account = Account::factory()->create([
            'name' => 'GoPay Lama',
            'type' => 'e-wallet',
        ]);

        $response = $this->put(route('accounts.update', $account), [
            'name' => 'GoPay Baru',
            'type' => 'e-wallet',
            'account_number' => '08123456789',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('accounts.index'));
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'GoPay Baru',
        ]);
    }

    public function test_can_delete_bank_account(): void
    {
        $account = Account::factory()->create();

        $response = $this->delete(route('accounts.destroy', $account));

        $response->assertRedirect(route('accounts.index'));
        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
        ]);
    }
}

