<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('event_date', '>', now())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();
            
        $popularGroups = Group::withCount('musics')
            ->orderBy('musics_count', 'desc')
            ->limit(6)
            ->get();
            
        return view('home', compact('upcomingEvents', 'popularGroups'));
    }
}