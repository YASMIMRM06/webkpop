<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'title',
        'duration',
        'youtube_link',
        'release_date',
        'average_rating',
    ];

    protected $casts = [
        'release_date' => 'date',
        'duration' => 'datetime:H:i:s',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function updateAverageRating()
    {
        $this->average_rating = $this->ratings()->avg('rating');
        $this->save();
        return $this->average_rating;
    }
}