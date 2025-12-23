<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class RatingPolicy
{
    public function create(User $user, Ticket $ticket): bool
    {
        return $user->role === 'customer'
            && $ticket->customer_id === $user->id
            && $ticket->status === 'closed';
    }
}
