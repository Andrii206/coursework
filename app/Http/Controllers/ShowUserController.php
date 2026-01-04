<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ShowUserController extends Controller
{
    public function __invoke(User $user)
    {
        $user->load(['books', 'reviewsReceived.sender']);
        $averageRating = $user->reviewsReceived()->avg('rating');
        $pageCurrentUser = $user->id == Auth::id();

        return view('show-user', compact('user', 'averageRating', 'pageCurrentUser'));
    }
}
