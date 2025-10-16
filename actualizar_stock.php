<?php
require 'conexion.php'; // Asegúrate de que la conexión a la base de datos esté correcta

// Obtener los datos del carrito enviados por POST
$carrito = json_decode(file_get_contents('php://input'), true);

// Verificar si el carrito no está vacío
if (!empty($carrito)) {
    foreach ($carrito as $item) {
        $producto_id = $item['producto_id'];
        $cantidad = $item['cantidad'];

        // Consultar el stock actual del producto
        $stmt = $conn->prepare("SELECT stock FROM productos WHERE id = ?");
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $stmt->bind_result($stock_actual);
        $stmt->fetch();
        $stmt->close();

        // Verificar si hay suficiente stock
        if ($stock_actual >= $cantidad) {
            // Actualizar el stock en la base de datos
            $nuevo_stock = $stock_actual - $cantidad;
            $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
            $stmt->bind_param("ii", $nuevo_stock, $producto_id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Si no hay suficiente stock, devolver un error
            echo json_encode(['error' => 'No hay suficiente stock para el producto ID ' . $producto_id]);
            exit;
        }
    }

    // Responder con éxito
    echo json_encode(['success' => 'Stock actualizado correctamente']);
} else {
    // Si el carrito está vacío, devolver un error
    echo json_encode(['error' => 'El carrito está vacío']);
}
?>

