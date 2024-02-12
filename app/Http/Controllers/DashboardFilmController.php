<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Storage;

class DashboardFilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.films.index', [
            'films' => Film::with('genres')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.films.create', [
            'genres' => Genre::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'poster' => 'image',
            'video' => 'required',
            'release' => 'required',
            'description' => 'required',
        ]);
    
        if ($request->file('poster')) {
            $validatedData['poster'] = $request->file('poster')->store('film-posters', 'public');
        }
    
        $film = Film::create($validatedData);
    
        if ($request->has('genres')) {
            $film->genres()->sync($request->input('genres'));
        }
    
        return redirect('/dashboard/films')->with('success', 'New Film Has Been Uploaded!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Film $film)
    {
        // Eager load genre yang terkait dengan film
        $film->load('genres');

        return view('dashboard.films.edit', [
            'film' => $film,
            'genres' => Genre::all() // Mengambil semua genre untuk daftar pilihan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Film $film)
    {
        $rules = [
            'title' => 'required|max:255',
            'poster' => 'image|nullable',
            'video' => 'required',
            'release' => 'required',
            'description' => 'required',
        ];
    
        $validatedData = $request->validate($rules);
    
        if ($request->file('poster')) {
            if ($film->poster) {
                Storage::delete($film->poster);
            }
            $validatedData['poster'] = $request->file('poster')->store('film-posters');
        }
    
        $film->update($validatedData);
    
        if ($request->has('genres')) {
            $film->genres()->sync($request->input('genres'));
        }
    
        return redirect('/dashboard/films')->with('success', 'Film has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Film $film)
    {
    // Hapus gambar poster dari storage jika ada
    if ($film->poster) {
        Storage::delete($film->poster);
    }

    // Hapus film
    $film->delete();

    return redirect('/dashboard/films')->with('success', 'Film has been deleted!');
    }
}
