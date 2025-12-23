<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Models\Rating;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(StoreRatingRequest $request, Ticket $ticket)
    {
        $this->authorize('create', [\App\Policies\RatingPolicy::class, $ticket]);

        $rating = Rating::create([
            'ticket_id' => $ticket->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($rating, 201);
    }
}
