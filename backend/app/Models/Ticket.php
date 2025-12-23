<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'subject',
        'description',
        'category',
        'status',
        'customer_id',
        'customer_email',
        'assigned_agent_id',
        'sla_due_at',
        'first_response_at',
    ];

    protected $casts = [
        'sla_due_at' => 'datetime',
        'first_response_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }
}
