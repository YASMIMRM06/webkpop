<?php

namespace App\Http\Controllers;

use App\Models\Group; //
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::paginate(10); //
        return view('groups.index', compact('groups')); //
    }

    public function create()
    {
        return view('groups.create'); //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([ //
            'name' => 'required|string|max:255', //
            'formation_date' => 'required|date', //
            'company' => 'required|string|max:255', //
            'description' => 'nullable|string', //
            'photo' => 'nullable|image|max:2048', //
        ]);

        if ($request->hasFile('photo')) { //
            $validated['photo'] = $request->file('photo')->store('group_photos', 'public'); //
        }

        Group::create($validated); //
        return redirect()->route('groups.index')->with('success', 'Grupo criado com sucesso.'); //
    }

    public function show(Group $group)
    {
        $group->load('musics'); //
        return view('groups.show', compact('group')); //
    }

    public function edit(Group $group)
    {
        return view('groups.edit', compact('group')); //
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([ //
            'name' => 'required|string|max:255', //
            'formation_date' => 'required|date', //
            'company' => 'required|string|max:255', //
            'description' => 'nullable|string', //
            'photo' => 'nullable|image|max:2048', //
        ]);

        if ($request->hasFile('photo')) { //
            $validated['photo'] = $request->file('photo')->store('group_photos', 'public'); //
        }

        $group->update($validated); //
        return redirect()->route('groups.show', $group)->with('success', 'Grupo atualizado com sucesso.'); //
    }

    public function destroy(Group $group)
    {
        $group->delete(); //
        return redirect()->route('groups.index')->with('success', 'Grupo deletado com sucesso.'); //
    }
}