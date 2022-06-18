<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TvShowController extends Controller {
    public function index( Request $request ) {
        $perPage = $request->perPage ?: 5;
        return Inertia::render( 'TvShows/Index', array(
            'tvShows' => TvShow::query()
                ->when( $request->search, function ( $query, $search ) {
                    $query->where( 'name', 'like', "%{$search}%" );
                } )
                ->orderBy( 'id', 'desc' )
                ->paginate( $perPage )
                ->withQueryString(),
            'filter'  => $request->only( array( 'search', 'filter' ) ),
        ) );
    }

    public function store( Request $request ) {
        $tvShow = TvShow::where( 'tmdb_id', $request->tvShowTMDBId )->first();

        if ( $tvShow ) {
            return Redirect::back()->with( 'flash.banner', 'TvShow exists' );
        }

        $tmdb_tvShow = Http::asJson()->get( config( 'services.tmdb.endpoint' ) . 'tv/' . $request->tvShowTMDBId .
            '?api_key=' . config( 'services.tmdb.secret' ) . '&language=en-US' );

        if ( $tmdb_tvShow->successful() ) {
            TvShow::create( array(
                'tmdb_id'      => $tmdb_tvShow['id'],
                'name'         => $tmdb_tvShow['name'],
                'created_year' => $tmdb_tvShow['first_air_date'],
                'poster_path'  => $tmdb_tvShow['poster_path'],
            ) );
            return Redirect::back()->with( 'flash.banner', 'TvShow created' );
        } else {
            return Redirect::back()->with( array( 'flash.banner' => 'Api error', 'flash.bannerStyle' => 'danger' ) );
        }

    }

    public function edit( TvShow $tvShow ) {
        return Inertia::render( 'TvShows/Edit', array(
            'tvShow' => $tvShow,
        ) );
    }

    public function update( Request $request, TvShow $tvShow ) {
        $request->validate( array(
            'name'        => 'required',
            'poster_path' => 'required',
        ) );

        $tvShow->update( array(
            'name'        => $request->name,
            'poster_path' => $request->poster_path,
        ) );

        return Redirect::route( 'admin.tv-shows.index' )->with( 'flash.banner', 'TvShow updated' );
    }

    public function destroy( TvShow $tvShow ) {
        $tvShow->delete();
        return Redirect::back()->with( array( 'flash.banner' => 'TvShow deleted', 'flash.bannerStyle' => 'danger' ) );
    }

}
