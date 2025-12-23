<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\ReplyTicketRequest;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Services\TicketAssignmentService;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected TicketAssignmentService $assignmentService;

    public function __construct(TicketAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return Ticket::latest()->get();
        }

        if ($user->role === 'agent') {
            return $user->assignedTickets()->latest()->get();
        }

        return $user->createdTickets()->latest()->get();
    }

    public function store(StoreTicketRequest $request)
    {
        $agent = $this->assignmentService->assignAgent($request->category);
        $slaDueAt = $this->assignmentService->calculateSla();

        $ticket = Ticket::create([
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'status' => 'open',
            'customer_id' => Auth::id(),
            'customer_email' => $request->email,
            'assigned_agent_id' => $agent?->id,
            'sla_due_at' => $slaDueAt,
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_type' => Auth::check() ? 'customer' : 'guest',
            'sender_id' => Auth::id(),
            'message' => $request->description,
        ]);

        return response()->json($ticket, 201);
    }

    public function reply(ReplyTicketRequest $request, Ticket $ticket)
    {
        $this->authorize('reply', $ticket);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_type' => Auth::user()->role,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        if (!$ticket->first_response_at) {
            $ticket->update([
                'first_response_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        return response()->json(['message' => 'Reply sent']);
    }
}
