<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowUserController extends Controller
{
    public function __invoke(User $user)
    {
        $user->load(['books', 'reviewsReceived.sender']);
        $averageRating = number_format($user->reviewsReceived()->avg('rating'), 1);
        $pageCurrentUser = $user->id == Auth::id();

        return Inertia::render('Show/User', compact('user', 'averageRating', 'pageCurrentUser'));
    }
}
