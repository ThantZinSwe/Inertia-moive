<?php

namespace App\Models;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genre extends Model {
    use HasFactory;

    protected $fillable = array( 'tmdb_id', 'title', 'slug' );

    public function setTitleAttribute( $value ) {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug( $value );
    }

    public function movies() {
        return $this->belongsToMany( Movie::class );
    }
}
