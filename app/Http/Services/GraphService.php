<?php

namespace App\Http\Services;

use App\Models\Graph;

class GraphService
{
    private array $adjacencyList = [];

    private array $stack = [];

    public function __construct(
        private Graph $graphModel
    ) {

        $this->setAdjacencyList();
    }

    public static function make($graphModel)
    {
        return new static($graphModel);
    }

    public function setAdjacencyList(): static
    {
        foreach ($this->graphModel->nodes as $node) {
            $current_id = (string)$node->id;

            $this->adjacencyList[$current_id] = [];

            foreach ($node->childNodes as $node) {
                $this->adjacencyList[$current_id][] = $node->id;
            }
        }

        return $this;
    }

    public function getAdjacencyList(): array
    {
        return $this->adjacencyList;
    }
}
