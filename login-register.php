<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login y Registro - FastFood</title>
  <!-- Archivo de estilos -->
  <link rel="stylesheet" href="estilos.css" />
</head>
<body>
  <div class="contenedor">
    <!-- ENCABEZADO -->
    <header class="header">
      <div class="logo">
        <p>MENDO<span>FOOD</span></p>
      </div>
      <nav class="menu">
        <ul class="navegacion">
          <li><a href="index.php">Inicio</a></li>
          <li><a href="Menu.php">Menu</a></li>
          <li><a href="servicios.php">Servicios</a></li>
          <li><a href="We.php">Nosotros</a></li>
          <li><a href="#">Galeria</a></li>
        </ul>
      </nav>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="contenedor-principal">
      <div class="contenedor-formularios">

        <!-- Botones para alternar entre Login y Registro -->
        <div class="botones-toggle">
          <button id="btn-login">Iniciar Sesión</button>
          <button id="btn-register">Registrarse</button>
        </div>

        <!-- FORMULARIOS -->
        <div class="formularios">

          <!-- Formulario de Login -->
          <form id="form-login" class="formulario activo" action="login_usuario_be.php" method="POST">
            <h2>Iniciar Sesión</h2>
            <!-- Correo y contraseña del usuario -->
            <input type="email" placeholder="Correo" name="correo" required />
            <input type="password" placeholder="Contraseña" name="contrasena" required />
            <button type="submit">Entrar</button>
          </form>

          <!-- Formulario de Registro -->
          <form id="form-register" class="formulario" action="registro_usuario_be.php" method="POST">
            <h2>Registrarse</h2>
            <!-- Nombre, correo y contraseña para crear usuario -->
            <input type="text" placeholder="Nombre" name="nombre" required />
            <input type="email" placeholder="Correo" name="correo" required />
            <input type="password" placeholder="Contraseña" name="contrasena" required />
            <button type="submit">Registrarme</button>
          </form>

        </div>
      </div>

      <!-- Imagen decorativa al costado -->
      <div class="contenedor-imagen">
        <img src="imagenes/persona.png" alt="Comida rápida" />
      </div>
    </div>
  </div>

  <!-- Script para manejar la animación y el cambio entre formularios -->
  <script src="script.js"></script>
</body>
</html>
