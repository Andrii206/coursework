<?php

namespace App\Providers;

use App\Models\Book;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.main', function ($view) {
            $books = []; // За замовчуванням список порожній
            if (Auth::check()) {
                $books = Book::where('user_id', Auth::id())->get();
            }
            $view->with([
                'myBooks' => $books,
            ]);
        });
        Vite::prefetch(concurrency: 3);
        Paginator::useBootstrapFive();
    }
}
