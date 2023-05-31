<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function ventas()
    {
        //Consulta para el reporte diario
        $sql_reporte_diario = "SELECT DATE(fecha_hora) AS fecha_sin_hora,
        CASE DAYNAME(fecha_hora)
            WHEN 'Monday' THEN 'Lunes'
            WHEN 'Tuesday' THEN 'Martes'
            WHEN 'Wednesday' THEN 'Miércoles'
            WHEN 'Thursday' THEN 'Jueves'
            WHEN 'Friday' THEN 'Viernes'
            WHEN 'Saturday' THEN 'Sábado'
            WHEN 'Sunday' THEN 'Domingo'
        END AS dia_en_espanol,
        SUM(subtotal) AS total_ventas,
        SUM(propina) AS total_propina
        FROM ventas
        GROUP BY fecha_sin_hora
        ORDER BY fecha_sin_hora DESC;";
        $res_reporte_diario = DB::select($sql_reporte_diario);

        // Concatenar tabla HTML
        $reporte_diario = "";
        $fecha_actual = date("Y-m-d");

        if (count($res_reporte_diario) > 0) {
            $reporte_diario .= "";
            $contador = 1;
            foreach ($res_reporte_diario as $fila) {
                $reporte_diario .= '<tr>';
                $reporte_diario .= '<td data-label="Fecha" class="dt-folio">' . $fila->fecha_sin_hora . '</td>';
                if ($fila->fecha_sin_hora === $fecha_actual) {
                    $reporte_diario .= '<td data-label="dia" class="dt-folio2"><div class="badge badge-warning">' . $fila->dia_en_espanol . '</div></td>';
                } else {
                    $reporte_diario .= '<td data-label="dia" class="dt-folio2">' . $fila->dia_en_espanol . '</td>';
                }
                $reporte_diario .= '<td data-label="Total">$ ' . $fila->total_ventas . '</td>';
                $reporte_diario .= '<td data-label="Propina">$ ' . $fila->total_propina . '</td>';
                $reporte_diario .= '</tr>';
                $contador++;
            }
        } else {
            $reporte_diario .= "No se encontraron resultados";
        }

        //Consulta para el reporte semanal
        $sql_reporte_semanal = DB::table('ventas')
            ->select(
                DB::raw('CONCAT(YEAR(fecha_hora), "-", WEEK(fecha_hora)) AS semana'),
                DB::raw('DATE_ADD(DATE(fecha_hora), INTERVAL(1-DAYOFWEEK(fecha_hora)) DAY) AS inicio_semana'),
                DB::raw('DATE_ADD(DATE(fecha_hora), INTERVAL(7-DAYOFWEEK(fecha_hora)) DAY) AS fin_semana'),
                DB::raw('SUM(subtotal) AS total_ventas'),
                DB::raw('SUM(propina) AS total_propina')
            )
            ->groupBy(DB::raw('CONCAT(YEAR(fecha_hora), "-", WEEK(fecha_hora))'))
            ->orderBy('semana', 'DESC')
            ->get();

        // Concatenar tabla HTML
        $reporte_semanal = "";
        $fecha_actual = date("Y-m-d"); // Obtener la fecha actual en formato "yyyy-mm-dd"
        $semana_actual = date("Y-W", strtotime($fecha_actual)); // Obtener el número de la semana actual en formato "yyyy-W"

        if ($sql_reporte_semanal->count() > 0) {
            $reporte_semanal .= "";
            $contador = 1;
            foreach ($sql_reporte_semanal as $venta) {
                $reporte_semanal .= '<tr>';
                if ($venta->semana === $semana_actual) {
                    $reporte_semanal .= '<td data-label="Fecha" class="dt-folio"><div class="badge badge-warning">' . $venta->inicio_semana . '</div></td>';
                    $reporte_semanal .= '<td data-label="Día" class="dt-folio"><div class="badge badge-warning">' . $venta->fin_semana . '</div></td>';
                } else {
                    $reporte_semanal .= '<td data-label="Fecha" class="dt-folio">' . $venta->inicio_semana . '</td>';
                    $reporte_semanal .= '<td data-label="Día" class="dt-folio">' . $venta->fin_semana . '</td>';
                }
                $reporte_semanal .= '<td data-label="Total">$ ' . $venta->total_ventas . '</td>';
                $reporte_semanal .= '<td data-label="Propina">$ ' . $venta->total_propina . '</td>';
                $reporte_semanal .= '</tr>';
                $contador++;
            }
        } else {
            $reporte_semanal .= "No se encontraron resultados";
        }

        //Consulta para el reporte mensual
        $sql_reporte_mensual = "SELECT YEAR(fecha_hora) AS anio,
           CASE MONTHNAME(fecha_hora)
                WHEN 'January' THEN 'Enero'
                WHEN 'February' THEN 'Febrero'
                WHEN 'March' THEN 'Marzo'
                WHEN 'April' THEN 'Abril'
                WHEN 'May' THEN 'Mayo'
                WHEN 'June' THEN 'Junio'
                WHEN 'July' THEN 'Julio'
                WHEN 'August' THEN 'Agosto'
                WHEN 'September' THEN 'Septiembre'
                WHEN 'October' THEN 'Octubre'
                WHEN 'November' THEN 'Noviembre'
                WHEN 'December' THEN 'Diciembre'
            END AS mes,
            CONCAT(YEAR(fecha_hora), '-', LPAD(MONTH(fecha_hora), 2, '0')) AS mes_anio,
            SUM(subtotal) AS total_ventas,
            SUM(propina) AS total_propina
        FROM ventas
        GROUP BY mes_anio
        ORDER BY mes_anio DESC;";

        $res_reporte_mensual = DB::select($sql_reporte_mensual);

        // Concatenar tabla HTML
        $reporte_mensual = "";
        $fecha_actual = date("Y-m");

        if (count($res_reporte_mensual) > 0) {
            $contador = 1;
            foreach ($res_reporte_mensual as $fila) {
                $reporte_mensual .= '<tr>';
                $reporte_mensual .= '<td data-label="Fecha" class="dt-folio">' . $fila->anio . '</td>';

                if ($fila->mes_anio === $fecha_actual) {
                    $reporte_mensual .= '<td data-label="" class="dt-folio2"><div class="badge badge-warning">' . $fila->mes . '</div></td>';
                } else {
                    $reporte_mensual .= '<td data-label="" class="dt-folio2">' . $fila->mes . '</td>';
                }

                $reporte_mensual .= '<td data-label="Total">$ ' . $fila->total_ventas . '</td>';
                $reporte_mensual .= '<td data-label="Propina">$ ' . $fila->total_propina . '</td>';
                $reporte_mensual .= '</tr>';
                $contador++;
            }
        } else {
            $reporte_mensual .= "No se encontraron resultados";
        }

        $sql_reporte_anual = "SELECT DISTINCT YEAR(fecha_hora) AS anio,
           SUM(subtotal) AS total_ventas,
           SUM(propina) AS total_propina
           FROM ventas
           GROUP BY YEAR(fecha_hora)
           ORDER BY anio DESC";

        $res_reporte_anual = DB::select($sql_reporte_anual);

        // Concatenar tabla HTML
        $reporte_anual = "";
        $fecha_actual = date("Y");

        if (count($res_reporte_anual) > 0) {
            $reporte_anual .= "";
            $contador = 1;
            foreach ($res_reporte_anual as $fila) {
                $reporte_anual .= '<tr>';
                if ($fila->anio == $fecha_actual) {
                    $reporte_anual .= '<td data-label="anio" class="dt-folio"><div class="badge badge-warning">' . $fila->anio . '</div></td>';
                } else {
                    $reporte_anual .= '<td data-label="anio" class="dt-folio">' . $fila->anio . '</td>';
                }
                $reporte_anual .= '<td data-label="Total">$ ' . $fila->total_ventas . '</td>';
                $reporte_anual .= '<td data-label="Propina">$ ' . $fila->total_propina . '</td>';
                $reporte_anual .= '</tr>';
                $contador++;
            }
        } else {
            $reporte_anual .= "No se encontraron resultados";
        }

        return view('ventas.index', compact('reporte_diario', 'reporte_semanal', 'reporte_mensual', 'reporte_anual'));
    }

}