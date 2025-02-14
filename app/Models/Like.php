<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'art_id',
        'user_id'
    ];

    public function art()
    {
        return $this->belongsTo(Art::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
