<?php

namespace App\Models;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class TvShow extends Model implements Searchable {
    use HasFactory;

    protected $fillable = array( 'tmdb_id', 'name', 'slug', 'created_year', 'poster_path', 'visits' );

    public function setNameAttribute( $value ) {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug( $value );
    }

    public function getSearchResult(): SearchResult {
        $url = route( 'series.show', $this->slug );

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url
        );
    }

    public function seasons() {
        return $this->hasMany( Season::class );
    }
}
