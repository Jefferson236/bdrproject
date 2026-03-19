<?php
// controlador/AdminControlador.php
session_start();

// Importamos la conexión y el modelo (subimos un nivel con ../)
require_once '../config/db.php';
require_once '../modelo/AdminModelo.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { header("Location: ../index.php"); exit; }
if (!is_dir('../uploads')) { mkdir('../uploads', 0777, true); }

$modelo = new AdminModelo($pdo);
$mensaje = "";

// --- PROCESAR FORMULARIOS ---

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subir_logo'])) {
    if($_FILES['logo']['name']) {
        $nombre_archivo = time() . '_' . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], '../uploads/' . $nombre_archivo);
        $modelo->actualizarLogo($nombre_archivo);
        $mensaje = "<div class='alert alert-success'>Logo actualizado.</div>";
    }
}

if (isset($_POST['agregar_ingrediente'])) {
    $modelo->agregarIngrediente($_POST['nombre'], $_POST['unidad'], $_POST['stock'], $_POST['par'], $_POST['costo']);
    $mensaje = "<div class='alert alert-success'>Ingrediente agregado.</div>";
}

if (isset($_POST['editar_ingrediente'])) {
    $modelo->editarIngrediente($_POST['ingrediente_id'], $_POST['nombre'], $_POST['unidad'], $_POST['stock'], $_POST['par'], $_POST['costo']);
    $mensaje = "<div class='alert alert-success'>Stock actualizado.</div>";
}

if (isset($_GET['eliminar_ingrediente'])) {
    if ($modelo->eliminarIngrediente($_GET['eliminar_ingrediente'])) {
        $mensaje = "<div class='alert alert-success'>Ingrediente eliminado.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>No puedes eliminar esto, está en una receta.</div>";
    }
}

if (isset($_POST['agregar_producto'])) {
    $imagen = 'default.png';
    if($_FILES['imagen']['name']) {
        $imagen = time() . '_' . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], '../uploads/' . $imagen);
    }
    $modelo->agregarProducto($_POST['nombre'], $_POST['precio'], $imagen);
    $mensaje = "<div class='alert alert-success'>Producto creado.</div>";
}

if (isset($_POST['editar_producto'])) {
    $imagen = null;
    if ($_FILES['imagen_nueva']['name']) {
        $imagen = time() . '_' . $_FILES['imagen_nueva']['name'];
        move_uploaded_file($_FILES['imagen_nueva']['tmp_name'], '../uploads/' . $imagen);
    }
    $modelo->editarProducto($_POST['producto_id'], $_POST['nombre'], $_POST['precio'], $imagen);
    $mensaje = "<div class='alert alert-success'>Producto actualizado.</div>";
}

if (isset($_GET['eliminar_producto'])) {
    $modelo->eliminarProducto($_GET['eliminar_producto']); 
    $mensaje = "<div class='alert alert-success'>Producto eliminado.</div>";
}

if (isset($_POST['agregar_receta'])) {
    $modelo->agregarReceta($_POST['producto_id'], $_POST['ingrediente_id'], $_POST['cantidad']);
    $mensaje = "<div class='alert alert-success'>Receta actualizada.</div>";
}

if (isset($_GET['eliminar_receta'])) {
    $modelo->eliminarReceta($_GET['eliminar_receta']);
    $mensaje = "<div class='alert alert-success'>Ingrediente quitado.</div>";
}

if (isset($_POST['editar_usuario'])) {
    $modelo->editarUsuario($_POST['usuario_id'], $_POST['username'], $_POST['password'], $_POST['rol']);
    $mensaje = "<div class='alert alert-success'>Usuario actualizado.</div>";
}

if (isset($_GET['eliminar_usuario'])) {
    if ($modelo->eliminarUsuario($_GET['eliminar_usuario'], $_SESSION['usuario_id'])) {
        $mensaje = "<div class='alert alert-success'>Usuario eliminado.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>No puedes eliminar tu cuenta actual.</div>";
    }
}

// --- PREPARAR VARIABLES PARA LA VISTA ---
$ingredientes = $modelo->getIngredientes();
$productos = $modelo->getProductos();
$lista_usuarios = $modelo->getUsuarios();
$pedidos = $modelo->getPedidos();
$lista_recetas = $modelo->getRecetas();
$historial_compras = $modelo->getHistorialCompras();
$stats = $modelo->getStats();

$nombres_grafica = []; $cantidades_grafica = [];
foreach($stats['top'] as $tp) { $nombres_grafica[] = $tp['nombre']; $cantidades_grafica[] = $tp['total']; }

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'stats';

// --- LLAMAR A LA VISTA ---
require_once '../vista/admin.vista.php';
?>