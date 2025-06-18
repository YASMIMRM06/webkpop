<?php

namespace App\Http\Controllers;

use App\Models\Music;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Music $music)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Check if user already rated this music
        if ($music->ratings()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'You have already rated this music.');
        }

        $rating = new Rating($validated);
        $rating->user_id = Auth::id();
        $rating->music_id = $music->id;
        $rating->save();

        // Update music average rating
        $music->updateAverageRating();

        return back()->with('success', 'Thank you for your rating!');
    }

    public function update(Request $request, Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            abort(403, 'You can only update your own ratings.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ]);

        $rating->update($validated);

        // Update music average rating
        $rating->music->updateAverageRating();

        return back()->with('success', 'Rating updated successfully.');
    }

    public function destroy(Rating $rating)
    {
        if ($rating->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You can only delete your own ratings.');
        }

        $music = $rating->music;
        $rating->delete();

        // Update music average rating
        $music->updateAverageRating();

        return back()->with('success', 'Rating deleted successfully.');
    }
}