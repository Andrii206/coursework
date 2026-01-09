<?php

namespace App\Http\Middleware;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Middleware;


class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
             'global' => [
                'categories' => Category::take(10)->get(), 
                'authors' => Author::take(5)->get(),       
                'myBooks' => $request->user() ? Book::where('user_id', $request->user()->id)->with('author') ->get() : [],
            ],
        ];
    }
    
}
