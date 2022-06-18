<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TagController extends Controller {
    public function index( Request $request ) {
        $perPage = $request->perPage ?: 5;
        return Inertia::render( 'Tags/Index', array(
            'tags'   => Tag::query()
                ->when( $request->search, function ( $query, $search ) {
                    $query->where( 'tag_name', 'like', "%{$search}%" );
                } )
                ->orderBy( 'id', 'desc' )
                ->paginate( $perPage )
                ->withQueryString(),

            'filter' => $request->only( array( 'search', 'perPage' ) ),
        ) );
    }

    public function create() {
        return Inertia::render( 'Tags/Create' );
    }

    public function store( Request $request ) {
        $request->validate( array(
            'tagName' => 'required',
        ) );

        Tag::firstOrCreate( array(
            'tag_name' => $request->tagName,
            'slug'     => Str::slug( $request->tagName ),
        ) );

        return Redirect::route( 'admin.tags.index' )->with( 'flash.banner', 'Tag created' );
    }

    public function edit( Tag $tag ) {
        return Inertia::render( 'Tags/Edit', array(
            'tag' => $tag,
        ) );
    }

    public function update( Request $request, Tag $tag ) {
        $request->validate( array(
            'tagName' => 'required',
        ) );

        $tag->update( array(
            'tag_name' => $request->tagName,
            'slug'     => Str::slug( $request->tagName ),
        ) );

        return Redirect::route( 'admin.tags.index' )->with( 'flash.banner', 'Tag updated' );
    }

    public function destroy( Tag $tag ) {
        $tag->delete();

        return Redirect::route( 'admin.tags.index' )->with( array( 'flash.banner' => 'Tag deleted', 'flash.bannerStyle' => 'danger' ) );
    }
}
