<?php
// Conexion a la base de datos
$host = '50.6.138.109';
$usuario = 'catbobbe_foodlab_user_admin';
$contrasena_bd = 'Samuelqp6198@';
$base_de_datos = 'catbobbe_foodlab';
$puerto = 3306;

$conn = new mysqli($host, $usuario, $contrasena_bd, $base_de_datos, $puerto);
mysqli_set_charset($conn,"utf8");

// Verificar si la conexion es correcta
if ($conn->connect_errno) {
  http_response_code(500); // Codigo de error 500 Internal Server Error
  echo json_encode(array('mensaje' => 'Error de conexion con la base de datos.'));
  exit();
}
?>