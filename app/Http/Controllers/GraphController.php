<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGraphRequest;
use App\Models\Graph;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GraphController extends Controller
{
    public function index(): Response
    {
        //
    }

    public function store(): JsonResponse
    {
        $graph = Graph::create([]);

        // load empty attributes ['name', 'description']
        $graph->refresh();

        // TODO use eloquent resource
        return response()->json($graph->toArray(), 201);
    }

    public function show(Graph $graph): Response
    {
        //
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
}
