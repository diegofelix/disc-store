<?php

namespace Tests\Feature\Admin;

use App\Models\Disc\Disc;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DiscManagerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    public function testShouldGetAllDiscs(): void
    {
        // Actions
        $this->createADisc();
        $response = $this->get('api/discs');

        // Assertions
        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Number Ones',
                    'artist' => 'Michael Jackson',
                    'style' => 'pop',
                    'released_at' => '2022-01-16',
                    'stock' => 1
                ],
            ],
        ]);

        $response->assertStatus(200);
    }

    private function createADisc(): void
    {
        Disc::create([
            'name' => 'Number Ones',
            'artist' => 'Michael Jackson',
            'style' => 'pop',
            'released_at' => '2022-01-16',
            'stock' => 1
        ]);
    }
}
