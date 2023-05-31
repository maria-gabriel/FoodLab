<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="shortcut icon" href="{{ asset('img/logotipos/favicon-96x96-2.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backend.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/remixicon/fonts/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

</head>

<body>
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <div id="app">
        @guest
            <!-- @if (Route::has('login'))
    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
    @endif

                @if (Route::has('register'))
    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
    @endif -->
        @else
            <div class="iq-sidebar  sidebar-default ">
                <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
                    <a href="../vis/index.php" class="header-logo">
                        <img src="{{ asset('img/logotipos/ms-icon-150x150.png') }}"
                            class="img-fluid rounded-normal light-logo" alt="logo">
                        <h5 class="logo-title text-dark light-logo ml-2">FoodLab</h5>
                    </a>
                    <div class="iq-menu-bt-sidebar">
                        <i class="las la-bars wrapper-menu pointer"></i>
                    </div>
                </div>
                <hr class="hr-1">
                <div class="data-scrollbar" data-scroll="1">
                    <nav class="iq-sidebar-menu">
                        <ul id="iq-sidebar-toggle" class="iq-menu">
                            <li class="{{ request()->is('home') ? 'active' : '' }}">
                                <a href="{{ route('home') }}" class="svg-icon">
                                    <svg class="svg-icon svg-menu" id="p-dash1" width="20" height="20"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                    <span class="ml-4">Inicio</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->is('comandas') ? 'active' : '' }} {{ request()->is('comandas.editar') ? 'active' : '' }}">
                                <a href="{{ route('comandas') }}" class="svg-icon">
                                    <svg class="svg-icon svg-menu" id="p-dash7" width="20" height="20"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    <span class="ml-4">Comandas</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('historial') ? 'active' : '' }}">
                                <a href="{{ route('historial') }}" class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock svg-menu">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span class="ml-4">Historial</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('ventas') ? 'active' : '' }}">
                                <a href="{{ route('ventas') }}" class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-check-circle svg-menu">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                        <polyline points="22 4 12 14.01 9 11.01" />
                                    </svg>
                                    <span class="ml-4">Ventas</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('usuarios') ? 'active' : '' }}">
                                <a href="{{ route('usuarios') }}" class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-users svg-menu">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <span class="ml-4">Usuarios</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!--<div id="sidebar-bottom" class="position-relative sidebar-bottom">-->
                    <!--    <div class="card border-none">-->
                    <!--        <div class="card-body p-0">-->
                    <!--            <div class="sidebarbottom-content">-->
                    <!--                <div class="image"><img src="../assets/img/layouts/side-bkg.png" class="img-fluid" alt="side-bkg"></div>-->
                    <!--                <h6 class="mt-4 px-4 body-title">Get More Feature by Upgrading</h6>-->
                    <!--                <button type="button" class="btn sidebar-bottom-btn mt-4">Go Premium</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="p-3"></div>-->
                </div>
            </div>
            <div class="iq-top-navbar">
                <div class="iq-navbar-custom">
                    <nav class="navbar navbar-expand-lg navbar-light p-0">
                        <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                            <i class="ri-menu-line wrapper-menu"></i>
                            <a href="../vis/index.php" class="header-logo">
                                <img src="{{ asset('img/logotipos/ms-icon-150x150.png') }}"
                                    class="img-fluid rounded-circle" alt="logo">
                                <h6 class="logo-title text-white ml-1 mb-0 mt-1">FoodLab</h6>

                            </a>
                        </div>
                        <div class="iq-search-bar device-search">
                            <!-- <form action="#" class="searchbox">
                              <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                              <input type="text" class="text search-input" placeholder="Search here...">
                          </form> -->
                        </div>
                        <div class="d-flex align-items-center">
                            <!--<button class="navbar-toggler" type="button" data-toggle="collapse"-->
                            <!--    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"-->
                            <!--    aria-label="Toggle navigation">-->
                            <!--    <i class="ri-menu-3-line"></i>-->
                            <!--</button>-->
                            <button class="navbar-toggler" id="dropdownMenuButton4" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ri-menu-3-line"></i>
                            </button>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 text-center">
                                        <div class="media-body profile-detail text-center mt-5 p-3">
                                            <img src="https://catbobber.com/wp-content/uploads/2022/05/web-1.png"
                                                alt="profile-img" class="rounded profile-img img-fluid avatar-70">
                                        </div>
                                        <div class="p-3">
                                            <h5 class="mb-1">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
                                            </h5>
                                            <p class="mb-0">{{ Auth::user()->correo }}</p>
                                            <div class="d-flex align-items-center justify-content-center mt-3">
                                                <a href="{{ route('perfil') }}"
                                                    class="btn btn-outline-secondary mr-2">Perfil</a>
                                                <button type="button" class="btn btn-danger btn-exit">Salir</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto navbar-list align-items-center">
                                    <li>
                                        <!--<a href="#" class="btn border text-white add-btn shadow-none mx-2 d-none d-md-block"-->
                                        <!--    data-toggle="modal" data-target="#new-order"><i class="las la-plus mr-2"></i>Orden rápida</a>-->
                                    </li>
                                    <li class="nav-item nav-icon search-content">
                                        <!--<a href="#" class="search-toggle rounded" id="dropdownSearch" data-toggle="dropdown"-->
                                        <!--    aria-haspopup="true" aria-expanded="false">-->
                                        <!--    <i class="ri-search-line"></i>-->
                                        <!--</a>-->
                                        <div class="iq-search-bar iq-sub-dropdown dropdown-menu"
                                            aria-labelledby="dropdownSearch">
                                            <form action="#" class="searchbox p-2">
                                                <div class="form-group mb-0 position-relative">
                                                    <input type="text" class="text search-input font-size-12"
                                                        placeholder="type here to search...">
                                                    <a href="#" class="search-link"><i
                                                            class="las la-search"></i></a>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                    <li class="nav-item nav-icon dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                            <path
                                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                            </path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                        <span class="text-white">{{ Auth::user()->correo }}</span>                                        
                                    </li>
                                    <li class="nav-item nav-icon dropdown caption-content">
                                        <a href="#" class="search-toggle dropdown-toggle"
                                            id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <img src="https://catbobber.com/wp-content/uploads/2022/05/letra-amarilla-1.png"
                                                class="rounded" alt="user">
                                        </a>
                                        <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <div class="card shadow-none m-0">
                                                <div class="card-body p-0 text-center">
                                                    <div class="media-body profile-detail text-center mt-5 p-3">
                                                        <img src="https://catbobber.com/wp-content/uploads/2022/05/web-1.png"
                                                            alt="profile-img"
                                                            class="rounded profile-img img-fluid avatar-70">
                                                    </div>
                                                    <div class="p-3">
                                                        <h5 class="mb-1">{{ Auth::user()->nombre }}
                                                            {{ Auth::user()->apellido }}</h5>
                                                        <p class="mb-0">{{ Auth::user()->correo }}</p>
                                                        <div
                                                            class="d-flex align-items-center justify-content-center mt-3">
                                                            <a href="{{ route('perfil') }}"
                                                                class="btn btn-outline-secondary mr-2">Perfil</a>
                                                            <button type="button"
                                                                class="btn btn-danger btn-exit">Salir</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="modal fade" id="new-order" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="popup text-left">
                                <h4 class="mb-3">Nueva Comanda</h4>
                                <form id="crear-form" action="{{ route('comandas.crear') }}" method="POST" enctype='multipart/form-data'>
                                    @csrf
                                    <div class="content create-workform bg-body">
                                        <div class="pb-3">
                                            <input type="text" class="form-control" placeholder="Cliente"
                                                name="cliente">
                                        </div>
                                        <div class="pb-1">
                                            <input type="text" class="form-control" placeholder="No. mesa"
                                                name="mesa">
                                        </div>
                                        <span id="crear-mns" class="invalid-feedback pl-1" role="alert">
                                            <strong>Debe ingresar al menos uno de los campos.</strong>
                                        </span>
                                        <div class="col-lg-12 mt-4">
                                            <div class="d-flex flex-wrap align-items-ceter justify-content-center">
                                                <button id="crear-btn" type="submit" class="btn btn-secondary mr-4">Crear</button>
                                                <div class="btn btn-outline-dark" data-dismiss="modal">Cancelar</div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="new-user" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="popup text-left">
                                <h4 id="crear-title-user" class="mb-3">Nuevo usuario</h4>
                                <form id="crear-form-user" action="{{ route('usuarios.crear') }}" method="POST" enctype='multipart/form-data'>
                                    @csrf
                                    <div class="content create-workform bg-body">
                                        <div class="pb-1">
                                            <input type="text" class="form-control" placeholder="Nombre"
                                                name="nombre" required>
                                        </div>
                                        <div class="pb-1">
                                            <input type="text" class="form-control" placeholder="Apellidos"
                                                name="apellido" required>
                                        </div>
                                        <div class="pb-1">
                                            <input type="email" class="form-control" placeholder="Correo"
                                                name="correo" required>
                                            <span id="crear-mns3-user" class="invalid-feedback pl-1" role="alert">
                                                <strong>El correo ya se encuentra registrado.</strong>
                                            </span>
                                        </div>
                                        <div class="pb-1">
                                            <input type="password" class="form-control" placeholder="Contraseña"
                                                name="contrasena" required>
                                        </div>
                                        <div class="pb-1">
                                            <input type="password" class="form-control" placeholder="Repetir contraseña"
                                                name="contrasena2" required>
                                            <span id="crear-mns-user" class="invalid-feedback pl-1" role="alert">
                                                <strong>Las contraseñas no coinciden.</strong>
                                            </span>
                                        </div>
                                        <div class="pb-1 pl-1">
                                        <p class="mb-0">Seleccione el rol del usuario:</p>
                                        @php
                                            $resultado_roles = DB::select('SELECT * FROM rol');
                                        @endphp
                                        @foreach ($resultado_roles as $key => $rol)
                                        <div class="custom-control custom-radio custom-radio-color custom-control-inline">
                                            <input type="radio" id="{{$rol->id}}" value="{{$rol->id}}" name="roles" class="custom-control-input bg-warning">
                                            <label class="custom-control-label" for="{{$rol->id}}"> {{$rol->nombre}} </label>
                                         </div>
                                         @endforeach
                                         <span id="crear-mns2-user" class="invalid-feedback pl-1" role="alert">
                                            <strong>Debe seleccionar al menos un rol.</strong>
                                        </span>
                                        </div>
                                        <input type="hidden" name="id_user" value="">
                                        <div class="col-lg-12 mt-4">
                                            <div class="d-flex flex-wrap align-items-ceter justify-content-center">
                                                <button id="crear-btn-user" type="submit" class="btn btn-secondary mr-4">Crear</button>
                                                <div class="btn btn-outline-dark" data-dismiss="modal">Cancelar</div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-final" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="popup text-left">
                                <h4 class="mb-3">Finalizar</h4>
                                <form id="form_finalizar_orden">
                                    <div class="content create-workform bg-body">
                                        <div class="pb-3" style="display: none;">
                                            <input type="number" class="form-control" name="id_orden"
                                                min="0">
                                        </div>

                                        <div class="pb-3" style="display: none;">
                                            <input type="number" class="form-control" name="orden_subtotal"
                                                min="0">
                                        </div>

                                        <div class="pb-3">
                                            <select class="form-control mb-3" name="metodo_pago" required>
                                                <option value="">Metodo de Pago</option>
                                                <option value="1">Efectivo</option>
                                                <option value="2">Tarjeta</option>
                                                <option value="3">Transferencia</option>
                                                <option value="4">Pendiente</option>
                                            </select>
                                        </div>
                                        <label>Propina</label>
                                        <div class="pb-3">
                                            <div class="input-group mb-4">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-orange" id="basic-addon1">$</span>
                                                </div>

                                                <input type="number" class="form-control" name="propina"
                                                    min="0" value="0.0" aria-label="Username"
                                                    aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-4">
                                            <div class="d-flex flex-wrap align-items-ceter justify-content-center">
                                                <button type="submit" class="btn btn-secondary mr-4">Aceptar</button>
                                                <div class="btn btn-outline-dark" data-dismiss="modal">Cancelar</div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-update" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="card-header d-flex justify-content-between py-2">
                            <div class="header-title">
                                <h4 class="card-title mt-2 mb-0">Actualizar</h4>
                            </div>
                            <div>
                                <div class="btn btn-outline-dark btn-sm" data-dismiss="modal"><i
                                        class="ri-close-fill m-0"></i></div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="popup text-left">

                                <form id="form_actualizar_orden">
                                    <div class="content create-workform bg-body">
                                        <div class="listado"></div>

                                        <div class="col-lg-12 mt-4 listado-opc">

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-visor" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="card-header d-flex justify-content-between py-2">
                            <div class="header-title">
                                <h4 class="card-title mt-2 mb-0">Detalles</h4>
                            </div>
                            <div>
                                <div class="btn btn-outline-dark btn-sm" data-dismiss="modal"><i
                                        class="ri-close-fill m-0"></i></div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="popup text-left">
                                <form id="form_visor_orden">
                                    <div class="content create-workform bg-body">
                                        <div class="listado_visor"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-opciones" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="popup text-left">
                                <h4 id="title-modal-opciones" class="modal-title mb-3"></h4>
                                <div class="container">
                                    <form>

                                        <div class="pb-3" style="display: none;">
                                            <input type="number" class="form-control" name="id_area_producto"
                                                min="0">
                                        </div>


                                        <div id="list-opciones-check">

                                        </div>

                                        <div id="list-opciones2-check">

                                        </div>

                                        <div class="form-group">
                                            <div class="d-flex my-2 justify-content-center">
                                                <div class="quantity-buttons">
                                                    <input id="cant_orden_actual" type="number" min="1"
                                                        value="1" step="1" readonly=""
                                                        class="input-modal">
                                                </div>
                                            </div>
                                            <div class="d-flex my-2 justify-content-center">
                                                <h3 id="total-opciones-check">$ 0.00</h3>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-4">
                                            <div class="d-flex flex-wrap align-items-center justify-content-center">
                                                <button onclick="aceptarOrden()" type="button"
                                                    class="btn btn-secondary mr-4">Aceptar</button>
                                                <div class="btn btn-outline-dark" data-dismiss="modal">Cancelar</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-test" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="card-header d-flex justify-content-between py-2">
                            <div class="header-title">
                                <h4 class="card-title mt-2 mb-0">Test</h4>
                            </div>
                            <div>
                                <div class="btn btn-outline-dark btn-sm" data-dismiss="modal"><i
                                        class="ri-close-fill m-0"></i></div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="popup text-left">
                                <form id="form_test_orden">
                                    <p class="res-test"></p>
                                    <div class="content create-workform bg-body">
                                        <p>Radio (obligatorio y puede deseleccionarse)</p>
                                        <div class="form-check">
                                            <div class="custom-control custom-radio custom-radio-color-checked">
                                                <input type="radio" id="radio-1" name="radio"
                                                    class="custom-control-input bg-warning un-sel" data-val="false">
                                                <label class="custom-control-label" for="radio-1">Radio uno</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-radio-color-checked">
                                                <input type="radio" id="radio-2" name="radio"
                                                    class="custom-control-input bg-warning un-sel" data-val="false">
                                                <label class="custom-control-label" for="radio-2">Radio dos</label>
                                            </div>
                                        </div>
                                        <p>ChecBox (al menos uno)</p>
                                        <div class="custom-control custom-checkbox custom-checkbox-color-check">
                                            <input type="checkbox" class="custom-control-input bg-warning min-one"
                                                id="customCheck-1">
                                            <label class="custom-control-label" for="customCheck-1">Opcion uno</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-checkbox-color-check">
                                            <input type="checkbox" class="custom-control-input bg-warning min-one"
                                                id="customCheck-2">
                                            <label class="custom-control-label" for="customCheck-2">Opcion dos</label>
                                        </div>
                                        <p>Números (al menos tres)</p>
                                        <div
                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                            <input type="checkbox" class="custom-control-input bg-warning min-two"
                                                id="customCheck-3">
                                            <label class="custom-control-label" for="customCheck-3">Cero</label>
                                        </div>
                                        <div
                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                            <input type="checkbox" class="custom-control-input bg-warning min-two"
                                                id="customCheck-4">
                                            <label class="custom-control-label" for="customCheck-4">Uno</label>
                                        </div>
                                        <div
                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                            <input type="checkbox" class="custom-control-input bg-warning min-two"
                                                id="customCheck-5">
                                            <label class="custom-control-label" for="customCheck-5">Dos</label>
                                        </div>
                                        <div
                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                            <input type="checkbox" class="custom-control-input bg-warning min-two"
                                                id="customCheck-6">
                                            <label class="custom-control-label" for="customCheck-6">Tres</label>
                                        </div>
                                        <div
                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                            <input type="checkbox" class="custom-control-input bg-warning min-two"
                                                id="customCheck-7">
                                            <label class="custom-control-label" for="customCheck-7">Cuatro</label>
                                        </div>

                                        <div class="col-lg-12 mt-4">
                                            <div class="d-flex flex-wrap align-items-ceter justify-content-center">
                                                <button class="btn btn-secondary sav-test mr-3">Enviar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endguest

        <main>
            @yield('content')
        </main>
    </div>
    <a class="goodbye invisible" href="{{ route('logout') }}"
        onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    
    <!-- <footer class="iq-footer">
            <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item"><a >www.catbobber.com</a></li>
                                <li class="list-inline-item"><a ></a></li>
                            </ul>
                        </div>
                        <div class="col-lg-6 text-right">
                            <span class="mr-1"><script>
                                document.write(new Date().getFullYear())
                            </script>©</span> <a  class="">FoodLab</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer> -->

    <!-- app JavaScript -->
    <script src="{{ asset('js/backend-bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/table-treeview.js') }}"></script>
    <script src="{{ asset('js/customizer.js') }}"></script>
    <script async src="{{ asset('js/chart-custom.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        //Formulario de validación de comandas
        var form = document.getElementById('crear-form');
        var boton = document.getElementById('crear-btn');
        var mns = document.getElementById('crear-mns');
        boton.addEventListener('click', function(e) {
            if (!form.cliente.value && !form.mesa.value) {
                e.preventDefault();
                mns.classList.add('d-block');
                setTimeout(() => {
                mns.classList.remove('d-block');
                }, 3000);
            }
        });

        //Formulario de validación de usuarios
        var formuser = document.getElementById('crear-form-user');
        var botonuser = document.getElementById('crear-btn-user');
        var mnsuser = document.getElementById('crear-mns-user');
        var mnsuser2 = document.getElementById('crear-mns2-user');
        var mnsuser3 = document.getElementById('crear-mns3-user');
        botonuser.addEventListener('click', function(e) {
        var radios = document.querySelectorAll('input[name="roles"]');

        if (formuser.correo.value && formuser.id_user.value == ''){
            $.ajax({
                url: 'usuarios/verificar',
                method: 'POST',
                dataType: "json",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "correo": formuser.correo.value
                },
                async: false,
                success: function(response) {
                    if(response == 'found'){
                        e.preventDefault();
                        mnsuser3.classList.add('d-block');
                        setTimeout(() => {
                        mnsuser3.classList.remove('d-block');
                        }, 4000);
                    }
                },
            });
        }

            if (formuser.contrasena.value != formuser.contrasena2.value) {
                e.preventDefault();
                formuser.contrasena.value = '';
                formuser.contrasena2.value = '';
                mnsuser.classList.add('d-block');
                setTimeout(() => {
                mnsuser.classList.remove('d-block');
                }, 4000);
            }
            let seleccionado_rad = false;
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    seleccionado_rad = true;
                    break;
                }
            }
            if (formuser.nombre.value && formuser.apellido.value && formuser.correo.value && formuser.contrasena.value && !seleccionado_rad) {
                e.preventDefault();
                mnsuser2.classList.add('d-block');
                setTimeout(() => {
                mnsuser2.classList.remove('d-block');
                }, 4000);
            }
        });


        $('.btn-exit').on('click', function() {
            swal({
                    title: 'Desea salir del sistema?',
                    text: "La sesión actual se cerrará y abandonará el sistema.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Salir',
                    confirmButtonColor: '#ff3800',
                    cancelButtonText: 'Cancelar',
                    closeOnConfirm: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $('.goodbye').click();
                    }
                });
        });

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }
    </script>
</body>

</html>
