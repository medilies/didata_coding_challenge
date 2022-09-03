<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShapeGraphRequest;
use App\Http\Requests\UpdateGraphRequest;
use App\Http\Services\GraphService;
use App\Models\Graph;
use App\Models\Node;
use App\Models\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    public function index(): JsonResponse
    {
        $graphs = Graph::select(['name', 'description'])->get();

        return response()->json($graphs->toArray());
    }

    public function store(): JsonResponse
    {
        $graph = Graph::create([]);

        // load empty attributes ['name', 'description']
        $graph->refresh();

        // TODO use eloquent resource
        return response()->json($graph->toArray(), 201);
    }

    public function show(Graph $graph): JsonResponse
    {
        $graph->load([
            'nodes' => [
                'childNodes',
            ],
        ]);

        return response()->json(GraphService::make($graph)->getAdjacencyList());
    }

    public function update(UpdateGraphRequest $request, Graph $graph): JsonResponse
    {
        $graph->update($request->safe(['name', 'description']));

        return response()->json($graph->toArray());
    }

    public function destroy(Graph $graph): Response
    {
        $graph->delete();

        return response(status: 200);
    }

    /**
     * Url: api/v1/gaphs/{graph}/shape
     * HTTP method: PUT
     * example: [
     *   "deletable_nodes_ids" => [2,3,4]
     *     "adjacency_list" =>
     *       1 => [5, (4)] // node 4 will be deleted
     *       5 => [5]
     *     ]
     *   ]
     */
    public function shape(ShapeGraphRequest $request, Graph $graph): Response
    {
        $graph->load('relations');

        $delete_query = Relation::query();
        $new_relations = [];

        foreach ($request->adjacency_list as $node => $children) {
            foreach ($children as $child) {
                // Relation exists in request but not in DB => Insert
                if (! $graph->relations->where('parent_node_id', $node)->where('child_node_id', $child)->count()) {
                    $new_relations[] = [
                        'parent_node_id' => $node,
                        'child_node_id' => $child,
                        'graph_id' => $graph->id,
                    ];
                }
            }
        }

        foreach ($graph->relations as $relations) {
            // Relation exists in DB but not in request => Delete
            if (empty($request->adjacency_list[$relations->parent_node_id][$relations->child_node_id])) {
                $delete_query->orWhere(function ($query) use ($relations) {
                    $query->Where('parent_node_id', $relations->parent_node_id)
                        ->where('child_node_id', $relations->child_node_id);
                });
            }
        }

        DB::transaction(function () use ($request, $delete_query, $new_relations) {
            Node::whereIn('id', $request->deletable_nodes_ids)->delete();

            DB::table('node_node')->insert($new_relations);

            $delete_query->delete();
        });

        return response(status: 200);
    }
}
