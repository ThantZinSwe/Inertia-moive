<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Inertia\Inertia;

class GenreController extends Controller {
    public function show( Genre $genre ) {
        return Inertia::render( 'Frontend/Genres/Show', array(
            'movies' => $genre->movies()->with( 'genres' )->orderBy( 'created_at', 'desc' )->paginate( 12 ),
            'genre'  => $genre,
        ) );
    }
}
