<?php

namespace Tests\Feature\V1;

use App\Constants\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\Helpers\ClientHelper;
use Tests\Helpers\OrderHelper;
use Tests\Helpers\ProductHelper;
use Tests\Helpers\UserHelper;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    const RESOURCE_STRUCTURE = [
        'id', 'client_id', 'product_id', 'price', 'status', 'paid_at', 'expired_at'
    ];

    /**
     * @return void
     */
    public function test_admin_can_see_list_of_orders()
    {
        UserHelper::actAsAdmin();

        $response = $this->getJson('/api/v1/orders/');

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
    public function test_admin_can_add_new_order()
    {
        UserHelper::actAsAdmin();

        $client = ClientHelper::getRandomClient();

        $product = ProductHelper::getRandomProduct();

        $form = [
            'client_id' => $client->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'status' => Order::STATUS_PAID,
        ];

        $response = $this->postJson('/api/v1/orders', $form);

        $response->assertStatus(201)
            ->assertJson(['data' => $form])
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_see_order_info_by_id()
    {
        Artisan::call('db:seed');

        UserHelper::actAsAdmin();

        $order = OrderHelper::getRandomOrder();

        $response = $this->getJson('/api/v1/orders/' . $order->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_edit_product_by_id()
    {
        Artisan::call('db:seed');

        UserHelper::actAsAdmin();

        $order = OrderHelper::getRandomOrder();

        $form = [
            'client_id' => $order->client_id,
            'product_id' => $order->product_id,
            'price' => 500,
            'status' => Order::STATUS_PAID,
        ];

        $response = $this->putJson('/api/v1/orders/' . $order->id, $form);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ])
            ->assertJsonPath('data.price', $form['price'])
            ->assertJsonPath('data.id', $order->id);
    }

    /**
     * @return void
     */
    public function test_admin_can_change_order_status_by_id()
    {
        Artisan::call('db:seed');

        UserHelper::actAsAdmin();

        $order = OrderHelper::getRandomOrder();

        $form = [
            'status' => Order::STATUS_CANCELED,
        ];

        $response = $this->postJson('/api/v1/orders/' . $order->id . '/change-status', $form);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ])
            ->assertJsonPath('data.status', $form['status']);
    }

    /**
     * @return void
     */
    public function test_user_with_role_guest_can_not_work_with_orders_data()
    {
        Artisan::call('db:seed');

        UserHelper::actAsAdminWithGuestRole();

        $order = OrderHelper::getRandomOrder();

        // List
        $this->getJson('/api/v1/orders/')
            ->assertStatus(403);

        // Show
        $this->getJson('/api/v1/orders/' . $order->id)
            ->assertStatus(403);

        // Add
        $this->postJson('/api/v1/orders')
            ->assertStatus(403);

        // Edit
        $this->putJson('/api/v1/orders/' . $order->id)
            ->assertStatus(403);

    }
}
