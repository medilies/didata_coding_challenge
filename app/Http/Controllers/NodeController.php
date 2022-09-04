<?php

namespace App\Http\Controllers;

use App\Models\Graph;
use App\Models\Node;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NodeController extends Controller
{
    public function store(Graph $graph): JsonResponse
    {
        $node = $graph->nodes()->create();

        return response()->json($node, 201);
    }

    public function destroy(Node $node): Response
    {
        $node->parentNodes()->detach();
        $node->childNodes()->detach();
        $node->delete();

        return response(status: 200);
    }

    public function attach(Node $parent_node, Node $child_node): Response
    {
        $parent_node->childNodes()->attach($child_node->id);

        return response(status: 200);
    }
}
