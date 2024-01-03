<?php

namespace Modules\Cart\src\Http\Controllers;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        return view("Modules-Cart::subfolder.text");
    }
}
