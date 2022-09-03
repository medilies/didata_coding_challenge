<?php

namespace App\Http\Services;

use App\Models\Graph;
use App\Models\Node;

class GraphGeneratorService
{
    public static function make(): static
    {
        return new static();
    }

    public function run(int $nb_nodes): Graph
    {
        $graph = Graph::factory()->create();

        $nodes = Node::factory()->count($nb_nodes)->for($graph)->create();

        foreach ($nodes as $key => $node) {
            foreach ($nodes->except($key)->random(random_int(0, $nb_nodes - 1)) as $child_node) {
                $node->childNodes()->attach($child_node->id, ['graph_id' => $graph->id]);
            }
        }

        // $nodes->load('childNodes');

        $graph->setRelation('nodes', $nodes);
        $graph->load('relations');

        return $graph;
    }
}