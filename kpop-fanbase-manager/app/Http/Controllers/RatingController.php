<?php

namespace App\Http\Controllers;

use App\Models\Music; // 
use App\Models\Rating; // 
use Illuminate\Http\Request; // 
use Illuminate\Support\Facades\Auth; // 

class RatingController extends Controller
{
    public function store(Request $request, Music $music)
    {
        $validated = $request->validate([ // 
            'rating' => 'required|integer|between:1,5', // 
            'comment' => 'nullable|string|max:500', // 
        ]);

        if ($music->ratings()->where('user_id', Auth::id())->exists()) { // 
            return back()->with('error', 'Você já avaliou esta música.'); // 
        }

        $rating = new Rating($validated); // 
        $rating->user_id = Auth::id(); // 
        $rating->music_id = $music->id; // 
        $rating->save(); // 

        $music->updateAverageRating(); // 
        return back()->with('success', 'Obrigado pela sua avaliação!'); // 
    }

    public function update(Request $request, Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) { // 
            abort(403, 'Você só pode atualizar suas próprias avaliações.'); // 
        }

        $validated = $request->validate([ // 
            'rating' => 'required|integer|between:1,5', // 
            'comment' => 'nullable|string|max:500', // 
        ]);

        $rating->update($validated); // 
        $rating->music->updateAverageRating(); // 
        return back()->with('success', 'Avaliação atualizada com sucesso.'); // 
    }

    public function destroy(Rating $rating)
    {
        if ($rating->user_id !== Auth::id() && !Auth::user()->isAdmin()) { // 
            abort(403, 'Você só pode deletar suas próprias avaliações.'); // 
        }

        $music = $rating->music; // 
        $rating->delete(); // 

        $music->updateAverageRating(); // 
        return back()->with('success', 'Avaliação deletada com sucesso.'); // 
    }
}