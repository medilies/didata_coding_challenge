<?php

namespace App\Console\Commands;

use App\Models\Graph;
use Illuminate\Console\Command;

class GraphStats extends Command
{
    /** @var string */
    protected $signature = 'graph:stats {--gid=}';

    /** @var string */
    protected $description = 'Display a graph stats';

    /**
     * @return int
     */
    public function handle()
    {
        $graph_id = $this->option('gid');

        $graph = Graph::select(['name', 'description'])->withCount('nodes', 'relations')->find($graph_id);

        if (is_null($graph)) {
            $this->error("Failed to find a graph with id={$graph_id}");

            return 0;
        }

        $this->table(
            ['Name', 'Description', 'Number of nodes', 'Number of relations'],
            [
                [
                    $graph->name,
                    $graph->description,
                    $graph->nodes_count,
                    $graph->relations_count,
                ],
            ]
        );

        return 0;
    }
}
