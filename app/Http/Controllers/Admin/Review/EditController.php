<?php

namespace App\Http\Controllers\Admin\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;


class EditController extends Controller
{
    public function __invoke(Review $review)
    {   
        $user = User::all();
        return view('admin.review.edit', compact('review', 'user'));
    }
}
