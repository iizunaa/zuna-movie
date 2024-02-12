<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class LikedController extends Controller
{
    public function index()
    {
        // Dapatkan pengguna yang sedang login
        $user = Auth::user();

        // Dapatkan film yang disukai pengguna
        $likedFilms = $user->likedFilms()->with('genres')->orderBy('ratings.created_at', 'desc')->get();

        return view('liked.index', [
            'likedFilms' => $likedFilms,
        ]);
    }
}
