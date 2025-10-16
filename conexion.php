<?php
// Conexión a la base de datos usando mysqli
$conexion = mysqli_connect("localhost", "root", "", "restaurante_log_reg");

// Verificamos si la conexión tuvo éxito
if(!$conexion){
    // Si hay error, termina la ejecución y muestra el mensaje de error
    die("Error en la conexión: " . mysqli_connect_error());
}
?>
