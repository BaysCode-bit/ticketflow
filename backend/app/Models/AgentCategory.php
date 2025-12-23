<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentCategory extends Model
{
    protected $fillable = [
        'agent_id',
        'category',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
