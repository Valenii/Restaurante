<?php
// Iniciamos la sesión para poder guardar datos del usuario una vez que se loguea
session_start();

// Incluimos el archivo de conexión a la base de datos
include 'conexion.php';

// Recibimos los datos que el usuario envió desde el formulario (correo y contraseña)
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Preparamos la consulta SQL para buscar el usuario que tenga ese correo
$query = "SELECT * FROM usuarios WHERE Correo='$correo'";
$resultado = mysqli_query($conexion, $query);

// Verificamos si la consulta encontró algún usuario con ese correo
if (mysqli_num_rows($resultado) > 0) {
    // Obtenemos los datos del usuario en un array asociativo
    $usuario = mysqli_fetch_assoc($resultado);

    // Verificamos que la contraseña ingresada coincida con la contraseña encriptada de la BD
    if (password_verify($contrasena, $usuario['Contraseña'])) {
        // Si la contraseña es correcta, guardamos el nombre del usuario en la sesión
        $_SESSION['usuario'] = $usuario['Nombre'];
        
        // Redirigimos al usuario a la página principal (index.php)
        header("Location: index.php");
        exit;
    } else {
        // Si la contraseña no coincide, mostramos un mensaje y volvemos al login
        echo "<script>alert('❌ Contraseña incorrecta'); window.location='login.php';</script>";
    }
} else {
    // Si el correo no existe en la base de datos, mostramos un mensaje y volvemos al login
    echo "<script>alert('❌ El correo no está registrado'); window.location='login.php';</script>";
}

// Cerramos la conexión a la base de datos para liberar recursos
mysqli_close($conexion);
?>

