<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->search;
    
        if ($searchTerm) {
            // Logika pencarian
            $films = Film::query()
                ->where('title', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('genres', function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%{$searchTerm}%");
                })
                ->with('genres')
                ->withCount(['ratings as averageRating' => function($query) {
                    $query->select(DB::raw('coalesce(avg(rating), 0)'));
                }])
                ->get();
    
            return view('home.index', [
                'films' => $films,
                'searchTerm' => $searchTerm,
                'topRatedFilms' => collect(), // Kirim koleksi kosong atau sesuai kebutuhan
                'genres' => Genre::with('films.genres')->get(),
            ]);
        } else {
            // Ambil 5 film dengan rating tertinggi untuk bagian Top Rating
            $topRatedFilms = Film::with('genres')
                ->withCount(['ratings as averageRating' => function($query) {
                    $query->select(DB::raw('coalesce(avg(rating), 0)'));
                }])
                ->orderByDesc('averageRating')
                ->take(5)
                ->get();
    
            // Ambil semua genre dan film terkait untuk setiap genre
            // Tanpa mempertimbangkan rating film
            $genres = Genre::with('films.genres')->get(); // Hapus subquery yang mengurutkan film berdasarkan rating
            if (Auth::check()) {
                $userLikedFilms = $this->getUserLikedFilms(); // Mendapatkan film yang disukai oleh pengguna
                $cfRecommendations = $this->getCFRecommendations($userLikedFilms); // Rekomendasi CF
        
                // Perbarui ini untuk memanggil metode yang baru
                $cbfRecommendations = $this->getCBFRecommendations(); // Rekomendasi CBF berdasarkan tontonan
        
                // Gabungkan kedua rekomendasi dan ambil unik berdasarkan id
                // Gabungkan kedua rekomendasi dan ambil unik berdasarkan id
                $recommendations = $cfRecommendations->merge($cbfRecommendations)->unique('id')->shuffle();
            } else {
                $recommendations = collect(); // Koleksi kosong jika user tidak login
            }
            return view('home.index', [
                'topRatedFilms' => $topRatedFilms,
                'genres' => $genres,
                'recommendations' => $recommendations,
            ]);
        }
    }

    public function show(Film $film)
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
    
        $userRating = null; // Inisialisasi variabel userRating
        if ($user) {
            // Menyimpan histori tontonan
            $user->watchedFilms()->attach($film->id, ['watched_at' => now()]);
    
            // Cari rating yang diberikan user terhadap film ini
            $rating = $user->ratings()->where('film_id', $film->id)->first();
            $userRating = $rating ? $rating->rating : null;
        }
    
        // Menghitung rating rata-rata film
        $totalLikes = $film->ratings()->where('rating', 1)->count();
        $totalDislikes = $film->ratings()->where('rating', -1)->count();
        $totalRaters = $film->ratings()->where('rating', '!=', 0)->count(); // Hanya hitung yang like/dislike

        $averageRating = 0;
        if ($totalRaters > 0) {
            $averageRating = (($totalLikes - $totalDislikes + $totalRaters) / (2 * $totalRaters)) * 10;
        }

        return view('home.film', [
            'film' => $film,
            'userRating' => $userRating,
            'averageRating' => $averageRating,
        ]);
    }

    public function rateFilm(Request $request, Film $film)
    {
        $user = Auth::user();
        $action = $request->input('action');
    
        $rating = $user->ratings()->updateOrCreate(
            ['film_id' => $film->id],
            ['rating' => $action == 'like' ? 1 : ($action == 'dislike' ? -1 : 0)]
        );
    
        return response()->json(['message' => 'Rating updated']);
    }
    
    protected function getUserLikedFilms()
    {
        // Mengambil film yang disukai oleh pengguna saat ini
        return Auth::user()->ratings()->where('rating', 1)->with('film.genres')->get()->pluck('film');
    }

    protected function getWatchedFilmIds()
    {
        // Mengumpulkan ID film yang telah ditonton oleh pengguna
        return Auth::user()->watchedFilms()->pluck('film_id');
    }

    protected function getCFRecommendations($userLikedFilms)
    {
        $userId = Auth::id();
        $userLikedFilmIds = $userLikedFilms->pluck('id');
    
        // Langkah 1: Temukan pengguna serupa berdasarkan kegemaran pada minimal 3 film yang sama
        $similarUsers = DB::table('ratings')
            ->whereIn('film_id', $userLikedFilmIds)
            ->where('rating', 1) // Hanya mempertimbangkan film yang disukai
            ->groupBy('user_id')
            ->havingRaw('COUNT(DISTINCT film_id) >= 3')
            ->pluck('user_id');
    
    
        // Langkah 2: Dapatkan film yang disukai oleh pengguna serupa tapi belum ditonton oleh user saat ini
        $filmsLikedBySimilarUsers = DB::table('ratings')
            ->whereIn('user_id', $similarUsers)
            ->where('rating', 1)
            ->whereNotIn('film_id', $userLikedFilmIds)
            ->inRandomOrder() // Mengacak urutan hasil
            ->distinct()
            ->pluck('film_id');
    
        // Langkah 3: Ambil detail film untuk rekomendasi
        $recommendations = Film::whereIn('id', $filmsLikedBySimilarUsers)->take(5)->get();
    
        return $recommendations;
    }

    protected function getCBFRecommendations()
    {
        // Pastikan pengguna telah login
        if (!Auth::check()) {
            return collect();
        }
    
        // Mengambil 3 film terakhir yang ditonton oleh pengguna
        $latestWatchedFilms = Auth::user()->watchedFilms()
        ->orderBy('watch_history.watched_at', 'desc')
        ->take(3)->get();
    
        $genreIds = $latestWatchedFilms->flatMap(function ($film) {
            return $film->genres->pluck('id');
        })->unique()->toArray();
    
        // Mencari film dengan genre yang sama, tapi bukan yang sudah ditonton
        $watchedFilmIds = $latestWatchedFilms->pluck('id')->toArray();
        $recommendations = Film::whereHas('genres', function ($query) use ($genreIds) {
                                $query->whereIn('id', $genreIds);
                            })
                            ->whereNotIn('id', $watchedFilmIds)
                            ->inRandomOrder() // Mengacak urutan hasil
                            ->get();
    
        return $recommendations;
    }
}
