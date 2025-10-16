<?php
// Iniciamos la sesión para poder usar variables de sesión (usuario logueado)
session_start();

// Conexión a la base de datos MySQL
$conexion = new mysqli("localhost", "root", "", "restaurante_log_reg");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta SQL: obtenemos todos los productos de la tabla 'productos'
$resultado = $conexion->query("SELECT ID, Nombre, Precio, Stock FROM productos");

// Creamos un array vacío para guardar los productos
$productos = [];
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        // Guardamos cada producto en el array usando su ID como índice
        $productos[$row['ID']] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menú - MendoFood</title>
  <link rel="stylesheet" href="normalize.css" />
  <link rel="stylesheet" href="index.css" />

  <!-- Ionicons para los íconos -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <style>
    /* Estilos del modal del carrito */
    .modal {
      display: none; /* Inicialmente oculto */
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.6); /* Fondo oscuro transparente */
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    .modal-contenido {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 320px;
      max-height: 80vh;
      overflow-y: auto; /* Scroll si hay muchos productos */
    }
    .modal-contenido ul { list-style: none; padding: 0; margin: 0 0 10px 0; }
    .modal-contenido li {
      border-bottom: 1px solid #ccc;
      padding: 5px 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .modal-contenido li span.nombre-producto { flex: 1; }
    .modal-contenido li button.eliminar {
      background: none;
      color: red;
      border: none;
      cursor: pointer;
      font-size: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .modal-contenido li button.eliminar:hover { color: darkred; }

    /* Estilos del carrito en el header */
    .carrito { position: relative; display: inline-block; margin-left: 20px; cursor: pointer; }
    .carrito-icono { width: 30px; }
    #contador-carrito {
      position: absolute;
      top: -8px;
      right: -10px;
      background-color: orange;
      color: white;
      font-size: 12px;
      font-weight: bold;
      padding: 3px 6px;
      border-radius: 50%;
      min-width: 18px;
      text-align: center;
    }

    /* Botones de cerrar modal o comprar */
    .cerrar {
      background: #444;
      color: white;
      padding: 8px 15px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      margin-top: 10px;
      width: 100%;
      font-weight: 700;
    }
  </style>
</head>
<body>
<div class="contenedor">
  <!-- Header con logo, menú y carrito -->
  <header class="header">
    <div class="logo"><p>MENDO<span>FOOD</span></p></div>
    <div class="hamburguesa"><img src="Imagenes/menu.png" alt="Menu hamburguesa"></div>
    <nav class="menu">
      <ul class="navegacion">
        <li><a href="index.php">Inicio</a></li>
        <li><a href="Menu.php">Menú</a></li>
        <li><a href="servicios.php">Servicios</a></li>
        <li><a href="We.php">Nosotros</a></li>
        <li><a href="#">Galería</a></li>
        <li>
          <?php if(isset($_SESSION['usuario'])): ?>
            <!-- Si el usuario está logueado -->
            <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            <a href="logout.php">Cerrar sesión</a>
          <?php else: ?>
            <!-- Si no está logueado -->
            <a href="login-register.php">Iniciar Sesión</a>
            <a href="login-register.php">Registrarse</a>
          <?php endif; ?>
        </li>
      </ul>

      <!-- Icono de carrito -->
      <div class="carrito" id="abrir-carrito">
        <img src="Imagenes/icons8-carrito-de-compras-30.png" alt="Carrito" class="carrito-icono" />
        <span id="contador-carrito">0</span>
      </div>
    </nav>
  </header>

  <!-- Sección del menú de comida -->
<main class="comida">
  <h2 class="comida--titulo">Nuestro Menú</h2>
  <div class="platos">
    <article class="plato">
      <a href="menu/hamburguesas.php">
        <img src="Imagenes/Hamburguesas.png" alt="Hamburguesas" />
      </a>
      <h1>Hamburguesas</h1>
    </article>
    <article class="plato">
      <a href="menu/papas.php">
        <img src="Imagenes/Papas.png" alt="Papas Fritas" />
      </a>
      <h1>Papas Fritas</h1>
    </article>
    <article class="plato">
      <a href="menu/bebidas.php">
        <img src="Imagenes/Bebida.png" alt="Bebidas" />
      </a>
      <h1>Bebidas</h1>
    </article>
    <article class="plato">
      <a href="menu/pollo.php">
        <img src="Imagenes/Pollo_fritos.png" alt="Pollo Frito" />
      </a>
      <h1>Pollo Frito</h1>
    </article>
  </div>
</main>

<!-- Modal del carrito -->
<div class="modal" id="modal-carrito">
  <div class="modal-contenido">
    <h2>Tu carrito</h2>
    <ul id="lista-carrito"></ul>
    <p><strong>Total:</strong> $<span id="total-carrito">0.00</span></p>
    <button class="cerrar" id="cerrar-carrito">Cerrar</button>
    <button class="cerrar" id="btn-comprar">Comprar</button>
  </div>
</div>

<script>
// Selecciones de elementos del DOM
const abrirCarrito = document.getElementById("abrir-carrito");
const cerrarCarrito = document.getElementById("cerrar-carrito");
const modal = document.getElementById("modal-carrito");
const listaCarrito = document.getElementById("lista-carrito");
const totalCarrito = document.getElementById("total-carrito");
const contadorCarrito = document.getElementById("contador-carrito");
const btnComprar = document.getElementById("btn-comprar");

// Saber si el usuario está logueado
const usuarioLogueado = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;

// Cargar carrito desde localStorage (persistencia en navegador)
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
// Filtrar elementos válidos
carrito = carrito.filter(item => item.nombre && item.precio && item.producto_id);
localStorage.setItem("carrito", JSON.stringify(carrito));

// Función para actualizar el contador del carrito
function actualizarContador() {
  const totalCantidad = carrito.reduce((acc, item) => acc + item.cantidad, 0);
  contadorCarrito.textContent = totalCantidad;
}

// Función para mostrar los productos en el modal del carrito
function mostrarCarrito() {
  listaCarrito.innerHTML = "";
  let total = 0;
  carrito.forEach((item, index) => {
    total += item.precio * item.cantidad;

    // Creamos elementos HTML dinámicamente
    const li = document.createElement("li");

    const nombreSpan = document.createElement("span");
    nombreSpan.textContent = `${item.nombre} x${item.cantidad}`;
    nombreSpan.classList.add("nombre-producto");

    const precioSpan = document.createElement("span");
    precioSpan.textContent = `$${(item.precio * item.cantidad).toFixed(2)}`;
    // Botón para eliminar productos

    const botonEliminar = document.createElement("button");
    botonEliminar.innerHTML = '<ion-icon name="close-outline"></ion-icon>';
    botonEliminar.classList.add("eliminar");
    botonEliminar.addEventListener("click", () => {
      carrito.splice(index, 1); // Eliminar producto del carrito
      localStorage.setItem("carrito", JSON.stringify(carrito));
      mostrarCarrito();
      actualizarContador();
    });
  // Armamos la fila del carrito
    li.appendChild(nombreSpan);
    li.appendChild(precioSpan);
    li.appendChild(botonEliminar);
    listaCarrito.appendChild(li);
  });
  totalCarrito.textContent = total.toFixed(2);
}

// Abrir modal del carrito
abrirCarrito.addEventListener("click", () => {
  mostrarCarrito();
  modal.style.display = "flex";
});

// Cerrar modal
cerrarCarrito.addEventListener("click", () => { modal.style.display = "none"; });
window.addEventListener("click", e => { if(e.target === modal) modal.style.display = "none"; });

// Comprar productos
btnComprar.addEventListener("click", () => {
  if (!usuarioLogueado) {
    // Si no está logueado, mostrar alerta y redirigir
    alert("⚠️ Primero tienes que iniciar sesión para comprar.");
    window.location.href = "login-register.php";
  } else {
   // Enviamos los datos a PHP con fetch
    fetch("procesar_compra.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "carrito=" + encodeURIComponent(JSON.stringify(carrito))
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(data.success);
        carrito = [];
        localStorage.setItem("carrito", JSON.stringify(carrito));
        mostrarCarrito();
        actualizarContador();
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch(err => alert("Error al procesar la compra: " + err));
  }
});

// Actualizar contador al cargar la página
document.addEventListener("DOMContentLoaded", actualizarContador);
</script>

</body>
</html>
