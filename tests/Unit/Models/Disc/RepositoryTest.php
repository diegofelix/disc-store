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
            ->all()
            ->andReturn($collection);

        // Actions
        $result = $repository->list();

        // Assertions
        $this->assertInstanceOf(Collection::class, $result);
    }
}
