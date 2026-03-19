<?php
// controlador/CocinaControlador.php
session_start();

require_once '../config/db.php';
require_once '../modelo/CocinaModelo.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'chef')) { 
    header("Location: ../index.php"); 
    exit; 
}

$modelo = new CocinaModelo($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $modelo->cambiarEstado($_POST['venta_id'], $_POST['nuevo_estado']);
    header("Location: CocinaControlador.php"); 
    exit;
}

$pedidos_raw = $modelo->getPedidosPendientes();
$pedidos = [];

foreach ($pedidos_raw as $row) {
    $id = $row['venta_id'];
    if (!isset($pedidos[$id])) {
        $pedidos[$id] = [
            'fecha'   => $row['fecha'],
            'estado'  => $row['estado'],
            'cliente' => $row['cliente'], // Aquí guardamos el nombre obtenido del JOIN
            'items'   => []
        ];
    }
    $pedidos[$id]['items'][] = [
        'cantidad' => $row['cantidad'], 
        'producto' => $row['nombre']
    ];
}

require_once '../vista/cocina.vista.php';
?>