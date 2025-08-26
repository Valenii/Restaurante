<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hamburguesas - MendoFood</title>
  <link rel="stylesheet" href="../normalize.css" />
  <link rel="stylesheet" href="../index.css" />
  <style>
    /* Modal del carrito */
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); justify-content: center; align-items: center; z-index: 1000; }
    .modal-contenido { background: white; padding: 20px; border-radius: 10px; width: 320px; max-height: 80vh; overflow-y: auto; }
    .modal-contenido ul { list-style: none; padding: 0; margin: 0 0 10px 0; }
    .modal-contenido li { border-bottom: 1px solid #ccc; padding: 5px 0; display: flex; justify-content: space-between; align-items: center; }
    .modal-contenido li span.nombre-producto { flex: 1; }
    .modal-contenido li button.eliminar { background: none; color: red; border: none; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; }
    .modal-contenido li button.eliminar:hover { color: darkred; }
    .carrito { position: relative; display: inline-block; margin-left: 20px; cursor: pointer; }
    .carrito-icono { width: 30px; }
    #contador-carrito { position: absolute; top: -8px; right: -10px; background-color: orange; color: white; font-size: 12px; font-weight: bold; padding: 3px 6px; border-radius: 50%; min-width: 18px; text-align: center; }
    .cerrar { background: #444; color: white; padding: 8px 15px; border: none; cursor: pointer; border-radius: 5px; margin-top: 10px; width: 100%; font-weight: 700; }
  </style>
</head>
<body>

  <div class="contenedor">
    <!-- HEADER con carrito -->
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
              <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
              <a href="../logout.php">Cerrar sesión</a>
            <?php else: ?>
              <a href="../login-register.php">Iniciar Sesión</a>
              <a href="../login-register.php">Registrarse</a>
            <?php endif; ?>
          </li>
        </ul>

        <!-- Carrito -->
        <div class="carrito" id="abrir-carrito">
          <img src="../Imagenes/icons8-carrito-de-compras-30.png" alt="Carrito" class="carrito-icono" />
          <span id="contador-carrito">0</span>
        </div>
      </nav>
    </header>

     <!-- Sección de Pollo Frito -->
  <main class="comida">
    <h2 class="comida--titulo">Pollo Frito</h2>
    <div class="platos">

      <!-- Pollo Frito 1 -->
      <article class="plato">
        <img src="../Imagenes/Pollo_fritos.png" alt="Pollo Frito Clásico" />
        <h1>Pollo Frito Clásico</h1>
        <p>Trozos de pollo frito clásico, dorados a la perfección y con un rebozado crujiente que encierra un interior jugoso y sabroso. ¡Un favorito que nunca falla!</p>
        <div class="plato--info">
          <p>$7.99</p>
          <button type="button" class="btn-agregar" data-id="1" data-nombre="Pollo Frito Clásico" data-precio="7.99" data-categoria="Pollo Frito">+</button>
        </div>
      </article>

      <!-- Pollo Frito 2 -->
      <article class="plato">
        <img src="../Imagenes/pequeña cubeta de pollo frito.png" alt="Pollo Frito Pequeño" />
        <h1>Cubeta Pequeña</h1>
        <p>Porción pequeña de pollo frito dorado y crujiente, ideal para disfrutar un snack irresistible y lleno de sabor en cualquier momento.</p>
        <div class="plato--info">
          <p>$9.49</p>
          <button type="button" class="btn-agregar" data-id="2" data-nombre="Cubeta Pequeña" data-precio="9.49" data-categoria="Pollo Frito">+</button>
        </div>
      </article>

      <!-- Pollo Frito 3 -->
      <article class="plato">
        <img src="../Imagenes/cubeta de pollo frito.png" alt="Pollo Frito Mediano" />
        <h1>Cubeta Mediana</h1>
        <p>Generosa cubeta con trozos de pollo frito crujiente por fuera y jugoso por dentro, perfecta para compartir y satisfacer tu antojo.</p>
        <div class="plato--info">
          <p>$12.99</p>
          <button type="button" class="btn-agregar" data-id="3" data-nombre="Cubeta Mediana" data-precio="12.99" data-categoria="Pollo Frito">+</button>
        </div>
      </article>

      <!-- Pollo Frito 4 -->
      <article class="plato">
        <img src="../Imagenes/pollo-frito.png" alt="Pollo Grande Frito" />
        <h1>Pollo Grande Frito</h1>
        <p>Jugosa pechuga de pollo empanada y frita al punto justo, acompañada de bacon crocante, lechuga fresca, tomate, queso cheddar derretido y nuestra salsa especial, servida en un pan artesanal tostado. ¡Una explosión de sabor crujiente!</p>
        <div class="plato--info">
          <p>$13.49</p>
          <button type="button" class="btn-agregar" data-id="4" data-nombre="Pollo Grande Frito" data-precio="13.49" data-categoria="Pollo Frito">+</button>
        </div>
      </article>

    </div>
  </main>
</div>
 <!-- Modal Carrito -->
    <div class="modal" id="modal-carrito">
      <div class="modal-contenido">
        <h2>Tu carrito</h2>
        <ul id="lista-carrito"></ul>
        <p><strong>Total:</strong> $<span id="total-carrito">0.00</span></p>
        <button class="cerrar" id="cerrar-carrito">Cerrar</button>
        <button class="cerrar" id="btn-comprar">Comprar</button>
      </div>
    </div>
  </div>

  <script>
    const abrirCarrito = document.getElementById("abrir-carrito");
    const cerrarCarrito = document.getElementById("cerrar-carrito");
    const modal = document.getElementById("modal-carrito");
    const listaCarrito = document.getElementById("lista-carrito");
    const totalCarrito = document.getElementById("total-carrito");
    const contadorCarrito = document.getElementById("contador-carrito");
    const botonesAgregar = document.querySelectorAll(".btn-agregar");
    const btnComprar = document.getElementById("btn-comprar");
    const usuarioLogueado = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;

    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito = carrito.filter(item => item.nombre && item.precio && item.producto_id);
    localStorage.setItem("carrito", JSON.stringify(carrito));

    function actualizarContador() {
      const totalCantidad = carrito.reduce((acc, item) => acc + item.cantidad, 0);
      contadorCarrito.textContent = totalCantidad;
    }

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

    function eliminarProducto(index) {
      carrito.splice(index, 1);
      guardarCarrito();
      mostrarCarrito();
      actualizarContador();
    }

    function guardarCarrito() {
      localStorage.setItem("carrito", JSON.stringify(carrito));
    }

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

    abrirCarrito.addEventListener("click", () => { mostrarCarrito(); modal.style.display = "flex"; });
    cerrarCarrito.addEventListener("click", () => { modal.style.display = "none"; });
    window.addEventListener("click", e => { if(e.target === modal) modal.style.display = "none"; });
    actualizarContador();

    btnComprar.addEventListener("click", () => {
      if (!usuarioLogueado) {
        alert("⚠️ Primero tienes que iniciar sesión para comprar.");
        window.location.href = "../login-register.php";
      } else {
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
  </script>

</body>
</html>
