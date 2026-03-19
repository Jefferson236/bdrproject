<?php
// login.php (CONTROLADOR)
session_start();

require_once 'config/db.php';
require_once 'modelo/AuthModelo.php';

$modelo = new AuthModelo($pdo);
$mensaje = '';

// Lógica de Registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    $user = $_POST['new_username'];
    $pass = $_POST['new_password'];
    
    // Encriptación que tú mismo hiciste
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

    try {
        $modelo->registrarUsuario($user, $pass_hash);
        $mensaje = "<div class='alert alert-success shadow-sm'>¡Registro exitoso! Ya puedes iniciar sesión.</div>";
    } catch(PDOException $e) {
        $mensaje = "<div class='alert alert-danger shadow-sm'>El usuario ya existe o hubo un error.</div>";
    }
}

// Lógica de Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $usuario = $modelo->buscarUsuarioPorUsername($user);

    // Verificar si el usuario existe y si la contraseña coincide con el Hash
    if ($usuario && password_verify($pass, $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['rol'] = $usuario['rol'];
        
        // Redirigir según el rol
        if ($usuario['rol'] === 'admin') {
            header("Location: controlador/AdminControlador.php");
        } else if ($usuario['rol'] === 'chef') {
            header("Location: cocina.php"); // Este lo convertiremos a MVC después
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $mensaje = "<div class='alert alert-danger shadow-sm'>Usuario o contraseña incorrectos.</div>";
    }
}

// Cargar la vista visual
require_once 'vista/login.vista.php';
?>