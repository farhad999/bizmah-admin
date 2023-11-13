@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Create User">
    <x-form action="{{route('users.store')}}">
      <div class="row">
        <div class="col-sm-6">
          <x-form.input
            name="name"
            label="Name"
            :required="true"
            data-rules="required"
          />
        </div>
        <div class="col-sm-6">
          <x-form.input
            name="username"
            label="Username"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="email"
            label="Email"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="password"
            label="password"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>

      </div>
    </x-form>
  </x-content>
@endsection
