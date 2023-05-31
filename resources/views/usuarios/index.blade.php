@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="content-page">
     <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Usuarios</h4>
                    </div>
                    <div>
                    <a href="#" onclick="document.getElementById('crear-form-user').reset();" class="btn btn-secondary add-list" data-toggle="modal" data-target="#new-user"><i class="las la-plus mr-2"></i>Nuevo usuario</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="rounded mb-3">
                <table class="data-tables table mb-0 tbl-server-info table-usuarios">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th data-orderable="false"></th>
                            <th>Nombre </th>
                            <th>Apellido </th>
                            <th>Correo </th>
                            <th>Rol </th>
                            <th>Creación </th>
                            <th data-orderable="false"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_usuarios" class="ligth-body">
                        @foreach($usuarios as $key => $usuario)
                                <tr>
                                <td class="td-figure"><figure class="avatar avatar-sm">
                                    <span class="avatar-title {{$usuario->nombre_rol == 'Admin' ? 'bg-warning' : ($usuario->nombre_rol == 'Gerente' ? 'bg-info' : 'bg-success')}} text-white rounded-circle">{{
                                        substr($usuario->nombre, 0, 1) }}</span>
                                  </figure></td>
                                  <td class="td-nombre">
                                    <div class="nombre-container">
                                        <div class="usuario-detalles">
                                        <p class="m-0 mt-1">{{$usuario->nombre}} {{$usuario->apellido}}</p><p class="text-secondary m-0"> {{$usuario->correo}}</p><p class="text-muted small m-0">Creación: {{ date('d/m/Y', strtotime($usuario->created_at)) }}</p>
                                        <div class="mb-2 badge {{$usuario->nombre_rol == 'Admin' ? 'badge-warning' : ($usuario->nombre_rol == 'Gerente' ? 'badge-info' : 'badge-success')}}">
                                            {{$usuario->nombre_rol}}</div>
                                    </div>
                                    </div>
                                        <span class="usuario-nombre text-dark">{{$usuario->nombre}}</span>
                                     </td>
                                  <td class="td-apellido">{{$usuario->apellido}}</td>
                                  <td class="td-correo">{{$usuario->correo}}</td>
                                  <td class="td-rol"><div class="badge {{$usuario->nombre_rol == 'Admin' ? 'badge-warning' : ($usuario->nombre_rol == 'Gerente' ? 'badge-info' : 'badge-success')}}">
                                    {{$usuario->nombre_rol}}</div>
                                  </td>
                                  <td class="td-crea">{{ date('d/m/Y', strtotime($usuario->created_at)) }}</td>
                                  <td class="td-actions">
                                    <div class="d-flex align-items-center list-action">
                                        <a class="badge badge-yellow mr-2 pointer user-edit" data-toggle="modal" data-backdrop="static" data-target="#new-user" data-id="{{$usuario->id}}"><i class="ri-pencil-line mr-0 text-xl"></i></a>
                                    </div>
                                </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    </div>
    </div>
<!-- Wrapper End-->

<script>
    $(document).ready(function() {
        toastr.options = {positionClass: 'toast-bottom-left',timeOut: '500'}
            @if(session('ok'))
             toastr.info('Se ha realizado el registro.');
            @elseif(session('nook'))
            toastr.info('No se pudo realizar el registro.');
            @endif
            
        $('.data-tables').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            order: [[4, 'desc']]
        });
    });

    $('.user-edit').click(function() {
        var id_user = $(this).data('id');
        $.ajax({
            url: 'usuarios/detalles',
            method: 'POST',
            dataType: "json",
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "id": id_user
            },
            async: false,
            success: function(response) {
                console.log(response.nombre);
                document.getElementById('crear-title-user').textContent = 'Editar usuario';
                document.getElementById('crear-btn-user').innerText = 'Editar';
                var formuser = document.getElementById('crear-form-user');
                formuser.nombre.value = response.nombre;
                formuser.apellido.value = response.apellido;
                formuser.correo.value = response.correo;
                formuser.contrasena.value = response.contrasena;
                formuser.contrasena2.value = response.contrasena;
                formuser.roles.value = response.id_rol;
                formuser.id_user.value = response.id;
            },
        });
    });
</script>
@endsection
