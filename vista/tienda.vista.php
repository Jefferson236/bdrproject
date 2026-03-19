<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú - Tienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-danger mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">🍔 Menú para Clientes</a>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Hola, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <?php if($_SESSION['rol'] === 'admin'): ?>
                <a href="AdminControlador.php" class="btn btn-sm btn-light me-2">Volver al Gestor</a>
            <?php endif; ?>
            <a href="../logout.php" class="btn btn-sm btn-outline-light">Salir</a>
        </div>
    </div>
</nav>

<div class="container">
    <?= $mensaje ?>
    <h2>¿Qué vas a pedir hoy?</h2>
    <div class="row mt-4">
        <?php foreach ($productos as $prod): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
                    <p class="fs-4 text-success">$<?= htmlspecialchars($prod['precio_venta']) ?></p>
                    <form method="POST">
                        <input type="hidden" name="producto_id" value="<?= $prod['id'] ?>">
                        <input type="hidden" name="precio" value="<?= $prod['precio_venta'] ?>">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Cant.</span>
                            <input type="number" name="cantidad" value="1" min="1" class="form-control" required>
                        </div>
                        <button type="submit" name="comprar" class="btn btn-danger w-100">Comprar Ahora</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>