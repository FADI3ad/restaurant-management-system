<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Item;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Item $item1;
    protected Item $item2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user
        $this->user = User::factory()->create([
            'type' => 'admin',
        ]);

        // Create Section, Category, Subcategory and Items
        $section = Section::create([
            'name' => 'Main Section',
            'display_order' => 1,
            'status' => true,
        ]);

        $category = Category::create([
            'section_id' => $section->id,
            'name' => 'Main Category',
            'display_order' => 1,
            'status' => true,
        ]);

        $subcategory = Subcategory::create([
            'category_id' => $category->id,
            'name' => 'Main Subcategory',
            'display_order' => 1,
            'status' => true,
        ]);

        $this->item1 = Item::create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Meal 1',
            'price' => 100.00,
            'status' => true,
        ]);

        $this->item2 = Item::create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Meal 2',
            'price' => 150.00,
            'status' => true,
        ]);
    }

    public function test_guest_cannot_access_offers()
    {
        $response = $this->get(route('offers.index'));
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_offers()
    {
        $response = $this->actingAs($this->user)->get(route('offers.index'));
        $response->assertStatus(200);
        $response->assertViewIs('offers');
    }

    public function test_admin_can_create_offer_with_items()
    {
        $postData = [
            'name' => 'Weekend Special',
            'duration' => '3 Days',
            'discount_price' => 25.50,
            'status' => 1,
            'item_ids' => [$this->item1->id, $this->item2->id],
        ];

        $response = $this->actingAs($this->user)->post(route('offers.store'), $postData);

        $response->assertRedirect(route('offers.index'));
        $response->assertSessionHas('success', 'تم إضافة العرض بنجاح.');

        $this->assertDatabaseHas('offers', [
            'name' => 'Weekend Special',
            'duration' => '3 Days',
            'discount_price' => 25.50,
            'status' => true,
        ]);

        $offer = Offer::where('name', 'Weekend Special')->first();
        $this->assertCount(2, $offer->items);
    }

    public function test_admin_can_update_offer_and_items()
    {
        $offer = Offer::create([
            'name' => 'Old Offer',
            'duration' => '1 Day',
            'discount_price' => 10.00,
            'status' => true,
        ]);
        $offer->items()->sync([$this->item1->id]);

        $putData = [
            'name' => 'Updated Offer',
            'duration' => '5 Days',
            'discount_price' => 30.00,
            'status' => 1,
            'item_ids' => [$this->item2->id],
        ];

        $response = $this->actingAs($this->user)->put(route('offers.update', $offer), $putData);

        $response->assertRedirect(route('offers.index'));
        $response->assertSessionHas('success', 'تم تعديل العرض بنجاح.');

        $this->assertDatabaseHas('offers', [
            'id' => $offer->id,
            'name' => 'Updated Offer',
            'duration' => '5 Days',
            'discount_price' => 30.00,
            'status' => true,
        ]);

        $offer->refresh();
        $this->assertCount(1, $offer->items);
        $this->assertEquals('Meal 2', $offer->items->first()->name);
    }

    public function test_admin_can_delete_offer()
    {
        $offer = Offer::create([
            'name' => 'To Be Deleted',
            'duration' => '1 Day',
            'discount_price' => 5.00,
            'status' => true,
        ]);
        $offer->items()->sync([$this->item1->id]);

        $response = $this->actingAs($this->user)->delete(route('offers.destroy', $offer));

        $response->assertRedirect(route('offers.index'));
        $response->assertSessionHas('success', 'تم حذف العرض بنجاح.');

        $this->assertDatabaseMissing('offers', [
            'id' => $offer->id,
        ]);

        $this->assertDatabaseMissing('offer_item', [
            'offer_id' => $offer->id,
        ]);
    }
}
