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
            ->has(
                Node::factory()->count(2)->for($graph),
                'childNodes'
            )
            ->for($graph)
            ->create();
    }
}
