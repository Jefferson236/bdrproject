<?php require_once 'vista/header.php'; ?>

    <?php if($pedido_activo): ?>
        <div class="alert alert-warning text-center m-0" style="border-radius:0; font-family: Arial; font-weight:bold;">
            🔔 ¡Tienes un pedido en la cocina (Ticket #00<?= $pedido_activo['id'] ?>)! 
            <a href="controlador/SeguimientoControlador.php?id=<?= $pedido_activo['id'] ?>" class="btn btn-sm btn-danger ms-2">Ver mi pedido</a>
        </div>
    <?php endif; ?>

    <section class="hero">
        <div class="hero-text">
            <h1 style="font-size: 3rem; margin-bottom: 10px;">¡SABOR INIGUALABLE!</h1>
            <p style="font-family: sans-serif; font-size: 1.2rem; margin-bottom: 30px; color: #555;">Pide online fácil, rápido y con la mejor calidad.</p>
            <a href="#menu-id">VER MENÚ COMPLETO</a>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80" alt="Hamburguesa Don Pancho">
        </div>
    </section>

    <div class="banner-rojo">
        <h2>NUESTRO MENÚ</h2>
        <p>"Para chuparse los dedos"</p>
    </div>

    <section class="menu-section" id="menu-id">
        <h2 class="section-title">COMBOS Y PRODUCTOS</h2>
        <div class="grid-combos">
            <?php foreach ($productos as $prod): ?>
                <div class="card-combo">
                    <?php if($prod['imagen'] && $prod['imagen'] !== 'default.png'): ?>
                        <img src="uploads/<?= $prod['imagen'] ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                    <?php else: ?>
                        <div style="height:220px; background:#ddd; display:flex; align-items:center; justify-content:center; color:#888;">Sin Imagen</div>
                    <?php endif; ?>
                    
                    <h3><?= htmlspecialchars($prod['nombre']) ?></h3>
                    <div class="precio-box">$<?= htmlspecialchars($prod['precio_venta']) ?></div>

                    <form method="POST" class="form-compra">
                        <input type="hidden" name="producto_id" value="<?= $prod['id'] ?>">
                        <input type="hidden" name="nombre_producto" value="<?= htmlspecialchars($prod['nombre']) ?>">
                        <input type="hidden" name="precio" value="<?= $prod['precio_venta'] ?>">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <span style="font-family: sans-serif;">Cantidad:</span>
                            <input type="number" name="cantidad" value="1" min="1" required>
                        </div>
                        <button type="submit" name="agregar_carrito" class="btn-comprar">🍔 AGREGAR AL CARRITO</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php if(!empty($_SESSION['carrito'])): 
        $items_total = 0; foreach($_SESSION['carrito'] as $i) $items_total += $i['cantidad'];
    ?>
        <a href="controlador/CarritoControlador.php" class="btn-flotante-carrito">
            🛒 IR A PAGAR (<?= $items_total ?>)
        </a>
    <?php endif; ?>

</body>
</html>