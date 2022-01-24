<?php

namespace Tests\Feature;

use App\Models\Disc\Disc;
use App\Models\Order\Order;
use App\Models\User\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldCreateANewOrder(): void
    {
        // Actions
        $this->createCustomers();
        $this->createDiscs();
        $this->createOrders();
        $response = $this->postJson('api/orders', [
            'customer_id' => 1,
            'disc_id' => 1,
            'quantity' => 10,
        ]);

        // Assertions
        $response->assertJson([
            'id' => 4,
            'status' => 'processing',
            'customer' => [
                'id' => 1,
                'name' => 'Diego Felix',
                'email' => 'diego@diegofelix.com.br',
                'fiscal_id' => '11.111.111-1',
                'birthdate' => '1987-03-22',
                'phone' => '+55 11 96293 7145',
            ],
            'disc' => [
                'id' => 1,
                'name' => 'Number Ones',
                'artist' => 'Michael Jackson',
                'style' => 'pop',
                'released_at' => '2022-01-16',
                'stock' => 100,
            ],
            'quantity' => 10,
            'created_at' => (new DateTime())->format('Y-m-d H:i'),
        ]);

        $response->assertStatus(200);
    }

    public function testShouldNotCreateOrderForInvalidStock(): void
    {
        // Actions
        $this->createCustomers();
        $this->createDiscs();
        $this->createOrders();
        $response = $this->postJson('api/orders', [
            'customer_id' => 1,
            'disc_id' => 1,
            'quantity' => 101,
        ]);

        // Assertions
        $response->assertJson([
            'error' => 'There is no stock for the disc selected.',
        ]);

        $response->assertStatus(422);
    }

    public function testShouldNotCreateAnOrderForAnInvalidUser(): void
    {
        // Actions
        $this->createCustomers();
        $this->createDiscs();
        $this->createOrders();
        $response = $this->postJson('api/orders', [
            'customer_id' => 3,
            'disc_id' => 1,
            'quantity' => 10,
        ]);

        // Assertions
        $response->assertJson([
            'error' => 'Customer does not exist',
        ]);

        $response->assertStatus(422);
    }

    public function testShouldListOrders(): void
    {
        // Actions
        $this->createCustomers();
        $this->createDiscs();
        $this->createOrders();

        $response = $this->getJson('api/orders');

        // Assertions
        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'status' => 'processing',
                    'disc_id' => 1,
                    'customer_id' => 2,
                    'quantity' => 10,
                    'created_at' => (new DateTime('2022-01-01'))->format('Y-m-d H:i'),
                ],
                [
                    'id' => 2,
                    'status' => 'success',
                    'disc_id' => 1,
                    'customer_id' => 2,
                    'quantity' => 10,
                    'created_at' => (new DateTime('2022-01-10'))->format('Y-m-d H:i'),
                ],
                [
                    'id' => 3,
                    'status' => 'canceled',
                    'disc_id' => 2,
                    'customer_id' => 1,
                    'quantity' => 10,
                    'created_at' => (new DateTime())->format('Y-m-d H:i'),
                ],
            ],
        ]);

        $response->assertStatus(200);
    }

    /** @dataProvider getFilters */
    public function testShouldGetOrdersFiltering(array $filter, int $expectedCount, int $expectedId): void
    {
        // Actions
        $this->createCustomers();
        $this->createDiscs();
        $this->createOrders();

        $queryString = http_build_query($filter);
        $response = $this->getJson("api/orders?$queryString");

        // Assertions
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertCount($expectedCount, $jsonData);
        $this->assertSame($expectedId, $jsonData[0]['id']);
    }

    private function createOrders(): void
    {
        $anotherOrder = new Order();
        $anotherOrder->fill([
            'status' => Order::STATUS_PROCESSING,
            'customer_id' => 2,
            'disc_id' => 1,
            'quantity' => 10,
        ]);
        $anotherOrder->setCreatedAt(new DateTime('2022-01-01'));
        $anotherOrder->save();

        $anotherOrder = new Order();
        $anotherOrder->fill([
            'status' => Order::STATUS_SUCCESS,
            'customer_id' => 2,
            'disc_id' => 1,
            'quantity' => 10,
        ]);
        $anotherOrder->setCreatedAt(new DateTime('2022-01-10'));
        $anotherOrder->save();

        Order::create([
            'status' => Order::STATUS_CANCELED,
            'customer_id' => 1,
            'disc_id' => 2,
            'quantity' => 10,
        ]);
    }

    private function createDiscs(): void
    {
        Disc::create([
            'name' => 'Number Ones',
            'artist' => 'Michael Jackson',
            'style' => 'pop',
            'released_at' => '2022-01-16',
            'stock' => 100,
        ]);

        Disc::create([
            'name' => 'I am sasha fierce',
            'artist' => 'Beyoncé',
            'style' => 'pop',
            'released_at' => '2022-01-16',
            'stock' => 100,
        ]);
    }

    private function createCustomers(): void
    {
        User::create([
            'name' => 'Diego Felix',
            'email' => 'diego@diegofelix.com.br',
            'fiscal_id' => '11.111.111-1',
            'birthdate' => '1987-03-22',
            'password' => 'some pass',
            'phone' => '+55 11 96293 7145',
        ]);

        User::create([
            'name' => 'David',
            'email' => 'david@diegofelix.com.br',
            'fiscal_id' => '11.111.111-2',
            'birthdate' => '1987-03-23',
            'password' => 'some pass',
            'phone' => '+55 11 96293 7146',
        ]);

        $invalidUser = User::create([
            'name' => 'Invalid User',
            'email' => 'invalid@diegofelix.com.br',
            'fiscal_id' => '11.111.111-3',
            'birthdate' => '1987-03-24',
            'password' => 'some pass',
            'phone' => '+55 11 96293 7146',
        ]);
        $invalidUser->delete();
    }

    public function getFilters(): array
    {
        return [
            'filter by customer_id' => [
                'input' => ['filter' => ['customer_id' => 1]],
                'expectedCount' => 1,
                'expectedId' => 3,
            ],
            'filter from a date' => [
                'input' => ['filter' => ['from' => '2022-01-02']],
                'expectedCount' => 2,
                'expectedId' => 2,
            ],
            'filter until a date' => [
                'input' => ['filter' => ['until' => '2022-01-02']],
                'expectedCount' => 1,
                'expectedId' => 1,
            ],
            'filter between a date' => [
                'input' => [
                    'filter' => [
                        'from' => '2022-01-02',
                        'until' => '2022-01-11',
                    ],
                ],
                'expectedCount' => 1,
                'expectedId' => 2,
            ],
        ];
    }
}
