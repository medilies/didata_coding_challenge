<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'node_node';

    protected $guarded = [];

    public function graph(): BelongsTo
    {
        return $this->belongsTo(Graph::class);
    }

    public function parentNodes(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_node_id');
    }

    public function childNodes(): BelongsTo
    {
        return $this->belongsTo(static::class, 'child_node_id');
    }
}
