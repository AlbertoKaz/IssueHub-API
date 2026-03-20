<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::query()
            ->with('user')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'status' => 'open',
            'priority' => $request->validated('priority') ?? 'medium',
        ]);

        $ticket->load('user');

        return (new TicketResource($ticket))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $ticket->load('user');

        return new TicketResource($ticket);
    }

    public function update(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'min:10'],
            'status' => ['sometimes', 'in:open,in_progress,closed'],
            'priority' => ['sometimes', 'in:low,medium,high'],
        ]);

        $ticket->update($validated);
        $ticket->load('user');

        return new TicketResource($ticket);
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $ticket->delete();

        return response()->json([
            'message' => 'Ticket deleted successfully.',
        ]);
    }
}
