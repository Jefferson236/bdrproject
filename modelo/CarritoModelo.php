<?php
// modelo/CarritoModelo.php

class CarritoModelo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function procesarCompra($usuario_id, $carrito, $canal, $metodo) {
        $total = 0;
        foreach ($carrito as $item) { $total += ($item['precio'] * $item['cantidad']); }

        $this->pdo->beginTransaction();
        try {
            // 1. Cabecera de la venta
            $stmtVenta = $this->pdo->prepare("INSERT INTO ventas (canal, total, usuario_id, estado, metodo_pago) VALUES (?, ?, ?, 'pendiente', ?)");
            $stmtVenta->execute([$canal, $total, $usuario_id, $metodo]);
            $venta_id = $this->pdo->lastInsertId();

            // 2. Detalles y Receta BOM
            foreach ($carrito as $prod_id => $item) {
                $this->pdo->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)")
                    ->execute([$venta_id, $prod_id, $item['cantidad'], $item['precio']]);
                
                $receta = $this->pdo->prepare("SELECT ingrediente_id, cantidad_requerida FROM recetas WHERE producto_id = ?");
                $receta->execute([$prod_id]);
                
                foreach ($receta->fetchAll() as $ing) {
                    $this->pdo->prepare("UPDATE ingredientes SET stock_actual = stock_actual - ? WHERE id = ?")
                        ->execute([$ing['cantidad_requerida'] * $item['cantidad'], $ing['ingrediente_id']]);
                }
            }
            $this->pdo->commit();
            return $venta_id;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
?>