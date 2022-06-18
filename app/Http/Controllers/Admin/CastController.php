<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CastController extends Controller {
    public function index( Request $request ) {
        $perPage = $request->perPage ?: 5;
        return Inertia::render( 'Casts/Index', array(
            'casts'  => Cast::query()
                ->when( $request->search, function ( $query, $search ) {
                    $query->where( 'name', 'like', "%{$search}%" );
                } )
                ->orderBy( 'id', 'desc' )
                ->paginate( $perPage )
                ->withQueryString(),
            'filter' => $request->only( array( 'search', 'perPage' ) ),
        ) );
    }

    public function store( Request $request ) {
        $cast = Cast::where( 'tmdb_id', $request->castTMDBId )->first();

        if ( $cast ) {
            return Redirect::back()->with( 'flash.banner', 'Cast exists' );
        }

        $tmdb_cast = Http::asJson()->get( config( 'services.tmdb.endpoint' ) . 'person/' . $request->castTMDBId .
            '?api_key=' . config( 'services.tmdb.secret' ) . '&language=en-US' );

        if ( $tmdb_cast->successful() ) {
            Cast::create( array(
                'tmdb_id'     => $tmdb_cast['id'],
                'name'        => $tmdb_cast['name'],
                'slug'        => Str::slug( $tmdb_cast['name'] ),
                'poster_path' => $tmdb_cast['profile_path'],
            ) );
            return Redirect::back()->with( 'flash.banner', 'Cast created' );
        } else {
            return Redirect::back()->with( array( 'flash.banner' => 'Api error', 'flash.bannerStyle' => 'danger' ) );
        }

    }

    public function edit( Cast $cast ) {
        return Inertia::render( 'Casts/Edit', array(
            'cast' => $cast,
        ) );
    }

    public function update( Request $request, Cast $cast ) {
        $request->validate( array(
            'name'        => 'required',
            'poster_path' => 'required',
        ) );

        $cast->update( array(
            'name'        => $request->name,
            'poster_path' => $request->poster_path,
        ) );

        return Redirect::route( 'admin.casts.index' )->with( 'flash.banner', 'Cast updated' );
    }

    public function destroy( Cast $cast ) {
        $cast->delete();
        return Redirect::back()->with( array( 'flash.banner' => 'Cast deleted', 'flash.bannerStyle' => 'danger' ) );
    }

}
