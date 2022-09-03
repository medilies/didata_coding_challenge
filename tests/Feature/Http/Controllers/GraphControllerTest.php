<?php

namespace Tests\Feature\Http\Controllers;

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

        $name = $this->faker->city();
        $description = $this->faker->text(120);

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

        Node::factory()
            ->count(random_int(6, 9))
            ->for($graph)
            ->hasAttached(
                Node::factory()
                    ->count(random_int(1, 3))
                    ->for($graph)
                    ->hasAttached(
                        Node::factory()
                            ->for($graph),
                        ['graph_id' => $graph->id],
                        'childNodes'
                    ),
                ['graph_id' => $graph->id],
                'childNodes'
            )
            ->create();

        $this->get(route('graphs.show', ['graph' => $graph->id]))
            ->assertOk()
            ->assertJsonStructure([
                '*' => []
            ]);
    }
}
