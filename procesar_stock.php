<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario inició sesión
if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    echo json_encode(["error" => "No autorizado. Debes iniciar sesión."]);
    exit;
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "restaurante_log_reg");

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error al conectar con la base de datos."]);
    exit;
}

// Recibir datos en formato JSON (productos comprados)
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['productos']) || !is_array($data['productos'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos inválidos."]);
    exit;
}

// Procesar cada producto comprado
foreach ($data['productos'] as $producto) {
    $id = intval($producto['id']);           // ID del producto
    $cantidad = intval($producto['cantidad']); // Cantidad a comprar

    // Verificar stock disponible
    $consulta = $conexion->prepare("SELECT Stock FROM productos WHERE ID = ?");
    $consulta->bind_param("i", $id);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows === 0) {
        continue; // Producto no encontrado
    }

    $row = $resultado->fetch_assoc();
    $stock_actual = intval($row['Stock']);

    // Verificar si hay stock suficiente
    if ($stock_actual >= $cantidad) {
        $nuevo_stock = $stock_actual - $cantidad;

        // Actualizar stock en la base de datos
        $update = $conexion->prepare("UPDATE productos SET Stock = ? WHERE ID = ?");
        $update->bind_param("ii", $nuevo_stock, $id);
        $update->execute();
    }
}

echo json_encode(["mensaje" => "Compra realizada con éxito."]);
?>
