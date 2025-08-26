<?php
session_start();
include 'conexion.php';

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Buscar usuario
$query = "SELECT * FROM usuarios WHERE Correo='$correo'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    $usuario = mysqli_fetch_assoc($resultado);

    // Verificar la contraseña encriptada
    if (password_verify($contrasena, $usuario['Contraseña'])) {
        // Guardar datos en sesión
        $_SESSION['usuario'] = $usuario['Nombre'];
        
        // Redirigir al inicio
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('❌ Contraseña incorrecta'); window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('❌ El correo no está registrado'); window.location='login.php';</script>";
}

mysqli_close($conexion);
?>

