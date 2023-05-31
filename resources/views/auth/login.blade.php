@extends('layouts.app')

@section('content')
{{--  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="correo" class="col-md-4 col-form-label text-md-end">{{ __('Correo') }}</label>

                            <div class="col-md-6">
                                <input id="correo" type="email" class="form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo') }}" required autocomplete="correo" autofocus>

                                @error('correo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="contrasena" class="col-md-4 col-form-label text-md-end">{{ __('contrasena') }}</label>

                            <div class="col-md-6">
                                <input id="contrasena" type="password" class="form-control @error('contrasena') is-invalid @enderror" name="contrasena" required autocomplete="current-contrasena">

                                @error('contrasena')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('contrasena.request'))
                                    <a class="btn btn-link" href="{{ route('contrasena.request') }}">
                                        {{ __('Forgot Your contrasena?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>  --}}

<div class="wrapper bg-secondary">
<section class="login-content">
   <div class="container">
      <div class="row align-items-center justify-content-center height-self-center">
         <div class="col-lg-8">
            <div class="card auth-card">
               <div class="card-body p-0">
                  <div class="d-flex align-items-center auth-content">
                     <div class="col-lg-7 align-self-center">
                        <div class="p-3">
                           <h2 class="mb-2">Iniciar sesión</h2>
                           <p>Bienvenido a <b>FoodLab</b></p>
                           <form action="{{ route('login') }}" method="POST">
                           @csrf
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="floating-label form-group">
                                       <input id="correo" type="email" class="floating-input form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo') }}" required autocomplete="correo" autofocus>
                                       <label>Correo</label>
                                       @error('correo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="floating-label form-group">
                                       <input id="contrasena" type="password" class="floating-input form-control @error('contrasena') is-invalid @enderror" name="contrasena" minlength="8" required autocomplete="current-contrasena">
                                       <label>Contraseña</label>
                                       @error('contrasena')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                 </div>
                                 <div class="col-lg-6">
                                    {{-- <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                       <label class="custom-control-label control-label-1" for="remember">Recordar</label>
                                    </div> --}}
                                 </div>
                                 <div class="col-lg-6">
                                    <a href="{{ route('password.request') }}" class="text-primary float-right">Olvidé mi contraseña?</a>
                                 </div>
                              </div>

                              <button type="submit" class="btn btn-secondary">Iniciar<i class="ri-send-plane-2-line ml-2"></i></button>
                              <p class="mt-3">
                                 Crea una cuenta <a href="{{ route('register') }}" class="text-primary">Registrarme</a>
                              </p>
                           </form>
                        </div>
                     </div>
                     <div class="col-lg-5 content-right">
                        <img src="{{ asset('img/logotipos/ms-icon-310x310.png') }}" class="img-fluid image-right" alt="">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
</div>

@endsection
