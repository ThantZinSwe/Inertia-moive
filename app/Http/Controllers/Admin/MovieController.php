<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MovieController extends Controller {
    public function index( Request $request ) {
        $perPage = $request->perPage ?: 5;
        return Inertia::render( 'Movies/Index', array(
            'movies' => Movie::query()
                ->when( $request->search, function ( $query, $search ) {
                    $query->where( 'title', 'like', "%{$search}%" );
                } )
                ->when( $request->has( 'column' ), function ( $query ) use ( $request ) {
                    $query->orderBy( $request->column, $request->direction );
                } )
                ->orderBy( 'id', 'desc' )
                ->paginate( $perPage )
                ->withQueryString(),
            'filter' => $request->only( array( 'search', 'perPage', 'column', 'direction' ) ),
        ) );
    }

    public function store( Request $request ) {
        $movie = Movie::where( 'tmdb_id', $request->movieTMDBId )->exists();

        if ( $movie ) {
            return Redirect::back()->with( 'flash.banner', 'Movie exists' );
        }

        $api_movie = Http::asJson()->get( config( 'services.tmdb.endpoint' ) . 'movie/' . $request->movieTMDBId .
            '?api_key=' . config( 'services.tmdb.secret' ) . '&language=en-US' );

        if ( $api_movie->successful() ) {
            $create_movie = Movie::create( array(
                'tmdb_id'       => $api_movie['id'],
                'title'         => $api_movie['title'],
                'release_date'  => $api_movie['release_date'],
                'runtime'       => $api_movie['runtime'],
                'lang'          => $api_movie['original_language'],
                'video_format'  => 'HD',
                'is_public'     => false,
                'rating'        => $api_movie['vote_average'],
                'overview'      => $api_movie['overview'],
                'poster_path'   => $api_movie['poster_path'],
                'backdrop_path' => $api_movie['backdrop_path'],
            ) );

            $tmdb_genres_id = collect( $api_movie['genres'] )->pluck( 'id' );
            $genres = Genre::whereIn( 'tmdb_id', $tmdb_genres_id )->get();

            $create_movie->genres()->attach( $genres );

            return Redirect::back()->with( 'flash.banner', 'Movie created' );
        } else {
            return Redirect::back()->with( array( 'flash.banner' => 'Api error', 'flash.bannerStyle' => 'danger' ) );

        }

    }

    public function edit( Movie $movie ) {
        return Inertia::render( 'Movies/Edit', array(
            'movie' => $movie,
        ) );
    }

    public function update( Request $request, Movie $movie ) {
        $request->validate( array(
            'title'         => 'required',
            'runtime'       => 'required',
            'language'      => 'required',
            'format'        => 'required',
            'rating'        => 'required',
            'poster_path'   => 'required',
            'backdrop_path' => 'required',
            'overview'      => 'required',
        ) );

        $movie->update( array(
            'title'         => $request->title,
            'runtime'       => $request->runtime,
            'lang'          => $request->language,
            'video_format'  => $request->format,
            'rating'        => $request->rating,
            'poster_path'   => $request->poster_path,
            'backdrop_path' => $request->backdrop_path,
            'overview'      => $request->overview,
            'is_public'     => $request->is_public,
        ) );

        return Redirect::route( 'admin.movies.index' )->with( 'flash.banner', 'Movie updated' );
    }

    public function destroy( Movie $movie ) {
        $movie->genres()->sync( array() );
        $movie->delete();
        $movie->trailers()->delete();
        return Redirect::back()->with( array( 'flash.banner' => 'Movie deleted', 'flash.bannerStyle' => 'danger' ) );
    }

}
