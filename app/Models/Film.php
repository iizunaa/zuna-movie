<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'film_genre');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Untuk menghitung rata-rata rating, Anda bisa menambahkan fungsi seperti ini
    public function averageRating()
    {
        return $this->ratings()->average('rating');
    }

    public function watchers()
    {
        return $this->belongsToMany(User::class, 'watch_history')->withTimestamps()->withPivot('watched_at');
    }

    public function getAverageRatingAttribute()
    {
        $totalLikes = $this->ratings()->where('rating', 1)->count();
        $totalDislikes = $this->ratings()->where('rating', -1)->count();
        $totalRaters = $this->ratings()->where('rating', '!=', 0)->count();

        if ($totalRaters == 0) {
            return 0;
        }

        return (($totalLikes - $totalDislikes + $totalRaters) / (2 * $totalRaters)) * 10;
    }
}
