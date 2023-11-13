<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    function index(){

      $pageConfigs = ['myLayout' => 'blank'];

      return view('auth.login', compact('pageConfigs'));
    }
    function store(){

    }
}
