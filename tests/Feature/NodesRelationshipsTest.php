<?php

namespace Tests\Feature;

use App\Models\Graph;
use App\Models\Node;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NodesRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nodes_test()
    {
        $graph = Graph::factory()->create();

        $node = $graph->nodes()->create();

        $child_node = $node->childNodes()->create(['graph_id' => $graph->id], ['graph_id' => $graph->id]);

        $this->assertDatabaseCount('nodes', 2);

        $this->assertEquals($node->id, $child_node->id - 1);

        $this->assertEquals($node->childNodes->first()->id, $child_node->id);

        $this->assertEquals($child_node->ParentNodes->first()->id, $node->id);
    }

    /** @test */
    public function child_nodes_factory_test()
    {
        $graph = Graph::factory()->create();

        $node = Node::factory()
            ->for($graph)
            ->hasAttached(
                Node::factory()->for($graph),
                ['graph_id' => $graph->id],
                'childNodes'
            )
            ->create();

        $this->assertDatabaseCount('nodes', 2);

        $this->assertEquals($node->id, $node->childNodes()->first()->id - 1);
    }
}
