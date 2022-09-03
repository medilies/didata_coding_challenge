<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_node', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parent_node_id');
            $table->unsignedBigInteger('child_node_id');
            $table->unsignedBigInteger('graph_id');

            $table->foreign('parent_node_id')->references('id')->on('nodes')->cascadeOnDelete();
            $table->foreign('child_node_id')->references('id')->on('nodes')->cascadeOnDelete();
            $table->foreign('graph_id')->references('id')->on('graphs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_node');
    }
};
