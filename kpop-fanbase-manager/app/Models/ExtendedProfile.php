<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtendedProfile extends Model // [cite: 13]
{
    use HasFactory;

    protected $fillable = [ // 
        'user_id',
        'bio',
        'social_networks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // 
    }
}