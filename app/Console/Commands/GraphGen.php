<?php

namespace App\Console\Commands;

use App\Models\Graph;
use App\Models\Node;
use Illuminate\Console\Command;

class GraphGen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graph:gen {--nbNodes=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a random graph with nodes and random relations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nb_nodes = $this->option('nbNodes');

        $graph = Graph::factory()->create();

        $nodes = Node::factory()->count($nb_nodes)->for($graph)->create();

        foreach ($nodes as $key => $node) {
            foreach ($nodes->except($key)->random(random_int(0, $nb_nodes - 1)) as $child_node) {
                $node->childNodes()->attach($child_node->id, ['graph_id' => $graph->id]);
            }
        }

        $this->info('The command was successful!');
    }
}
