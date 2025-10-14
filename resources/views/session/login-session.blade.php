@extends('layouts.user_type.guest')

@section('content')

<main class="main-content mt-0">
  <section>
    <div class="page-header min-vh-75">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto animate__animated animate__fadeInUp animate__faster">
            <div class="card card-plain mt-8 shadow-lg rounded-3 transition-all duration-300 hover:scale-[1.02]">
              <div class="card-header pb-0 text-left bg-transparent animate__animated animate__fadeInDown animate__delay-1s">
                <h3 class="font-weight-bolder text-info text-gradient">Access Your Cloud</h3>
                <p class="mb-0">Secure your new digital space.<br></p>
                <p class="mb-0">OR Sign in with this demo account:</p>
                <p class="mb-0">Email <b>demo@cyberdrive.io</b></p>
                <p class="mb-0">Password <b>filesafe</b></p>
              </div>

              <div class="card-body animate__animated animate__fadeIn animate__delay-1s">
                <form role="form" method="POST" action="/session">
                  @csrf
                  <label>Email</label>
                  <div class="mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="admin@softui.com" aria-label="Email">
                    @error('email')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <label>Password</label>
                  <div class="mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="secret" aria-label="Password">
                    @error('password')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0 transition-all duration-300 hover:scale-105 hover:shadow-lg">Sign in</button>
                  </div>
                </form>
              </div>

              <div class="card-footer text-center pt-0 px-lg-2 px-1 animate__animated animate__fadeInUp animate__delay-2s">
                <small class="text-muted">
                  Forgot your password? Reset it 
                  <a href="/login/forgot-password" class="text-info text-gradient font-weight-bold">here</a>
                </small>
                <p class="mb-4 text-sm mx-auto">
                  Don't have an account?
                  <a href="register" class="text-info text-gradient font-weight-bold">Sign up</a>
                </p>
              </div>
            </div>
          </div>

          <div class="col-md-6 animate__animated animate__fadeIn animate__delay-1s">
            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
              <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" 
                   style="background-image:url('../assets/img/curved-images/curved6.jpg'); 
                          animation: zoomInBg 10s ease-in-out infinite alternate;">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

{{-- Tambahkan Animate.css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

{{-- Animasi tambahan custom --}}
<style>
@keyframes zoomInBg {
  from { transform: scale(1); filter: brightness(0.9); }
  to { transform: scale(1.05); filter: brightness(1); }
}
</style>

@endsection
