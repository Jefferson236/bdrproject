<?php
// controlador/TiendaControlador.php
session_start();

require_once '../config/db.php';
require_once '../modelo/TiendaModelo.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: ../login.php"); exit; }

$modelo = new TiendaModelo($pdo);
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar'])) {
    $pdo->beginTransaction();
    try {
        $venta_id = $modelo->registrarVentaDirecta($_SESSION['usuario_id'], $_POST['producto_id'], $_POST['cantidad'], $_POST['precio']);
        $pdo->commit();
        $mensaje = "<div class='alert alert-success'>¡Compra exitosa, bro! Tu pedido #00$venta_id está en marcha y el inventario se actualizó.</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $mensaje = "<div class='alert alert-danger'>Error al procesar la compra.</div>";
    }
}

$productos = $modelo->getProductos();
require_once '../vista/tienda.vista.php';
?>