<?php
// modelo/TiendaModelo.php

class TiendaModelo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- MÉTODOS PARA INDEX.PHP ---
    public function registrarVisita($fecha) {
        $stmt = $this->pdo->prepare("INSERT INTO visitas (fecha, contador) VALUES (?, 1) ON DUPLICATE KEY UPDATE contador = contador + 1");
        return $stmt->execute([$fecha]);
    }

    public function getConfiguracion() {
        return $this->pdo->query("SELECT * FROM configuracion")->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function getProductos() {
        return $this->pdo->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPedidoActivo($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT id, estado FROM ventas WHERE usuario_id = ? AND estado != 'entregado' ORDER BY fecha DESC LIMIT 1");
        $stmt->execute([$usuario_id]);
        return $stmt->fetch();
    }

    // --- MÉTODOS PARA TIENDA.PHP (COMPRA DIRECTA) ---
    public function registrarVentaDirecta($usuario_id, $producto_id, $cantidad, $precio) {
        $total_venta = $cantidad * $precio;

        try {
            // 1. Venta
            $stmtVenta = $this->pdo->prepare("INSERT INTO ventas (canal, total, usuario_id) VALUES ('local', ?, ?)");
            $stmtVenta->execute([$total_venta, $usuario_id]);
            $venta_id = $this->pdo->lastInsertId();

            // 2. Detalle
            $stmtDetalle = $this->pdo->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            $stmtDetalle->execute([$venta_id, $producto_id, $cantidad, $precio]);

            // 3. Descontar Inventario
            $stmtReceta = $this->pdo->prepare("SELECT ingrediente_id, cantidad_requerida FROM recetas WHERE producto_id = ?");
            $stmtReceta->execute([$producto_id]);
            
            foreach ($stmtReceta->fetchAll() as $item) {
                $cant_restar = $item['cantidad_requerida'] * $cantidad;
                $this->pdo->prepare("UPDATE ingredientes SET stock_actual = stock_actual - ? WHERE id = ?")->execute([$cant_restar, $item['ingrediente_id']]);
            }

            return $venta_id; // Retorna el ID de la venta si fue éxito
        } catch (Exception $e) {
            throw $e; // Lanza el error para que el controlador lo atrape
        }
    }
}
?>