<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function before(User $user): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'agent') {
            return $ticket->assigned_agent_id === $user->id;
        }

        if ($user->role === 'customer') {
            return $ticket->customer_id === $user->id;
        }

        return false;
    }

    public function reply(User $user, Ticket $ticket): bool
    {
        return $user->role === 'agent'
            && $ticket->assigned_agent_id === $user->id
            && $ticket->status !== 'closed';
    }

    public function reassign(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function close(User $user): bool
    {
        return $user->role === 'admin';
    }
}
