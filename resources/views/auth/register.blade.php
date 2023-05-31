@extends('layouts.app')

@section('content')
{{--  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>

                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="apellido" class="col-md-4 col-form-label text-md-end">{{ __('Apellidos') }}</label>

                            <div class="col-md-6">
                                <input id="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required autocomplete="apellido" autofocus>

                                @error('apellido')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="correo" class="col-md-4 col-form-label text-md-end">{{ __('Correo') }}</label>

                            <div class="col-md-6">
                                <input id="correo" type="email" class="form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo') }}" required autocomplete="correo">

                                @error('correo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="contrasena" class="col-md-4 col-form-label text-md-end">{{ __('Contrasena') }}</label>

                            <div class="col-md-6">
                                <input id="contrasena" type="password" class="form-control @error('contrasena') is-invalid @enderror" name="contrasena" required autocomplete="new-contrasena">

                                @error('contrasena')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
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
               <div class="col-lg-6">
                  <div class="card auth-card">
                     <div class="card-body p-0">
                        <div class="d-flex align-items-center auth-content">
                           <div class="col-lg-12 align-self-center">
                              <div class="p-3">
                                 <h2 class="mb-2">Registro</h2>
                                 <p>Crea tu cuenta y únete a nosotros</p>
                                 <form method="POST" action="{{ route('register') }}">
                                 @csrf
                                    <div class="row">
                                       <div class="col-lg-6">
                                          <div class="floating-label form-group">
                                             {{--  <input class="floating-input form-control" type="text" placeholder=" ">  --}}
                                             <input id="nombre" type="text" class="floating-input form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>
                                             <label>Nombre</label>
                                             @error('nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="floating-label form-group">
                                             {{--  <input class="floating-input form-control" type="text" placeholder=" ">  --}}
                                             <input id="apellido" type="text" class="floating-input form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required autocomplete="apellido" autofocus>
                                             <label>Apellidos</label>
                                             @error('apellido')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                          </div>
                                       </div>
                                       <div class="col-lg-12">
                                          <div class="floating-label form-group">
                                             {{--  <input class="floating-input form-control" type="email" placeholder=" ">  --}}
                                             <input id="correo" type="email" class="floating-input form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo') }}" required autocomplete="correo">
                                             <label>Correo</label>
                                             @error('correo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="floating-label form-group">
                                             {{--  <input class="floating-input form-control" type="password" placeholder=" ">  --}}
                                             <input id="contrasena" type="password" class="floating-input form-control @error('contrasena') is-invalid @enderror" name="contrasena" required autocomplete="new-contrasena">
                                             <label>Contraseña</label>
                                             @error('contrasena')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="floating-label form-group">
                                            <input id="password-confirm" type="password" class="floating-input form-control" name="password_confirmation" required autocomplete="new-password">
                                             {{--  <input class="floating-input form-control" type="password" placeholder=" ">  --}}
                                             <label>Confirmar contraseña</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-12">
                                          <div class="custom-control custom-checkbox mb-3">
                                             <input type="checkbox" class="custom-control-input" id="customCheck1">
                                             <label class="custom-control-label" for="customCheck1">Acepto los términos y condiciones</label>
                                          </div>
                                       </div>
                                    </div>
                                    <button type="submit" class="btn btn-secondary">Registrarme</button>
                                    <p class="mt-3">
                                       Ya tengo una cuenta <a href="{{ route('login') }}" class="text-primary">Iniciar sesión</a>
                                    </p>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
    </div>

<!-- app JavaScript -->
<script src="{{ asset('js/backend-bundle.min.js') }}"></script>
<script src="{{ asset('js/table-treeview.js') }}"></script>
<script src="{{ asset('js/customizer.js') }}"></script>
<script async src="{{ asset('js/chart-custom.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

@endsection
