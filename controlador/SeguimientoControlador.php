<?php
// controlador/SeguimientoControlador.php
session_start();

require_once '../config/db.php';
require_once '../modelo/SeguimientoModelo.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: ../login.php"); exit; }

$modelo = new SeguimientoModelo($pdo);

// API AJAX
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode($modelo->getEstadosAjax($_SESSION['usuario_id']));
    exit;
}

$resultados = $modelo->getPedidosDetallados($_SESSION['usuario_id']);
$pedidos = [];
foreach ($resultados as $row) {
    $id = $row['venta_id'];
    if (!isset($pedidos[$id])) {
        $pedidos[$id] = ['fecha' => $row['fecha'], 'estado' => $row['estado'], 'total' => $row['total'], 'items' => []];
    }
    $pedidos[$id]['items'][] = $row;
}

require_once '../vista/seguimiento.vista.php';
?>