<?php

namespace App\Http\Controllers\Admin\Main;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;



class IndexController extends Controller
{
    public function __invoke()
    {

        return view('admin.main.index');
    }
}
