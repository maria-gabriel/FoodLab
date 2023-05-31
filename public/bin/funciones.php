<?php


function total_orden($idOrden){
// session_start();
include('connection.php');

$sqlsum1 = "SELECT *,ordenes_productos.id AS id_ordenes_p FROM ordenes_productos 
INNER JOIN productos on productos.id=ordenes_productos.id_producto
WHERE ordenes_productos.id_orden=$idOrden;";   //id orden
$resultadosum1 = $conn->query($sqlsum1);
$total_global = 0; // Inicializar la variable con un valor de 0


    
while ($filasum1 = $resultadosum1->fetch_assoc()) {

  if ($filasum1['opciones'] == 0) {
        $total_global = $total_global + ( $filasum1['cantidad'] * $filasum1['precio']);
    }
 
  if($filasum1['opciones']==1){   
      
   $sqlnombrestipos = "SELECT *,ordenes_tipos.id AS id_orde_tip FROM ordenes_tipos 
    INNER JOIN ordenes_productos 
    on ordenes_productos.id = ordenes_tipos.id_ordenes_productos 
    INNER JOIN tipos on tipos.id = ordenes_tipos.id_tipos 
    WHERE ordenes_productos.id={$filasum1['id_ordenes_p']}"; //id ordenes productos//id ordenes productos
 
    $resultado2 = $conn->query($sqlnombrestipos);
    
            //solo se itera un productos porque la orden tiene un tipo
           if ($fila2 = $resultado2->fetch_assoc()) {
         
                $total_tipos= $fila2['costo'];
                $id_tipos=$fila2['id_orde_tip'];
             
                   $sqlnombresespc="SELECT * FROM `ordenes_especificaciones` 
                   INNER JOIN tipos_categorias_especificaciones ON tipos_categorias_especificaciones.id =  id_categorias_especificaciones
                   WHERE id_ordenes_tipos= $id_tipos";
                
                $resultado3 = $conn->query($sqlnombresespc);

                 while ($fila3 = $resultado3->fetch_assoc()) {

                    $total_tipos=$total_tipos + $fila3['costo_extra'] ;
                 }

                  $total_global = $total_global + ($total_tipos  * $filasum1['cantidad']);
            }
   }
 
 }

mysqli_close($conn);

return $total_global;

}

?>



