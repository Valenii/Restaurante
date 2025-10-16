<?php
session_start();
require_once "conexion.php"; // conexión en $conexion

// Activar errores de MySQL como excepciones
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Enviar siempre JSON
header('Content-Type: application/json');

// Validar sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'No estás logueado']);
    exit;
}

$usuario = $_SESSION['usuario'];

// Validar que el carrito se haya enviado
if (!isset($_POST['carrito'])) {
    echo json_encode(['error' => 'Carrito no enviado']);
    exit;
}

$carrito = json_decode($_POST['carrito'], true);
if (!$carrito || !is_array($carrito) || count($carrito) === 0) {
    echo json_encode(['error' => 'Carrito vacío o inválido']);
    exit;
}

// Obtener el ID del usuario
$result = mysqli_query($conexion, "SELECT id FROM usuarios WHERE nombre = '".mysqli_real_escape_string($conexion, $usuario)."'");
$usuarioData = mysqli_fetch_assoc($result);

if (!$usuarioData) {
    echo json_encode(['error' => 'Usuario no encontrado en la BD']);
    exit;
}

$usuario_id = intval($usuarioData['id']);
$totalCompra = 0;

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    foreach ($carrito as $item) {
        // Validar campos necesarios
        if (!isset($item['nombre'], $item['cantidad'], $item['precio'])) {
            throw new Exception("Datos de producto inválidos");
        }

        $nombre   = mysqli_real_escape_string($conexion, $item['nombre']);
        $cantidad = intval($item['cantidad']);
        $precio   = floatval($item['precio']);

        $subtotal = $precio * $cantidad;
        $totalCompra += $subtotal;

        // Insertar directamente en compras sin depender de productos
        mysqli_query($conexion, "INSERT INTO compras (usuario_id, producto_id, cantidad, total, fecha) 
                                VALUES (0, 0, $cantidad, $subtotal, NOW())");
    }
// Confirmar todos los inserts si no hubo errores
    mysqli_commit($conexion);
    echo json_encode(['success' => 'Compra realizada con éxito 🎉']);
} catch (Exception $e) {
      // Deshacer los cambios si ocurrió un error
    mysqli_rollback($conexion);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
