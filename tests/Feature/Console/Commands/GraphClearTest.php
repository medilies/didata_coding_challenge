<?php

namespace Tests\Feature\Console\Commands;

use App\Http\Services\GraphGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GraphClearTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deletes_two_empty_graph_out_of_three()
    {
        GraphGeneratorService::make()->run(0);
        GraphGeneratorService::make()->run(1);
        GraphGeneratorService::make()->run(0);

        $this->assertDatabaseCount($this->graph->getTable(), 3);

        $this->artisan('graph:clear')
            ->assertExitCode(0);

        $this->assertDatabaseCount($this->graph->getTable(), 1);
    }
}
