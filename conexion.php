<?php
$conexion = mysqli_connect("localhost", "root", "", "restaurante_log_reg");

if(!$conexion){
    die("Error en la conexión: " . mysqli_connect_error());
}
?>
