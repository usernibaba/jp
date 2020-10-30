<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoveController extends Controller
{
    //
    public  function  list()
    {
            return view('index/index/move');
    }
}
