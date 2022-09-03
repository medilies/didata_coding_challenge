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
        $graph = GraphGeneratorService::make()->run(random_int(5, 7));

        $adjacency_list = GraphService::make($graph)->getAdjacencyList();

        // * select random nodes ids to delete
        $deletable_nodes_ids = $graph->nodes->random(random_int(2, 3))->map(fn ($n) => $n->id)->toArray();

        $remaining_nodes_ids = $graph->nodes->whereNotIn('id', $deletable_nodes_ids)->map(fn ($n) => $n->id)->toArray();

        $adjacency_list = array_filter($adjacency_list, fn ($children, $key) => !in_array($key, $deletable_nodes_ids), ARRAY_FILTER_USE_BOTH);

        foreach ($adjacency_list as $node => $child_nodes) {
            $potential_child = $remaining_nodes_ids[array_rand($remaining_nodes_ids)];

            // Delete random relations
            $adjacency_list[$node] = array_filter($adjacency_list[$node], fn ($children) => random_int(1, 5) !== 3);

            if (!in_array($potential_child, $child_nodes)) {
                // Push one new child
                $adjacency_list[$node][] = $potential_child;
            }
        }

        $this->post(route('graph.shape', ['graph' => $graph->id]), compact('deletable_nodes_ids', 'adjacency_list'))
            ->assertOk();

        $this->assertEquals(Node::whereIn('id', $deletable_nodes_ids)->count(), 0);
    }
}
