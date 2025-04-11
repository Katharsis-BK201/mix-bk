@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="app-brand-text demo text-body fw-bold">{{config('variables.templateName')}}</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">Welcome to {{config('variables.templateName')}}! 👋</h4>
          <p class="mb-4">Please sign-in to your account and start the adventure</p>

          <form id="formAuthentication" class="mb-3">
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus>
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
                <a href="{{url('auth/forgot-password-basic')}}">
                  <small>Forgot Password?</small>
                </a>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember-me">
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>
          <div class="text-center">
            <span class="d-block mb-2">OR</span>
          </div>
          <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{route('auth.redirect','google')}}" class="btn btn-outline-google d-flex align-items-center gap-2 px-4 py-2">
              <i class="bx bxl-google fs-4"></i>
              <span class="fw-semibold">Sign in with Google</span>
            </a>

            <a href="{{route('auth.redirect', 'facebook')}}" class="btn btn-outline-facebook d-flex align-items-center gap-2 px-4 py-2">
              <i class="bx bxl-facebook fs-4"></i>
              <span class="fw-semibold">Sign in with Facebook</span>
            </a>
          </div>

          {{-- <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{url('auth/register-basic')}}">
              <span>Create an account</span>
            </a>
          </p> --}}
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
</div>
<script>
  $(document).ready(function() {
    $('#formAuthentication').submit(function(e){
      e.preventDefault();

      $.ajax({
        url: '{{route('login')}}',
        type: 'POST',
        data: $(this).serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.status == 'success') {
            window.location.href = response.redirect;
          } else {
            Swal.fire({
              title: "Error!",
              text: response.message,
              icon: "error"
            });


          }
        },
        error: function(xhr) {
          Swal.fire({
            title: "Error!",
            text: xhr.responseJSON.message,
            icon: "error"
          });

        }
      });
    });
  });
</script>
@endsection
