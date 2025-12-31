<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'customer_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_agent_id');
    }

    public function agentCategories()
    {
        return $this->hasMany(AgentCategory::class, 'agent_id');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'sender_id');
    }
}
