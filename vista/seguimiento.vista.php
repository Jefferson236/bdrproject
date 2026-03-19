<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Radar Don Pancho | Seguimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .goku-container { background: #000; border-radius: 10px; overflow: hidden; position: relative; border: 4px solid #ff9800; box-shadow: 0 0 20px #ff9800; }
        .goku-video { width: 100%; height: auto; display: block; opacity: 1; }
        .goku-img { width: 100%; height: auto; border-radius: 10px; display: block; }
        .badge-estado { font-size: 1rem; padding: 10px 15px; transition: 0.3s; }
        @keyframes videoFade { 0% { opacity: 0; transform: scale(0.98); } 100% { opacity: 1; transform: scale(1); } }
        .fade-in { animation: videoFade 0.8s ease forwards; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php">🍔 Hamburguesas Don Pancho</a>
        <a href="../index.php" class="btn btn-outline-light">Volver a la tienda</a>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4 fw-bold">RADAR DON PANCHO 🐉</h2>
    <?php if(empty($pedidos)): ?><div class="alert alert-warning text-center">No tienes pedidos activos bro. ¡Ve a comprar algo!</div><?php endif; ?>

    <?php foreach ($pedidos as $id => $pedido): ?>
        <?php 
            $colorEstado = 'bg-secondary';
            if($pedido['estado'] == 'preparando') $colorEstado = 'bg-warning text-dark';
            if($pedido['estado'] == 'listo' || $pedido['estado'] == 'entregado') $colorEstado = 'bg-success';
        ?>
        <div class="card shadow-sm mb-4 border-0" id="pedido-card-<?= $id ?>" data-estado="<?= $pedido['estado'] ?>">
            <div class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Ticket #<?= $id ?> <small class="text-muted ms-2 fs-6"><?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></small></h5>
                <span id="badge-<?= $id ?>" class="badge <?= $colorEstado ?> badge-estado text-uppercase"><?= htmlspecialchars($pedido['estado']) ?></span>
            </div>
            
            <div class="card-body row">
                <div class="col-md-7 border-end">
                    <h6 class="text-muted text-uppercase mb-3">Detalles de tu pedido:</h6>
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach($pedido['items'] as $item): ?>
                            <li class="list-group-item d-flex align-items-center px-0">
                                <img src="../uploads/<?= htmlspecialchars($item['imagen'] ? $item['imagen'] : 'default.png') ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" class="me-3">
                                <div class="flex-grow-1"><h6 class="mb-0 fw-bold"><?= htmlspecialchars($item['nombre']) ?></h6><small class="text-muted">Cant: <?= $item['cantidad'] ?> x $<?= $item['precio_unitario'] ?></small></div>
                                <div class="fw-bold">$<?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                        <span class="fs-5 fw-bold">TOTAL:</span><span class="fs-4 fw-bold text-success">$<?= number_format($pedido['total'], 2) ?></span>
                    </div>
                </div>

                <div class="col-md-5 d-flex flex-column justify-content-center align-items-center p-4">
                    <h6 class="fw-bold mb-3 text-center">Estado del pedido 🔴</h6>
                    <div class="goku-container w-100" id="goku-container-<?= $id ?>">
                        <?php if($pedido['estado'] == 'listo' || $pedido['estado'] == 'entregado'): ?>
                            <img src="../goku_satisfecho.jpg" alt="Goku Satisfecho" class="goku-img fade-in">
                        <?php else: ?>
                            <video id="goku-vid-<?= $id ?>" data-id="<?= $id ?>" class="goku-video fade-in" autoplay muted playsinline>
                                <source src="../goku_video.mp4" type="video/mp4">
                            </video>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    document.querySelectorAll('.goku-video').forEach(video => {
        let id = video.dataset.id;
        let card = document.getElementById('pedido-card-' + id);
        
        video.addEventListener("timeupdate", function() {
            let estado = card.getAttribute('data-estado');
            let start_time = 0; let end_time = 30; 

            if (estado === "preparando") { start_time = 30; end_time = 60; }

            if (this.currentTime >= end_time || this.currentTime < start_time) {
                this.style.animation = "none"; void this.offsetWidth; 
                this.style.animation = "videoFade 0.5s ease"; 
                this.currentTime = start_time; this.play();
            }
        });
    });

    setInterval(() => {
        fetch('?ajax=1') // Llama al controlador actual
        .then(response => response.json())
        .then(data => {
            for (let id in data) {
                let nuevoEstado = data[id];
                let card = document.getElementById('pedido-card-' + id);
                
                if (card) {
                    let estadoActual = card.getAttribute('data-estado');
                    
                    if (estadoActual !== nuevoEstado) {
                        card.setAttribute('data-estado', nuevoEstado);
                        
                        let badge = document.getElementById('badge-' + id);
                        badge.innerText = nuevoEstado;
                        badge.className = "badge badge-estado text-uppercase ";
                        if (nuevoEstado === 'pendiente') badge.className += "bg-secondary";
                        else if (nuevoEstado === 'preparando') badge.className += "bg-warning text-dark";
                        else if (nuevoEstado === 'listo' || nuevoEstado === 'entregado') badge.className += "bg-success";

                        if (nuevoEstado === 'listo' || nuevoEstado === 'entregado') {
                            let container = document.getElementById('goku-container-' + id);
                            container.innerHTML = '<img src="../goku_satisfecho.jpg" alt="Goku Satisfecho" class="goku-img fade-in">';
                        }
                    }
                }
            }
        })
        .catch(error => console.error('Error cargando el radar:', error));
    }, 3000);
</script>
</body>
</html>