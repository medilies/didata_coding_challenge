<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Graph;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NodeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function store()
    {
        $graph = Graph::factory()->create();

        $this->post(route('nodes.create', ['graph' => $graph->id]))
            ->assertCreated();

        $this->assertDatabaseCount($this->graph->getTable(), 1)
            ->assertDatabaseCount($this->node->getTable(), 1)
            ->assertDatabaseHas($this->node->getTable(), ['graph_id' => $graph->id]);
    }
}
