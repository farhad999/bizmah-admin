<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  function login()
  {

    $pageConfigs = ['myLayout' => 'blank'];

    return view('auth.login', compact('pageConfigs'));
  }

  function postLogin(Request $request)
  {

    $request->validate([
      'username' => 'required',
      'password' => 'required|min:6'
    ]);

    $data = $request->only(['username', 'password']);
    $rememberMe = $request->input('remember_me', false);

    if (auth()->attempt($data, $rememberMe)) {
      return redirect()->route('home.index');
    }

    toastr()->error('Invalid username or password');

    return redirect()->back();

  }

  function logout()
  {
    auth()->logout();
    return redirect()->route('login');
  }

}
