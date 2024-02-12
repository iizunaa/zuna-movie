<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Mengambil semua histori dengan relasi genres dan mengurutkannya berdasarkan watched_at
        $allHistory = $user->watchedFilms()->with('genres')->orderBy('watch_history.watched_at', 'desc')->get();
    
        // Filter untuk mendapatkan entri unik berdasarkan film_id
        $history = collect();
        foreach ($allHistory as $entry) {
            if (!$history->contains('id', $entry->id)) {
                $history->push($entry);
            }
        }
    
        return view('history.index', [
            'history' => $history,
        ]);
    }
}
