<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Node extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function graph(): BelongsTo
    {
        return $this->belongsTo(Graph::class);
    }

    public function parentNodes(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'node_node', 'child_node_id', 'parent_node_id');
    }

    public function childNodes(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'node_node', 'parent_node_id', 'child_node_id');
    }
}
