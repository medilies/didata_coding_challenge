<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNodeRequest;
use App\Http\Requests\UpdateNodeRequest;
use App\Models\Node;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNodeRequest $request)
    {
        //
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
}
