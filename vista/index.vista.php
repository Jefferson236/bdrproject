<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Hamburguesas Don Pancho | El Sabor de la Parrilla</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --color-rojo: #d62300; --color-crema: #f5ebdc; --color-oscuro: #202020; }
        html { scroll-behavior: smooth; }
        body { margin: 0; font-family: 'Arial Black', sans-serif; background-color: #f4f4f4; }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 15px 5%; background: white; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .logo { font-size: 22px; color: black; text-transform: uppercase; display: flex; align-items: center; text-decoration: none;}
        .logo img { height: 40px; margin-right: 10px; }
        .nav-links { display: flex; align-items: center; }
        .nav-links a, .nav-links span { text-decoration: none; color: black; margin-left: 20px; font-size: 14px; }
        .btn-login { background-color: var(--color-oscuro); color: white !important; padding: 8px 18px; border-radius: 5px; transition: 0.3s; }
        .btn-login:hover { background-color: var(--color-rojo); }
        .btn-admin { background-color: #ffc107; color: black !important; padding: 8px 18px; border-radius: 5px; transition: 0.3s; }
        .btn-cocina { background-color: #0dcaf0; color: black !important; padding: 8px 18px; border-radius: 5px; transition: 0.3s; margin-left:10px; }
        .btn-logout { border: 1px solid var(--color-oscuro); padding: 7px 15px; border-radius: 5px; transition: 0.3s; }
        .btn-logout:hover { background-color: var(--color-oscuro); color: white !important; }
        .hero { display: flex; align-items: center; justify-content: space-around; padding: 60px 10%; background-color: white; min-height: 70vh; }
        .hero-text a { text-decoration: none; color: black; font-size: 20px; border-bottom: 3px solid black; padding-bottom: 5px; }
        .hero-image img { max-width: 500px; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .banner-rojo { background-color: var(--color-rojo); background-image: radial-gradient(rgba(0,0,0,0.1) 15%, transparent 16%); background-size: 10px 10px; color: white; text-align: center; padding: 80px 20px; }
        .banner-rojo h2 { font-size: 60px; margin: 0; text-transform: uppercase; }
        .banner-rojo p { font-style: italic; font-size: 24px; margin-bottom: 30px; }
        .botones-group { display: flex; justify-content: center; gap: 20px; }
        .btn-blanco { background: white; color: var(--color-rojo); padding: 15px 40px; border-radius: 50px; text-decoration: none; font-weight: bold; font-size: 18px; transition: 0.3s; }
        .btn-blanco:hover { transform: scale(1.05); background: var(--color-crema); }
        .menu-section { padding: 60px 5%; max-width: 1200px; margin: 0 auto; }
        .section-title { font-size: 32px; border-bottom: 4px solid var(--color-rojo); display: inline-block; margin-bottom: 40px; }
        .grid-combos { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .card-combo { background: white; border-radius: 8px; overflow: hidden; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; padding-bottom:15px; }
        .card-combo:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .card-combo img { width: 100%; height: 220px; object-fit: cover; }
        .card-combo h3 { margin: 15px 0 5px; font-size: 18px; }
        .precio-box { color: var(--color-rojo); font-size: 24px; font-weight: bold; margin-bottom: 15px;}
        .form-compra { padding: 0 20px; }
        .form-compra input[type="number"] { width: 60px; padding: 5px; text-align: center; border: 1px solid #ccc; border-radius: 5px; font-weight: bold; }
        .btn-comprar { background: var(--color-oscuro); color: white; border: none; padding: 10px 15px; width: 100%; border-radius: 5px; cursor: pointer; font-family: 'Arial Black', sans-serif; transition: 0.3s; margin-top: 10px; }
        .btn-comprar:hover { background: var(--color-rojo); }
        /* Botón flotante del carrito */
        .btn-flotante-carrito { position: fixed; bottom: 30px; right: 30px; background: var(--color-rojo); color: white; padding: 15px 30px; border-radius: 50px; font-size: 18px; font-weight: bold; text-decoration: none; box-shadow: 0 10px 20px rgba(0,0,0,0.3); transition: 0.3s; z-index: 1000; border: 3px solid white;}
        .btn-flotante-carrito:hover { background: #b01c00; color: white; transform: scale(1.1); }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo">
            <?php if(strpos($logo, 'uploads') !== false): ?> <img src="<?= $logo ?>" alt="Logo"> <?php else: ?> <?= $logo ?> <?php endif; ?> Don Pancho
        </a>
        <div class="nav-links">
            <a href="#menu-id">MENÚ</a>
            <a href="#">OFERTAS</a>
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <span style="color: var(--color-rojo);">Hola, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <?php if($_SESSION['rol'] === 'admin'): ?>
                    <a href="controlador/AdminControlador.php" class="btn-admin">⚙️ GESTOR</a>
                    <a href="controlador/CocinaControlador.php" class="btn-cocina">👨‍🍳 COCINA</a>
                <?php endif; ?>
                <?php if($_SESSION['rol'] === 'chef'): ?>
                    <a href="controlador/CocinaControlador.php" class="btn-cocina">👨‍🍳 COCINA</a>
                <?php endif; ?>
                <a href="logout.php" class="btn-logout">SALIR</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">LOGIN / REGISTRO</a>
            <?php endif; ?>
        </div>
    </nav>

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
            <img src="https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80">
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
                        <img src="uploads/<?= $prod['imagen'] ?>">
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