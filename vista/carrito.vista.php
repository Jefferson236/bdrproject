<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Don Pancho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Arial Black', sans-serif; background-color: #f4f4f4; padding-top: 50px;}
        .checkout-box { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .resumen-lista { font-family: 'Arial', sans-serif; font-size: 18px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="../index.php" class="btn btn-outline-dark mb-4">⬅ Volver al Menú</a>
            <?= $mensaje ?>
            <div class="checkout-box border-top border-danger border-5">
                <h2 class="text-center mb-4" style="color: #d62300;">🛒 TU CARRITO DE COMPRAS</h2>
                <?php if(empty($_SESSION['carrito'])): ?>
                    <div class="text-center py-5">
                        <h4 class="text-muted">Tu carrito está vacío</h4>
                        <a href="../index.php" class="btn btn-danger mt-3">Ir a comprar</a>
                    </div>
                <?php else: ?>
                    <ul class="list-group mb-4 resumen-lista">
                        <?php $total_pagar = 0; foreach($_SESSION['carrito'] as $id => $item): 
                            $subtotal = $item['cantidad'] * $item['precio']; $total_pagar += $subtotal; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div><span class="badge bg-dark rounded-pill me-2"><?= $item['cantidad'] ?></span><?= htmlspecialchars($item['nombre']) ?></div>
                                <strong>$<?= number_format($subtotal, 2) ?></strong>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light fs-4 text-danger fw-bold">TOTAL A PAGAR:<span>$<?= number_format($total_pagar, 2) ?></span></li>
                    </ul>
                    <form method="POST">
                        <div class="row mb-4" style="font-family: Arial;">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">¿Dónde consumirás?</label>
                                <select name="canal" class="form-select border-dark" required>
                                    <option value="local">Comer en el Local</option><option value="delivery">Envío a Domicilio</option><option value="takeaway">Para Llevar</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Método de Pago</label>
                                <select name="metodo_pago" class="form-select border-dark" required>
                                    <option value="efectivo">💵 Efectivo (Al entregar)</option><option value="tarjeta">💳 Tarjeta</option><option value="qr">📱 Código QR</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <button type="submit" name="vaciar_carrito" class="btn btn-outline-secondary w-25" formnovalidate>Vaciar</button>
                            <button type="submit" name="finalizar_compra" class="btn btn-danger w-75 fw-bold fs-5">CONFIRMAR PEDIDO</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>