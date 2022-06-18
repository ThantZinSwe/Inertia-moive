<?php

use App\Http\Controllers\Admin\CastController;
use App\Http\Controllers\Admin\EpisodeController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\MovieAttachController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TvShowController;
use App\Http\Controllers\Frontend\CastController as FrontendCastController;
use App\Http\Controllers\Frontend\GenreController as FrontendGenreController;
use App\Http\Controllers\Frontend\MovieController as FrontendMovieController;
use App\Http\Controllers\Frontend\TagController as FrontendTagController;
use App\Http\Controllers\Frontend\TvShowController as FrontendTvShowController;
use App\Http\Controllers\Frontend\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get( '/', array( WelcomeController::class, 'index' ) );
Route::get( '/movies', array( FrontendMovieController::class, 'index' ) )->name( 'movies.index' );
Route::get( '/movies/{movie:slug}', array( FrontendMovieController::class, 'show' ) )->name( 'movies.show' );

Route::get( '/tv-shows', array( FrontendTvShowController::class, 'index' ) )->name( 'tv-shows.index' );
Route::get( '/tv-shows/{tv_show:slug}', array( FrontendTvShowController::class, 'show' ) )->name( 'tv-shows.show' );
Route::get( '/tv-shows/{tv_show:slug}/seasons/{season:slug}', array( FrontendTvShowController::class, 'seasonShow' ) )->name( 'seasons.show' );
Route::get( '/episodes/{episode:slug}', array( FrontendTvShowController::class, 'showEpisode' ) )->name( 'episodes.show' );

Route::get( '/casts', array( FrontendCastController::class, 'index' ) )->name( 'casts.index' );
Route::get( '/casts/{cast:slug}', array( FrontendCastController::class, 'show' ) )->name( 'casts.show' );

Route::get( 'genres/{genre:slug}', array( FrontendGenreController::class, 'show' ) )->name( 'genres.show' );

Route::get( 'tags/{tag:slug}', array( FrontendTagController::class, 'show' ) )->name( 'tags.show' );

Route::middleware( array( 'auth:sanctum', 'verified', 'role:admin' ), )->prefix( 'admin' )->name( 'admin.' )->group( function () {
    Route::get( '/', function () {
        return Inertia::render( 'Admin/Index' );
    } )->name( 'index' );

    Route::resource( '/movies', MovieController::class );
    Route::get( '/movies/{movie}/attach', array( MovieAttachController::class, 'index' ) )->name( 'movies.attach' );
    Route::post( '/movies/{movie}/attach', array( MovieAttachController::class, 'createTrailer' ) )->name( 'movies.attach.createTrailer' );
    Route::delete( '/trailer-urls/{trailer_url}', array( MovieAttachController::class, 'destroyTrailer' ) )->name( 'trailer_urls.destroy' );
    Route::post( '/movies/{movie}/add-casts', array( MovieAttachController::class, 'addCasts' ) )->name( 'movies.attach.addCasts' );
    Route::post( '/movies/{movie}/add-tags', array( MovieAttachController::class, 'addTags' ) )->name( 'movies.attach.addTags' );

    Route::resource( '/tv-shows', TvShowController::class );
    Route::resource( '/tv-shows/{tv_show}/seasons', SeasonController::class );
    Route::resource( '/tv-shows/{tv_show}/seasons/{season}/episodes', EpisodeController::class );
    Route::resource( '/genres', GenreController::class );
    Route::resource( '/casts', CastController::class );
    Route::resource( '/tags', TagController::class );
} );

Route::middleware( array(
    'auth:sanctum',
    config( 'jetstream.auth_session' ),
    'verified',
) )->group( function () {
    Route::get( '/dashboard', function () {
        return Inertia::render( 'Dashboard' );
    } )->name( 'dashboard' );
} );
