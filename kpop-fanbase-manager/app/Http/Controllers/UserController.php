<?php

namespace App\Http\Controllers;

use App\Models\User; //
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //
use Illuminate\Validation\Rule; //

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('extendedProfile')->paginate(10); //
        return view('users.index', compact('users')); //
    }

    public function create()
    {
        return view('users.create'); //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([ //
            'name' => 'required|string|max:255', //
            'email' => 'required|string|email|max:255|unique:users', //
            'password' => 'required|string|min:8|confirmed', //
            'type' => ['required', Rule::in(['fan', 'manager', 'admin'])], //
            'birth_date' => 'nullable|date', //
            'profile_picture' => 'nullable|image|max:2048', //
        ]);

        $validated['password'] = Hash::make($validated['password']); //
        if ($request->hasFile('profile_picture')) { //
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public'); //
        }

        $user = User::create($validated); //
        return redirect()->route('users.show', $user)->with('success', 'Usuário criado com sucesso.'); //
    }

    public function show(User $user)
    {
        return view('users.show', compact('user')); //
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user')); //
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([ //
            'name' => 'required|string|max:255', //
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], //
            'password' => 'nullable|string|min:8|confirmed', //
            'type' => ['required', Rule::in(['fan', 'manager', 'admin'])], //
            'birth_date' => 'nullable|date', //
            'profile_picture' => 'nullable|image|max:2048', //
        ]);

        if ($request->filled('password')) { //
            $validated['password'] = Hash::make($validated['password']); //
        } else {
            unset($validated['password']); //
        }

        if ($request->hasFile('profile_picture')) { //
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public'); //
        }

        $user->update($validated); //
        return redirect()->route('users.show', $user)->with('success', 'Usuário atualizado com sucesso.'); //
    }

    public function destroy(User $user)
    {
        $user->delete(); //
        return redirect()->route('users.index')->with('success', 'Usuário deletado com sucesso.'); //
    }

    public function editProfile(User $user)
    {
        return view('users.profile', compact('user')); //
    }

    public function updateProfile(Request $request, User $user)
    {
        $validated = $request->validate([ //
            'bio' => 'nullable|string|max:500', //
            'social_networks' => 'nullable|string|max:255', //
        ]);

        $user->extendedProfile()->updateOrCreate( //
            ['user_id' => $user->id], //
            $validated //
        );

        return redirect()->route('users.profile', $user)->with('success', 'Perfil atualizado com sucesso.'); //
    }
}