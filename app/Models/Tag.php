<?php

namespace App\Models;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
    use HasFactory;

    protected $fillable = array( 'tag_name', 'slug' );

    public function movies() {
        return $this->morphedByMany( Movie::class, 'taggable' );
    }
}
