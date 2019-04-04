<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reading;

class ReadingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

}
