<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/home">
                <i class="bi bi-cup-hot-fill"></i> Coffee Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="/home">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item me-3">
                            <span class="user-badge">
                                <i class="bi bi-person-circle"></i> 
                                <?php echo htmlspecialchars($userName); ?> 
                                <small>(<?php echo ucfirst($userRole); ?>)</small>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a href="/logout" class="btn btn-logout">
                                <i class="bi bi-box-arrow-right"></i> Salir
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item me-2">
                            <a href="/login" class="btn btn-outline-light">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/register" class="btn btn-light">
                                <i class="bi bi-person-plus"></i> Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-cup-hot"></i> Nuestros Cafés
            </h1>
            <p class="lead">Descubre nuestra selección de los mejores cafés artesanales</p>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container mb-5">
        <div class="row">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-cup" style="font-size: 4rem; color: var(--coffee-light);"></i>
                    <h3 class="mt-3" style="color: var(--coffee-dark);">No hay productos disponibles</h3>
                    <p class="text-muted">Pronto tendremos nuevos cafés para ti</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card position-relative">
                            <div class="product-image">
                                <i class="<?php echo htmlspecialchars($product->icon ?? 'bi bi-cup-hot-fill'); ?>"></i>
                            </div>
                            <?php if (isset($product->is_new) && $product->is_new): ?>
                                <span class="badge-new">Nuevo</span>
                            <?php endif; ?>
                            <div class="product-body">
                                <h3 class="product-title"><?php echo htmlspecialchars($product->name); ?></h3>
                                <p class="product-description"><?php echo htmlspecialchars($product->description); ?></p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="product-price">$<?php echo number_format($product->price, 2); ?></span>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($product->size ?? 'Regular'); ?></span>
                                </div>
                                <button class="btn btn-add-cart" onclick="showComingSoon()">
                                    <i class="bi bi-cart-plus"></i> Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showComingSoon() {
            alert('Carrito de compras - Próximamente');
        }
    </script>
</body>
</html>
