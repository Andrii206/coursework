<?php

namespace App\Http\Controllers\Admin\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Review $review)
    {
        $review->delete();
        return redirect()->route('review.index');
    }
}
