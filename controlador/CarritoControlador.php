<?php
// controlador/CarritoControlador.php
session_start();

require_once '../config/db.php';
require_once '../modelo/CarritoModelo.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: ../login.php"); exit; }

$modelo = new CarritoModelo($pdo);
$mensaje = "";

if (isset($_POST['vaciar_carrito'])) {
    $_SESSION['carrito'] = [];
    header("Location: ../index.php"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
    if (empty($_SESSION['carrito'])) { header("Location: ../index.php"); exit; }

    try {
        $venta_id = $modelo->procesarCompra($_SESSION['usuario_id'], $_SESSION['carrito'], $_POST['canal'], $_POST['metodo_pago']);
        $_SESSION['carrito'] = [];
        header("Location: SeguimientoControlador.php?id=" . $venta_id);
        exit;
    } catch (Exception $e) {
        $mensaje = "<div class='alert alert-danger'>Ocurrió un error al procesar tu pedido. Verifica el stock.</div>";
    }
}

require_once '../vista/carrito.vista.php';
?>