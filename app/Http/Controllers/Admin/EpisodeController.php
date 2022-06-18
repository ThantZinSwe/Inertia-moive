<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EpisodeController extends Controller {
    public function index( TvShow $tvShow, Season $season, Request $request ) {
        $perPage = $request->perPage ?: '5';
        return Inertia::render( 'TvShows/Seasons/Episodes/Index', array(
            'tvShow'   => $tvShow,
            'season'   => $season,
            'episodes' => Episode::query()
                ->where( 'season_id', $season->id )
                ->when( $request->search, function ( $query, $search ) {
                    $query->where( 'name', 'like', "%{$search}%" );
                } )
                ->orderBy( 'id', 'desc' )
                ->paginate( $perPage )
                ->withQueryString(),
            'filter'   => $request->only( array( 'search', 'filter' ) ),
        ) );
    }

    public function store( TvShow $tvShow, Season $season, Request $request ) {
        $episode = $season->episodes()->where( 'episode_number', $request->episodeNumber )->exists();

        if ( $episode ) {
            return Redirect::back()->with( 'flash.banner', 'Episode exists' );
        }

        $episode_num = Http::asJson()->get( config( 'services.tmdb.endpoint' ) . 'tv/' . $tvShow->tmdb_id .
            '/season/' . $season->season_number . '/episode/' . $request->episodeNumber . '?api_key='
            . config( 'services.tmdb.secret' ) . '&language=en-US' );

        if ( $episode_num->successful() ) {
            Episode::create( array(
                'tmdb_id'        => $episode_num['id'],
                'season_id'      => $season->id,
                'name'           => $episode_num['name'],
                'episode_number' => $episode_num['episode_number'],
                'overview'       => $episode_num['overview'],
            ) );
            return Redirect::back()->with( 'flash.banner', 'Episode created' );
        } else {
            return Redirect::back()->with( array( 'flash.banner' => 'Api error', 'flash.bannerStyle' => 'danger' ) );
        }

    }

    public function edit( TvShow $tvShow, Season $season, Episode $episode ) {
        return Inertia::render( 'TvShows/Seasons/Episodes/Edit', array(
            'tvShow'  => $tvShow,
            'season'  => $season,
            'episode' => $episode,
        ) );
    }

    public function update( TvShow $tvShow, Season $season, Episode $episode, Request $request ) {
        $request->validate( array(
            'name'     => 'required',
            'overview' => 'required',
        ) );

        $episode->update( array(
            'name'      => $request->name,
            'overview'  => $request->overview,
            'is_public' => $request->is_public,
        ) );

        return Redirect::route( 'admin.episodes.index', array( $tvShow->id, $season->id ) )->with( 'flash.banner', 'Episode updated' );
    }

    public function destroy( TvShow $tvShow, Season $season, Episode $episode ) {
        $episode->delete();
        return Redirect::back()->with( array( 'flash.banner' => 'Episode deleted', 'flash.bannerStyle' => 'danger' ) );
    }

}
