<?php 
namespace App\Http\Controllers; 
use App\Models\Group; 
use App\Models\Music; 
use Illuminate\Http\Request; 
class MusicController extends Controller 
{ 
    public function index(Group $group) 
    { 
        $musics = $group->musics()->paginate(10); 
        return view('musics.index', compact('group', 'musics')); 
    } 
    public function create(Group $group) 
    { 
        return view('musics.create', compact('group')); 
    } 
    public function store(Request $request, Group $group) 
    { 
        $validated = $request->validate([ 
            'title' => 'required|string|max:255', 
            'duration' => 'required|date_format:H:i:s', 
            'youtube_link' => 'nullable|url', 
            'release_date' => 'required|date', 
        ]); 
        $group->musics()->create($validated); 
        return redirect()->route('groups.musics.index', $group)->with('success', 'Música adicionada 
com sucesso.'); 
    } 
    public function show(Group $group, Music $music) 
    { 
        $music->load('ratings.user'); 
        return view('musics.show', compact('group', 'music')); 
    } 
    public function edit(Group $group, Music $music) 
    { 
        return view('musics.edit', compact('group', 'music')); 
    } 
    public function update(Request $request, Group $group, Music $music) 
    { 
        $validated = $request->validate([ 
            'title' => 'required|string|max:255', 
            'duration' => 'required|date_format:H:i:s', 
            'youtube_link' => 'nullable|url', 
            'release_date' => 'required|date', 
        ]); 
        $music->update($validated); 
        return redirect()->route('groups.musics.show', [$group, $music])->with('success', 'Música 
atualizada com sucesso.'); 
    } 
    public function destroy(Group $group, Music $music) 
    { 
        $music->delete(); 
        return redirect()->route('groups.musics.index', $group)->with('success', 'Música deletada 
com sucesso.'); 
    } 
} 