<?php

namespace App\Console\Commands;

use App\Models\Graph;
use Illuminate\Console\Command;

class GraphClear extends Command
{
    /** @var string */
    protected $signature = 'graph:clear';

    /** @var string */
    protected $description = 'delete all empty graphs';

    /**
     * @return int
     */
    public function handle()
    {
        Graph::doesntHave('nodes')->delete();

        $this->info('The command was successful!');

        return 0;
    }
}
