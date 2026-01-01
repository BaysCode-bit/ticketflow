<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketManagementController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with([
                'category',
                'assignedAgent:id,name,email'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tickets);
    }
    
    public function reassign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'agent_id' => ['required', 'exists:users,id'],
        ]);

        $ticket->update([
            'assigned_agent_id' => $validated['agent_id'],
            'status' => 'in_progress',
        ]);

        return response()->json([
            'message' => 'Ticket reassigned successfully',
            'ticket' => $ticket,
        ]);
    }

    public function close(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'closed',
        ]);

        return response()->json([
            'message' => 'Ticket closed',
        ]);
    }
}
