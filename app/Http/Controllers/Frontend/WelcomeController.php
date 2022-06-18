<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Movie;
use App\Models\TvShow;
use Inertia\Inertia;

class WelcomeController extends Controller {
    public function index() {
        $movies = Movie::with( 'genres' )->orderBy( 'created_at', 'desc' )->take( 12 )->get();
        $tvShows = TvShow::withCount( 'seasons' )->orderBy( 'created_at', 'desc' )->take( 12 )->get();
        $episodes = Episode::with( 'season' )->orderBy( 'created_at', 'desc' )->take( 12 )->get();
        return Inertia::render( 'Welcome', array(
            'movies'   => $movies,
            'tvShows'  => $tvShows,
            'episodes' => $episodes,
        ) );
    }
}
