<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <a href="index.php" class="btn btn-outline-secondary px-4 shadow-sm">⬅ Volver a la Tienda</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <?= $mensaje ?>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4 fw-bold">Iniciar Sesión</h4>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="text" name="username" class="form-control" placeholder="Usuario" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-dark w-100 py-2">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-lg border-primary">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4 text-primary fw-bold">Crear Cuenta</h4>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="text" name="new_username" class="form-control" placeholder="Nuevo Usuario" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="new_password" class="form-control" placeholder="Contraseña" required>
                            </div>
                            <button type="submit" name="registrar" class="btn btn-primary w-100 py-2">Registrarme</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5 text-muted small">
            &copy; <?= date('Y') ?> Sistema de Gestión de Restaurante - Conexión Segura Activa
        </div>
    </div>
</body>
</html>