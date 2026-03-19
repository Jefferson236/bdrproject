<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pantalla de Cocina - KDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="15">
    <style>
        body { background-color: #121212; color: #ffffff; font-family: 'Arial Black', sans-serif; }
        .navbar-kds { background-color: #000; border-bottom: 3px solid #d62300; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .ticket { background-color: #1e1e1e; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.5); display: flex; flex-direction: column; height: 100%; }
        .ticket-header { padding: 15px; text-align: center; font-size: 24px; color: #fff; }
        .bg-pendiente { background-color: #d62300; }
        .bg-preparando { background-color: #ff9800; }
        .ticket-body { padding: 20px; flex-grow: 1; font-family: 'Arial', sans-serif; font-size: 18px; }
        .ticket-item { border-bottom: 1px dashed #444; padding: 10px 0; display: flex; align-items: center; }
        .ticket-item strong { font-size: 24px; margin-right: 15px; color: #ffc107; }
        .ticket-footer { padding: 15px; background-color: #1a1a1a; }
        .btn-kds { width: 100%; padding: 15px; font-size: 20px; font-weight: bold; text-transform: uppercase; border: none; border-radius: 5px; transition: 0.2s; }
        .btn-empezar { background-color: #ff9800; color: #000; } .btn-empezar:hover { background-color: #e68a00; }
        .btn-terminar { background-color: #4caf50; color: #fff; } .btn-terminar:hover { background-color: #388e3c; }
        .time-badge { font-size: 14px; background: rgba(0,0,0,0.5); padding: 5px 10px; border-radius: 20px; margin-top: 5px; display: inline-block; font-weight: normal; }
    </style>
</head>
<body>
    <div class="navbar-kds">
        <div><h2 style="margin:0; color: #d62300;">🔥 KITCHEN DISPLAY SYSTEM</h2><small style="color: #aaa; font-family: Arial;">Actualización cada 15s</small></div>
        <div>
            <span class="me-3">Chef: <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="../index.php" class="btn btn-outline-light btn-sm me-2">Ir a Tienda</a>
            <a href="../logout.php" class="btn btn-danger btn-sm">Salir</a>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div class="row g-4">
            <?php if(empty($pedidos)): ?>
                <div class="col-12 text-center mt-5"><h1 style="color:#444;">No hay pedidos pendientes.</h1></div>
            <?php endif; ?>
            <?php foreach($pedidos as $id => $pedido): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="ticket">
                        <div class="ticket-header <?= $pedido['estado'] == 'pendiente' ? 'bg-pendiente' : 'bg-preparando' ?>">
                            TICKET #00<?= $id ?><br><span class="time-badge">🕒 <?= date('H:i', strtotime($pedido['fecha'])) ?></span>
                        </div>
                        <div class="ticket-body">
                            <?php foreach($pedido['items'] as $item): ?>
                                <div class="ticket-item"><strong><?= $item['cantidad'] ?>x</strong> <?= htmlspecialchars($item['producto']) ?></div>
                            <?php endforeach; ?>
                        </div>
                        <div class="ticket-footer">
                            <form method="POST">
                                <input type="hidden" name="venta_id" value="<?= $id ?>">
                                <?php if($pedido['estado'] == 'pendiente'): ?>
                                    <input type="hidden" name="nuevo_estado" value="preparando"><button type="submit" name="cambiar_estado" class="btn-kds btn-empezar">👨‍🍳 Empezar a Cocinar</button>
                                <?php else: ?>
                                    <input type="hidden" name="nuevo_estado" value="listo"><button type="submit" name="cambiar_estado" class="btn-kds btn-terminar">✅ ¡Pedido Listo!</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>