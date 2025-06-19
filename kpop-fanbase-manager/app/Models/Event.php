<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [ // 
        'user_id',
        'name',
        'description',
        'event_date',
        'location',
        'capacity',
        'status',
    ];

    protected $casts = [ // 
        'event_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id'); // 
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participations') // 
            ->withPivot('status')
            ->withTimestamps();
    }

    public function confirmedParticipantsCount()
    {
        return $this->participants()->wherePivot('status', 'confirmed')->count(); // 
    }

    public function isFull()
    {
        return $this->confirmedParticipantsCount() >= $this->capacity; // 
    }
}