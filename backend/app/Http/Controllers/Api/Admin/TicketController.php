<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function index(): JsonResponse
    {
        $tickets = Ticket::query()
            ->with([
                'category:id,name',
                'assignedAgent:id,name,email'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tickets);
    }
}
