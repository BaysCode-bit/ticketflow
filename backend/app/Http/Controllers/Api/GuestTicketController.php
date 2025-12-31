<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestTicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'subject'  => 'required|string|max:255',
            'category' => 'required|string',
            'message'  => 'required|string|min:10',
        ]);

        return DB::transaction(function () use ($validated) {

            $ticket = Ticket::create([
                'subject'        => $validated['subject'],
                'description'    => $validated['message'],
                'category'       => $validated['category'],
                'status'         => 'open',
                'customer_id'    => null,
                'customer_email' => $validated['email'],
            ]);

            TicketMessage::create([
                'ticket_id'  => $ticket->id,
                'sender_id'  => null,
                'sender_type'=> 'customer',
                'message'    => $validated['message'],
            ]);

            return response()->json([
                'ticket_id' => $ticket->id,
                'message'   => 'Your ticket has been submitted successfully.',
            ], 201);
        });
    }
}
