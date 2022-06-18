<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class GenreController extends Controller {
    public function index( Request $request ) {
        $perPage = $request->perPage ?: 5;
        return Inertia::render( 'Genres/Index', array(
            'genres' => Genre::query()
                ->when( $request->search, function ( $query, $search ) {
                    $query->where( 'title', 'like', "%{$search}%" );
                } )
                ->orderBy( 'id', 'desc' )
                ->paginate( $perPage )
                ->withQueryString(),
            'filter' => $request->only( array( 'search', 'perPage' ) ),
        ) );
    }

    public function store() {
        $tmdb_genres = Http::get( config( 'services.tmdb.endpoint' ) . 'genre/movie/list?api_key='
            . config( 'services.tmdb.secret' ) . '&language=en-US' );

        if ( $tmdb_genres->successful() ) {

            foreach ( $tmdb_genres->json() as $single_tmdb_genre ) {

                foreach ( $single_tmdb_genre as $tgenre ) {
                    $genre = Genre::where( 'tmdb_id', $tgenre['id'] )->first();

                    if ( !$genre ) {
                        Genre::create( array(
                            'tmdb_id' => $tgenre['id'],
                            'title'   => $tgenre['name'],
                        ) );
                    }

                }

            }

            return Redirect::back()->with( 'flash.banner', 'Genres created' );

        }

        return Redirect::back()->with( array( 'flash.banner' => 'Api error', 'flash.bannerStyle' => 'danger' ) );

    }

    public function edit( Genre $genre ) {
        return Inertia::render( 'Genres/Edit', array(
            'genre' => $genre,
        ) );
    }

    public function update( Request $request, Genre $genre ) {
        $request->validate( array(
            'title' => 'required',
        ) );

        $genre->update( array(
            'title' => $request->title,
        ) );
        return Redirect::route( 'admin.genres.index' )->with( 'flash.banner', 'Genre updated' );
    }

    public function destroy( Genre $genre ) {
        $genre->delete();
        return Redirect::back()->with( array( 'flash.banner' => 'Genre deleted', 'flash.bannerStyle' => 'danger' ) );
    }

}
