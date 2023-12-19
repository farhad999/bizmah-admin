@extends('layouts.layoutMaster')

@section('title', 'Edit User')

@section('content')
  <x-content title="Edit User">
    <x-form action="{{route('users.update', $user->id)}}" method="PUT">
      <div class="row">
        <div class="col-sm-6">
          <x-form.input
            name="name"
            label="Name"
            :required="true"
            data-rules="required"
            value="{{$user->name}}"
          />
        </div>
        <div class="col-sm-6">
          <x-form.input
            name="username"
            label="Username"
            :required="true"
            data-rules="required"
            value="{{$user->username}}"
          />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="email"
            label="Email"
            :required="true"
            data-rules="required"
            value="{{$user->email}}"
          />
        </div>

        <div class="col-sm-6">
          @include('partials.active-status', ['user' => $user->status])
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>

      </div>
    </x-form>
  </x-content>
@endsection
