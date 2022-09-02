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

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGraphRequest  $request
     * @param  \App\Models\Graph  $graph
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGraphRequest $request, Graph $graph)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Graph  $graph
     * @return \Illuminate\Http\Response
     */
    public function destroy(Graph $graph)
    {
        //
    }
}
