<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_display_categories_index_page(): void
    {
        Category::factory()->create([
            'name' => 'Kopi & Nongkrong',
            'type' => 'expense',
            'icon' => '☕',
        ]);

        $response = $this->get(route('categories.index'));

        $response->assertOk();
        $response->assertSee('Kopi & Nongkrong');
        $response->assertSee('☕');
    }

    public function test_can_store_new_category(): void
    {
        $payload = [
            'name' => 'Bonus Kinerja',
            'type' => 'income',
            'icon' => '🎁',
        ];

        $response = $this->post(route('categories.store'), $payload);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Bonus Kinerja',
            'type' => 'income',
            'icon' => '🎁',
        ]);
    }

    public function test_validates_required_fields_when_storing_category(): void
    {
        $response = $this->post(route('categories.store'), []);

        $response->assertSessionHasErrors(['name', 'type']);
    }

    public function test_can_update_category(): void
    {
        $category = Category::factory()->create([
            'name' => 'Makan',
            'type' => 'expense',
        ]);

        $response = $this->put(route('categories.update', $category), [
            'name' => 'Makan & Minum Mewah',
            'type' => 'expense',
            'icon' => '🥩',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Makan & Minum Mewah',
            'icon' => '🥩',
        ]);
    }

    public function test_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}

