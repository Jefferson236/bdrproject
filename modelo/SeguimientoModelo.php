<?php
// modelo/SeguimientoModelo.php

class SeguimientoModelo {
    private $pdo;

    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getEstadosAjax($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT id, estado FROM ventas WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $estados = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $estados[$row['id']] = $row['estado']; }
        return $estados;
    }

    public function getPedidosDetallados($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT v.id as venta_id, v.fecha, v.estado, v.total, p.nombre, p.imagen, dv.cantidad, dv.precio_unitario FROM ventas v JOIN detalle_ventas dv ON v.id = dv.venta_id LEFT JOIN productos p ON dv.producto_id = p.id WHERE v.usuario_id = ? ORDER BY v.fecha DESC");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>