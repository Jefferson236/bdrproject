<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <a class="navbar-brand" href="#">⚙️ Panel de Control Administrador</a>
        <div>
            <a href="../index.php" class="btn btn-primary btn-sm me-2">Ver Tienda</a>
            <a href="../logout.php" class="btn btn-outline-danger btn-sm">Salir</a>
        </div>
    </div>
</nav>

<div class="container">
    <?= $mensaje ?>
    
    <ul class="nav nav-pills mb-4 bg-white p-2 rounded shadow-sm flex-wrap">
        <li class="nav-item"><a class="nav-link <?= $activeTab=='stats'?'active':'' ?>" href="?tab=stats">📊 Estadísticas</a></li>
        <li class="nav-item"><a class="nav-link <?= $activeTab=='pedidos'?'active':'' ?>" href="?tab=pedidos">📋 Pedidos</a></li>
        <li class="nav-item"><a class="nav-link <?= $activeTab=='usuarios'?'active':'' ?>" href="?tab=usuarios">👥 Usuarios</a></li>
        <li class="nav-item"><a class="nav-link <?= $activeTab=='inventario'?'active':'' ?>" href="?tab=inventario">📦 Inventario</a></li>
        <li class="nav-item"><a class="nav-link <?= $activeTab=='productos'?'active':'' ?>" href="?tab=productos">🍔 Productos</a></li>
        <li class="nav-item"><a class="nav-link <?= $activeTab=='recetas'?'active':'' ?>" href="?tab=recetas">📜 Recetas</a></li>
        <li class="nav-item"><a class="nav-link <?= $activeTab=='config'?'active':'' ?>" href="?tab=config">⚙️ Configuración</a></li>
    </ul>

    <div class="tab-content">
        <?php if($activeTab == 'stats'): ?>
        <div class="row">
            <div class="col-md-3"><div class="card text-white bg-primary mb-3 shadow"><div class="card-body"><h5>Visitas Tienda</h5><h2><?= $stats['visitas'] ?></h2></div></div></div>
            <div class="col-md-3"><div class="card text-white bg-success mb-3 shadow"><div class="card-body"><h5>Ingresos Totales</h5><h2>$<?= number_format($stats['ingresos'], 2) ?></h2></div></div></div>
            <div class="col-md-3"><div class="card text-white bg-warning mb-3 shadow"><div class="card-body"><h5>Top Ventas</h5><h4><?= $stats['top'][0]['nombre'] ?? 'N/A' ?></h4></div></div></div>
            <div class="col-md-3"><div class="card text-white bg-danger mb-3 shadow"><div class="card-body"><h5>Menos Vendido</h5><h4><?= $stats['peor']['nombre'] ?? 'N/A' ?></h4></div></div></div>
        </div>
        <div class="row">
            <div class="col-md-7"><div class="card shadow h-100"><div class="card-body"><h5 class="text-center">Ventas por Producto</h5><canvas id="miGrafica"></canvas></div></div></div>
            <div class="col-md-5"><div class="card shadow h-100"><div class="card-body"><h5>Ranking Detallado</h5><table class="table"><tr><th>Producto</th><th>Unidades</th></tr><?php foreach($stats['top'] as $tp): ?><tr><td><?= htmlspecialchars($tp['nombre']) ?></td><td><span class="badge bg-success"><?= $tp['total'] ?></span></td></tr><?php endforeach; ?></table></div></div></div>
        </div>
        <script>
            new Chart(document.getElementById('miGrafica'), { type: 'bar', data: { labels: <?= json_encode($nombres_grafica) ?>, datasets: [{ label: 'Unidades', data: <?= json_encode($cantidades_grafica) ?>, backgroundColor: 'rgba(54, 162, 235, 0.6)' }] }, options: { scales: { y: { beginAtZero: true } } } });
        </script>
        <?php endif; ?>

        <?php if($activeTab == 'pedidos'): ?>
        <div class="card shadow"><div class="card-body"><h5>Historial de Pedidos</h5>
            <table class="table table-hover align-middle"><thead class="table-dark"><tr><th># Ticket</th><th>Fecha</th><th>Cliente</th><th>Canal</th><th>Total</th></tr></thead><tbody>
                <?php foreach($pedidos as $pedido): ?><tr><td><strong>#00<?= htmlspecialchars($pedido['id']) ?></strong></td><td><?= htmlspecialchars($pedido['fecha']) ?></td><td><?= htmlspecialchars($pedido['username'] ? $pedido['username'] : 'Invitado') ?></td><td><span class="badge bg-secondary"><?= strtoupper($pedido['canal']) ?></span></td><td class="text-success fw-bold">$<?= number_format($pedido['total'], 2) ?></td></tr><?php endforeach; ?>
            </tbody></table>
        </div></div>
        <?php endif; ?>

        <?php if($activeTab == 'usuarios'): ?>
        <div class="card shadow">
            <div class="card-body">
                <h5>Gestión de Usuarios Registrados</h5>
                <table class="table align-middle table-hover">
                    <thead class="table-light"><tr><th>ID</th><th>Usuario</th><th>Contraseña</th><th>Rol</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach($lista_usuarios as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($u['password']) ?></span></td>
                            <td>
                                <?php if($u['rol'] == 'admin'): ?> <span class="badge bg-primary">Admin</span>
                                <?php elseif($u['rol'] == 'chef'): ?> <span class="badge bg-info">Chef</span>
                                <?php else: ?> <span class="badge bg-secondary">Cliente</span> <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#historialModal<?= $u['id'] ?>">🛒 Historial</button>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $u['id'] ?>">⚙️ Editar</button>
                                <?php if($u['id'] != $_SESSION['usuario_id']): ?>
                                    <a href="?tab=usuarios&eliminar_usuario=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">🗑️ Eliminar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php foreach($lista_usuarios as $u): ?>
        <div class="modal fade" id="editUserModal<?= $u['id'] ?>" tabindex="-1">
            <div class="modal-dialog"><div class="modal-content"><form method="POST" action="?tab=usuarios">
                <div class="modal-header"><h5 class="modal-title">Editar Usuario</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="usuario_id" value="<?= $u['id'] ?>">
                    <label>Nombre de Usuario:</label>
                    <input type="text" name="username" class="form-control mb-3" value="<?= htmlspecialchars($u['username']) ?>" required>
                    <label>Contraseña:</label>
                    <input type="text" name="password" class="form-control mb-3" value="<?= htmlspecialchars($u['password']) ?>" required>
                    <label>Rol del Sistema:</label>
                    <select name="rol" class="form-select mb-3">
                        <option value="cliente" <?= $u['rol']=='cliente'?'selected':'' ?>>Cliente</option>
                        <option value="chef" <?= $u['rol']=='chef'?'selected':'' ?>>Chef</option>
                        <option value="admin" <?= $u['rol']=='admin'?'selected':'' ?>>Administrador</option>
                    </select>
                </div>
                <div class="modal-footer"><button type="submit" name="editar_usuario" class="btn btn-primary">Guardar Cambios</button></div>
            </form></div></div>
        </div>

        <div class="modal fade" id="historialModal<?= $u['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg"><div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Compras de <?= htmlspecialchars($u['username']) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <?php if(isset($historial_compras[$u['id']])): ?>
                        <table class="table table-sm text-center">
                            <thead class="table-dark"><tr><th>Ticket</th><th>Fecha</th><th>Total Pagado</th></tr></thead>
                            <tbody>
                                <?php foreach($historial_compras[$u['id']] as $compra): ?>
                                    <tr><td>#00<?= $compra['id'] ?></td><td><?= $compra['fecha'] ?></td><td class="text-success fw-bold">$<?= number_format($compra['total'], 2) ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center text-muted my-3">Este usuario aún no ha realizado compras.</p>
                    <?php endif; ?>
                </div>
            </div></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if($activeTab == 'inventario'): ?>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow"><div class="card-body"><h5>Nuevo Ingrediente</h5><form method="POST" action="?tab=inventario"><input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required><input type="text" name="unidad" class="form-control mb-2" placeholder="Unidad (kg, lts...)" required><input type="number" step="0.01" name="stock" class="form-control mb-2" placeholder="Stock Inicial" required><input type="number" step="0.01" name="par" class="form-control mb-2" placeholder="Par Mínimo" required><input type="number" step="0.01" name="costo" class="form-control mb-2" placeholder="Costo Unitario" required><button type="submit" name="agregar_ingrediente" class="btn btn-primary w-100">Agregar</button></form></div></div>
            </div>
            <div class="col-md-8">
                <div class="card shadow"><div class="card-body">
                    <table class="table align-middle"><tr><th>Nombre</th><th>Stock Actual</th><th>Acciones</th></tr>
                    <?php foreach($ingredientes as $i): ?>
                        <tr><td><?= htmlspecialchars($i['nombre']) ?></td><td class="<?= $i['stock_actual'] <= $i['par_stock'] ? 'text-danger fw-bold' : 'text-success' ?>"><?= htmlspecialchars($i['stock_actual']) ?> <?= htmlspecialchars($i['unidad_medida']) ?></td><td><button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editIngModal<?= $i['id'] ?>">Editar Stock</button> <a href="?tab=inventario&eliminar_ingrediente=<?= $i['id'] ?>" class="btn btn-sm btn-outline-danger">Eliminar</a></td></tr>
                    <?php endforeach; ?>
                    </table>
                </div></div>
            </div>
        </div>
        
        <?php foreach($ingredientes as $i): ?>
            <div class="modal fade" id="editIngModal<?= $i['id'] ?>" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="?tab=inventario"><div class="modal-header"><h5 class="modal-title">Editar Ingrediente</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><input type="hidden" name="ingrediente_id" value="<?= $i['id'] ?>"><label>Nombre:</label><input type="text" name="nombre" class="form-control mb-2" value="<?= htmlspecialchars($i['nombre']) ?>" required><label>Unidad:</label><input type="text" name="unidad" class="form-control mb-2" value="<?= htmlspecialchars($i['unidad_medida']) ?>" required><label class="text-primary fw-bold">Stock Actual:</label><input type="number" step="0.01" name="stock" class="form-control mb-2 border-primary" value="<?= $i['stock_actual'] ?>" required><label>Par Mínimo:</label><input type="number" step="0.01" name="par" class="form-control mb-2" value="<?= $i['par_stock'] ?>" required><label>Costo:</label><input type="number" step="0.01" name="costo" class="form-control mb-2" value="<?= $i['costo_unitario'] ?>" required></div><div class="modal-footer"><button type="submit" name="editar_ingrediente" class="btn btn-primary">Guardar</button></div></form></div></div></div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if($activeTab == 'productos'): ?>
        <div class="row">
            <div class="col-md-4"><div class="card shadow"><div class="card-body"><h5>Crear Producto</h5><form method="POST" action="?tab=productos" enctype="multipart/form-data"><input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required><input type="number" step="0.01" name="precio" class="form-control mb-2" placeholder="Precio" required><label class="form-label text-muted small">Imagen:</label><input type="file" name="imagen" class="form-control mb-3" accept="image/*"><button type="submit" name="agregar_producto" class="btn btn-success w-100">Crear</button></form></div></div></div>
            <div class="col-md-8"><div class="card shadow"><div class="card-body">
                <table class="table align-middle"><tr><th>IMG</th><th>Producto</th><th>Precio</th><th>Acciones</th></tr>
                <?php foreach($productos as $p): ?>
                    <tr><td><img src="../uploads/<?= $p['imagen'] ?>" height="40" width="40" style="object-fit:cover; border-radius:5px;"></td><td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td><td>$<?= htmlspecialchars($p['precio_venta']) ?></td><td><button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id'] ?>">Editar</button> <a href="?tab=productos&eliminar_producto=<?= $p['id'] ?>" class="btn btn-sm btn-danger">Eliminar</a></td></tr>
                <?php endforeach; ?>
                </table>
            </div></div></div>
        </div>

        <?php foreach($productos as $p): ?>
            <div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="?tab=productos" enctype="multipart/form-data"><div class="modal-header"><h5 class="modal-title">Editar <?= htmlspecialchars($p['nombre']) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><input type="hidden" name="producto_id" value="<?= $p['id'] ?>"><label>Nombre:</label><input type="text" name="nombre" class="form-control mb-3" value="<?= htmlspecialchars($p['nombre']) ?>" required><label>Precio:</label><input type="number" step="0.01" name="precio" class="form-control mb-3" value="<?= $p['precio_venta'] ?>" required><label>Actualizar Imagen (Opcional):</label><input type="file" name="imagen_nueva" class="form-control" accept="image/*"></div><div class="modal-footer"><button type="submit" name="editar_producto" class="btn btn-primary">Guardar</button></div></form></div></div></div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if($activeTab == 'recetas'): ?>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow border-info">
                    <div class="card-body">
                        <h5>Añadir Ingrediente a Receta</h5>
                        <form method="POST" action="?tab=recetas">
                            <select name="producto_id" class="form-select mb-2" required>
                                <option value="">1. Selecciona Producto (Receta)...</option>
                                <?php foreach($productos as $p) echo "<option value='{$p['id']}'>{$p['nombre']}</option>"; ?>
                            </select>
                            <select name="ingrediente_id" class="form-select mb-2" required>
                                <option value="">2. Selecciona Ingrediente...</option>
                                <?php foreach($ingredientes as $i) echo "<option value='{$i['id']}'>{$i['nombre']} ({$i['unidad_medida']})</option>"; ?>
                            </select>
                            <input type="number" step="0.01" name="cantidad" class="form-control mb-2" placeholder="3. Cantidad a descontar (ej: 0.15)" required>
                            <button type="submit" name="agregar_receta" class="btn btn-info w-100 text-white fw-bold">Guardar en Receta</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <h5 class="mb-3">Recetas Configuradas</h5>
                
                <?php if(empty($recetas_agrupadas)): ?>
                    <div class="alert alert-warning">Aún no hay recetas configuradas. ¡Agrega una!</div>
                <?php else: ?>
                    <div class="accordion shadow-sm" id="accordionRecetas">
                        <?php foreach($recetas_agrupadas as $prod_id => $grupo): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $prod_id ?>">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $prod_id ?>">
                                        🍔 Receta de: <?= htmlspecialchars($grupo['producto']) ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $prod_id ?>" class="accordion-collapse collapse" data-bs-parent="#accordionRecetas">
                                    <div class="accordion-body p-0">
                                        <table class="table table-sm table-hover align-middle m-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3">Ingrediente</th>
                                                    <th>Cantidad a descontar por unidad</th>
                                                    <th class="text-end pe-3">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($grupo['ingredientes'] as $ing): ?>
                                                    <tr>
                                                        <td class="ps-3"><?= htmlspecialchars($ing['ingrediente']) ?></td>
                                                        <td><span class="badge bg-primary fs-6"><?= htmlspecialchars($ing['cantidad_requerida']) ?> <?= htmlspecialchars($ing['unidad_medida']) ?></span></td>
                                                        <td class="text-end pe-3">
                                                            <a href="?tab=recetas&eliminar_receta=<?= $ing['receta_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Quitar este ingrediente de la receta?');">Quitar</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($activeTab == 'config'): ?>
        <div class="col-md-6 mx-auto"><div class="card shadow"><div class="card-body"><h5>Configuración de la Tienda</h5><form method="POST" action="?tab=config" enctype="multipart/form-data"><label>Subir nuevo Logo (PNG/JPG):</label><input type="file" name="logo" class="form-control my-3" accept="image/*" required><button type="submit" name="subir_logo" class="btn btn-dark w-100">Actualizar Logo</button></form></div></div></div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>