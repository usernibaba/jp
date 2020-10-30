<?php
namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public  function home(){
        return view('index/index/home');
    }


}

