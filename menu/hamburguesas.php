<?php
// Iniciamos sesión para poder guardar datos del usuario (ejemplo: login activo)
session_start();

// Conexión a la base de datos MySQL
$conexion = new mysqli("localhost", "root", "", "restaurante_log_reg");

// Verificamos si hubo error al conectar
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// IDs de hamburguesas que queremos mostrar en esta página
$idsHamburguesas = [8,1,10,11,12,4];

// Convertimos el array de IDs en una cadena separada por comas para usar en SQL
$idsStr = implode(",", $idsHamburguesas);

// Array donde guardaremos los productos traídos de la base de datos
$productos = [];

// Consulta SQL para traer solo los productos correspondientes a los IDs indicados
$resultado = $conexion->query("SELECT ID, Nombre, Precio, Stock FROM productos WHERE ID IN ($idsStr)");

// Verificamos si la consulta fue exitosa
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        // Guardamos cada producto en el array $productos usando su ID como clave
        $productos[$row['ID']] = $row;
    }
} else {
    // Si hubo error en la consulta, mostramos el error y detenemos el script
    die("Error en la consulta: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hamburguesas - MendoFood</title>
  <!-- Normalize.css para estilos base consistentes entre navegadores -->
  <link rel="stylesheet" href="../normalize.css" />
  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="../index.css" />
  <style>
    /* Estilos del modal del carrito */
    .modal { 
      display: none; /* Oculto por defecto */
      position: fixed; /* Fijo en toda la pantalla */
      top: 0; left: 0; width: 100%; height: 100%; 
      background-color: rgba(0,0,0,0.6); /* Fondo semitransparente */
      justify-content: center; align-items: center; z-index: 1000;
    }
    .modal-contenido { 
      background: white; padding: 20px; border-radius: 10px; 
      width: 320px; max-height: 80vh; overflow-y: auto;
    }
    .modal-contenido ul { list-style: none; padding: 0; margin: 0 0 10px 0; }
    .modal-contenido li { 
      border-bottom: 1px solid #ccc; padding: 5px 0; 
      display: flex; justify-content: space-between; align-items: center; 
    }
    .modal-contenido li span.nombre-producto { flex: 1; }
    .modal-contenido li button.eliminar { 
      background: none; color: red; border: none; cursor: pointer; font-size: 20px; 
      display: flex; align-items: center; justify-content: center; 
    }
    .modal-contenido li button.eliminar:hover { color: darkred; }
    .carrito { position: relative; display: inline-block; margin-left: 20px; cursor: pointer; }
    .carrito-icono { width: 30px; }
    #contador-carrito { 
      position: absolute; top: -8px; right: -10px; background-color: orange; 
      color: white; font-size: 12px; font-weight: bold; padding: 3px 6px; 
      border-radius: 50%; min-width: 18px; text-align: center; 
    }
    .cerrar { 
      background: #444; color: white; padding: 8px 15px; border: none; cursor: pointer; 
      border-radius: 5px; margin-top: 10px; width: 100%; font-weight: 700; 
    }
  </style>
</head>
<body>
<div class="contenedor">
  <!-- HEADER -->
  <header class="header">
    <div class="logo"><p>MENDO<span>FOOD</span></p></div>
    <div class="hamburguesa"><img src="../Imagenes/menu.png" alt="Menu hamburguesa"></div>
    <nav class="menu">
      <ul class="navegacion">
        <li><a href="../index.php">Inicio</a></li>
        <li><a href="../Menu.php">Menú</a></li>
        <li><a href="../servicios.php">Servicios</a></li>
        <li><a href="../We.php">Nosotros</a></li>
        <li><a href="#">Galería</a></li>
        <li>
          <!-- Comprobamos si el usuario está logueado -->
          <?php if(isset($_SESSION['usuario'])): ?>
            <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            <a href="../logout.php">Cerrar sesión</a>
          <?php else: ?>
            <a href="../login-register.php">Iniciar Sesión</a>
            <a href="../login-register.php">Registrarse</a>
          <?php endif; ?>
        </li>
      </ul>

      <!-- Icono del carrito -->
      <div class="carrito" id="abrir-carrito">
        <img src="../Imagenes/icons8-carrito-de-compras-30.png" alt="Carrito" class="carrito-icono" />
        <span id="contador-carrito">0</span>
      </div>
    </nav>
  </header>

<!-- Sección de hamburguesas -->
<main class="comida">
  <h2 class="comida--titulo">Hamburguesas</h2>
  <div class="platos">

    <!-- Cada hamburguesa es un artículo -->
    <!-- Hamburguesa 1: Clásica -->
    <article class="plato">
      <img src="../Imagenes/Hamburguesas.png" alt="Clásica" />
      <h1>Clásica</h1>
      <p>Pan, doble carne, cheddar, lechuga, tomate, pepinos y salsa especial.</p>
      <div class="plato--info">
        <!-- Mostramos precio y stock desde la base de datos -->
        <p data-precio="<?php echo $productos[8]['Precio']; ?>">$<?php echo $productos[8]['Precio']; ?></p>
        <p>Stock: <span class="stock" data-id="8"><?php echo $productos[8]['Stock']; ?></span></p>
        <!-- Botón para agregar al carrito -->
        <button type="button" class="btn-agregar"
                data-nombre="Clásica"
                data-precio="<?php echo $productos[8]['Precio']; ?>"
                data-categoria="hamburguesas"
                data-id="8">+</button>
      </div>
    </article>

    <!-- Hamburguesa 2: Hamburguesa de Carne -->
    <article class="plato">
      <img src="../Imagenes/hamburguesa.png" alt="Hamburguesa de Carne" />
      <h1>Hamburguesa de Carne</h1>
      <p>Deliciosa hamburguesa de carne vacuna con bacon crocante, lechuga fresca, tomate, queso cheddar fundido, cebolla morada y salsa especial.</p>
      <div class="plato--info">
        <p data-precio="<?php echo $productos[1]['Precio']; ?>">$<?php echo $productos[1]['Precio']; ?></p>
        <p>Stock: <span class="stock" data-id="1"><?php echo $productos[1]['Stock']; ?></span></p>
        <button type="button" class="btn-agregar"
                data-nombre="Hamburguesa de Carne"
                data-precio="<?php echo $productos[1]['Precio']; ?>"
                data-categoria="hamburguesas"
                data-id="1">+</button>
      </div>
    </article>

    <!-- Hamburguesa 3: Pollo Crocante -->
    <article class="plato">
      <img src="../Imagenes/Hamburguesa con pollo frito.png" alt="Hamburguesa de Pollo Crocante" />
      <h1>Hamburguesa de Pollo Crocante</h1>
      <p>Pollo frito crocante, tomate fresco, queso cheddar fundido, pepinillos y salsa picante.</p>
      <div class="plato--info">
        <p data-precio="<?php echo $productos[10]['Precio']; ?>">$<?php echo $productos[10]['Precio']; ?></p>
        <p>Stock: <span class="stock" data-id="10"><?php echo $productos[10]['Stock']; ?></span></p>
        <button type="button" class="btn-agregar"
                data-nombre="Hamburguesa de Pollo Crocante"
                data-precio="<?php echo $productos[10]['Precio']; ?>"
                data-categoria="hamburguesas"
                data-id="10">+</button>
      </div>
    </article>

    <!-- Hamburguesa 4: Con Aros de Cebolla -->
    <article class="plato">
      <img src="../Imagenes/Hamburguesa con aros de cebolla.png" alt="Hamburguesa con Aros de Cebolla" />
      <h1>Hamburguesa con Aros de Cebolla</h1>
      <p>Carne vacuna, queso cheddar, tocino crocante y aros de cebolla en pan artesanal tostado.</p>
      <div class="plato--info">
        <p data-precio="<?php echo $productos[11]['Precio']; ?>">$<?php echo $productos[11]['Precio']; ?></p>
        <p>Stock: <span class="stock" data-id="11"><?php echo $productos[11]['Stock']; ?></span></p>
        <button type="button" class="btn-agregar"
                data-nombre="Hamburguesa con Aros de Cebolla"
                data-precio="<?php echo $productos[11]['Precio']; ?>"
                data-categoria="hamburguesas"
                data-id="11">+</button>
      </div>
    </article>

    <!-- Hamburguesa 5: Pollo simple -->
    <article class="plato">
      <img src="../Imagenes/Hamburguesa peqeu.png" alt="Hamburguesa de Pollo" />
      <h1>Hamburguesa de Pollo</h1>
      <p>Pollo frito crocante, queso cheddar fundido y lechuga en pan artesanal recién tostado.</p>
      <div class="plato--info">
        <p data-precio="<?php echo $productos[12]['Precio']; ?>">$<?php echo $productos[12]['Precio']; ?></p>
        <p>Stock: <span class="stock" data-id="12"><?php echo $productos[12]['Stock']; ?></span></p>
        <button type="button" class="btn-agregar"
                data-nombre="Hamburguesa de Pollo"
                data-precio="<?php echo $productos[12]['Precio']; ?>"
                data-categoria="hamburguesas"
                data-id="12">+</button>
      </div>
    </article>

    <!-- Hamburguesa 6: XL -->
    <article class="plato">
      <img src="../Imagenes/hamburguesa-grande.png" alt="Hamburguesa XL" />
      <h1>Hamburguesa XL</h1>
      <p>Doble carne vacuna, bacon crocante, doble queso cheddar, lechuga, tomate, cebolla caramelizada y salsa especial en pan artesanal.</p>
      <div class="plato--info">
        <p data-precio="<?php echo $productos[4]['Precio']; ?>">$<?php echo $productos[4]['Precio']; ?></p>
        <p>Stock: <span class="stock" data-id="4"><?php echo $productos[4]['Stock']; ?></span></p>
        <button type="button" class="btn-agregar"
                data-nombre="Hamburguesa XL"
                data-precio="<?php echo $productos[4]['Precio']; ?>"
                data-categoria="hamburguesas"
                data-id="4">+</button>
      </div>
    </article>

  </div>
</main>

</div>

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
// Referencias a elementos del DOM
const abrirCarrito = document.getElementById("abrir-carrito");
const cerrarCarrito = document.getElementById("cerrar-carrito");
const modal = document.getElementById("modal-carrito");
const listaCarrito = document.getElementById("lista-carrito");
const totalCarrito = document.getElementById("total-carrito");
const contadorCarrito = document.getElementById("contador-carrito");
const botonesAgregar = document.querySelectorAll(".btn-agregar");
const btnComprar = document.getElementById("btn-comprar");

// Comprobamos si el usuario está logueado (variable PHP pasada a JS)
const usuarioLogueado = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;

// Recuperamos carrito del localStorage o iniciamos vacío
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

// Actualiza el contador del carrito
function actualizarContador() {
  const totalCantidad = carrito.reduce((acc, item) => acc + item.cantidad, 0);
  contadorCarrito.textContent = totalCantidad;
}

// Muestra los productos en el modal del carrito
function mostrarCarrito() {
  listaCarrito.innerHTML = "";
  let total = 0;
  carrito.forEach((item, index) => {
    total += item.precio * item.cantidad;
    const li = document.createElement("li");
    li.innerHTML = `<span class="nombre-producto">${item.nombre} x${item.cantidad}</span>
                    <span>$${(item.precio * item.cantidad).toFixed(2)}</span>
                    <button class="eliminar">×</button>`;
    // Función para eliminar producto del carrito
    li.querySelector(".eliminar").addEventListener("click", () => {
      carrito.splice(index, 1);
      guardarCarrito();
      mostrarCarrito();
      actualizarContador();
    });
    listaCarrito.appendChild(li);
  });
  totalCarrito.textContent = total.toFixed(2);
}

// Guardar carrito en localStorage
function guardarCarrito() {
  localStorage.setItem("carrito", JSON.stringify(carrito));
}

// Agrega producto al carrito
function agregarAlCarrito(nombre, precio, categoria, producto_id) {
  producto_id = parseInt(producto_id);
  const index = carrito.findIndex(item => item.nombre === nombre && item.categoria === categoria);
  if (index !== -1) {
    carrito[index].cantidad++;
  } else {
    carrito.push({ nombre, precio: parseFloat(precio), cantidad: 1, categoria, producto_id });
  }
  guardarCarrito();
  actualizarContador();
}

// Evento al presionar cualquier botón de agregar
botonesAgregar.forEach(boton => {
  boton.addEventListener("click", () => {
    const nombre = boton.getAttribute("data-nombre");
    const precio = boton.getAttribute("data-precio");
    const categoria = boton.getAttribute("data-categoria");
    const producto_id = boton.getAttribute("data-id");
    agregarAlCarrito(nombre, precio, categoria, producto_id);
    mostrarCarrito();
  });
});

// Mostrar modal al hacer click en carrito
abrirCarrito.addEventListener("click", () => { 
  mostrarCarrito(); 
  modal.style.display = "flex"; 
});

// Cerrar modal
cerrarCarrito.addEventListener("click", () => { modal.style.display = "none"; });

// Cerrar modal si se hace click fuera del contenido
window.addEventListener("click", e => { 
  if(e.target === modal) modal.style.display = "none"; 
});

// Evento para comprar los productos del carrito
btnComprar.addEventListener("click", () => {
  if (!usuarioLogueado) {
    alert("⚠️ Primero tienes que iniciar sesión para comprar.");
    window.location.href = "../login-register.php";
  } else {
    fetch("../procesar_compra.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "carrito=" + encodeURIComponent(JSON.stringify(carrito))
    })
    .then(res => res.json())
    .then(data => {
      if(data.success){
        alert(data.success);
        carrito = [];
        guardarCarrito();
        mostrarCarrito();
        actualizarContador();
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch(err => alert("Error al procesar la compra: " + err));
  }
});

// Inicializamos contador al cargar la página
document.addEventListener("DOMContentLoaded", actualizarContador);
</script>
</body>
</html>
