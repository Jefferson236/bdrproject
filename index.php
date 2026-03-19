<?php
// index.php (CONTROLADOR PRINCIPAL)
session_start();

// Como estamos en la raíz, llamamos a las carpetas
require_once 'config/db.php';
require_once 'modelo/TiendaModelo.php';

$modelo = new TiendaModelo($pdo);

// Inicializar el carrito
if (!isset($_SESSION['carrito'])) { $_SESSION['carrito'] = []; }

// 1. Visitas y Configuración
$modelo->registrarVisita(date('Y-m-d'));
$config = $modelo->getConfiguracion();
$logo = isset($config['logo']) && $config['logo'] !== '🍔' ? 'uploads/'.$config['logo'] : '🍔';

// 2. Agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_carrito'])) {
    $id = $_POST['producto_id'];
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] += $_POST['cantidad'];
    } else {
        $_SESSION['carrito'][$id] = ['nombre' => $_POST['nombre_producto'], 'precio' => $_POST['precio'], 'cantidad' => $_POST['cantidad']];
    }
    header("Location: index.php"); 
    exit;
}

// 3. Variables para la vista
$pedido_activo = isset($_SESSION['usuario_id']) ? $modelo->getPedidoActivo($_SESSION['usuario_id']) : null;
$productos = $modelo->getProductos();

// 4. Llamar a la Vista
require_once 'vista/index.vista.php';
?>