<?php
// modelo/CocinaModelo.php

class CocinaModelo {
    private $pdo;

    public function __construct($pdo) { $this->pdo = $pdo; }

    public function cambiarEstado($venta_id, $nuevo_estado) {
        return $this->pdo->prepare("UPDATE ventas SET estado = ? WHERE id = ?")->execute([$nuevo_estado, $venta_id]);
    }

    public function getPedidosPendientes() {
        $stmt = $this->pdo->query("SELECT v.id as venta_id, v.fecha, v.estado, dv.cantidad, p.nombre FROM ventas v JOIN detalle_ventas dv ON v.id = dv.venta_id JOIN productos p ON dv.producto_id = p.id WHERE v.estado IN ('pendiente', 'preparando') ORDER BY v.fecha ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>