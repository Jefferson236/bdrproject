<?php
// modelo/CocinaModelo.php

class CocinaModelo {
    private $pdo;

    public function __construct($pdo) { 
        $this->pdo = $pdo; 
    }

    public function cambiarEstado($venta_id, $nuevo_estado) {
        $stmt = $this->pdo->prepare("UPDATE ventas SET estado = ? WHERE id = ?");
        return $stmt->execute([$nuevo_estado, $venta_id]);
    }

    public function getPedidosPendientes() {
        // Hacemos JOIN con la tabla usuarios para obtener el nombre del cliente
        // Cambia 'u.username' por 'u.nombre' si así se llama la columna en tu tabla usuarios
        $sql = "SELECT v.id as venta_id, v.fecha, v.estado, u.username as cliente, dv.cantidad, p.nombre 
                FROM ventas v 
                JOIN detalle_ventas dv ON v.id = dv.venta_id 
                JOIN productos p ON dv.producto_id = p.id 
                JOIN usuarios u ON v.usuario_id = u.id 
                WHERE v.estado IN ('pendiente', 'preparando') 
                ORDER BY v.fecha ASC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>