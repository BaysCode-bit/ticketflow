<?php

namespace App\Services;

use App\Models\AgentCategory;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;

class TicketAssignmentService
{
    public function assignAgent(string $category): ?User
    {
        $agentIds = AgentCategory::where('category', $category)
            ->pluck('agent_id');

        if ($agentIds->isEmpty()) {
            return null;
        }

        return User::whereIn('id', $agentIds)
            ->withCount(['assignedTickets' => function ($query) {
                $query->where('status', '!=', 'closed');
            }])
            ->orderBy('assigned_tickets_count')
            ->first();
    }

    public function calculateSla(): Carbon
    {
        return now()->addHours(24);
    }
}
