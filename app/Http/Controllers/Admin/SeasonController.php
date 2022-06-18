<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SeasonController extends Controller {
    public function index( TvShow $tvShow, Request $request ) {
        $perPage = $request->perPage ?: 5;
        return Inertia::render( 'TvShows/Seasons/Index', array(
            'tvShow'  => $tvShow,
            'seasons' => Season::query()
                ->where( 'tv_show_id', $tvShow->id )
                ->when( $request->search, function ( $query, $search ) {
                    $query->where( 'name', 'like', "%{$search}%" );
                } )
                ->orderBy( 'id', 'desc' )
                ->paginate( $perPage )
                ->withQueryString(),
            'filter'  => $request->only( array( 'search', 'perPage' ) ),
        ) );
    }

    public function store( Request $request, TvShow $tvShow ) {
        $season = $tvShow->seasons()->where( 'season_number', $request->seasonNumber )->exists();

        if ( $season ) {
            return Redirect::back()->with( 'flash.banner', 'Season exists' );
        }

        $season_num = Http::asJson()->get( config( 'services.tmdb.endpoint' ) . 'tv/' . $tvShow->tmdb_id .
            '/season/' . $request->seasonNumber . '?api_key=' . config( 'services.tmdb.secret' ) . '&language=en-US' );

        if ( $season_num->successful() ) {
            Season::create( array(
                'tmdb_id'       => $season_num['id'],
                'tv_show_id'    => $tvShow->id,
                'name'          => $season_num['name'],
                'season_number' => $season_num['season_number'],
                'poster_path'   => $season_num['poster_path'],
            ) );
            return Redirect::back()->with( 'flash.banner', 'Season created' );
        } else {
            return Redirect::back()->with( array( 'flash.banner' => 'Api error', 'flash.bannerStyle' => 'danger' ) );
        }

    }

    public function edit( TvShow $tvShow, Season $season ) {
        return Inertia::render( 'TvShows/Seasons/Edit', array(
            'tvShow' => $tvShow,
            'season' => $season,
        ) );
    }

    public function update( TvShow $tvShow, Season $season, Request $request ) {
        $request->validate( array(
            'name'        => 'required',
            'poster_path' => 'required',
        ) );

        $season->update( array(
            'name'        => $request->name,
            'poster_path' => $request->poster_path,
        ) );

        return Redirect::route( 'admin.seasons.index', $tvShow->id )->with( 'flash.banner', 'Season updated' );
    }

    public function destroy( TvShow $tvShow, Season $season ) {
        $season->delete();
        return Redirect::back()->with( array( 'flash.banner' => 'Season deleted', 'flash.bannerStyle' => 'danger' ) );
    }

}
