@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                            <div>
                                <h4 class="mb-3">Historial</h4>
                                <p class="mb-0"></p>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="rounded mb-3">
                            <table class="data-tables table mb-0 tbl-server-info table-comandas">
                                <thead>
                                    <tr class="ligth ligth-data">
                                        <th data-orderable="false">Supervisor</th>
                                        <th>Folio </th>
                                        <th>Fecha </th>
                                        <th>Cliente<small class="text-muted"> Mesa </small></th>
                                        <th>Estatus </th>
                                        <th>Subtotal </th>
                                        <th data-orderable="false"> </th>
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
            <!-- Modal Edit -->
            <div class="modal fade" id="edit-note" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="popup text-left">
                                <div class="media align-items-top justify-content-between">
                                    <h3 class="mb-3">Product</h3>
                                    <div class="btn-cancel p-0" data-dismiss="modal"><i class="las la-times"></i></div>
                                </div>
                                <div class="content edit-notes">
                                    <div class="card card-transparent card-block card-stretch event-note mb-0">
                                        <div class="card-body px-0 bukmark">
                                            <div
                                                class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom">
                                                <div class="quill-tool">
                                                </div>
                                            </div>
                                            <div id="quill-toolbar1">
                                                <p>Virtual Digital Marketing Course every week on Monday, Wednesday and
                                                    Saturday.Virtual Digital Marketing Course every week on Monday</p>
                                            </div>
                                        </div>
                                        <div class="card-footer border-0">
                                            <div class="d-flex flex-wrap align-items-ceter justify-content-end">
                                                <div class="btn btn-primary mr-3" data-dismiss="modal">Cancel</div>
                                                <div class="btn btn-outline-primary" data-dismiss="modal">Save</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper End-->

    <script>
        var id_orden_global = 0;
        let orden = {
            id_orden: id_orden_global,
            productos: [],
            eliminados: []
        };

        function actualizarProductoOrden(idProducto, cantidad) {
            var existeProducto = false;
            // Busca si ya existe el producto en el array
            for (var i = 0; i < orden.productos.length; i++) {
                if (orden.productos[i].id === idProducto) {
                    orden.productos[i].cantidad = cantidad;
                    existeProducto = true;
                    break;
                }
            }

            // Si no existe el producto, lo agrega al array
            if (!existeProducto) {
                var producto = {
                    id: idProducto,
                    cantidad: cantidad
                };
                orden.productos.push(producto);
            }
            console.log(orden);
        }


        function agregarEliminadoProductoOrden(id) {
            // Verifica si el id ya existe en el array
            if (!orden.eliminados.includes(id)) {
                var indiceProducto = -1;
                for (var i = 0; i < orden.productos.length; i++) {
                    if (orden.productos[i].id === id) {
                        indiceProducto = i;
                        break;
                    }
                }
                // Si el producto existe en el array de productos, lo elimina
                if (indiceProducto !== -1) {
                    orden.productos.splice(indiceProducto, 1);
                }
                // Agrega el id del producto al array de eliminados
                orden.eliminados.push(id);
                var tabla = document.getElementById("table-update");
                // Obtener una referencia a la fila que desea eliminar por su id
                var fila = document.getElementById(`trid-${id}`);
                fila.parentNode.removeChild(fila);
            }
            console.log(orden);
        }


        $(document).ready(function() {
            $('[data-target="#modal-final"]').click(function() {
                consolo.log("entro");
                // Obtiene el valor de "data-id" y "data-subtotal" del bot車n que abrir el modal
                var id_orden = $(this).data('id');
                var orden_subtotal = $(this).data('subtotal');
                $('#subtotal').val(orden_subtotal);
                // Establece el valor del campo oculto "id_orden" en el formulario dentro del modal
                $('#modal-final').find('[name="id_orden"]').val(id_orden);
            });


            $('#modal-update').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Bot車n que activa el modal
                var id = button.data('id'); // Extrae el ID del atributo data-id
                id_orden_global = id;
                var modal = $(this);
                var nhtml = '';
                modal.find('.card-title').text('Actualizar orden #' + id); // Actualiza el t赤tulo del modal con el ID
                $.ajax({
                    url: 'historial/detalles',
                    method: 'POST',
                    dataType: "json",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "id": id
                    },
                    async: false,
                    success: function(response) {
                        var response = response.replace(/\\/g, "");
                        modal.find('.listado').html(response); // Actualiza el contenido del modal con los detalles
                        nhtml +=
                            '<div class="d-flex flex-wrap align-items-ceter justify-content-center">';
                        nhtml += '<button class="btn btn-outline-secondary edit-button-' + id +
                            ' mr-3">Editar</button>';
                        nhtml += '<button class="btn btn-secondary sav-button-' + id +
                            ' mr-3" style="display: none">Guardar</button>';
                        nhtml += '<button class="btn btn-outline-dark can-button-' + id +
                            ' mr-3" style="display: none">Cancelar</button>';
                        nhtml += '</div>';
                        console.log(nhtml);
                        modal.find('.listado-opc').html(nhtml);
                        id_orden_global = id;
                        orden.id_orden = id;
                        generar_botones(id);
                    }
                });
            });


            $('#form_actualizar_orden').submit(function(event) {
                event.preventDefault(); // Evita que el formulario se env赤e de forma convencional
                var formData = $(this)
                    .serialize(); // Serializa los datos del formulario para enviarlos a trav谷s de Ajax
                console.log(formData);
            });
            llenarTabla();
        });


        function llenarTabla() {
            let data = @json($tabla_historial, JSON_PRETTY_PRINT);
            $("#tbody_ordenes").html(data);
            generar_eliminar();
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
            xhr.open('POST', '../bin/recibo_pdf.php');
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
                        '<html><head><title>Recibo</title><style>button { display: block; width: 100%; padding: 10px; margin-bottom: 10px; }</style></head><body>'
                    );
                    if (isMobile) {
                        // Crear el objeto Blob con los datos del PDF
                        var blob = new Blob([this.response], {
                            type: 'application/pdf'
                        });
                        // Crear un objeto URL para el Blob
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

        function generar_eliminar() {
            toastr.options = {
                positionClass: 'toast-bottom-left',
                timeOut: '500'
            }
            $('.btn-eliminar').on('click', function() {
                var id_orden = this.getAttribute('data-id');
                swal({
                        title: 'Desea eliminar la orden #' + id_orden + '?',
                        text: "No podrá restablecer la información.",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Eliminar',
                        confirmButtonColor: '#ff3800',
                        cancelButtonText: 'Cancelar',
                        closeOnConfirm: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: 'historial/eliminar',
                                method: 'POST',
                                dataType: "json",
                                data: {
                                    "_token": $('meta[name="csrf-token"]').attr('content'),
                                    'id_orden': id_orden
                                },
                                async: false,
                                success: function(response) {
                                    console.log(response);
                                    // Aquí puedes realizar acciones en función de la respuesta recibida
                                        if(response  == 'success'){
                                            location.reload();
                                        }else{
                                            toastr.info('No se ha eliminado correctamente.');
                                        }
                                }
                            });
                        }
                    });
            });
        }

        function generar_botones(idbon) {
            const inputNumber = document.querySelectorAll("input[name='update_cant']");
            inputNumber.forEach(function(item) {
                let wrapper = document.createElement('div');
                wrapper.classList.add("quantity-buttons")
                item.parentNode.insertBefore(wrapper, item);
                wrapper.appendChild(item);
                item.insertAdjacentHTML('beforebegin',
                    '<button type="button" class="btn btn-outline-secondary mr-1 btn-sm minus-button" disabled><i class="ri-subtract-fill m-0"></i></button>'
                );
                item.insertAdjacentHTML('afterend',
                    '<button type="button" class="btn btn-outline-secondary ml-1 btn-sm plus-button" disabled><i class="ri-add-fill m-0"></i></button>'
                );
            });

            const plusButton = document.querySelectorAll(".plus-button");
            plusButton.forEach(function(btn) {
                btn.addEventListener('click', function(element) {
                    let inputNumber = this.previousElementSibling;
                    inputNumber.stepUp();
                    let change = new Event("change");
                    inputNumber.dispatchEvent(change);
                    const input = btn.parentElement.querySelector('input');
                    const id = input.dataset.id;
                    var cantInput = document.getElementById(`idinput-${id}`);
                    var cant = cantInput.value;

                    actualizarProductoOrden(id, cant);
                })
            });

            const minusButton = document.querySelectorAll(".minus-button");
            minusButton.forEach(function(btn) {
                btn.addEventListener('click', function(element) {
                    let inputNumber = this.nextElementSibling;
                    inputNumber.stepDown();
                    let change = new Event("change");
                    inputNumber.dispatchEvent(change);
                    const input = btn.parentElement.querySelector('input');
                    const id = input.dataset.id;
                    var cantInput = document.getElementById(`idinput-${id}`);
                    var cant = cantInput.value;
                    actualizarProductoOrden(id, cant);
                })
            });

            const buttonsDel = document.querySelectorAll('.btn-eliminar-orden');
            const cancelTd = document.querySelectorAll('.td-delete-' + idbon);
            const editButton = document.querySelector('.edit-button-' + idbon);
            const saveButton = document.querySelector('.sav-button-' + idbon);
            const cancelButton = document.querySelector('.can-button-' + idbon);

            editButton.addEventListener('click', function() {
                minusButton.forEach(btn => btn.disabled = false);
                plusButton.forEach(btn => btn.disabled = false);
                editButton.style.display = "none";
                cancelTd.forEach(function(cancel) {
                    cancel.style.display = "table-cell";
                });
                saveButton.style.display = "block";
                cancelButton.style.display = "block";

            });

            cancelButton.addEventListener('click', function() {
                minusButton.forEach(btn => btn.disabled = true);
                plusButton.forEach(btn => btn.disabled = true);
                editButton.textContent = 'Editar';
                editButton.style.display = "block";
                cancelTd.forEach(function(cancel) {
                    cancel.style.display = "none";
                });
                saveButton.style.display = "none";
                cancelButton.style.display = "none";
                $.ajax({
                    url: 'historial/detalles',
                    method: 'POST',
                    dataType: "json",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "id": id_orden_global
                    },
                    async: false,
                    success: function(response) {
                        var response = response.replace(/\\/g, "");
                        $('#modal-update').find('.listado').html(""); // Actualiza el contenido del modal con los detalles del usuario
                        $('#modal-update').find('.listado').html(response); // Actualiza el contenido del modal con los detalles del usuario
                        generar_botones(idbon);
                    }
                });
            });


            saveButton.addEventListener('click', function() {
                $("#modal-update").modal("hide");
                swal({
                        title: 'Desea guardar los cambios?',
                        text: "No podrá restablecer la información.",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#2778c4',
                        cancelButtonText: 'Cancelar',
                        cancelButtonColor: '#ff3800',
                        closeOnConfirm: true
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: 'historial/editar',
                                method: 'POST',
                                dataType: "json",
                                data: {
                                    "_token": $('meta[name="csrf-token"]').attr('content'),
                                    'orden': JSON.stringify(orden)
                                },
                                async: false,
                                success: function(response) {
                                    if(response  == 'success'){
                                        Swal.fire({
                                        title: 'Orden actualizada',
                                        icon: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Aceptar',
                                        allowOutsideClick: false
                                    }).then((resultado) => {
                                        if (resultado.isConfirmed) {
                                            window.location.reload();
                                        }
                                    })
                                    }else{
                                        toastr.info('No se ha eliminado correctamente.');
                                    }
                                }
                            });
                        } else {

                        }
                    });
            });

            buttonsDel.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    agregarEliminadoProductoOrden(id);
                });
            });

        }
    </script>
@endsection
