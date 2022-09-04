<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Graph extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }

    public function relations(): HasManyThrough
    {
        return $this->hasManyThrough(Relation::class, Node::class, 'graph_id', 'parent_node_id');
    }
}
