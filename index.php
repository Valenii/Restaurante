<?php
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "restaurante_log_reg");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Traer todos los productos
$resultado = $conexion->query("SELECT ID, Nombre, Precio, Stock FROM productos");
$productos = [];
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[$row['ID']] = $row; // guardamos por ID
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MendoFood</title>
  <link rel="stylesheet" href="normalize.css" />
  <link rel="stylesheet" href="index.css" />

  <!-- Ionicons -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
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
  <header class="header">
    <div class="logo"><p>MENDO<span>FOOD</span></p></div>
    <div class="hamburguesa"><img src="Imagenes/menu.png" alt="Menu hamburguesa" /></div>
    <nav class="menu">
      <ul class="navegacion">
        <li><a href="#">Inicio</a></li>
        <li><a href="Menu.php">Menu</a></li>
        <li><a href="servicios.php">Servicios</a></li>
        <li><a href="We.php">Nosotros</a></li>
        <li><a href="#">Galeria</a></li>
        <li>
          <?php if(isset($_SESSION['usuario'])): ?>
            <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            <a href="logout.php">Cerrar sesión</a>
          <?php else: ?>
            <a href="login-register.php">Iniciar Sesión</a>
            <a href="login-register.php">Registrarse</a>
          <?php endif; ?>
        </li>
      </ul>

      <!-- Carrito -->
      <div class="carrito" id="abrir-carrito">
        <img src="Imagenes/icons8-carrito-de-compras-30.png" alt="Carrito" class="carrito-icono" />
        <span id="contador-carrito">0</span>
      </div>
    </nav>
  </header>

  <aside class="presentacion">
    <div class="informacion">
      <h1>Ordenar tu comida favorita</h1>
      <div>
        <p>Hola, nuestra deliciosa comida está esperando a ti,</p>
        <p>siempre estamos cerca para ti con comida fresca</p>
      </div>
      <div class="informacion--botton">
        <button type="button">Explorar Comida</button>
      </div>
    </div>
    <div class="presentacion--imagen">
      <img src="Imagenes/design.png" alt="Imagen presentacion" />
    </div>
  </aside>

  <main class="comida">
    <h2 class="comida--titulo">Platos populares</h2>
    <div class="platos">
      <?php
      // Información de imágenes y descripción para cada plato
      $platosInfo = [
        1 => ["img"=>"Imagenes/hamburguesa.png","desc"=>"Deliciosa hamburguesa de carne vacuna con bacon crocante..."],
        2 => ["img"=>"Imagenes/pollo-frito.png","desc"=>"Jugosa pechuga de pollo empanada y frita..."],
        3 => ["img"=>"Imagenes/papas-fritas.png","desc"=>"Porción generosa de papas fritas doradas y crujientes..."],
        4 => ["img"=>"Imagenes/hamburguesa-grande.png","desc"=>"Hamburguesa extra grande con doble carne vacuna..."]
      ];

      foreach($platosInfo as $id => $info):
        if(!isset($productos[$id])) continue; // Si no existe en la DB
        $prod = $productos[$id];
      ?>
      <article class="plato">
        <img src="<?php echo $info['img']; ?>" alt="<?php echo htmlspecialchars($prod['Nombre']); ?>" />
        <h1><?php echo htmlspecialchars($prod['Nombre']); ?></h1>
        <p><?php echo $info['desc']; ?></p>
        <div class="plato--info">
          <p data-precio="<?php echo $prod['Precio']; ?>">$<?php echo $prod['Precio']; ?></p>
          <p>Stock: <span class="stock" data-id="<?php echo $prod['ID']; ?>"><?php echo $prod['Stock']; ?></span></p>
          <button type="button" class="btn-agregar"
                  data-id="<?php echo $prod['ID']; ?>"
                  data-nombre="<?php echo htmlspecialchars($prod['Nombre']); ?>"
                  data-precio="<?php echo $prod['Precio']; ?>"
                  data-categoria="Platos Populares">+</button>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </main>

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
  carrito.forEach((item,index)=>{
    total += item.precio * item.cantidad;
    const li = document.createElement("li");
    const nombreSpan = document.createElement("span");
    nombreSpan.textContent = `${item.nombre} x${item.cantidad}`;
    nombreSpan.classList.add("nombre-producto");
    const precioSpan = document.createElement("span");
    precioSpan.textContent = `$${(item.precio*item.cantidad).toFixed(2)}`;
    const botonEliminar = document.createElement("button");
    botonEliminar.innerHTML = '<ion-icon name="close-outline"></ion-icon>';
    botonEliminar.classList.add("eliminar");
    botonEliminar.addEventListener("click",()=>{eliminarProducto(index);});
    li.appendChild(nombreSpan);
    li.appendChild(precioSpan);
    li.appendChild(botonEliminar);
    listaCarrito.appendChild(li);
  });
  totalCarrito.textContent = total.toFixed(2);
}

function eliminarProducto(index){
  carrito.splice(index,1);
  guardarCarrito();
  mostrarCarrito();
  actualizarContador();
}

function guardarCarrito(){
  localStorage.setItem("carrito",JSON.stringify(carrito));
}

function agregarAlCarrito(nombre,precio,categoria,producto_id){
  producto_id = parseInt(producto_id);
  const index = carrito.findIndex(item=>item.nombre===nombre && item.categoria===categoria);
  if(index!==-1){
    carrito[index].cantidad++;
  } else {
    carrito.push({nombre,precio:parseFloat(precio),cantidad:1,categoria,producto_id});
  }
  guardarCarrito();
  actualizarContador();
}

botonesAgregar.forEach(boton=>{
  boton.addEventListener("click",()=>{
    const nombre = boton.getAttribute("data-nombre");
    const precio = boton.getAttribute("data-precio");
    const categoria = boton.getAttribute("data-categoria");
    const producto_id = boton.getAttribute("data-id");
    agregarAlCarrito(nombre,precio,categoria,producto_id);
    mostrarCarrito();
  });
});

abrirCarrito.addEventListener("click",()=>{ mostrarCarrito(); modal.style.display="flex"; });
cerrarCarrito.addEventListener("click",()=>{ modal.style.display="none"; });
window.addEventListener("click",e=>{ if(e.target===modal) modal.style.display="none"; });
actualizarContador();

btnComprar.addEventListener("click",()=>{
  if(!usuarioLogueado){
    alert("⚠️ Primero tienes que iniciar sesión para comprar.");
    window.location.href="login-register.php";
  } else {
    const payload = { productos: carrito.map(item=>({id:item.producto_id,cantidad:item.cantidad})) };
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
