@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                            <div>
                                <h4 class="mb-3">Comandas</h4>
                                <p class="mb-0"></p>
                            </div>
                            <div>
                                <a href="ordenes_rapida.php" class="btn btn-primary add-list"><i
                                        class="las la-tachometer-alt"></i>Orden rápida</a>
                                <a href="#" class="btn btn-secondary add-list" data-toggle="modal"
                                    data-target="#new-order"><i class="las la-plus mr-2"></i>Nueva comanda</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="rounded mb-3">
                            <table class="data-tables table mb-0 tbl-server-info table-comandas">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th data-orderable="false">Supervisor</th>
                                        <th>Folio </th>
                                        <th>Fecha </th>
                                        <th>Cliente<small class="text-muted"> Mesa </small></th>
                                        <th>Estatus </th>
                                        <th>Subtotal </th>
                                        <th data-orderable="false">Acciones</th>
                                        <th data-orderable="false">Finalizar</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_ordenes" class="ligth-body">

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

            $('#form_finalizar_orden').submit(function(event) {
                event.preventDefault(); // Evita que el formulario se envíe de forma convencional
                var formData = $(this).serialize(); // Serializa los datos del formulario para enviarlos a través de Ajax
                finalizarOrden(formData);
            });


            $('#modal-visor').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que activa el modal
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-title').text('Detalles Orden #' + id);
                $.ajax({
                    url: 'comandas/productos',
                    method: 'POST',
                    dataType: "json",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "id": id
                    },
                    async: false,
                    success: function(response) {
                        var response = response.replace(/\\/g, "");
                        modal.find('.listado_visor').html(response);
                        generar_botones();
                    },
                });
            });

            $('#form_actualizar_orden').submit(function(event) {
                event.preventDefault(); // Evita que el formulario se envíe de forma convencional
                var formData = $(this).serialize(); // Serializa los datos del formulario para enviarlos a través de Ajax
                console.log(formData);
            });
            llenarTabla();
        });


        function llenarTabla() {
            let data = @json($tabla_comandas, JSON_PRETTY_PRINT);
            // Asignar la tabla al div con id "tabla_ordenes"
            $("#tbody_ordenes").html(data);
            $('[data-target="#modal-final"]').click(function() {
                var id_orden = $(this).data('id');
                var orden_subtotal = $(this).data('subtotal');
                
                $('#modal-final').find('[name="id_orden"]').val(id_orden);
                $('#modal-final').find('[name="orden_subtotal"]').val(orden_subtotal);
            });
            $('.data-tables').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                order: [[1, 'desc']]
            });
        }


        function generarRecibo(idOrden) {
            // Hacer la petición AJAX para generar el PDF
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'comandas/recibo');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.responseType = 'blob';
            xhr.onload = function() {
                if (this.status === 200) {
                    // Crear el objeto Blob con los datos del PDF
                    var blob = new Blob([this.response], {
                        type: 'application/pdf'
                    });
                    // Crear un objeto URL para el Blob
                    var url = window.URL.createObjectURL(blob);
                    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                    var width = isMobile ? 320 : 800;
                    var height = isMobile ? 480 : 600;
                    var left = (screen.width - width) / 2;
                    var top = (screen.height - height) / 2;
                    var win = window.open('', 'Recibo', 'width=' + width + ',height=' + height + ',left=' + left +
                        ',top=' + top);
                    // Agregar HTML y CSS para la apariencia de los botones
                    win.document.write(
                        '<html><head><title>Recibo</title><style>button { display: block; width: 100%; padding: 10px; margin-bottom: 10px; }</style></head><body>');
                    if (isMobile) {
                        // Crear el objeto Blob con los datos del PDF
                        var blob = new Blob([this.response], {
                            type: 'application/pdf'
                        });
                        var url = window.URL.createObjectURL(blob);
                        window.open(url);
                    } else {
                        win.document.write('<button onclick="window.print();">Imprimir</button>');
                        win.document.write('<button onclick="window.close();">Cerrar</button>');
                    }
                    // Agregar el objeto PDF a la ventana emergente
                    win.document.write('<embed width="100%" height="100%" src="' + url + '" type="application/pdf" />');
                    win.document.write('</body></html>');
                }
            };
            xhr.send('id_orden=' + idOrden);
        }

        function finalizarOrden(formData) {
            var orden_subtotal = $('[name="orden_subtotal"]').val();
            var id_orden = $('[name="id_orden"]').val();
            if (orden_subtotal > 0) {
                $('#modal-final').modal('hide');
                swal({
                        title: 'Desea finalizar la orden #'+id_orden+'?',
                        text: "No podrá restablecer la información",
                        type: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#ff3800',
                        cancelButtonText: 'Cancelar',
                        closeOnConfirm: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: 'comandas/finalizar',
                                method: 'POST',
                                dataType: "json",
                                data: {
                                    "_token": $('meta[name="csrf-token"]').attr('content'),
                                    'formData': formData
                                },
                                async: false,
                                success: function(response) {
                                    console.log(response);
                                    // Aquí puedes realizar acciones en función de la respuesta recibida
                                    if(response  == 'success'){
                                        location.reload();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    alert('Error al finalizar la orden');
                                }
                            });
                        } else {
                            $('#modal-final').modal('show');
                        }
                    });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'No es posible finalizar cuentas con subtotales en $0.0',
                    confirmButtonColor: '#ff3800',
                    confirmButtonText: 'Aceptar'
                });
            }
        }

        function generar_botones() {
            //Selecciona todos los inputs que pertenecen a la clase update_cant para insertar los botones de más y menos
            const inputNumber = document.querySelectorAll("input[name='update_cant']");
            inputNumber.forEach(function(item) {
                let wrapper = document.createElement('div');
                wrapper.classList.add("quantity-buttons")
                item.parentNode.insertBefore(wrapper, item);
                wrapper.appendChild(item);
                item.insertAdjacentHTML('beforebegin',
                    '<button type="button" class="btn btn-outline-secondary mr-1 btn-sm minus-button" disabled><i class="ri-subtract-fill m-0"></i></button>');
                item.insertAdjacentHTML('afterend',
                    '<button type="button" class="btn btn-outline-secondary ml-1 btn-sm plus-button" disabled><i class="ri-add-fill m-0"></i></button>');
            });

            //Se asigna la función que tendrán los botones de más
            const plusButton = document.querySelectorAll(".plus-button");
            plusButton.forEach(function(btn) {
                btn.addEventListener('click', function(element) {
                    let inputNumber = this.previousElementSibling;
                    inputNumber.stepUp();
                    let change = new Event("change");
                    inputNumber.dispatchEvent(change);
                })
            });

            //Se asigna la función que tendrán los botones de menos
            const minusButton = document.querySelectorAll(".minus-button");
            minusButton.forEach(function(btn) {
                btn.addEventListener('click', function(element) {
                    let inputNumber = this.nextElementSibling;
                    inputNumber.stepDown();
                    let change = new Event("change");
                    inputNumber.dispatchEvent(change);
                })
            });

            //Se generan los botones para la sección de view (detalles de la comanda)
            const editButton = document.querySelector('.edit-button');
            const saveButton = document.querySelector('.sav-button');
            const cancelButton = document.querySelector('.can-button');
            editButton.addEventListener('click', function() {
                minusButton.forEach(btn => btn.disabled = false);
                plusButton.forEach(btn => btn.disabled = false);
                editButton.textContent = 'Listo';
                editButton.style.display = "none";
                saveButton.style.display = "block";
                cancelButton.style.display = "block";
            });
            cancelButton.addEventListener('click', function() {
                minusButton.forEach(btn => btn.disabled = true);
                plusButton.forEach(btn => btn.disabled = true);
                editButton.textContent = 'Editar';
                editButton.style.display = "block";
                saveButton.style.display = "none";
                cancelButton.style.display = "none";
            });
        }

    </script>
@endsection
