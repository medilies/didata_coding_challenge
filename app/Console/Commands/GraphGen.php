<?php

namespace App\Console\Commands;

use App\Http\Services\GraphGeneratorService;
use Illuminate\Console\Command;

class GraphGen extends Command
{
    /** @var string */
    protected $signature = 'graph:gen {--nbNodes=}';

    /** @var string */
    protected $description = 'Create a random graph with nodes and random relations';

    /**
     * @return int
     */
    public function handle(GraphGeneratorService $generator)
    {
        $nb_nodes = $this->option('nbNodes');

        $graph = $generator->run($nb_nodes);

        dump($graph->toArray());

        $this->info('The command was successful!');

        $this->line("Graph id: {$graph->id}");

        return 0;
    }
}
