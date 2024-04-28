<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'category', 'is_audio_article', 'cover_image', 'audio_file', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
