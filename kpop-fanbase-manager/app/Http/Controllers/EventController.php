<?php

namespace App\Http\Controllers;

use App\Models\Event; //
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //
use Illuminate\Validation\Rule; //

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator')->paginate(10); //
        return view('events.index', compact('events')); //
    }

    public function create()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) { //
            abort(403, 'Apenas administradores e gerentes podem criar eventos.'); //
        }
        return view('events.create'); //
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) { //
            abort(403, 'Apenas administradores e gerentes podem criar eventos.'); //
        }

        $validated = $request->validate([ //
            'name' => 'required|string|max:255', //
            'description' => 'required|string', //
            'event_date' => 'required|date', //
            'location' => 'required|string|max:255', //
            'capacity' => 'required|integer|min:1', //
        ]);

        $validated['user_id'] = Auth::id(); //
        Event::create($validated); //
        return redirect()->route('events.index')->with('success', 'Evento criado com sucesso.'); //
    }

    public function show(Event $event)
    {
        $event->load('creator', 'participants'); //
        $isParticipating = Auth::check() ? $event->participants()->where('user_id', Auth::id())->exists() : false; //
        return view('events.show', compact('event', 'isParticipating')); //
    }

    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) { //
            abort(403, 'Você só pode editar eventos que criou.'); //
        }
        return view('events.edit', compact('event')); //
    }

    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) { //
            abort(403, 'Você só pode editar eventos que criou.'); //
        }

        $validated = $request->validate([ //
            'name' => 'required|string|max:255', //
            'description' => 'required|string', //
            'event_date' => 'required|date', //
            'location' => 'required|string|max:255', //
            'capacity' => 'required|integer|min:1', //
            'status' => ['required', Rule::in(['scheduled', 'canceled', 'completed'])], //
        ]);

        $event->update($validated); //
        return redirect()->route('events.show', $event)->with('success', 'Evento atualizado com sucesso.'); //
    }

    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) { //
            abort(403, 'Você só pode deletar eventos que criou.'); //
        }
        $event->delete(); //
        return redirect()->route('events.index')->with('success', 'Evento deletado com sucesso.'); //
    }

    public function participate(Request $request, Event $event)
    {
        if ($event->isFull()) { //
            return back()->with('error', 'Este evento já está lotado.'); //
        }

        Auth::user()->participatingEvents()->syncWithoutDetaching([$event->id => ['status' => 'confirmed']]); //
        return back()->with('success', 'Você se registrou para o evento com sucesso.'); //
    }

    public function cancelParticipation(Request $request, Event $event)
    {
        Auth::user()->participatingEvents()->detach($event->id); //
        return back()->with('success', 'Você cancelou sua participação no evento.'); //
    }
}