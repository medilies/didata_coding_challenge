<?php

namespace Tests\Feature\Console\Commands;

use App\Http\Services\GraphGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GraphStatsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_example()
    {
        $graph = GraphGeneratorService::make()->run(7);

        $graph->loadCount('nodes', 'relations');

        $this->artisan("graph:stats --gid={$graph->id}")
            ->expectsTable(
                ['Name', 'Description', 'Number of nodes', 'Number of relations'],
                [
                    [
                        $graph->name,
                        $graph->description,
                        $graph->nodes_count,
                        $graph->relations_count,
                    ],
                ]
            )
            ->assertExitCode(0);
    }
}
