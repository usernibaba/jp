<?php

namespace App\Http\Controllers\Index;
use App\Model\LoginModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
class LoginController extends Controller
{
    public  function  register(Request $request){
        return view('index/login/register');

    }
    public  function  login(){
        return view('index/login/login');

    }
}
