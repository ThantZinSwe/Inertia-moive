<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Movie;
use App\Models\Season;
use App\Models\TvShow;
use Inertia\Inertia;

class TvShowController extends Controller {
    public function index() {
        return Inertia::render( 'Frontend/TvShows/Index', array(
            'tvShows' => TvShow::orderBy( 'created_at', 'desc' )->paginate( 12 ),
        ) );
    }

    public function show( TvShow $tvShow ) {
        $latest = Movie::orderBy( 'created_at', 'desc' )->take( 9 )->get();
        return Inertia::render( 'Frontend/TvShows/Show', array(
            'tvShow'  => $tvShow,
            'latests' => $latest,
            'seasons' => $tvShow->seasons,
        ) );
    }

    public function seasonShow( TvShow $tvShow, Season $season ) {
        $latest = Movie::orderBy( 'created_at', 'desc' )->take( 9 )->get();
        return Inertia::render( 'Frontend/TvShows/Seasons/Show', array(
            'tvShow'   => $tvShow,
            'latests'  => $latest,
            'season'   => $season,
            'episodes' => $season->episodes,
        ) );
    }

    public function showEpisode( Episode $episode ) {
        $latest = Episode::where( 'season_id', $episode->season_id )->latest()->take( 12 )->get();
        return Inertia::render( 'Frontend/TvShows/Seasons/Episodes/Show', array(
            'episode' => $episode,
            'latests' => $latest,
            'season'  => $episode->season,
        ) );
    }
}
