<?php

namespace App\Console\Commands;

use App\Models\Graph;
use Illuminate\Console\Command;

class GraphStats extends Command
{
    /** @var string */
    protected $signature = 'graph:stats {--gid=}';

    /** @var string */
    protected $description = 'Command description';

    /**
     * @return int
     */
    public function handle()
    {
        $graph_id = $this->option('gid');

        $graph = Graph::select(['name', 'description'])->withCount('nodes', 'relations')->find($graph_id);

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
