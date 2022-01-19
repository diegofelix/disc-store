<?php

namespace Models\Disc;

use App\Models\Disc\Disc;
use App\Models\Disc\Repository;
use Mockery as m;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    public function testShouldGetAllDiscs(): void
    {
        // Set
        $repository = new Repository();
        $collection = new Collection([new Disc()]);
        $disc = $this->instance(Disc::class, m::mock(Disc::class));

        // Expectations
        $disc->expects()
            ->get()
            ->andReturn($collection);

        // Actions
        $result = $repository->list();

        // Assertions
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testShouldGetAllDiscsFilteringIt(): void
    {
        // Set
        $repository = new Repository();
        $collection = new Collection([new Disc()]);
        $disc = $this->instance(Disc::class, m::mock(Disc::class));

        // Expectations
        $disc->expects()
            ->where('some', '=', 'filter')
            ->andReturnSelf();

        $disc->expects()
            ->get()
            ->andReturn($collection);

        // Actions
        $result = $repository->list(['some' => 'filter']);

        // Assertions
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testShouldFilterReleaseDate(): void
    {
        // Set
        $repository = new Repository();
        $collection = new Collection([new Disc()]);
        $disc = $this->instance(Disc::class, m::mock(Disc::class));

        // Expectations
        $disc->expects()
            ->where('released_at', '>=', '2020-01-01')
            ->andReturnSelf();

        $disc->expects()
            ->get()
            ->andReturn($collection);

        // Actions
        $result = $repository->list(['released_at' => '2020-01-01']);

        // Assertions
        $this->assertInstanceOf(Collection::class, $result);
    }
}
