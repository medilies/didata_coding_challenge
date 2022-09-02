<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNodeRequest;
use App\Http\Requests\UpdateNodeRequest;
use App\Models\Graph;
use App\Models\Node;
use Illuminate\Http\JsonResponse;

class NodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function store(StoreNodeRequest $request, Graph $graph): JsonResponse
    {
        $node = $graph->nodes()->create([]);

        return response()->json($node, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function show(Node $node)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function edit(Node $node)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNodeRequest  $request
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNodeRequest $request, Node $node)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function destroy(Node $node)
    {
        //
    }

    public function attach(Node $parent_node, Node $child_node)
    {
        $parent_node->childNodes()->attach($child_node->id);

        return response(status: 200);
    }
}
