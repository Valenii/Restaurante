<?php
session_start(); 
// Conexión a la base de datos MySQL

$conexion = new mysqli("localhost", "root", "", "restaurante_log_reg");
// Verificamos si hay error en la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// IDs de los productos que queremos mostrar (en este caso, bebidas)
// NOTA: sería más claro llamarlas $idsBebidas en lugar de $idsHamburguesas
$idsBebidas = [5,6,7];
$idsStr = implode(",", $idsBebidas); // Convierte el array en "5,6,7"

// Traemos solo los productos necesarios desde la base de datos
$productos = []; // Array donde guardaremos los productos
$resultado = $conexion->query("SELECT ID, Nombre, Precio, Stock FROM productos WHERE ID IN ($idsStr)");
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[$row['ID']] = $row; // Guardamos los productos usando su ID como clave
    }
} else {
    die("Error en la consulta: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bebidas - MendoFood</title>
  <link rel="stylesheet" href="../normalize.css" /> <!-- Reset de estilos -->
  <link rel="stylesheet" href="../index.css" /> <!-- Estilos generales del sitio -->
<style>
    /* Estilos del modal del carrito */
    .modal { 
      display: none; /* Oculto por defecto */
      position: fixed; 
      top: 0; left: 0; 
      width: 100%; height: 100%; 
      background-color: rgba(0,0,0,0.6); /* Fondo semi-transparente */
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
      overflow-y: auto; 
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
    .carrito { position: relative; display: inline-block; margin-left: 20px; cursor: pointer; }
    .carrito-icono { width: 30px; }
    #contador-carrito { 
      position: absolute; 
      top: -8px; right: -10px; 
      background-color: orange; 
      color: white; 
      font-size: 12px; 
      font-weight: bold; 
      padding: 3px 6px; 
      border-radius: 50%; 
      min-width: 18px; 
      text-align: center; 
    }
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
            <?php if(isset($_SESSION['usuario'])): ?>
              <!-- Muestra el usuario logueado y opción de cerrar sesión -->
              <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
              <a href="../logout.php">Cerrar sesión</a>
            <?php else: ?>
              <!-- Muestra links de login y registro -->
              <a href="../login-register.php">Iniciar Sesión</a>
              <a href="../login-register.php">Registrarse</a>
            <?php endif; ?>
          </li>
        </ul>
        <!-- Carrito dentro del nav -->
        <div class="carrito" id="abrir-carrito">
          <img src="../Imagenes/icons8-carrito-de-compras-30.png" alt="Carrito" class="carrito-icono" />
          <span id="contador-carrito">0</span>
        </div>
      </nav>
   </header>

   <!-- Sección de Bebidas -->
<main class="comida">
  <h2 class="comida--titulo">Bebidas</h2>
  <div class="platos">

    <!-- Bebida 1: Bebida Clásica -->
    <article class="plato">
      <img src="../Imagenes/Bebida.png" alt="Bebida Clásica" />
      <h1>Bebida Clásica</h1>
      <p>Refrescante y equilibrada, la bebida clásica que acompaña perfectamente cada bocado...</p>
      <div class="plato--info">
        <!-- Precio dinámico desde la DB -->
        <p data-precio="<?php echo $productos[5]['Precio']; ?>">$<?php echo $productos[5]['Precio']; ?></p>
        <!-- Stock dinámico desde la DB -->
        <p>Stock: <span class="stock" data-id="5"><?php echo $productos[5]['Stock']; ?></span></p>
        <!-- Botón para agregar al carrito -->
        <button type="button" class="btn-agregar"
                data-nombre="Bebida Clásica"
                data-precio="<?php echo $productos[5]['Precio']; ?>"
                data-categoria="bebidas"
                data-id="5">+</button>
      </div>
    </article>

    <!-- Bebida 2: Agua Sin Gas -->
    <article class="plato">
      <img src="../Imagenes/Aguas.png" alt="Agua Sin Gas" />
      <h1>Agua Sin Gas</h1>
      <p>Pureza en cada sorbo, agua sin gas cristalina que hidrata y refresca...</p>
      <div class="plato--info">
        <p data-precio="<?php echo $productos[6]['Precio']; ?>">$<?php echo $productos[6]['Precio']; ?></p>
        <p>Stock: <span class="stock" data-id="6"><?php echo $productos[6]['Stock']; ?></span></p>
        <button type="button" class="btn-agregar"
                data-nombre="Agua Sin Gas"
                data-precio="<?php echo $productos[6]['Precio']; ?>"
                data-categoria="bebidas"
                data-id="6">+</button>
      </div>
    </article>

    <!-- Bebida 3: Lata de Fanta -->
    <article class="plato">
      <img src="../Imagenes/Lata de Fanta.png" alt="Lata de Fanta" />
      <h1>Lata de Fanta</h1>
      <p>Explosión cítrica y burbujeante que despierta tus sentidos...</p>
      <div class="plato--info">
        <p data-precio="<?php echo $productos[7]['Precio']; ?>">$<?php echo $productos[7]['Precio']; ?></p>
        <p>Stock: <span class="stock" data-id="7"><?php echo $productos[7]['Stock']; ?></span></p>
        <button type="button" class="btn-agregar"
                data-nombre="Lata de Fanta"
                data-precio="<?php echo $productos[7]['Precio']; ?>"
                data-categoria="bebidas"
                data-id="7">+</button>
      </div>
    </article>

  </div>
</main>

<!-- Modal del carrito -->
<div class="modal" id="modal-carrito">
  <div class="modal-contenido">
    <h2>Tu carrito</h2>
    <ul id="lista-carrito"></ul> <!-- Lista de productos agregados -->
    <p><strong>Total:</strong> $<span id="total-carrito">0.00</span></p>
    <button class="cerrar" id="cerrar-carrito">Cerrar</button>
    <button class="cerrar" id="btn-comprar">Comprar</button>
  </div>
</div>

<script>
  // Elementos del DOM
  const abrirCarrito = document.getElementById("abrir-carrito");
  const cerrarCarrito = document.getElementById("cerrar-carrito");
  const modal = document.getElementById("modal-carrito");
  const listaCarrito = document.getElementById("lista-carrito");
  const totalCarrito = document.getElementById("total-carrito");
  const contadorCarrito = document.getElementById("contador-carrito");
  const botonesAgregar = document.querySelectorAll(".btn-agregar");
  const btnComprar = document.getElementById("btn-comprar");
  const usuarioLogueado = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>; // true si hay usuario logueado

  // Cargar carrito desde localStorage o crear uno vacío
  let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  carrito = carrito.filter(item => item.nombre && item.precio && item.producto_id); // limpieza
  localStorage.setItem("carrito", JSON.stringify(carrito));

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
      const nombreSpan = document.createElement("span");
      nombreSpan.textContent = `${item.nombre} x${item.cantidad}`;
      nombreSpan.classList.add("nombre-producto");

      const precioSpan = document.createElement("span");
      precioSpan.textContent = `$${(item.precio * item.cantidad).toFixed(2)}`;

      const botonEliminar = document.createElement("button");
      botonEliminar.innerHTML = '<ion-icon name="close-outline"></ion-icon>';
      botonEliminar.classList.add("eliminar");
      botonEliminar.addEventListener("click", () => eliminarProducto(index));

      li.appendChild(nombreSpan);
      li.appendChild(precioSpan);
      li.appendChild(botonEliminar);
      listaCarrito.appendChild(li);
    });
    totalCarrito.textContent = total.toFixed(2);
  }

  // Elimina un producto del carrito
  function eliminarProducto(index) {
    carrito.splice(index, 1);
    guardarCarrito();
    mostrarCarrito();
    actualizarContador();
  }

  // Guarda el carrito en localStorage
  function guardarCarrito() {
    localStorage.setItem("carrito", JSON.stringify(carrito));
  }

  // Agrega un producto al carrito
  function agregarAlCarrito(nombre, precio, categoria, producto_id) {
    producto_id = parseInt(producto_id);
    const index = carrito.findIndex(item => item.nombre === nombre && item.categoria === categoria);
    if (index !== -1) {
      carrito[index].cantidad++; // Si ya existe, aumenta cantidad
    } else {
      carrito.push({ nombre, precio: parseFloat(precio), cantidad: 1, categoria, producto_id });
    }
    guardarCarrito();
    actualizarContador();
  }

  // Eventos para los botones de agregar
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

  // Abrir y cerrar modal del carrito
  abrirCarrito.addEventListener("click", () => { mostrarCarrito(); modal.style.display = "flex"; });
  cerrarCarrito.addEventListener("click", () => { modal.style.display = "none"; });
  window.addEventListener("click", e => { if(e.target === modal) modal.style.display = "none"; });

  actualizarContador(); // Inicializa contador al cargar la página

  // Comprar productos (restar stock desde PHP)
btnComprar.addEventListener("click",()=>{
  if(!usuarioLogueado){
    alert("⚠️ Primero tienes que iniciar sesión para comprar.");
    window.location.href="login-register.php";
  } else {
    // Creamos un JSON con productos y cantidades
    const payload = { productos: carrito.map(item=>({id:item.producto_id,cantidad:item.cantidad})) };

    // Enviamos los datos a PHP con fetch
    fetch("procesar_stock.php",{
      method:"POST",
      headers:{"Content-Type":"application/json"},
      body:JSON.stringify(payload)
    })
    .then(res=>res.json())
    .then(data=>{
      if(data.mensaje){
        alert("Compra realizada con éxito.");
        carrito=[];
        guardarCarrito();
        mostrarCarrito();
        actualizarContador();
      } else {
        alert("Error al actualizar stock: "+data.error);
      }
    }).catch(err=>alert("Error al procesar la compra: "+err));
  }
});
</script>
</body>
</html>
