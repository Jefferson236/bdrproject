<?php
// modelo/AdminModelo.php

class AdminModelo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- MÉTODOS DE ESCRITURA ---
    public function actualizarLogo($nombre_archivo) {
        $stmt = $this->pdo->prepare("INSERT INTO configuracion (clave, valor) VALUES ('logo', ?) ON DUPLICATE KEY UPDATE valor = ?");
        return $stmt->execute([$nombre_archivo, $nombre_archivo]);
    }

    public function agregarIngrediente($nombre, $unidad, $stock, $par, $costo) {
        $stmt = $this->pdo->prepare("INSERT INTO ingredientes (nombre, unidad_medida, stock_actual, par_stock, costo_unitario) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $unidad, $stock, $par, $costo]);
    }

    public function editarIngrediente($id, $nombre, $unidad, $stock, $par, $costo) {
        $stmt = $this->pdo->prepare("UPDATE ingredientes SET nombre=?, unidad_medida=?, stock_actual=?, par_stock=?, costo_unitario=? WHERE id=?");
        return $stmt->execute([$nombre, $unidad, $stock, $par, $costo, $id]);
    }

    public function eliminarIngrediente($id) {
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM recetas WHERE ingrediente_id = ?"); 
        $check->execute([$id]);
        if ($check->fetchColumn() > 0) return false; // Está en uso
        $this->pdo->prepare("DELETE FROM ingredientes WHERE id = ?")->execute([$id]);
        return true;
    }

    public function agregarProducto($nombre, $precio, $imagen) {
        return $this->pdo->prepare("INSERT INTO productos (nombre, precio_venta, imagen) VALUES (?, ?, ?)")->execute([$nombre, $precio, $imagen]);
    }

    public function editarProducto($id, $nombre, $precio, $imagen = null) {
        if ($imagen) {
            return $this->pdo->prepare("UPDATE productos SET nombre = ?, precio_venta = ?, imagen = ? WHERE id = ?")->execute([$nombre, $precio, $imagen, $id]);
        } else {
            return $this->pdo->prepare("UPDATE productos SET nombre = ?, precio_venta = ? WHERE id = ?")->execute([$nombre, $precio, $id]);
        }
    }

    public function eliminarProducto($id) {
        return $this->pdo->prepare("DELETE FROM productos WHERE id = ?")->execute([$id]);
    }

    public function agregarReceta($prod_id, $ing_id, $cant) {
        return $this->pdo->prepare("INSERT INTO recetas (producto_id, ingrediente_id, cantidad_requerida) VALUES (?, ?, ?)")->execute([$prod_id, $ing_id, $cant]);
    }

    public function eliminarReceta($id) {
        return $this->pdo->prepare("DELETE FROM recetas WHERE id = ?")->execute([$id]);
    }

    public function editarUsuario($id, $user, $pass, $rol) {
        return $this->pdo->prepare("UPDATE usuarios SET username = ?, password = ?, rol = ? WHERE id = ?")->execute([$user, $pass, $rol, $id]);
    }

    public function eliminarUsuario($id_user, $sesion_id) {
        if ($id_user == $sesion_id) return false;
        $this->pdo->prepare("UPDATE ventas SET usuario_id = NULL WHERE usuario_id = ?")->execute([$id_user]);
        $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id_user]);
        return true;
    }

    // --- MÉTODOS DE LECTURA ---
    public function getIngredientes() { return $this->pdo->query("SELECT * FROM ingredientes")->fetchAll(); }
    public function getProductos() { return $this->pdo->query("SELECT * FROM productos")->fetchAll(); }
    public function getUsuarios() { return $this->pdo->query("SELECT * FROM usuarios")->fetchAll(); }
    public function getPedidos() { return $this->pdo->query("SELECT v.id, v.fecha, v.canal, v.total, u.username FROM ventas v LEFT JOIN usuarios u ON v.usuario_id = u.id ORDER BY v.fecha DESC")->fetchAll(); }
    
    // MAGIA DE AGRUPACIÓN DE RECETAS
    public function getRecetasAgrupadas() { 
        $stmt = $this->pdo->query("SELECT r.id as receta_id, p.id as producto_id, p.nombre as producto, i.nombre as ingrediente, r.cantidad_requerida, i.unidad_medida FROM recetas r JOIN productos p ON r.producto_id = p.id JOIN ingredientes i ON r.ingrediente_id = i.id ORDER BY p.nombre, i.nombre");
        $recetas_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $agrupadas = [];
        foreach ($recetas_raw as $r) {
            $prod_id = $r['producto_id'];
            if (!isset($agrupadas[$prod_id])) {
                $agrupadas[$prod_id] = [
                    'producto' => $r['producto'],
                    'ingredientes' => []
                ];
            }
            $agrupadas[$prod_id]['ingredientes'][] = $r;
        }
        return $agrupadas;
    }
    
    public function getStats() {
        return [
            'visitas' => $this->pdo->query("SELECT SUM(contador) FROM visitas")->fetchColumn() ?? 0,
            'ingresos' => $this->pdo->query("SELECT SUM(total) FROM ventas")->fetchColumn() ?? 0,
            'top' => $this->pdo->query("SELECT p.nombre, SUM(dv.cantidad) as total FROM detalle_ventas dv JOIN productos p ON dv.producto_id = p.id GROUP BY p.id ORDER BY total DESC")->fetchAll(),
            'peor' => $this->pdo->query("SELECT p.nombre, IFNULL(SUM(dv.cantidad), 0) as total FROM productos p LEFT JOIN detalle_ventas dv ON p.id = dv.producto_id GROUP BY p.id ORDER BY total ASC LIMIT 1")->fetch()
        ];
    }

    public function getHistorialCompras() {
        $ventas = $this->pdo->query("SELECT usuario_id, id, fecha, total, canal FROM ventas WHERE usuario_id IS NOT NULL ORDER BY fecha DESC")->fetchAll(PDO::FETCH_ASSOC);
        $historial = [];
        foreach($ventas as $v) $historial[$v['usuario_id']][] = $v;
        return $historial;
    }
}
?>