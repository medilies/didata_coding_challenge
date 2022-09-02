<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Graph;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GraphControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function store()
    {
        $this->post(route('graphs.store', []))
            ->assertCreated()
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['id', 'name', 'description', 'created_at', 'updated_at'])
            );

        $this->assertDatabaseCount((new Graph([]))->getTable(), 1);
    }
}
