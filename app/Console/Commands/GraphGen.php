<?php

namespace App\Console\Commands;

use App\Http\Services\GraphGeneratorService;
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
    public function handle(GraphGeneratorService $generator)
    {
        $nb_nodes = $this->option('nbNodes');

        $graph = $generator->run($nb_nodes);

        dump($graph->toArray());

        $this->info('The command was successful!');
    }
}
