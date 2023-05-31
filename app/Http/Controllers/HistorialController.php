<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function historial()
    {
        $sql = "SELECT
          ordenes.id,
          ordenes.fecha,
          ordenes.nombre_cliente,
          ordenes.numero_mesa,
          estatus.nombre AS estatus,
          IFNULL(SUM(ordenes_productos.cantidad), 0) AS cantidad,
          usuarios.nombre AS nombre_usuario
        FROM
          ordenes
          LEFT JOIN ordenes_productos ON ordenes.id = ordenes_productos.id_orden
          LEFT JOIN productos ON ordenes_productos.id_producto = productos.id
          INNER JOIN estatus ON ordenes.id_estatus = estatus.id
          INNER JOIN usuarios ON ordenes.id_usuario = usuarios.id
        GROUP BY
          ordenes.fecha DESC;";

        $resultado = DB::select($sql);

        $tabla_historial = '';

        foreach ($resultado as $fila) {

            $total_global = $this->total_orden($fila->id);
            $tabla_historial .= '<tr>
            <td class="td-figure"><figure class="avatar avatar-sm"><span class="avatar-title bg-light text-white rounded-circle">'.substr($fila->nombre_usuario, 0, 1).'</span></figure><span class="usuario-detalles">'.$fila->id.'</span></td>
            <td class="td-folio">' . $fila->id . '</td>
            <td class="td-fecha">' . date('d/m/Y H:i', strtotime($fila->fecha)) . '</td>
            <td class="td-nombre"> 
                                    <div class="nombre-container">
                                        <div class="usuario-detalles">
                                        <p class="m-0 mt-1">'. $fila->nombre_cliente .' <span class="text-secondary">'. $fila->numero_mesa .'</span></p>';
                                        if ($fila->estatus == 'Abierto') {
                                            $tabla_historial .= '<div class="mt-2 badge border border-success text-success mt-2">' . $fila->estatus . '</div>';
                                        } else if ($fila->estatus == 'Pendiente') {
                                            $tabla_historial .= '<div class="mt-2 badge border border-warning text-warning mt-2">' . $fila->estatus . '</div>';
                                        } else {
                                            $tabla_historial .= '<div class="mt-2 badge border border-info text-info mt-2">' . $fila->estatus . '</div>';
                                        }
                                        $tabla_historial .='<p class="text-muted small m-0">Creación: '. date('d/m/Y', strtotime($fila->fecha)) .'</p><p class="small m-0">Subtotal: <b>$'. number_format($total_global, 2, '.', '') .'</b></p>
                                    </div>
                    <span class="cliente-nombre text-dark">'. $fila->nombre_cliente . '<div class="badge badge-warning ml-1">' . $fila->numero_mesa . '</div></span>
                    </td>
            <td class="td-estado">';
            if ($fila->estatus == 'Abierto') {
                $tabla_historial .= '<div class="mt-2 badge border border-success text-success mt-2">' . $fila->estatus . '</div>';
            } else if ($fila->estatus == 'Pendiente') {
                $tabla_historial .= '<div class="mt-2 badge border border-warning text-warning mt-2">' . $fila->estatus . '</div>';
            } else {
                $tabla_historial .= '<div class="mt-2 badge border border-info text-info mt-2">' . $fila->estatus . '</div>';
            }
            $tabla_historial .= '</td>
            <td class="td-subtotal">$ ' . number_format($total_global, 2, '.', '') . '</td>

            <td class="td-opciones">
                <div class="d-flex align-items-center list-action">
                    <a class="badge badge-yellow mr-2 pointer lc-1" data-toggle="modal" data-backdrop="static" data-target="#modal-update" data-id="' . $fila->id . '"><i class="ri-pencil-line mr-0 text-xl"></i></a>
                    <a class="badge badge-warning mr-2 pointer lc-2 btn-eliminar" data-id="' . $fila->id . '"><i class="ri-delete-bin-line mr-0 text-xl"></i></a>
                    <a class="badge badge-info mr-2 pointer lc-3" onclick="generarRecibo(' . $fila->id . ')"><i class="ri-printer-line mr-0 text-xl"></i></a>
                </div>
            </td>
        </tr>';
        }

        $tabla_historial .= '';
        return view('historial.index', compact('tabla_historial'));
    }

    public function historial_detalles(Request $request)
    {
        $id_orden = $request->id;
        $productos = DB::table('ordenes_productos')
            ->select('ordenes_productos.*', 'productos.*', 'ordenes_productos.id AS id_ordenes_p')
            ->join('productos', 'productos.id', '=', 'ordenes_productos.id_producto')
            ->where('ordenes_productos.id_orden', $id_orden)
            ->get();

        $html = '<table class="table-update">';
        foreach ($productos as $fila) {
            if ($fila->opciones == 0) {
                $html .= '<tr id="trid-' . $fila->id . '">';
                $html .= '<td class="td-delete td-delete-' . $id_orden . '" style="display: none;">';
                $html .= '<a title="Eliminar ' . $fila->nombre . '" class="badge badge-pill border border-warning text-secondary pointer btn-eliminar-orden" data-id="' . $fila->id . '"><i class="ri-delete-bin-line mr-0 text-xl"></i></a>';
                $html .= '</td>';
                $html .= '<td class="td-nombre">';
                $html .= '<div class="nombre-container">';
                $html .= '<img src="' . $fila->foto . '" alt="' . $fila->nombre . '"  style="max-width: 40px;" class="img-fluid product-image img-thumbnail mr-2">';
                $html .= '<p class="product-title line-title m-0">' . $fila->nombre . '<small class="product-price text-secondary"> $' . $fila->precio . '</small></p>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '<td class="td-precio"><small class="text-muted">$' . $fila->precio . '</small></td>';
                $html .= '<td class="td-cantidad"><div class="quantity-buttons">';
                $html .= '<input id="idinput-' . $fila->id . '" type="number" min="1" name="update_cant" value="' . $fila->cantidad . '" step="1" readonly="" data-id="' . $fila->id . '"><div class="product-total badge badge-warning ml-1 ">$' . ($fila->cantidad * $fila->precio) . '</div></td>';
                $html .= '<td class="td-total"><div class="ml-2 badge badge-warning ml-1 ">$' . ($fila->cantidad * $fila->precio) . '</div></td>';
                $html .= '</tr>';
            } elseif ($fila->opciones == 1) {
                $ordenes_tipos = DB::table('ordenes_tipos')
                    ->join('ordenes_productos', 'ordenes_productos.id', '=', 'ordenes_tipos.id_ordenes_productos')
                    ->join('tipos', 'tipos.id', '=', 'ordenes_tipos.id_tipos')
                    ->where('ordenes_productos.id', '=', $fila->id_ordenes_p)
                    ->select('ordenes_tipos.id AS id_orde_tip', 'tipos.nombre', 'tipos.costo')
                    ->first();
                $nombres_tipo = '';
                //solo se itera un productos porque la orden tiene un tipo
                if ($ordenes_tipos) {
                    $nombres_tipo = $ordenes_tipos->nombre;
                    $total_tipos = $ordenes_tipos->costo;
                    $tipos_categorias_especificaciones = DB::table('ordenes_especificaciones')
                        ->join('tipos_categorias_especificaciones', 'tipos_categorias_especificaciones.id', '=', 'ordenes_especificaciones.id_categorias_especificaciones')
                        ->where('id_ordenes_tipos', '=', $ordenes_tipos->id_orde_tip)
                        ->select('tipos_categorias_especificaciones.nombre', 'tipos_categorias_especificaciones.costo_extra')
                        ->get();
                    $nombres_espec = '';
                    foreach ($tipos_categorias_especificaciones as $fila3) {
                        $nombres_espec .= '-' . substr($fila3->nombre, 0, 3);
                        $total_tipos = $total_tipos + $fila3->costo_extra;
                    }

                    $html .= '<tr id="trid-' . $fila->id . '">';
                    $html .= '<td class="td-delete td-delete-' . $id_orden . '" style="display: none;">';
                    $html .= '<a title="Eliminar ' . $fila->nombre . '" class="badge badge-pill border border-warning text-secondary pointer btn-eliminar-orden" data-id="' . $fila->id . '"><i class="ri-delete-bin-line mr-0 text-xl"></i></a>';
                    $html .= '</td>';
                    $html .= '<td class="td-nombre">';
                    $html .= '<div class="nombre-container">';
                    $html .= '<img src="' . $fila->foto . '" alt="' . $fila->nombre . '"  style="max-width: 40px;" class="img-fluid product-image img-thumbnail mr-2">';
                    $html .= '<p class="product-title line-title m-0">' . $fila->nombre . ' ' . substr($nombres_tipo, 0, 3) . '' . $nombres_espec . '<small class="product-price text-secondary"> $' . $fila->precio . '</small></p>';
                    $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="td-precio"><small class="text-muted">$' . $total_tipos . '</small></td>';
                    $html .= '<td class="td-cantidad"><div class="quantity-buttons">';
                    $html .= '<input type="number" min="1" name="update_cant" value="' . $fila->cantidad . '" step="1" readonly="" data-id="' . $fila->id . '"><div class="product-total badge badge-warning ml-1 ">$' . ($total_tipos * $fila->cantidad) . '</div></td>';
                    $html .= '<td class="td-total"><div class="ml-2 badge badge-warning ml-1">$' . ($total_tipos * $fila->cantidad) . '</td>';
                    $html .= '</tr>';
                }
            }
        }

        $html .= '</table>';
        return response()->json($html);
    }

    public function historial_eliminar(Request $request)
    {
        $orden = $request->id_orden;
        try {
            DB::table('ordenes')->where('id', '=', $orden)->delete();
            return response()->json('success');
        } catch (\Exception $e) {
            return response()->json('error');
        }
    }

    public function historial_editar(Request $request)
    {
        $orden = json_decode($_POST['orden']);

        // Obtener los datos de la orden
        $id_orden = $orden->id_orden;
        $eliminados = $orden->eliminados;
        $productos = $orden->productos;

        foreach ($eliminados as $idProducto) {
            DB::table('ordenes_productos')
                ->where('id_orden', $id_orden)
                ->where('id_producto', $idProducto)
                ->delete();
        }

        foreach ($productos as $producto) {
            $idProduct = $producto->id;
            $cantidad = $producto->cantidad;

            DB::table('ordenes_productos')
                ->where('id_orden', $id_orden)
                ->where('id_producto', $idProduct)
                ->update(['cantidad' => $cantidad]);
        }

        $nuevo_subtotal = $this->total_orden($id_orden);
        DB::table('ventas')
            ->where('id_orden', $id_orden)
            ->update(['subtotal' => $nuevo_subtotal, 'date_update' => DB::raw('NOW()')]);

        return response()->json('success');
    }

    protected function total_orden($idOrden)
    {
        $sqlsum = "SELECT *,ordenes_productos.id AS id_ordenes_p FROM ordenes_productos 
        INNER JOIN productos on productos.id=ordenes_productos.id_producto
        WHERE ordenes_productos.id_orden=$idOrden;";
        $resultadosum1 = DB::select($sqlsum);
        $total_global = 0;

        foreach ($resultadosum1 as $filasum1) {
            if ($filasum1->opciones == 0) {
                $total_global = $total_global + ($filasum1->cantidad * $filasum1->precio);
            }
            if ($filasum1->opciones == 1) {
                $sqlnombrestipos = "SELECT *,ordenes_tipos.id AS id_orde_tip FROM ordenes_tipos 
            INNER JOIN ordenes_productos 
            on ordenes_productos.id = ordenes_tipos.id_ordenes_productos 
            INNER JOIN tipos on tipos.id = ordenes_tipos.id_tipos 
            WHERE ordenes_productos.id={$filasum1->id_ordenes_p}";
                $resultado2 = DB::select($sqlnombrestipos);
                //solo se itera un productos porque la orden tiene un tipo
                if ($resultado2) {
                    $fila2 = $resultado2[0];
                    $total_tipos = $fila2->costo;
                    $id_tipos = $fila2->id_orde_tip;
                    $sqlnombresespc = "SELECT * FROM `ordenes_especificaciones` 
                        INNER JOIN tipos_categorias_especificaciones ON tipos_categorias_especificaciones.id =  id_categorias_especificaciones
                        WHERE id_ordenes_tipos= $id_tipos";
                    $resultado3 = DB::select($sqlnombresespc);
                    foreach ($resultado3 as $fila3) {
                        $total_tipos = $total_tipos + $fila3->costo_extra;
                    }
                    $total_global = $total_global + ($total_tipos * $filasum1->cantidad);
                }
            }
        }
        return $total_global;
    }

}
