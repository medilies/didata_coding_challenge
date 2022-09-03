<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GraphGenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creates_graph_with_nodes()
    {
        $this->artisan('graph:gen --nbNodes=2')
            ->assertExitCode(0);

        $this->assertDatabaseCount($this->graph->getTable(), 1);
        $this->assertDatabaseCount($this->node->getTable(), 2);
    }
}
