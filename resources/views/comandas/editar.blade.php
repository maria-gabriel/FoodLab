@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="content-page">
            <div class="container-fluid add-form-list">
                <form action="page-list-purchase.html" data-toggle="validator">
                    <div class="row">
                        <div class="col-sm-12 col-lg-8 col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between py-2">
                                    <div class="header-title">
                                        <h4 class="card-title"><a class="badge badge-warning mr-4 pointer"
                                                href="javascript:history.back()" data-toggle="tooltip"
                                                data-placement="bottom" title data-original-title="Regresar"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                    viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-arrow-left">
                                                    <line x1="19" y1="12" x2="5" y2="12" />
                                                    <polyline points="12 19 5 12 12 5" />
                                                </svg></a>Orden</h4>
                                    </div>
                                    <div>
                                        <h6 class="text-muted m-0">
                                            <i class="ri-game-fill"></i>
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle></svg>
                                            @php echo $cliente @endphp
                                        </h6>
                                    </div>
                                </div>

                                <div class="card-body pt-2">
                                    <ul class="nav nav-tabs scroller" id="myTab-three" role="tablist">
                                        @foreach ($resultado_categorias as $key => $fila_categorias)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $key == 0 ? 'active' : '' }}"
                                                    id="panel-{{ $fila_categorias->id }}-tab-three" data-toggle="tab"
                                                    href="#panel-{{ $fila_categorias->id }}-three" role="tab"
                                                    aria-controls="panel-aria{{ $fila_categorias->id }}"
                                                    aria-selected="false">{{ $fila_categorias->nombre }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content" id="myTabContent-4">
                                        @php
                                            $resultado_categorias = DB::select('SELECT * FROM categorias ORDER BY posicion ASC');
                                        @endphp

                                        @foreach ($resultado_categorias as $key => $fila_categorias)
                                            <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}"
                                                id="panel-{{ $fila_categorias->id }}-three" role="tabpanel"
                                                aria-labelledby="panel-aria-{{ $fila_categorias->id }}-tab-three">
                                                <div class="row">
                                                    @php
                                                        $id_categoria = $fila_categorias->id;
                                                        $resultado_productos = DB::select("SELECT * FROM productos WHERE id_categoria = $id_categoria AND activo = 1 ORDER BY nombre ASC");
                                                    @endphp

                                                    @foreach ($resultado_productos as $fila_productos)
                                                        @if ($fila_productos->id_categoria == $fila_categorias->id)
                                                            <div class="col-4 col-md-2 col-lg-2 p-1">
                                                                <div class="card card-menu mb-2 no-shadow"
                                                                    onclick="clicProducto('{{ $fila_productos->id }}','{{ $fila_productos->opciones }}','{{ $fila_productos->nombre }}','{{ $fila_productos->id_area }}')">
                                                                    <div class="row no-gutters justify-content-center">
                                                                        <div class="col-9 mt-2 h-80 d-flex">
                                                                            @if ($fila_productos->foto !== null)
                                                                                <img src="{{ $fila_productos->foto }}"
                                                                                    class="card-img img-fluid mx-auto d-block align-self-center"
                                                                                    alt="#">
                                                                            @else
                                                                                <img src="https://catbobber.com/wp-content/uploads/2023/03/winebottlebeverage_vino_botella_4001.png"
                                                                                    class="card-img img-fluid mx-auto d-block align-self-center"
                                                                                    alt="#">
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-12 text-center">
                                                                            <p class="mb-1 mt-3 heading-title line-title">
                                                                                {{ $fila_productos->nombre }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-4 col-md-4 pl-0 d-none d-md-block">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between py-2">
                                    <div class="header-title">
                                        <h4 class="card-title">Productos</h4>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <ul id="detallesOrdenDesktop" class="list-group">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('img/productos/ordenvacia.png') }}" width="50%">
                                        </div>
                                    </ul>

                                    <button onclick="guardarOrden()" type="button"
                                        class="btn btn-secondary btn-block mt-3">Ordenar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Page end  -->
                <a class="badge badge-dark pointer mb-2" data-toggle="modal" data-target="#modal-test"><i
                        class="ri-skull-fill text-xl"></i></a>
            </div>
        </div>
    </div>
    <div class="floating-button open-button text-center d-flex d-md-none">
        <i class="ri-eye-fill"></i>
    </div>
    <div class="floating-button close-button text-center" style="display: none;">
        <i class="ri-eye-off-fill"></i>
    </div>

    <div id="floating-div" class="card floating-div m-0 px-2">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="header-title">
                <h4 class="card-title">Productos</h4>
            </div>
        </div>
        <div class="card-body px-0 py-2">
            <ul id="detallesOrdenMobile" class="list-group " style="height: calc(100vh - 330px); overflow-y: auto;">
                <div class="d-flex justify-content-center align-items-center">
                    <img src="{{ asset('img/productos/ordenvacia.png') }}" width="60%">
                </div>
            </ul>
            <button onclick="guardarOrden()" type="button" class="btn btn-secondary m-2"
                style="position: absolute;bottom: 0;left: 0;width: -webkit-fill-available;">Ordenar</button>
        </div>
    </div>

    <script>
        // Select all INPUTS with type NUMBER
        const inputNumberModal = document.querySelectorAll(".input-modal");
        inputNumberModal.forEach(function(item) {
            let wrapper = document.createElement('div');
            wrapper.classList.add("quantity-buttons")
            // insert wrapper before item in the DOM tree
            item.parentNode.insertBefore(wrapper, item);
            wrapper.appendChild(item);
            // Inser plus and minus buttons
            item.insertAdjacentHTML('beforebegin',
                '<button type="button" class="btn btn-outline-secondary mr-1 btn-sm minus-button"><i class="ri-subtract-fill m-0"></i></button>');
            item.insertAdjacentHTML('afterend',
                '<button type="button" class="btn btn-outline-secondary ml-1 btn-sm plus-button"><i class="ri-add-fill m-0"></i></button>');
        });
        // Plus Button
        const plusButton = document.querySelectorAll(".plus-button");
        plusButton.forEach(function(btn) {
            btn.addEventListener('click', function(element) {
                let inputNumberModal = this.previousElementSibling;
                inputNumberModal.stepUp();
                let change = new Event("change");
                inputNumberModal.dispatchEvent(change);
            })
        })
        // Minus Button
        const minusButton = document.querySelectorAll(".minus-button");
        minusButton.forEach(function(btn) {
            btn.addEventListener('click', function(element) {
                let inputNumberModal = this.nextElementSibling;
                inputNumberModal.stepDown();
                let change = new Event("change");
                inputNumberModal.dispatchEvent(change);
            })
        });

        const button = document.querySelector('.open-button');
        const floatingDiv = document.getElementById('floating-div');
        const closeButton = document.querySelector('.close-button');

        button.addEventListener('click', () => {
            floatingDiv.style.display = 'block';
            button.style.display = 'none';
            closeButton.style.display = 'flex';
            closeButton.style.alignItems = 'center';
        });
        closeButton.addEventListener('click', () => {
            floatingDiv.style.display = 'none';
            button.style.display = 'flex';
            button.style.alignItems = 'center';
            closeButton.style.display = 'none';
        });

        const params = new URLSearchParams(window.location.search);
        var url = window.location.href.split('/');
        const id_orden_global = url[url.length - 1];
        var id_producto_global = '';
        let orden = {
            id_orden: id_orden_global,
            productos: [],
            actualizarVistaComanda: function() {
                console.log('Se ha modificado la propiedad productos');
                this.detallesOrden();
            },
            detallesOrden: function() {
                var servername = window.location.hostname != 'localhost' ? '../../' : '/';
                $.ajax({
                    url: servername+'comandas/detalles',
                    method: 'POST',
                    dataType: "json",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "orden": JSON.stringify(this)
                    },
                    async: false,
                    success: function(response) {
                        var response = response.replace(/\\/g, "");
                        console.log(response);
                        $('#detallesOrdenMobile').html(response);
                        $('#detallesOrdenDesktop').html(response);
                        generar_botones_comanda();
                        generar_eliminar_orden();
                        console.log(response);
                        var elementoTemporal = $('<div></div>');
                        elementoTemporal.html(response);
                        var inputs = elementoTemporal.find('input');

                        $.each(inputs, function(index, input) {
                            var inputName = $(input).attr('name');
                            var dataId = $(input).attr('data-id');

                            var botonAnterior = $('input[name="' + inputName + '"]').prev('button');
                            var botonPosterior = $('input[name="' + inputName + '"]').next('button');
                            botonAnterior.click(function() {
                                restarCantidadProducto(dataId, 1);
                                //alert('Boton para restar a ' + inputName);
                            });
                            botonPosterior.click(function() {
                                sumarCantidadProducto(dataId, 1);
                                //alert('Boton para sumar a ' + inputName);
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al enviar la orden');
                    }
                });
            }
        };

        function generar_botones_comanda() {
            const inputNumber = document.querySelectorAll(".input-comanda");
            inputNumber.forEach(function(item) {
                let wrapper = document.createElement('div');
                wrapper.classList.add("quantity-buttons")
                item.parentNode.insertBefore(wrapper, item);
                wrapper.appendChild(item);
                item.insertAdjacentHTML('beforebegin',
                    '<button type="button" class="btn btn-outline-secondary mr-1 btn-sm minus-button-co" data-id="tu_valor_aqui"><i class="ri-subtract-fill m-0"></i></button>');
                item.insertAdjacentHTML('afterend',
                    '<button type="button" class="btn btn-outline-secondary ml-1 btn-sm plus-button-co" data-id="tu_valor_aqui"><i class="ri-add-fill m-0"></i></button>');
            });

            // Plus Button
            const plusButton = document.querySelectorAll(".plus-button-co");
            plusButton.forEach(function(btn) {
                btn.addEventListener('click', function(element) {
                    let inputNumber = this.previousElementSibling;
                    inputNumber.stepUp();
                    let change = new Event("change");
                    inputNumber.dispatchEvent(change);
                })
            })
            // Minus Button
            const minusButton = document.querySelectorAll(".minus-button-co");
            minusButton.forEach(function(btn) {
                btn.addEventListener('click', function(element) {
                    let inputNumber = this.nextElementSibling;
                    inputNumber.stepDown();
                    let change = new Event("change");
                    inputNumber.dispatchEvent(change);
                })
            });
        }


        function clicProducto(idProducto, opcion, nombre, idArea) {
            id_producto_global = idProducto;
            document.getElementById("list-opciones-check").innerHTML = "";
            document.getElementById("list-opciones2-check").innerHTML = "";
            $('#total-opciones-check').text("$ 0.0");
            $('#cant_orden_actual').val('1');
            $('#id_area_producto').val(idArea);
            if (opcion == 1) {
                $("#title-modal-opciones").text(nombre);
                var servername = window.location.hostname != 'localhost' ? '../../' : '/';
                $.ajax({
                    url: servername+'comandas/opciones',
                    method: 'POST',
                    dataType: "json",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        'id_producto': idProducto
                    },
                    async: false,
                    success: function(response) {
                        var response = response.replace(/\\/g, "");
                        console.log(response);
                        $('#list-opciones-check').html(response);
                    },
                    error: function(error) {
                        var mensaje = 'Ha ocurrido un error: ' + error;
                        alert(JSON.stringify(error));
                        $('#list-opciones-check').html(mensaje);
                    }
                });

                $('#modal-opciones').modal('show');
            } else {
                //var producto = this.getAttribute('data-name');
                // 		toastr.options = {
                //       "closeButton": true,
                //       "positionClass": "toast-bottom-left",
                //       "preventDuplicates": true,
                //       "showDuration": "300",
                //       "hideDuration": "1000",
                //       "timeOut": 0,
                //       "extendedTimeOut": 0,
                //       "showEasing": "swing",
                //       "hideEasing": "linear",
                //       "showMethod": "fadeIn",
                //       "hideMethod": "fadeOut",
                //     }
                toastr.options = {
                    positionClass: 'toast-bottom-left',
                    timeOut: '450'
                }
                toastr.info(' agregado a la comanda.');
                var inputCantActual = $('#cant_orden_actual').val();
                agregarProducto(id_producto_global, inputCantActual, [], [], idArea);
                console.log(orden);
            }
        }

        function mostrarEspecificaciones(elem) {
            var idTipos = elem.getAttribute("data-id");
            var costo = elem.getAttribute("data-costo");
            $('#total-opciones-check').text(costo);
            var servername = window.location.hostname != 'localhost' ? '../../' : '/';
            $.ajax({
                url: servername+'comandas/opciones/detalles',
                method: 'POST',
                dataType: "json",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    'id_tipos': idTipos
                },
                async: false,
                success: function(response) {
                var response = response.replace(/\\/g, "");
                $('#list-opciones2-check').html(response);
                $('#total-opciones-check').text(costo);
                },
                error: function(error) {
                    var mensaje = 'Ha ocurrido un error: ' + error;
                    alert(JSON.stringify(error));
                    $('#list-opciones-check').html(mensaje);
                }
            });
        }


        function clicEspecificaciones(elem) {
            // Obtener el div con la clase "form-check"
            const formCheckDiv = document.querySelector('#list-opciones2-check');
            // Obtener todos los input dentro del div que estén chequeados
            const inputs = formCheckDiv.querySelectorAll('input:checked');
            const formData = new FormData();
            // Agregar los IDs seleccionados como parámetros en el objeto FormData
            for (let i = 0; i < inputs.length; i++) {
                formData.append('idsSeleccionados[]', inputs[i].dataset.id);
                console.log(inputs[i].dataset.id);
            }
            var servername = window.location.hostname != 'localhost' ? '../../' : '/';
                $.ajax({
                    url: servername+'comandas/opciones/suma',
                    method: 'POST',
                    dataType: "json",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        'formData': formData
                    },
                    async: false,
                    success: function(response) {
                    var response = response.replace(/\\/g, "");
                    $('#total-opciones-check').text(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText + ' ' + textStatus + ', ' + errorThrown);
                }
            });
        }

        function agregarProducto(idProducto, cantidad, tipos, tiposCatEsp, idArea) {
            const producto = {
                id_producto: idProducto,
                id_area: idArea,
                cantidad: cantidad,
                tipos: tipos,
                tipos_cat_esp: tiposCatEsp
            };
            orden.productos.push(producto);
            agruparProductos();
            orden.actualizarVistaComanda();
        }


        function aceptarOrden() {
            var inputCantActual = $('#cant_orden_actual').val();
            var inputArea = $('#id_area_producto').val();
            $('#modal-opciones').modal('hide');
            toastr.options = {
                positionClass: 'toast-bottom-left',
                timeOut: '500'
            }
            toastr.info(' agregado a la comanda.');
            var inputCantActual = $('#cant_orden_actual').val();
            const formCheckDiv = document.querySelector('#list-opciones-check');
            // Obtener todos los input dentro del div que estén chequeados
            const inputs = formCheckDiv.querySelectorAll('input:checked');
            // Crear un array para almacenar los IDs seleccionados
            const idsespecificaciones = [...inputs].map(input => input.dataset.id);
            const idsespecificacionesNumeros = idsespecificaciones.map(id => parseInt(id));

            const formCheckDiv2 = document.querySelector('#list-opciones2-check');
            // Obtener todos los input dentro del div que estén chequeados
            const inputs2 = formCheckDiv2.querySelectorAll('input:checked');
            // Crear un array para almacenar los IDs seleccionados
            const idsespecificaciones2 = [...inputs2].map(input => input.dataset.id);
            const idsespecificacionesNumeros2 = idsespecificaciones2.map(id => parseInt(id));
            agregarProducto(id_producto_global, inputCantActual, idsespecificacionesNumeros, idsespecificacionesNumeros2,inputArea);
            console.log(orden);

        }

        function guardarOrden() {
            floatingDiv.style.display = 'none';
            button.style.display = 'flex';
            button.style.alignItems = 'center';
            closeButton.style.display = 'none';

            Swal.fire({
                title: 'Confirmar',
                text: '¿Estás seguro de agregar la orden?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, estoy seguro',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var servername = window.location.hostname != 'localhost' ? '../../' : '/';
                    const guardarOrdenPromise = new Promise((resolve, reject) => {
                        $.ajax({
                            url: servername+'comandas/agregar',
                            method: 'POST',
                            dataType: "json",
                            data: {
                                "_token": $('meta[name="csrf-token"]').attr('content'),
                                'orden': JSON.stringify(orden)
                            },
                            async: false,
                            success: function(response) {
                                console.log(response);
                                resolve(response);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                reject(errorThrown);
                            }
                        });
                    });

                    // Enviar la solicitud AJAX para generar el PDF de la orden después de que se haya guardado la orden
                    guardarOrdenPromise.then(() => {
                        const generarPdfPromise = new Promise((resolve, reject) => {

                            Swal.fire({
                                title: '¿Desea imprimir la comanda?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Sí, imprimir',
                                cancelButtonText: 'No '
                            }).then((resultado) => {
                                if (resultado.isConfirmed) {
                                    // Si el usuario hace clic en el botón "Sí"

                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', '../bin/orden_pdf.php');
                                    xhr.setRequestHeader('Content-Type',
                                        'application/x-www-form-urlencoded');
                                    xhr.responseType = 'blob';
                                    xhr.onload = function() {
                                        if (this.status === 200) {
                                            // Crear el objeto Blob con los datos del PDF
                                            var blob = new Blob([this.response], {
                                                type: 'application/pdf'
                                            });
                                            // Crear un objeto URL para el Blob
                                            var url = window.URL.createObjectURL(blob);
                                            // Abrir la ventana emergente
                                            if (/Android/i.test(navigator.userAgent)) {
                                                isMobile = true;
                                            }

                                            // Verificar si el usuario está en un dispositivo iOS
                                            if (/iPhone|iPad|iPod/i.test(navigator
                                                    .userAgent)) {
                                                isMobile = true;
                                            }

                                            var left = (screen.width - width) / 2;
                                            var top = (screen.height - height) / 2;
                                            var win = window.open('', 'Recibo',
                                                'width=' + width + ',height=' +
                                                height + ',left=' + left + ',top=' +
                                                top);
                                            // Agregar HTML y CSS para la apariencia de los botones
                                            win.document.write(
                                                '<html><head><title>Recibo</title><style>button { display: block; width: 100%; padding: 10px; margin-bottom: 10px; }</style></head><body>'
                                                );
                                            if (isMobile) {
                                                // Agregar botón para imprimir automáticamente
                                                // Crear el objeto Blob con los datos del PDF
                                                var blob = new Blob([this.response], {
                                                    type: 'application/pdf'
                                                });
                                                // Crear un objeto URL para el Blob
                                                var url = window.URL.createObjectURL(
                                                    blob);
                                                window.open(url);

                                            } else {
                                                // Agregar botón para seleccionar la impresora
                                                win.document.write(
                                                    '<button onclick="window.print();">Imprimir</button>'
                                                    );
                                                // Agregar botón para cerrar la ventana
                                                win.document.write(
                                                    '<button onclick="window.close();">Cerrar</button>'
                                                    );
                                            }
                                            // Agregar el objeto PDF a la ventana emergente
                                            win.document.write(
                                                '<embed width="100%" height="100%" src="' +
                                                url + '" type="application/pdf" />');
                                            win.document.write('</body></html>');
                                        }
                                    };
                                    var ordenJSON = JSON.stringify(orden);
                                    xhr.send('orden=' + ordenJSON);

                                    Swal.fire({
                                        title: 'Orden Agregada',
                                        icon: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Aceptar',
                                        allowOutsideClick: false // Evita que el cuadro de diálogo se cierre al hacer clic fuera de él
                                    }).then((resultado) => {
                                        if (resultado.isConfirmed) {
                                            // Si el usuario hace clic en el botón "Sí"
                                            window.location.href = "{{ route('comandas') }}";
                                            // Agrega aquí el código para eliminar el registro
                                        }
                                    })

                                    // Agrega aquí el código para eliminar el registro
                                } else if (resultado.dismiss === Swal.DismissReason
                                    .cancel) {
                                    // Si el usuario hace clic en el botón "No"
                                    Swal.fire({
                                        title: 'Orden Agregada',
                                        icon: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Aceptar',
                                        allowOutsideClick: false // Evita que el cuadro de diálogo se cierre al hacer clic fuera de él
                                    }).then((resultado) => {
                                        if (resultado.isConfirmed) {
                                            // Si el usuario hace clic en el botón "Sí"
                                            window.location.href = "{{ route('comandas') }}";
                                            // Agrega aquí el código para eliminar el registro
                                        }
                                    })

                                }
                            })

                        });

                        generarPdfPromise.catch((error) => {
                            console.error(error);
                            alert(error);
                        });
                    }).catch((error) => {
                        console.error(error);
                        alert(error);
                    });
                }
            });
        }


        $('#modal-test').on('show.bs.modal', function() {
            const radios = document.querySelectorAll(".un-sel");
            radios.forEach((x) => {
                x.dataset.val = x.checked;
                x.addEventListener('click', (e) => {
                    let element = e.target;
                    if (element.dataset.val == 'false') {
                        element.dataset.val = 'true';
                        element.checked = true;
                    } else {
                        element.dataset.val = 'false';
                        element.checked = false;
                    }
                }, true);
            });
        });


        function validarSeleccionCheckbox() {
            const radios = document.querySelectorAll('input[type="radio"]');
            let seleccionado_rad = false;

            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    seleccionado_rad = true;
                    break;
                }
            }

            if (!seleccionado_rad) {
                const mensaje = document.querySelector('.res-test');
                mensaje.innerText = 'Debe seleccionar al menos un radio';
                mensaje.style.color = 'red';
                return false;
            }

            const checkboxes = document.querySelectorAll('.min-one');
            let seleccionado_check = false;

            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    seleccionado_check = true;
                    break;
                }
            }

            if (!seleccionado_check) {
                const mensaje = document.querySelector('.res-test');
                mensaje.innerText = 'Debe seleccionar al menos un checkbox';
                mensaje.style.color = 'red';
                return false;
            }

            const checkboxes_2 = document.querySelectorAll('.min-two');
            let seleccionado_check_2 = 0;

            for (let i = 0; i < checkboxes_2.length; i++) {
                if (checkboxes_2[i].checked) {
                    seleccionado_check_2++;
                }
            }

            if (seleccionado_check_2 < 3) {
                const mensaje = document.querySelector('.res-test');
                mensaje.innerText = 'Debe seleccionar al menos dos numeros';
                mensaje.style.color = 'red';
                return false;
            }


            return true;
        }

        const botonEnviar = document.querySelector('.sav-test');
        botonEnviar.addEventListener('click', function(evento) {
            evento.preventDefault();
            const valido = validarSeleccionCheckbox();
            if (valido) {
                const mensaje = document.querySelector('.res-test');
                mensaje.innerText = 'Enviado';
                mensaje.style.color = 'green';
                // document.querySelector('#form_test_orden').submit();
            }
        });


        function agruparProductos() {
            // Creamos un objeto para almacenar los productos agrupados
            var productosAgrupados = {};
            // Iteramos sobre los productos de la orden
            orden.productos.forEach(function(producto) {
                // Creamos una clave única para cada combinación de id_producto, tipos y tipos_cat_esp
                var clave = producto.id_producto + '-' + producto.tipos.join(',') + '-' + producto.tipos_cat_esp
                    .join(',');
                // Si ya existe una entrada para esa clave, sumamos la cantidad del producto existente
                if (productosAgrupados.hasOwnProperty(clave)) {
                    productosAgrupados[clave].cantidad += parseInt(producto.cantidad);
                } else {
                    // Si no existe una entrada para esa clave, creamos una nueva entrada con el producto actual
                    productosAgrupados[clave] = {
                        id_producto: producto.id_producto,
                        id_area: producto.id_area,
                        cantidad: parseInt(producto.cantidad),
                        tipos: producto.tipos,
                        tipos_cat_esp: producto.tipos_cat_esp
                    };
                }
            });
            // Convertimos el objeto de productos agrupados en un arreglo
            var productosAgrupadosArray = [];
            for (var clave in productosAgrupados) {
                if (productosAgrupados.hasOwnProperty(clave)) {
                    productosAgrupadosArray.push(productosAgrupados[clave]);
                }
            }
            // Actualizamos el objeto orden con los productos agrupados
            orden.productos = productosAgrupadosArray;
        }


        function restarCantidadProducto(idProducto, cantidad) {
            // Buscamos el producto en el objeto orden
            var productoEncontrado = false;
            for (var i = 0; i < orden.productos.length; i++) {
                if (orden.productos[i].id_producto === idProducto) {
                    // Si encontramos el producto, verificamos que la cantidad actual sea mayor que la cantidad especificada
                    if (orden.productos[i].cantidad > cantidad) {
                        // Restamos la cantidad especificada
                        orden.productos[i].cantidad -= cantidad;
                    } else {
                        // Si la cantidad actual es menor o igual a la cantidad especificada, establecemos la cantidad en 1
                        orden.productos[i].cantidad = 1;
                    }
                    productoEncontrado = true;
                    break;
                }
            }
            // Si no encontramos el producto, lanzamos un error
            if (!productoEncontrado) {
                throw new Error("El producto con ID " + idProducto + " no fue encontrado en la orden.");
            }
            console.log(orden);
        }


        function sumarCantidadProducto(idProducto, cantidad) {
            // Buscamos el producto en el objeto orden
            var productoEncontrado = false;
            for (var i = 0; i < orden.productos.length; i++) {
                if (orden.productos[i].id_producto === idProducto) {
                    // Si encontramos el producto, sumamos la cantidad especificada
                    orden.productos[i].cantidad += cantidad;
                    productoEncontrado = true;
                    break;
                }
            }
            // Si no encontramos el producto, lanzamos un error
            if (!productoEncontrado) {
                throw new Error("El producto con ID " + idProducto + " no fue encontrado en la orden.");
            }
            console.log(orden);
        }


        function generar_eliminar_orden() {
            $('.btn-eliminar-orden').on('click', function() {
                var id_orden = this.getAttribute('data-id');
                eliminarProducto(id_orden);
                orden.detallesOrden();
                ImagenListaVacia();
            });
        }


        function eliminarProducto(id_producto) {
            // Creamos un nuevo arreglo de productos que no incluya el producto a eliminar
            var nuevosProductos = orden.productos.filter(function(producto) {
                return producto.id_producto !== id_producto;
            });
            // Actualizamos el objeto orden con los nuevos productos
            orden.productos = nuevosProductos;
        }

        function ImagenListaVacia() {
            if (orden.productos.length === 0) {
            }
        }
    </script>

@endsection
