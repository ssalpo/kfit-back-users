<?php

namespace Tests\Feature\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\Helpers\ProductHelper;
use Tests\Helpers\UserHelper;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    const RESOURCE_STRUCTURE = [
        'id', 'title', 'description', 'price', 'products', 'expired_at',
    ];

    /**
     * @return void
     */
    public function test_admin_can_see_list_of_products()
    {
        UserHelper::actAsAdmin();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonStructure([
                'data' => [
                    '*' => self::RESOURCE_STRUCTURE
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_add_new_product()
    {
        UserHelper::actAsAdmin();

        $form = [
            'title' => 'Some product title',
            'description' => 'Some product description',
            'price' => 2000,
            'expired_at' => now()->addMonth()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/v1/products', $form);

        $response->assertStatus(201)
            ->assertJson(['data' => $form])
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_see_product_info_by_id()
    {
        UserHelper::actAsAdmin();

        $product = ProductHelper::getRandomUser();

        $response = $this->getJson('/api/v1/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => Arr::except(self::RESOURCE_STRUCTURE, 5)
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_edit_product_by_id()
    {
        UserHelper::actAsAdmin();

        $product = ProductHelper::getRandomUser();

        $form = [
            'title' => 'Some product title updated'
        ];

        $response = $this->putJson('/api/v1/products/' . $product->id, $form);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => Arr::except(self::RESOURCE_STRUCTURE, 5)
            ])
            ->assertJsonPath('data.title', $form['title'])
            ->assertJsonPath('data.id', $product->id);
    }

    /**
     * @return void
     */
    public function test_user_with_role_guest_can_not_work_with_products_data()
    {
        UserHelper::actAsAdminWithGuestRole();

        $product = ProductHelper::getRandomUser();

        // Add
        $this->postJson('/api/v1/products')
            ->assertStatus(403);

        // Edit
        $this->putJson('/api/v1/products/' . $product->id)
            ->assertStatus(403);
    }
}
