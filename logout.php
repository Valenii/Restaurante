<?php
// Iniciamos la sesión para poder manipularla
session_start();

// Elimina todas las variables de sesión creadas anteriormente
session_unset(); 

// Destruye la sesión actual (borra todo rastro del usuario logueado)
session_destroy(); 

// Redirige al usuario nuevamente a la página de login y registro
header("Location: login-register.php"); 

// Finaliza el script para asegurar que no se ejecute nada más
exit();
?> 
