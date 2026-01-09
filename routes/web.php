<?php

use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\MessageController;
use App\Http\Controllers\ChatController as ControllersChatController;
use App\Http\Controllers\Client\Review\StoreController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ShowBookController;
use App\Http\Controllers\ShowUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Auth::routes();

Route::get('/', MainController::class)->name("front-page");
Route::get('/book/{book}', ShowBookController::class)->name('main.book.show');
Route::get('/users/{user}', ShowUserController::class)->name('main.users.show');

Route::middleware(['auth'])->group(function () {

    Route::post('/users/{user}/reviews', StoreController::class)->middleware('auth')->name('users.reviews.store');

    Route::group(['prefix'=>'books'], function(){
        Route::get('/create', \App\Http\Controllers\Client\Book\CreateController::class)->name('main.book.create');
        Route::post('/', \App\Http\Controllers\Client\Book\StoreController::class)->name('main.book.store');
        Route::get('/{book}/edit', \App\Http\Controllers\Client\Book\EditController::class)->name('main.book.edit');
        Route::patch('/{book}', \App\Http\Controllers\Client\Book\UpdateController::class)->name('main.book.update');
        Route::delete('/{book}', \App\Http\Controllers\Client\Book\DeleteController::class)->name('main.book.delete');
    });

});

Route::middleware(['auth'])->group(function () {
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::post('/chats/check', [ChatController::class, 'checkOrCreate'])->name('chats.check');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/block', [ChatController::class, 'toggleBlock'])->name('chats.block');
    Route::post('/chats/{chat}/messages', [MessageController::class, 'store'])->name('messages.store');
});



Route::prefix('admin')->middleware(['admin', 'auth'])->group(function () {
    
    Route::get('/', \App\Http\Controllers\Admin\Main\IndexController::class)->name('main.index');

    
    Route::group(['prefix'=>'categories'], function(){
        Route::get('/', \App\Http\Controllers\Admin\Category\IndexController::class)->name('category.index');
        Route::get('/create', \App\Http\Controllers\Admin\Category\CreateController::class)->name('category.create');
        Route::post('/', \App\Http\Controllers\Admin\Category\StoreController::class)->name('category.store');
        Route::get('/{category}', \App\Http\Controllers\Admin\Category\ShowController::class)->name('category.show');
        Route::get('/{category}/edit', \App\Http\Controllers\Admin\Category\EditController::class)->name('category.edit');
        Route::patch('/{category}', \App\Http\Controllers\Admin\Category\UpdateController::class)->name('category.update');
        Route::delete('/{category}', \App\Http\Controllers\Admin\Category\DeleteController::class)->name('category.delete');
    });

    
    Route::group(['prefix'=>'tags'], function(){
        Route::get('/', \App\Http\Controllers\Admin\Tag\IndexController::class)->name('tag.index');
        Route::get('/create', \App\Http\Controllers\Admin\Tag\CreateController::class)->name('tag.create');
        Route::post('/', \App\Http\Controllers\Admin\Tag\StoreController::class)->name('tag.store');
        Route::get('/{tag}', \App\Http\Controllers\Admin\Tag\ShowController::class)->name('tag.show');
        Route::get('/{tag}/edit', \App\Http\Controllers\Admin\Tag\EditController::class)->name('tag.edit');
        Route::patch('/{tag}', \App\Http\Controllers\Admin\Tag\UpdateController::class)->name('tag.update');
        Route::delete('/{tag}', \App\Http\Controllers\Admin\Tag\DeleteController::class)->name('tag.delete');
    });

    
    Route::group(['prefix'=>'persons'], function(){
        Route::get('/', \App\Http\Controllers\Admin\User\IndexController::class)->name('user.index');
        Route::get('/create', \App\Http\Controllers\Admin\User\CreateController::class)->name('user.create');
        Route::post('/', \App\Http\Controllers\Admin\User\StoreController::class)->name('user.store');
        Route::get('/{user}', \App\Http\Controllers\Admin\User\ShowController::class)->name('user.show');
        Route::get('/{user}/edit', \App\Http\Controllers\Admin\User\EditController::class)->name('user.edit');
        Route::patch('/{user}', \App\Http\Controllers\Admin\User\UpdateController::class)->name('user.update');
        Route::delete('/{user}', \App\Http\Controllers\Admin\User\DeleteController::class)->name('user.delete');
    });

    
    Route::group(['prefix'=>'books'], function(){
        Route::get('/', \App\Http\Controllers\Admin\Book\IndexController::class)->name('book.index');
        Route::get('/create', [\App\Http\Controllers\Admin\Book\CreateController::class, 'admin'])->name('book.create');
        Route::post('/', \App\Http\Controllers\Admin\Book\StoreController::class)->name('book.store');
        Route::get('/{book}', \App\Http\Controllers\Admin\Book\ShowController::class)->name('book.show');
        Route::get('/{book}/edit', \App\Http\Controllers\Admin\Book\EditController::class)->name('book.edit');
        Route::patch('/{book}', \App\Http\Controllers\Admin\Book\UpdateController::class)->name('book.update');
        Route::delete('/{book}', \App\Http\Controllers\Admin\Book\DeleteController::class)->name('book.delete');
    });

    
    Route::group(['prefix'=>'authors'], function(){
        Route::get('/', \App\Http\Controllers\Admin\Author\IndexController::class)->name('author.index');
        Route::get('/create', \App\Http\Controllers\Admin\Author\CreateController::class)->name('author.create');
        Route::post('/', \App\Http\Controllers\Admin\Author\StoreController::class)->name('author.store');
        Route::get('/{author}', \App\Http\Controllers\Admin\Author\ShowController::class)->name('author.show');
        Route::get('/{author}/edit', \App\Http\Controllers\Admin\Author\EditController::class)->name('author.edit');
        Route::patch('/{author}', \App\Http\Controllers\Admin\Author\UpdateController::class)->name('author.update');
        Route::delete('/{author}', \App\Http\Controllers\Admin\Author\DeleteController::class)->name('author.delete');
    });
});

