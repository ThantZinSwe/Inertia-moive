<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller {
    public function show( Tag $tag ) {
        return Inertia::render( 'Frontend/Tags/Show', array(
            'movies' => $tag->movies()->with( 'genres' )->orderBy( 'created_at', 'desc' )->paginate( 12 ),
            'tag'    => $tag,
        ) );
    }
}
