<?php
include 'conexion.php'; // Incluye la conexión

// Capturamos datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Encriptar la contraseña
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

// Verificar si el correo ya existe
$verificar = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Correo='$correo'");
if (mysqli_num_rows($verificar) > 0) {
    echo "<script>alert('⚠️ Este correo ya está registrado'); window.location='login.php';</script>";
    exit;
}

// Insertar usuario
$query = "INSERT INTO usuarios(Nombre, Correo, `Contraseña`) 
          VALUES('$nombre', '$correo', '$contrasenaHash')";

if (mysqli_query($conexion, $query)) {
    echo "<script>alert('✅ Registro exitoso. Ahora puedes iniciar sesión'); window.location='login.php';</script>";
} else {
    echo "Error: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>

