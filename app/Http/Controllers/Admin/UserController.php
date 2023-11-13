<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  function index()
  {
    if (\request()->ajax()) {
      return datatables()->of(User::query())
        ->addColumn('action', function ($row) {
          return view("user.action-buttons", compact('row'));
        })
        ->editColumn('status', function ($row) {
          if ($row) {
            return '<span class="badge bg-success">Active</span>';
          }
          return '<span class="badge bg-warning">Inactive</span>';
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
    }

    return view('user.index');
  }

  function create()
  {
    return view('user.create');
  }

  function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'username' => 'required|unique:users',
      'email' => 'required|unique:users',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
      return redirect()->back();
    }

    $data = $validator->valid();

    User::create($data);
    toastr()->success('User Added');
    return redirect()->route('users.index');

  }

  function edit(User $user)
  {
    return view('user.edit', compact('user'));
  }

  function update(Request $request, $id)
  {

    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'username' => 'required|unique:users,id,' . $user->id,
      'email' => 'required|unique:users,id,' . $user->id,
      'status' => 'required|boolean',
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
      return redirect()->back();
    }

    $data = $validator->valid();

    $user->update($data);

    toastr()->success('User Updated');
    return redirect()->route('users.index');

  }

}
