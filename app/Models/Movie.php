<?php

namespace App\Models;

use App\Models\Cast;
use App\Models\Genre;
use App\Models\Tag;
use App\Models\TrailerUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Movie extends Model implements Searchable {
    use HasFactory;

    protected $fillable = array(
        'tmdb_id',
        'title',
        'release_date',
        'runtime',
        'lang',
        'video_format',
        'is_public',
        'visits',
        'slug',
        'rating',
        'poster_path',
        'backdrop_path',
        'overview' );

    public function getSearchResult(): SearchResult {
        $url = route( 'movies.show', $this->slug );

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    public function setTitleAttribute( $value ) {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug( $value );
    }

    public function genres() {
        return $this->belongsToMany( Genre::class, 'genre_movie' );
    }

    public function trailers() {
        return $this->morphMany( TrailerUrl::class, 'trailerable' );
    }

    public function tags() {
        return $this->morphToMany( Tag::class, 'taggable' );
    }

    public function casts() {
        return $this->belongsToMany( Cast::class, 'cast_movie' );
    }
}
