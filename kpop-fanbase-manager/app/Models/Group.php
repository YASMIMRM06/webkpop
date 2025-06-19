<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model // [cite: 14]
{
    use HasFactory;

    protected $fillable = [ // 
        'name',
        'formation_date',
        'company',
        'description',
        'photo',
    ];

    protected $casts = [ // 
        'formation_date' => 'date',
    ];

    public function musics()
    {
        return $this->hasMany(Music::class); // 
    }
}