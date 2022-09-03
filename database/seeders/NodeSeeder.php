<?php

namespace Database\Seeders;

use App\Models\Graph;
use App\Models\Node;
use Illuminate\Database\Seeder;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $graph = Graph::find(1);

        Node::factory()
            ->count(3)
            ->hasAttached(
                Node::factory()->count(2)->for($graph),
                ['graph_id' => $graph->id],
                'childNodes'
            )
            ->for($graph)
            ->create();
    }
}
