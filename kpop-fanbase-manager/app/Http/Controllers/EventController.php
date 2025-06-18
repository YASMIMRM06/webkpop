<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator')->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            abort(403, 'Only admins and managers can create events.');
        }

        return view('events.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            abort(403, 'Only admins and managers can create events.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $validated['user_id'] = Auth::id();

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load('creator', 'participants');
        $isParticipating = Auth::check() ? $event->participants()->where('user_id', Auth::id())->exists() : false;
        return view('events.show', compact('event', 'isParticipating'));
    }

    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'You can only edit events you created.');
        }

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'You can only edit events you created.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['scheduled', 'canceled', 'completed'])],
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'You can only delete events you created.');
        }

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function participate(Request $request, Event $event)
    {
        if ($event->isFull()) {
            return back()->with('error', 'This event is already full.');
        }

        Auth::user()->participatingEvents()->syncWithoutDetaching([$event->id => ['status' => 'confirmed']]);

        return back()->with('success', 'You have successfully registered for the event.');
    }

    public function cancelParticipation(Request $request, Event $event)
    {
        Auth::user()->participatingEvents()->detach($event->id);
        return back()->with('success', 'You have canceled your participation in the event.');
    }
}