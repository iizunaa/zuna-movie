<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardGenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.genres.index', [
            'genres' => Genre::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.genres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:genres'
        ]);

        Genre::create($validatedData);

        return redirect('/dashboard/genres')->with('success', 'New Genre Has Been Upload!');
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
    public function edit($id)
    {
    // Dapatkan genre berdasarkan id
    $genre = Genre::findOrFail($id);

    return view('dashboard.genres.edit', [
        'genre' => $genre,
        'genres' => Genre::all()
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genre $genre)
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        $validatedData = $request->validate($rules);

        Genre::where('id', $genre->id)
            ->update($validatedData);

        return redirect('/dashboard/genres')->with('success', 'Category Has Been Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        Genre::destroy($genre->id);
    
        return redirect('/dashboard/genres')->with('success', 'Genre Has Been Deleted!');
    }
}
