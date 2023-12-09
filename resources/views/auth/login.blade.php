@php
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Login')

@section('css')
  <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection


@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-4">
        <!-- Login -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
              <a href="{{url('/')}}" class="app-brand-link gap-2">
                <img src="{{asset('assets/img/logo.png')}}" alt="BizMah"
                style="height: 40px;"
                >
              </a>
            </div>

            <x-form class="mb-3" action="{{route('auth.post-login')}}"
                    id="validate_form">
              <div class="mb-3 form-group">
                <label for="email" class="form-label">Username</label>
                <input type="text" class="form-control" id="email" name="username" placeholder="Enter username"
                       autofocus
                       data-rules="required"
                >
              </div>
              <div class="mb-3 form-password-toggle form-group">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Password</label>
                </div>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" placeholder="Password"
                         aria-describedby="password"
                         data-rules="required"
                  />
                  <span class="input-group-text cursor-pointer" id="toggle_password">
                    <i class="ti ti-eye-off"></i></span>
                </div>
              </div>
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember_me" value="1" id="remember-me">
                  <label class="form-check-label" for="remember-me">
                    Remember Me
                  </label>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
              </div>
            </x-form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      $('#toggle_password').on('click', function () {
        const type = $('#password').attr('type') === 'password' ? 'text' : 'password'
        $('#password').attr('type', type)
        $(this).find('i').toggleClass('ti-eye ti-eye-off')
      })
    })
  </script>
@endsection
