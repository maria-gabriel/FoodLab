@extends('layouts.app')

@section('content')
    <div class="wrapper">
        @php
            $orden = 144;
            $fila = DB::selectOne('SELECT * FROM ordenes WHERE id=?', [$orden]);
            $cliente = $fila->nombre_cliente != '' ? $fila->nombre_cliente : '';
            $cliente .= $fila->numero_mesa != '' ? '<div class="badge badge-warning ml-1">' . $fila->numero_mesa . '</div>' : '';
            $resultado_categorias = DB::select('SELECT * FROM categorias ORDER BY posicion ASC');
        @endphp

        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                            <div>
                                <h4 class="mb-3">Ventas</h4>
                                <p class="mb-0"></p>
                            </div>
                            <div class="card">
                                <ul class="nav nav-tabs m-0" id="myTab-three" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="panel-d1-tab-three" data-toggle="tab"
                                            href="#panel-d1-three" role="tab" aria-controls="panel-ariad1"
                                            aria-selected="true">Día</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="panel-s1-tab-three" data-toggle="tab"
                                            href="#panel-s1-three" role="tab" aria-controls="panel-arias1"
                                            aria-selected="false">Semana</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="panel-m1-tab-three" data-toggle="tab"
                                            href="#panel-m1-three" role="tab" aria-controls="panel-ariam1"
                                            aria-selected="false">Mes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="panel-a1-tab-three" data-toggle="tab"
                                            href="#panel-a1-three" role="tab" aria-controls="panel-ariaa1"
                                            aria-selected="false">Año</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                    <div class="tab-content" id="myTabContent-4">
                        <div class="tab-pane fade show active" id="panel-d1-three" role="tabpanel"
                            aria-labelledby="panel-aria-d1-tab-three">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="rounded mb-3">
                                        <table id="tablad1"
                                            class="data-tables table mb-0 tbl-server-info">
                                            <thead>
                                                <tr class="ligth ligth-data">
                                                    <th>Fecha </th>
                                                    <th>Día </th>
                                                    <th>Total </th>
                                                    <th>Propinas </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_ventas_diario" class="ligth-body">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="panel-s1-three" role="tabpanel"
                            aria-labelledby="panel-aria-s1-tab-three">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="rounded mb-3">
                                        <table id="tablas1"
                                            class="data-tables table mb-0 tbl-server-info">
                                            <thead>
                                                <tr class="ligth ligth-data">
                                                    <th>Inicio </th>
                                                    <th>Fin </th>
                                                    <th>Total </th>
                                                    <th>Propinas </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_ventas_semana" class="ligth-body">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="panel-m1-three" role="tabpanel"
                            aria-labelledby="panel-aria-m1-tab-three">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="rounded mb-3">
                                        <table id="tablam1"
                                            class="data-tables table mb-0 tbl-server-info">
                                            <thead>
                                                <tr class="ligth ligth-data">
                                                    <th>Año</th>
                                                    <th>Mes</th>
                                                    <th>Total</th>
                                                    <th>Propinas</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_ventas_mes" class="ligth-body">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="panel-a1-three" role="tabpanel"
                            aria-labelledby="panel-aria-a1-tab-three">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="rounded mb-3">
                                        <table id="tablaa1"
                                            class="data-tables table mb-0 tbl-server-info">
                                            <thead>
                                                <tr class="ligth ligth-data">
                                                    <th>Año </th>
                                                    <th>Total </th>
                                                    <th>Propinas </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_ventas_anio" class="ligth-body">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
            let reporte_diario = @json($reporte_diario, JSON_PRETTY_PRINT);
            let reporte_semanal = @json($reporte_semanal, JSON_PRETTY_PRINT);
            let reporte_mensual = @json($reporte_mensual, JSON_PRETTY_PRINT);
            let reporte_anual = @json($reporte_anual, JSON_PRETTY_PRINT);

            $("#tbody_ventas_diario").html(reporte_diario);
            $("#tbody_ventas_semana").html(reporte_semanal);
            $("#tbody_ventas_mes").html(reporte_mensual);
            $("#tbody_ventas_anio").html(reporte_anual);
            $('.data-tables').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                order: [[0, 'desc']]
            });
        });
    </script>

@endsection
