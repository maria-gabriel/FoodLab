<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    public function comandas_crear(Request $request)
    {
        $cliente = $request->cliente;
        $mesa = $request->mesa;
        $id_usuario = Auth::user()->id;
        try {
            $sql = "INSERT INTO ordenes (nombre_cliente, numero_mesa, id_usuario, fecha, id_estatus) VALUES (?, ?, ?, NOW(), 1)";
            DB::insert($sql, [$cliente, $mesa, $id_usuario]);
            return redirect()->route('comandas')->with('ok', 'ok');
        } catch (\Exception $e) {
            return redirect()->route('comandas')->with('nook', 'nook');
        }
    }

    public function comandas()
    {
        $ordenes = DB::table('ordenes')
            ->leftJoin('ordenes_productos', 'ordenes.id', '=', 'ordenes_productos.id_orden')
            ->leftJoin('productos', 'ordenes_productos.id_producto', '=', 'productos.id')
            ->join('estatus', 'ordenes.id_estatus', '=', 'estatus.id')
            ->join('usuarios', 'ordenes.id_usuario', '=', 'usuarios.id')
            ->where('estatus.id', '=', 1)
            ->groupBy('ordenes.fecha')
            ->select([
                'ordenes.id',
                'ordenes.fecha',
                'ordenes.nombre_cliente',
                'ordenes.numero_mesa',
                'estatus.nombre AS estatus',
                DB::raw('IFNULL(SUM(ordenes_productos.cantidad), 0) AS cantidad'),
                'usuarios.nombre AS nombre_usuario'
            ])
            ->orderByDesc('ordenes.fecha')
            ->get();

        $tabla_comandas = '';

        foreach ($ordenes as $orden) {
            $total_global = $this->total_orden($orden->id);

            $tabla_comandas .= '<tr>
                    <td class="td-figure"><figure class="avatar avatar-sm"><span class="avatar-title bg-light text-white rounded-circle">'.substr($orden->nombre_usuario, 0, 1).'</span></figure><span class="usuario-detalles">'.$orden->id.'</span></td>
                    <td class="td-folio">' . $orden->id . '</td>
                    <td class="td-fecha">' . date('d/m/Y H:i', strtotime($orden->fecha)) . '</td>
                    <td class="td-nombre"> 
                                    <div class="nombre-container">
                                        <div class="usuario-detalles">
                                        <p class="m-0 mt-1">'. $orden->nombre_cliente .' <span class="text-secondary">'. $orden->numero_mesa .'</span></p><div class="mt-2 badge border border-success text-success mt-2">' . $orden->estatus . '</div><p class="text-muted small m-0">Creación: '. date('d/m/Y', strtotime($orden->fecha)) .'</p><p class="small m-0">Subtotal: <b>$'. number_format($total_global, 2, '.', '') .'</b></p>
                                    </div>
                    <span class="cliente-nombre text-dark">'. $orden->nombre_cliente . '<div class="badge badge-warning ml-1">' . $orden->numero_mesa . '</div></span>
                    </td>
                    <td class="td-estado"><div class="mt-2 badge border border-success text-success mt-2">' . $orden->estatus . '</div></td>
                    <td class="td-subtotal">$ ' . number_format($total_global, 2, '.', '') . '</td>
                    <td class="td-opciones">
                        <div class="d-flex align-items-center list-action">
                            <a class="badge badge-warning mr-2 pointer lc-1" href="comandas/editar/' . $orden->id . '"><i class="ri-add-line mr-0 text-xl"></i></a>
                            <a class="badge badge-primary mr-2 pointer lc-2" data-toggle="modal" data-backdrop="static" data-target="#modal-visor" data-id="' . $orden->id . '"><i class="ri-eye-line mr-0 text-xl"></i></a>
                            <a class="badge badge-light mr-2 pointer lc-3" onclick="generarRecibo(' . $orden->id . ')"><i class="ri-printer-line mr-0 text-xl"></i></a>
                        </div>
                    </td>
                    <td class="td-final">
                           <a class="badge badge-success mr-2 pointer" data-toggle="modal" data-target="#modal-final" data-id="' . $orden->id . '" data-subtotal="' . $total_global . '"><i class="ri-checkbox-circle-line text-xl"></i></a>
                    </td>
                </tr>';
                
        }

        $tabla_comandas .= '';
        return view('comandas.index', compact('tabla_comandas'));
    }

    protected function total_orden($idOrden)
    {
        $sqlsum = "SELECT *,ordenes_productos.id AS id_ordenes_p FROM ordenes_productos 
        INNER JOIN productos on productos.id=ordenes_productos.id_producto
        WHERE ordenes_productos.id_orden=$idOrden;";
        $resultadosum1 = DB::select($sqlsum);
        $total_global = 0;
            
        foreach($resultadosum1 as $filasum1) {
        if ($filasum1->opciones == 0) {
                $total_global = $total_global + ( $filasum1->cantidad * $filasum1->precio);
            }
        if($filasum1->opciones == 1){        
        $sqlnombrestipos = "SELECT *,ordenes_tipos.id AS id_orde_tip FROM ordenes_tipos 
            INNER JOIN ordenes_productos 
            on ordenes_productos.id = ordenes_tipos.id_ordenes_productos 
            INNER JOIN tipos on tipos.id = ordenes_tipos.id_tipos 
            WHERE ordenes_productos.id={$filasum1->id_ordenes_p}";
            $resultado2 = DB::select($sqlnombrestipos);
                    //solo se itera un productos porque la orden tiene un tipo
                if ($resultado2) {
                    $fila2 = $resultado2[0];
                    $total_tipos= $fila2->costo;
                    $id_tipos=$fila2->id_orde_tip;
                    $sqlnombresespc="SELECT * FROM `ordenes_especificaciones` 
                    INNER JOIN tipos_categorias_especificaciones ON tipos_categorias_especificaciones.id =  id_categorias_especificaciones
                    WHERE id_ordenes_tipos= $id_tipos";
                    $resultado3 = DB::select($sqlnombresespc);
                    foreach($resultado3 as $fila3) {
                        $total_tipos=$total_tipos + $fila3->costo_extra;
                    }
                    $total_global = $total_global + ($total_tipos  * $filasum1->cantidad);
                }
            }
        }

        return $total_global;
    }

    public function comandas_editar(int $id)
    {
        $orden = $id;
        $fila = DB::table('ordenes')->where('id', '=', $orden)->first();
        $cliente = ($fila->nombre_cliente != '') ? $fila->nombre_cliente : '';
        $cliente .= ($fila->numero_mesa != '') ? '<div class="badge badge-warning ml-1">' . $fila->numero_mesa . '</div>' : '';

        $resultado_categorias = DB::table('categorias')->orderBy('posicion', 'ASC')->get();
        return view('comandas.editar', compact('orden', 'cliente', 'resultado_categorias'));
    }

    public function comandas_productos(Request $request)
    {
        $id_orden = $request->id;
        $productos = DB::table('ordenes_productos')
            ->select('ordenes_productos.*', 'productos.*', 'ordenes_productos.id AS id_ordenes_p')
            ->join('productos', 'productos.id', '=', 'ordenes_productos.id_producto')
            ->where('ordenes_productos.id_orden', $id_orden)
            ->get();

        $folio = 'Folio: ' . $id_orden;
        $total_global = 0; // Inicializar la variable con un valor de 0
        $html = '<table class="table-update2">';
        foreach ($productos as $fila) {
            if ($fila->opciones == 0) {
                $html .= '<tr>';
                $html .= '<td class="td-foto"><div class="col-8 mt-2 h-10 d-flex">';
                $html .= '<img src="' . $fila->foto . '" style="max-width: 40px;" class="card-img img-fluid align-self-center img-thumbnail mr-2" alt="#"></div>';
                $html .= '</td>';
                $html .= '<td class="td-nombre"><p class="line-title m-0">' . $fila->nombre . '</p></td>';
                $html .= '<td class="td-precio"><small class="text-muted">$' . $fila->precio . '</small></td>';
                $html .= '<td class="td-cantidad">';
                $html .= '<small class="text-muted">X ' . $fila->cantidad . '</small></td>';
                $html .= '<td class="td-total"><div class="ml-2 badge badge-warning ml-1 ">$' . ($fila->cantidad * $fila->precio) . '</td>';
                $html .= '</tr>';
            }

            if ($fila->opciones == 1) {
                $nombres_tipo = '';
                $total_tipos = $fila->precio;

                $ordenes_tipos = DB::table('ordenes_tipos')
                    ->join('ordenes_productos', 'ordenes_productos.id', '=', 'ordenes_tipos.id_ordenes_productos')
                    ->join('tipos', 'tipos.id', '=', 'ordenes_tipos.id_tipos')
                    ->where('ordenes_productos.id', '=', $fila->id_ordenes_p)
                    ->select('ordenes_tipos.id AS id_orde_tip', 'tipos.nombre', 'tipos.costo')
                    ->first();

                //solo se itera un productos porque la orden tiene un tipo
                if ($ordenes_tipos) {
                    $nombres_tipo = $ordenes_tipos->nombre;
                    $total_tipos= $ordenes_tipos->costo;
                    $id_tipos=$ordenes_tipos->id_orde_tip;
                    $tipos_categorias_especificaciones = DB::table('ordenes_especificaciones')
                        ->join('tipos_categorias_especificaciones', 'tipos_categorias_especificaciones.id', '=', 'ordenes_especificaciones.id_categorias_especificaciones')
                        ->where('id_ordenes_tipos', '=', $ordenes_tipos->id_orde_tip)
                        ->select('tipos_categorias_especificaciones.nombre', 'tipos_categorias_especificaciones.costo_extra')
                        ->get();
                    $nombres_espec = '';
                    foreach ($tipos_categorias_especificaciones as $espec) {

                        $nombres_espec .= '-' . substr($espec->nombre, 0, 3);
                        $total_tipos += $espec->costo_extra;
                    }
                    $html .= '<tr>';
                    $html .= '<td class="td-foto"><div class="col-8  h-10 d-flex">';
                    $html .= '<img src="' . $fila->foto . '" style="max-width: 40px;" class="card-img img-fluid align-self-center img-thumbnail mr-2" alt="#"></div>';
                    $html .= '</td>';
                    $html .= '<td class="td-nombre"><p class="line-title m-0">' . $fila->nombre . ' ' . substr($nombres_tipo, 0, 3) . '' . $nombres_espec . '</p></td>';
                    $html .= '<td class="td-precio"><small class="text-muted">$' . $total_tipos . '</small></td>';
                    $html .= '<td class="td-cantidad">';
                    $html .= '<small class="text-muted">X ' . $fila->cantidad . '</small></td>';
                    $html .= '<td class="td-total"><div class="ml-2 badge badge-warning ml-1">$' . ($total_tipos * $fila->cantidad) . '</td>';
                    $html .= '</tr>';
                }
            }
        }

        $html .= '</table>';
        return response()->json($html);
    }

    public function comandas_finalizar(Request $request)
    {
        parse_str($request->formData, $variables);
        $propina = 0;
        $id_orden = $variables['id_orden'];
        $propina = $variables['propina'];
        $metodo_pago = $variables['metodo_pago'];
        $id_usuario = Auth::user()->id;

        $total_global =  $this->total_orden($id_orden);

        if ($total_global > 0) {
            // Actualizar el estatus de la orden
            $idestatus = 2;
            $metodo_pago == 4 ?? $idestatus = 3;
            $sqlestatus = "UPDATE ordenes SET id_estatus = $idestatus WHERE id = $id_orden;";
            if (DB::update($sqlestatus)) {
                if ($metodo_pago != 4) {
                    // Insertar la nueva venta en la base de datos
                    $sqlventas = "INSERT INTO ventas (id_usuario_cierre, id_orden, id_metodo_pago, subtotal, iva, propina, fecha_hora, date_update)
                VALUES ($id_usuario,$id_orden, $metodo_pago, $total_global,null,$propina,NOW(),null);";

                    if (DB::insert($sqlventas)) {
                        return response()->json('success');
                    } else {
                        return response()->json('error3');
                    }
                } else {
                    return response()->json('success');
                }
            } else {
                return response()->json('error2');
            }
        } else {
            return response()->json('No se puede finalizar una cuenta con subtotal de $0.0');
        }
    }

    public function comandas_detalles(Request $request)
    {
        $orden = json_decode($_POST['orden']);
        $productos_html = '';
        // Recorrer los productos de la orden y obtener sus datos
        $productos_orden = $orden->productos;
        foreach ($productos_orden as $producto_orden) {
            $id_producto = $producto_orden->id_producto;
            $cantidad = $producto_orden->cantidad;
            $tipos = $producto_orden->tipos;
            $tipos_cat_esp = $producto_orden->tipos_cat_esp;
            // Consultar el precio y otros datos del producto en la tabla productos
            $producto_row = DB::table('productos')
                ->select('precio', 'nombre', 'descripcion', 'foto')
                ->where('id', '=', $id_producto)
                ->first();
            $precio_unitario = $producto_row->precio;
            $nombre_producto = $producto_row->nombre;
            $descripcion_producto = $producto_row->descripcion;
            $foto_producto = $producto_row->foto;

            // Calcular el precio total del producto
            $precio_total = $precio_unitario * $cantidad;
            // Consultar los tipos del producto en la tabla tipos
            $tipos_nombres = array();
            // Recorrer los tipos del producto y obtener sus nombres
            $tipos_nombres = array();
            foreach ($tipos as $tipo) {
                $tipo_row = DB::table('tipos')
                    ->select('nombre')
                    ->where('id', '=', $tipo)
                    ->first();
                $tipos_nombres[] = $tipo_row->nombre;
            }

            // Recorrer las categorías de especificaciones de los tipos y obtener sus nombres
            $tipos_cat_esp_nombres = array();
            foreach ($tipos_cat_esp as $tipo_cat_esp) {
                $tipo_cat_esp_row = DB::table('tipos_categorias_especificaciones')
                    ->select('nombre')
                    ->where('id', '=', $tipo_cat_esp)
                    ->first();
                $tipos_cat_esp_nombres[] = $tipo_cat_esp_row->nombre;
            }

            $tipos_nombres_html = "";
            foreach ($tipos_nombres as $tipo_nombre) {
                $tipos_nombres_html .= "" . substr($tipo_nombre, 0, 3) . " ";
            }
            $tipos_cat_esp_nombres_html = "";
            foreach ($tipos_cat_esp_nombres as $tipo_cat_esp_nombre) {
                $tipos_cat_esp_nombres_html .= "" . substr($tipo_cat_esp_nombre, 0, 3) . " ";
            }

            // Generar el HTML del producto y agregarlo a la variable $productos_html
            $productos_html .= '<li class="list-group-item d-flex justify-content-between align-items-center border-0 py-0 px-2">';
            $productos_html .= '<div class="col-2 mt-2 h-10 d-flex">';
            $productos_html .= '<a class="badge badge-warning mr-2 pointer btn-eliminar-orden" data-id="' . $id_producto . '"><i class="ri-delete-bin-line mr-0 text-xl"></i></a>';
            $productos_html .= '</div>';
            $productos_html .= '<div class="col-6 mt-2 h-10 d-flex">';
            $productos_html .= '<img src="' . $foto_producto . '" style="max-width: 40px;" class="card-img img-fluid align-self-center img-thumbnail mr-2" alt="#">';
            $productos_html .= '<div class="d-flex align-items-center w-50"><p class="line-title m-0">' . $nombre_producto . '<br><span class="small">' . $tipos_nombres_html . '' . $tipos_cat_esp_nombres_html . '</span></p></div>';
            $productos_html .= '</div>';
            $productos_html .= '<div class="col-4 d-flex my-2 justify-content-end">';
            $productos_html .= '<div class="quantity-buttons"><input type="number" min="1" value="' . $cantidad . '" step="1" readonly="" class="input-comanda" data-id="' . $id_producto . '" name="' . $nombre_producto . '"></div>';
            $productos_html .= '</div>';
            $productos_html .= '</li>';
        }
        return response()->json($productos_html);
    }

    public function comandas_opciones(Request $request)
    {
        $id_producto = $_POST['id_producto'];
        $html = '';
        $productos_tipos = DB::table('productos_tipos')
            ->where('id_producto', $id_producto)
            ->where('activo', 1)
            ->get();

        foreach ($productos_tipos as $producto_tipo) {
            $html .= '<label>' . $producto_tipo->tipo . '</label>';

            $tipos = DB::table('tipos')
                ->where('id_producto_tipo', $producto_tipo->id)
                ->where('activo', 1)
                ->get();

            $html .= '<div class="form-check">';
            foreach ($tipos as $tipo) {
                $html .= '<div class="custom-control custom-radio custom-radio-color-checked">';
                $html .= '<input onclick="mostrarEspecificaciones(this)" data-costo="$' . $tipo->costo . '" data-id="' . $tipo->id . '" type="radio" id="' . $tipo->nombre . '-' . $tipo->id . '" name="grupo-' . $producto_tipo->id . '" class="custom-control-input bg-warning" required>';
                $html .= '<label class="custom-control-label" for="' . $tipo->nombre . '-' . $tipo->id . '">' . $tipo->nombre . '</label>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        return response()->json($html);
    }

    public function comandas_opciones_detalles(Request $request)
    {
        $id_tipos = $_POST['id_tipos'];
        $html = '';
        $categorias = DB::table('tipos_categorias')
            ->select('id', 'nombre')
            ->where('id_tipos', '=', $id_tipos)
            ->where('activo', '=', 1)
            ->get();

        foreach ($categorias as $fila) {
            $html .= '<label>' . $fila->nombre . '</label>';
            $especificaciones = DB::table('tipos_categorias_especificaciones')
                ->select('id', 'nombre', 'costo_extra')
                ->where('id_tipos_categorias', '=', $fila->id)
                ->where('activo', '=', 1)
                ->get();

            $html .= '<div class="form-check">';
            foreach ($especificaciones as $fila2) {
                $costo = '<span style="color: gray;"> - sin cargo</span>';
                if ($fila2->costo_extra > 0) {
                    $costo = '<span style="color: gray;">' . ' $' . $fila2->costo_extra . '</span>';
                }
                $html .= '<div class="custom-control custom-radio custom-radio-color-checked">';
                $html .= '<input onclick="clicEspecificaciones(this)" type="radio"  id="' . $fila2->nombre . '-' . $fila2->id . '" name="opcion-' . $fila->id . '" class="custom-control-input bg-warning" data-id="' . $fila2->id . '">';
                $html .= '<label class="custom-control-label" for="' . $fila2->nombre . '-' . $fila2->id . '">' . $fila2->nombre . ' ' . ' ' . $costo . '</label>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        return response()->json($html);
    }

    public function comandas_opciones_suma(Request $request)
    {
        $ids = $_POST['idsSeleccionados'];
        $ids = implode(',', $ids);
        $suma = 0;
        // Obtener la suma de los costos extras de las opciones seleccionadas
        $totalCostosExtras = DB::table('tipos_categorias_especificaciones')
            ->whereIn('id', $ids)
            ->sum('costo_extra');
        $suma += $totalCostosExtras;
        // Obtener el costo del tipo del primer tipo de la lista de opciones seleccionadas
        $tipoId = DB::table('tipos_categorias_especificaciones')
            ->select('tipos.id')
            ->join('tipos_categorias', 'tipos_categorias_especificaciones.id_tipos_categorias', '=', 'tipos_categorias.id')
            ->join('tipos', 'tipos_categorias.id_tipos', '=', 'tipos.id')
            ->whereIn('tipos_categorias_especificaciones.id', $ids)
            ->first()
            ->id;
        $tipoCosto = DB::table('tipos')
            ->where('id', $tipoId)
            ->value('costo');

        $suma += $tipoCosto;
        $total = $suma;
        return response()->json($total);
    }

    public function comandas_agregar(Request $request)
    {
        $orden = json_decode($_POST['orden']);
        $id_usuario = Auth::user()->id;
        $id_orden = $orden->id_orden;
        // Recorrer los productos de la orden y obtener sus datos
        $productos = $orden->productos;
        foreach ($productos as $producto) {
            $id_producto = $producto->id_producto;
            $cantidad = $producto->cantidad;
            $tipos = $producto->tipos;
            $tipos_cat_esp = $producto->tipos_cat_esp;
            // Insertar el producto en la tabla ordenes_productos
            $id_registro_ordenes_productos = DB::table('ordenes_productos')->insertGetId([
                'id_usuario_responsable' => $id_usuario,
                'id_orden' => $id_orden,
                'id_producto' => $id_producto,
                'cantidad' => $cantidad,
                'fecha' => now(),
                'nota' => null,
                'opcion' => null
            ]);
            foreach ($tipos as $tipo) {
                // Insertar el tipo del producto en la tabla ordenes_tipos
                $id_registro_ordenes_tipos = DB::table('ordenes_tipos')->insertGetId([
                    'id_ordenes_productos' => $id_registro_ordenes_productos,
                    'id_tipos' => $tipo
                ]);
                // Recorrer las categorías de especificaciones del tipo y obtener sus datos
                foreach ($tipos_cat_esp as $tipo_cat_esp) {
                    // Insertar la categoría de especificaciones en la tabla ordenes_especificaciones
                    DB::table('ordenes_especificaciones')->insert([
                        'id_ordenes_tipos' => $id_registro_ordenes_tipos,
                        'id_categorias_especificaciones' => $tipo_cat_esp
                    ]);
                }
            }
        }
        return response()->json(['mensaje' => 'success']);
    }


    public function comandas_recibo(Request $request)
    {
    }
}
