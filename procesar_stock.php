<?php
// Iniciamos la sesión para poder saber quién está logueado
session_start();

// Indicamos que la respuesta será en formato JSON
header('Content-Type: application/json');

// Verificamos si el usuario inició sesión
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión, respondemos con error 403 (prohibido)
    http_response_code(403);
    echo json_encode(["error" => "No autorizado. Debes iniciar sesión."]);
    exit; // Detenemos el script
}

// Conexión a la base de datos MySQL
$conexion = new mysqli("localhost", "root", "", "restaurante_log_reg");

// Comprobamos si la conexión falló
if ($conexion->connect_error) {
    http_response_code(500); // Error interno del servidor
    echo json_encode(["error" => "Error al conectar con la base de datos."]);
    exit;
}

// Recibimos los datos enviados en JSON desde el frontend (productos comprados)
$data = json_decode(file_get_contents("php://input"), true);

// Validamos que exista la lista de productos y sea un array
if (!isset($data['productos']) || !is_array($data['productos'])) {
    http_response_code(400); // Error de solicitud inválida
    echo json_encode(["error" => "Datos inválidos."]);
    exit;
}

// Recorremos cada producto comprado
foreach ($data['productos'] as $producto) {
    $id = intval($producto['id']);           // Guardamos el ID del producto
    $cantidad = intval($producto['cantidad']); // Guardamos la cantidad que quiere comprar

    // Consultamos el stock actual del producto en la base de datos
    $consulta = $conexion->prepare("SELECT Stock FROM productos WHERE ID = ?");
    $consulta->bind_param("i", $id); // Pasamos el ID como parámetro seguro
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows === 0) {
        continue; // Si el producto no existe, seguimos con el siguiente
    }

    $row = $resultado->fetch_assoc();
    $stock_actual = intval($row['Stock']); // Obtenemos el stock actual

    // Verificamos si hay suficiente stock para la compra
    if ($stock_actual >= $cantidad) {
        $nuevo_stock = $stock_actual - $cantidad; // Calculamos el nuevo stock

        // Actualizamos el stock en la base de datos
        $update = $conexion->prepare("UPDATE productos SET Stock = ? WHERE ID = ?");
        $update->bind_param("ii", $nuevo_stock, $id);
        $update->execute();
    }
}

// Respondemos que la compra fue exitosa
echo json_encode(["mensaje" => "Compra realizada con éxito."]);
?>
