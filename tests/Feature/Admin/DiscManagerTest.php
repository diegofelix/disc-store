<?php

namespace Tests\Feature\Admin;

use App\Models\Disc\Disc;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DiscManagerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldGetAllDiscs(): void
    {
        // Actions
        $this->createDiscs();
        $response = $this->getJson('api/discs');

        // Assertions
        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Number Ones',
                    'artist' => 'Michael Jackson',
                    'style' => 'pop',
                    'released_at' => '2022-01-16',
                    'stock' => 1,
                ],
                [
                    'id' => 2,
                    'name' => 'Prazer, Ferrugem',
                    'artist' => 'Ferrugem',
                    'style' => 'pagode',
                    'released_at' => '2021-01-16',
                    'stock' => 100,
                ],
            ],
        ]);

        $response->assertStatus(200);
    }

    /** @dataProvider getFilters */
    public function testShouldGetDiscsFiltering(array $filter, int $expectedCount, int $expectedId): void
    {
        // Actions
        $this->createDiscs();
        $queryString = http_build_query($filter);
        $response = $this->getJson("api/discs?$queryString");

        // Assertions
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertCount($expectedCount, $jsonData);
        $this->assertSame($expectedId, $jsonData[0]['id']);
    }

    private function createDiscs(): void
    {
        Disc::create([
            'name' => 'Number Ones',
            'artist' => 'Michael Jackson',
            'style' => 'pop',
            'released_at' => '2022-01-16',
            'stock' => 1,
        ]);

        Disc::create([
            'name' => 'Prazer, Ferrugem',
            'artist' => 'Ferrugem',
            'style' => 'pagode',
            'released_at' => '2022-01-20',
            'stock' => 100,
        ]);
    }

    public function getFilters(): array
    {
        return [
            'filter by style' => [
                'input' => ['filter' => ['style' => 'pop']],
                'expectedCount' => 1,
                'expectedId' => 1,
            ],
            'filter by artist' => [
                'input' => ['filter' => ['artist' => 'Ferrugem']],
                'expectedCount' => 1,
                'expectedId' => 2,
            ],
            'filter by name' => [
                'input' => ['filter' => ['name' => 'Prazer, Ferrugem']],
                'expectedCount' => 1,
                'expectedId' => 2,
            ],
            'filter by release date' => [
                'input' => ['filter' => ['released_at' => '2022-01-16']],
                'expectedCount' => 2,
                'expectedId' => 1,
            ],
            'filter by a greater release date' => [
                'input' => ['filter' => ['released_at' => '2022-01-17']],
                'expectedCount' => 1,
                'expectedId' => 2,
            ],
            'filter by release date and name' => [
                'input' => ['filter' => ['released_at' => '2022-01-17', 'name' => 'Prazer, Ferrugem']],
                'expectedCount' => 1,
                'expectedId' => 2,
            ],
            'invalid filter should return all data' => [
                'input' => ['filter' => ['diego' => 'felix']],
                'expectedCount' => 2,
                'expectedId' => 1,
            ],
        ];
    }
}
