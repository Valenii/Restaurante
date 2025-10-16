<?php 
// Iniciamos sesión para poder guardar datos del usuario (ejemplo: login activo)
session_start(); 

// Conexión a la base de datos MySQL usando mysqli
$conexion = new mysqli("localhost", "root", "", "restaurante_log_reg");

// Verifica si hubo un error al conectarse
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error); // Termina el script si falla la conexión
}

// IDs de los productos de Pollo Frito que queremos mostrar
$idsPollos = [17,18,19,20];

// Convierte el array de IDs en una cadena separada por comas para usar en la consulta SQL
$idsStr = implode(",", $idsPollos);

// Creamos un array vacío donde guardaremos los productos
$productos = [];

// Ejecuta la consulta SQL para traer los productos con los IDs especificados
$resultado = $conexion->query("SELECT ID, Nombre, Precio, Stock FROM productos WHERE ID IN ($idsStr)");

// Verifica si la consulta fue exitosa
if ($resultado) {
    // Recorre los resultados de la consulta y los guarda en el array $productos usando el ID como clave
    while ($row = $resultado->fetch_assoc()) {
        $productos[$row['ID']] = $row; 
    }
} else {
    // Si hay un error en la consulta, muestra el error y termina el script
    die("Error en la consulta: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hamburguesas - MendoFood</title>
    <!-- Normalize.css para resetear estilos por defecto del navegador -->
    <link rel="stylesheet" href="../normalize.css" />
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../index.css" />
    <style>
        /* Estilos del modal del carrito */
        .modal {
            display: none; /* Oculto por defecto */
            position: fixed; /* Fijo en pantalla */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6); /* Fondo semi-transparente */
            justify-content: center; /* Centra horizontalmente */
            align-items: center; /* Centra verticalmente */
            z-index: 1000; /* Por encima de otros elementos */
        }
        .modal-contenido {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 320px;
            max-height: 80vh;
            overflow-y: auto; /* Scroll si el contenido supera altura */
        }
        .modal-contenido ul {
            list-style: none;
            padding: 0;
            margin: 0 0 10px 0;
        }
        .modal-contenido li {
            border-bottom: 1px solid #ccc;
            padding: 5px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-contenido li span.nombre-producto {
            flex: 1; /* Ocupa el espacio disponible */
        }
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
        .modal-contenido li button.eliminar:hover {
            color: darkred;
        }
        /* Estilos del ícono de carrito en el header */
        .carrito {
            position: relative;
            display: inline-block;
            margin-left: 20px;
            cursor: pointer;
        }
        .carrito-icono {
            width: 30px;
        }
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
        <!-- HEADER con logo, menú y carrito -->
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
                        <!-- Si el usuario está logueado muestra nombre y opción de cerrar sesión -->
                        <?php if(isset($_SESSION['usuario'])): ?>
                            <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
                            <a href="../logout.php">Cerrar sesión</a>
                        <?php else: ?>
                            <!-- Si no, muestra links de login y registro -->
                            <a href="../login-register.php">Iniciar Sesión</a>
                            <a href="../login-register.php">Registrarse</a>
                        <?php endif; ?>
                    </li>
                </ul>
                <!-- Icono de carrito -->
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
                <!-- Cada artículo representa un producto -->
                <article class="plato">
                    <img src="../Imagenes/Pollo_fritos.png" alt="Pollo Frito Clásico" />
                    <h1>Pollo Frito Clásico</h1>
                    <p>Trozos de pollo frito clásico, dorados a la perfección y con un rebozado crujiente que encierra un interior jugoso y sabroso. ¡Un favorito que nunca falla!</p>
                    <div class="plato--info">
                        <!-- Precio y stock dinámicos desde la base de datos -->
                        <p data-precio="<?php echo $productos[17]['Precio']; ?>">
                            $<?php echo $productos[17]['Precio']; ?>
                        </p>
                        <p>Stock: <span class="stock" data-id="17"><?php echo $productos[17]['Stock']; ?></span></p>
                        <!-- Botón para agregar al carrito -->
                        <button type="button" class="btn-agregar" data-nombre="Pollo Frito Clásico" data-precio="<?php echo $productos[17]['Precio']; ?>" data-categoria="pollo-frito" data-id="17">+</button>
                    </div>
                </article>

                <article class="plato">
                    <img src="../Imagenes/pequeña cubeta de pollo frito.png" alt="Pollo Frito Pequeño" />
                    <h1>Cubeta Pequeña</h1>
                    <p>Porción pequeña de pollo frito dorado y crujiente, ideal para disfrutar un snack irresistible y lleno de sabor en cualquier momento.</p>
                    <div class="plato--info">
                        <p data-precio="<?php echo $productos[18]['Precio']; ?>">$<?php echo $productos[18]['Precio']; ?></p>
                        <p>Stock: <span class="stock" data-id="18"><?php echo $productos[18]['Stock']; ?></span></p>
                        <button type="button" class="btn-agregar" data-nombre="Cubeta Pequeña" data-precio="<?php echo $productos[18]['Precio']; ?>" data-categoria="pollo-frito" data-id="18">+</button>
                    </div>
                </article>

                <article class="plato">
                    <img src="../Imagenes/cubeta de pollo frito.png" alt="Pollo Frito Mediano" />
                    <h1>Cubeta Mediana</h1>
                    <p>Generosa cubeta con trozos de pollo frito crujiente por fuera y jugoso por dentro, perfecta para compartir y satisfacer tu antojo.</p>
                    <div class="plato--info">
                        <p data-precio="<?php echo $productos[19]['Precio']; ?>">$<?php echo $productos[19]['Precio']; ?></p>
                        <p>Stock: <span class="stock" data-id="19"><?php echo $productos[19]['Stock']; ?></span></p>
                        <button type="button" class="btn-agregar" data-nombre="Cubeta Mediana" data-precio="<?php echo $productos[19]['Precio']; ?>" data-categoria="pollo-frito" data-id="19">+</button>
                    </div>
                </article>

                <article class="plato">
                    <img src="../Imagenes/pollo-frito.png" alt="Pollo Grande Frito" />
                    <h1>Pollo Grande Frito</h1>
                    <p>Jugosa pechuga de pollo empanada y frita al punto justo, acompañada de bacon crocante, lechuga fresca, tomate, queso cheddar derretido y nuestra salsa especial, servida en un pan artesanal tostado. ¡Una explosión de sabor crujiente!</p>
                    <div class="plato--info">
                        <p data-precio="<?php echo $productos[20]['Precio']; ?>">$<?php echo $productos[20]['Precio']; ?></p>
                        <p>Stock: <span class="stock" data-id="20"><?php echo $productos[20]['Stock']; ?></span></p>
                        <button type="button" class="btn-agregar" data-nombre="Pollo Grande Frito" data-precio="<?php echo $productos[20]['Precio']; ?>" data-categoria="pollo-frito" data-id="20">+</button>
                    </div>
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

        // Verifica si el usuario está logueado
        const usuarioLogueado = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;

        // Recupera carrito del localStorage o crea un array vacío
        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
        // Filtra items inválidos
        carrito = carrito.filter(item => item.nombre && item.precio && item.producto_id);
        localStorage.setItem("carrito", JSON.stringify(carrito));

        // Actualiza el contador del carrito en el header
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
                carrito[index].cantidad++;
            } else {
                carrito.push({ nombre, precio: parseFloat(precio), cantidad: 1, categoria, producto_id });
            }
            guardarCarrito();
            actualizarContador();
        }

        // Event listeners de los botones + de cada producto
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

        // Abrir y cerrar modal
        abrirCarrito.addEventListener("click", () => { mostrarCarrito(); modal.style.display = "flex"; });
        cerrarCarrito.addEventListener("click", () => { modal.style.display = "none"; });
        window.addEventListener("click", e => { if(e.target === modal) modal.style.display = "none"; });

        actualizarContador();

        // Procesar compra
        btnComprar.addEventListener("click", () => {
            if (!usuarioLogueado) {
                alert("⚠️ Primero tienes que iniciar sesión para comprar.");
                window.location.href = "../login-register.php";
            } else {
                // Envía los datos del carrito a un archivo PHP para procesar la compra
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
