<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Services\GraphGeneratorService;
use App\Http\Services\GraphService;
use App\Models\Graph;
use App\Models\Node;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GraphControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function store()
    {
        $this->post(route('graphs.store'), [])
            ->assertCreated()
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['id', 'name', 'description', 'created_at', 'updated_at'])
            );

        $this->assertDatabaseCount($this->graph->getTable(), 1);
    }

    /** @test */
    public function update()
    {
        $graph = Graph::factory(['name' => null, 'description' => null])->create();

        $name = $this->faker->unique()->city();
        $description = $this->faker->text(60);

        $this->patch(
            route('graphs.update', ['graph' => $graph->id]),
            compact('name', 'description')
        )
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['id', 'name', 'description', 'created_at', 'updated_at'])
            );

        $this->assertDatabaseCount($this->graph->getTable(), 1)
            ->assertDatabaseHas($this->graph->getTable(), compact('name', 'description') + ['id' => $graph->id]);
    }

    /** @test */
    public function destroy()
    {
        $graph = Graph::factory()->create();

        $this->delete(route('graphs.destroy', ['graph' => $graph->id]))
            ->assertOk();

        $this->assertDatabaseCount($this->graph->getTable(), 0);
    }

    /** @test */
    public function index()
    {
        Graph::factory()->count(10)->create();

        $this->get(route('graphs.index'))
            ->assertOk()
            ->assertJsonCount(10)
            ->assertJsonStructure(['*' => ['name', 'description']]);
    }

    /** @test */
    public function show()
    {
        $graph = Graph::factory()->create();

        GraphGeneratorService::make()->run(random_int(5, 7));

        $this->get(route('graphs.show', ['graph' => $graph->id]))
            ->assertOk()
            ->assertJsonStructure([
                '*' => [],
            ]);
    }

    /** @test */
    public function shape()
    {
        $nodes_count = random_int(5, 9);

        $deletable_nodes_count = random_int(2, 4);

        $graph = GraphGeneratorService::make()->run($nodes_count);

        $temp_graph = GraphGeneratorService::make()->run($nodes_count - $deletable_nodes_count);

        $adjacency_list = GraphService::make($graph)->getAdjacencyList();

        $temp_adjacency_list = GraphService::make($temp_graph)->getAdjacencyList();

        $temp_graph->delete();

        // * select random nodes ids to delete
        $deletable_nodes_ids = $graph->nodes->random($deletable_nodes_count)->map(fn ($n) => $n->id)->toArray();

        $new_adjacency_list = [];

        // dump('======Original:', $adjacency_list, '=========');
        // dump('------update source:', $temp_adjacency_list, '---------');
        dump('------DELETE:', $deletable_nodes_ids, '---------');

        foreach ($adjacency_list as $node => $x) {
            if (in_array($node, $deletable_nodes_ids)) {
                continue;
            }

            $new_adjacency_list[(string) $node] = [];

            foreach (array_pop($temp_adjacency_list) as $child) {
                $child -= $nodes_count;

                if (in_array($child, $deletable_nodes_ids)) {
                    continue;
                }
                $new_adjacency_list[(string) $node][] = $child;
            }
        }

        dump('------updated:', $new_adjacency_list, '---------');

        $this->put(route('graph.shape', ['graph' => $graph->id]), compact('deletable_nodes_ids') + ['adjacency_list' => $new_adjacency_list])
            ->assertOk();

        $this->assertEquals(Node::whereIn('id', $deletable_nodes_ids)->count(), 0);

        foreach ($new_adjacency_list as $node => $children) {
            foreach ($children as $child) {
                $this->assertDatabaseHas('node_node', ['parent_node_id' => $node, 'child_node_id' => $child]);
            }
        }
    }
}
