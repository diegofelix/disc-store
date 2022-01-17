<?php

namespace Models\Disc;

use App\Models\Disc\Disc;
use App\Models\Disc\Presenter;
use DateTime;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PresenterTest extends TestCase
{
    public function testPresent(): void
    {
        // Set
        $presenter = new Presenter();
        $data = [
            'name' => 'Number Ones',
            'artist' => 'Michael Jackson',
            'style' => 'pop',
            'released_at' => new DateTime('2022-01-01 00:00:00'),
            'stock' => 10,
        ];
        $disc = new Disc();
        $disc->id = 1;
        $disc->fill($data);
        $collection = new Collection([$disc]);
        $expected = [
            [
                'id' => 1,
                'name' => 'Number Ones',
                'artist' => 'Michael Jackson',
                'style' => 'pop',
                'released_at' => '2022-01-01',
                'stock' => 10,
            ],
        ];

        // Actions
        $result = $presenter->present($collection);

        // Assertions
        $this->assertSame($expected, $result);
    }

    public function testShouldPresentSingleDisc(): void
    {
        // Set
        $presenter = new Presenter();
        $data = [
            'name' => 'Number Ones',
            'artist' => 'Michael Jackson',
            'style' => 'pop',
            'released_at' => new DateTime('2022-01-01 00:00:00'),
            'stock' => 10,
        ];
        $disc = new Disc();
        $disc->id = 1;
        $disc->fill($data);
        $expected = [
            'id' => 1,
            'name' => 'Number Ones',
            'artist' => 'Michael Jackson',
            'style' => 'pop',
            'released_at' => '2022-01-01',
            'stock' => 10,
        ];

        // Actions
        $result = $presenter->presentSingleDisc($disc);

        // Assertions
        $this->assertSame($expected, $result);
    }
}
