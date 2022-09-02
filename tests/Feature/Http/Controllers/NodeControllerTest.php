<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Graph;
use App\Models\Node;
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

    /** @test */
    public function attach()
    {
        $graph = Graph::factory()->create();

        $nodes = Node::factory()->count(10)->for($graph)->create();

        [$parent_node, $child_node] = $nodes->random(2);

        $this->post(route('nodes.attach', ['parent_node' => $parent_node->id, 'child_node' => $child_node->id]))
            ->assertOk();

        $this->assertEquals($parent_node->childNodes->first()->id, $child_node->id);
        $this->assertEquals($child_node->parentNodes->first()->id, $parent_node->id);
    }

    /** @test */
    public function destroy()
    {
        $graph = Graph::factory()->create();

        $nodes = Node::factory()->count(10)->for($graph)->create();

        $node = $nodes->random()->toArray();

        $this->delete(route('nodes.destroy', ['node' => $node['id']]))
            ->assertOk();

        $this->assertDatabaseCount($this->node->getTable(), 9);
        $this->assertDatabaseMissing($this->node->getTable(), $node);
    }
}
