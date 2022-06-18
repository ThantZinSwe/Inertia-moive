<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\Movie;
use App\Models\Tag;
use App\Models\TrailerUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MovieAttachController extends Controller {
    public function index( Movie $movie ) {
        return Inertia::render( 'Movies/Attach', array(
            'movie'      => $movie,
            'trailers'   => $movie->trailers,
            'casts'      => Cast::all( 'id', 'name' ),
            'tags'       => Tag::all( 'id', 'tag_name' ),
            'movieCasts' => $movie->casts,
            'movieTags'  => $movie->tags,
        ) );
    }

    public function createTrailer( Movie $movie, Request $request ) {
        $request->validate( array(
            'name'       => 'required',
            'embed_html' => 'required',
        ) );

        $movie->trailers()->create( array(
            'name'       => $request->name,
            'embed_html' => $request->embed_html,
        ) );

        return Redirect::back()->with( 'flash.banner', 'Trailer created' );
    }

    public function destroyTrailer( TrailerUrl $trailerUrl ) {
        $trailerUrl->delete();
        return Redirect::back()->with( array( 'flash.banner' => 'Trailer deleted', 'flash.bannerStyle' => 'danger' ) );
    }

    public function addCasts( Movie $movie, Request $request ) {
        $casts = collect( $request->casts )->pluck( 'id' );
        $movie->casts()->sync( $casts );
        return Redirect::back()->with( 'flash.banner', 'Casts attach' );
    }

    public function addTags( Movie $movie, Request $request ) {
        $tags = collect( $request->tags )->pluck( 'id' );
        $movie->tags()->sync( $tags );
        return Redirect::back()->with( 'flash.banner', 'Tags attach' );
    }
}
