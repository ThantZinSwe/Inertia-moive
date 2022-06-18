<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Inertia\Inertia;

class MovieController extends Controller {
    public function index() {
        return Inertia::render( 'Frontend/Movies/Index', array(
            'movies' => Movie::with( 'genres' )->orderBy( 'created_at', 'desc' )->paginate( 12 ),
        ) );
    }

    public function show( Movie $movie ) {
        $latest = Movie::orderBy( 'created_at', 'desc' )->take( 9 )->get();
        return Inertia::render( 'Frontend/Movies/Show', array(
            'latests'  => $latest,
            'movie'    => $movie,
            'genres'   => $movie->genres,
            'tags'     => $movie->tags,
            'casts'    => $movie->casts,
            'trailers' => $movie->trailers,
        ) );
    }
}
