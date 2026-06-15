<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino - Sistema de Gestión Aduanera</title>
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- CSS del proyecto -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/home.css">
</head>

<body>
    <!-- ============================================ -->
    <!-- NAVBAR (con acceso a login y pre-registro) -->
    <!-- ============================================ -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-shield-check"></i> Latido Andino
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#como-funciona">¿Cómo funciona?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pre-registro">
                            <i class="bi bi-file-text"></i> Pre-registro
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/consulta-estado">
                            <i class="bi bi-search"></i> Consultar Estado
                        </a>
                    </li>
                    <?php if (isset($userName)): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($userName); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/<?php echo $userRole; ?>/dashboard">
                                    <i class="bi bi-speedometer2"></i> Mi Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/logout">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-2">
                            <a href="/login" class="btn btn-outline-light">
                                <i class="bi bi-box-arrow-in-right"></i> Acceder
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============================================ -->
    <!-- HERO SECTION (con estadísticas dinámicas) -->
    <!-- ============================================ -->
    <section id="inicio" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>
                        <i class="bi bi-shield-check" style="color: var(--sna-red);"></i>
                        Modernizando el Control Fronterizo
                    </h1>
                    <p class="lead">
                        Sistema integrado de gestión aduanera que reduce tiempos de espera 
                        mediante pre-registro y validación cruzada automatizada.
                    </p>
                    <div class="mt-4">
                        <a href="/pre-registro" class="btn btn-primary btn-lg me-2">
                            <i class="bi bi-file-text"></i> Pre-registro
                        </a>
                        <a href="#como-funciona" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-play-circle"></i> Ver más
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-card">
                                <i class="bi bi-people fs-1"></i>
                                <h3><?php echo number_format($stats['total_tramites'] ?? 0); ?></h3>
                                <p>Trámites realizados</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <i class="bi bi-check-circle fs-1"></i>
                                <h3>&lt;<?php echo htmlspecialchars($stats['tiempo_promedio'] ?? 2); ?>s</h3>
                                <p>Validación cruzada</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <i class="bi bi-building fs-1"></i>
                                <h3><?php echo htmlspecialchars($stats['instituciones_integradas'] ?? 7); ?></h3>
                                <p>Instituciones integradas</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <i class="bi bi-qr-code fs-1"></i>
                                <h3>100%</h3>
                                <p>Digitalizado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- CÓMO FUNCIONA (3 PASOS) -->
    <!-- ============================================ -->
    <section id="como-funciona" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold" style="color: var(--sna-blue);">
                    ¿Cómo funciona?
                </h2>
                <p class="lead">Tres pasos simples para agilizar tu cruce fronterizo</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <h4>1. Pre-registro</h4>
                        <p>
                            Completa tus datos, declara productos y vehículos desde cualquier 
                            dispositivo antes de llegar a la frontera.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h4>2. Validación Automática</h4>
                        <p>
                            El sistema consulta en paralelo a Aduanas, PDI, SAG, Interpol y más, 
                            entregando resultado en &lt;2 segundos.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-qr-code"></i>
                        </div>
                        <h4>3. Pase Ágil QR</h4>
                        <p>
                            Obtén tu código QR aprobado y preséntalo en el control para 
                            un paso prioritario.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- BENEFICIOS / POR QUÉ ELEGIRNOS -->
    <!-- ============================================ -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://placehold.co/600x400/002B5C/white?text=Latido+Andino" 
                        alt="Sistema Latido Andino" class="img-fluid rounded-4 shadow">
                </div>
                <div class="col-lg-6">
                    <h3 style="color: var(--sna-blue);">Beneficios del sistema</h3>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Para viajeros:</strong> Menos tiempo de espera, trámites digitales, seguimiento en línea.
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Para funcionarios:</strong> Información consolidada, validación rápida, reducción de errores.
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Para el Estado:</strong> Mejor fiscalización, datos en tiempo real, transparencia.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- PASOS FRONTERIZOS (muestra dinámica) -->
    <!-- ============================================ -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h3 style="color: var(--sna-blue);">Principales pasos fronterizos</h3>
                <p class="text-muted">Conectamos los principales cruces terrestres de Chile</p>
            </div>
            <div class="row">
                <?php if (!empty($pasos) && is_array($pasos)): ?>
                    <?php foreach ($pasos as $paso): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="bi bi-geo-alt-fill fs-1" style="color: var(--sna-blue);"></i>
                                    <h5 class="card-title mt-2"><?php echo htmlspecialchars($paso->nombre); ?></h5>
                                    <p class="card-text text-muted"><?php echo htmlspecialchars($paso->region); ?></p>
                                    <span class="badge bg-success">Activo</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">Cargando pasos fronterizos...</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- NOTICIAS O NOVEDADES -->
    <!-- ============================================ -->
    <?php if (!empty($noticias)): ?>
    <section class="bg-light py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h3 style="color: var(--sna-blue);">Últimas novedades</h3>
                <p class="text-muted">Mejoras y actualizaciones del sistema</p>
            </div>
            <div class="row">
                <?php foreach ($noticias as $noticia): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2">Novedad</span>
                                <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                                <p class="card-text text-muted small">
                                    <i class="bi bi-calendar"></i> <?php echo htmlspecialchars($noticia['fecha']); ?>
                                </p>
                                <p class="card-text"><?php echo htmlspecialchars($noticia['descripcion']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- CTA FINAL -->
    <!-- ============================================ -->
    <section class="cta-section">
        <div class="container text-center">
            <h3 class="mb-3">¿Listo para cruzar la frontera?</h3>
            <p class="mb-4">Realiza tu pre-registro ahora y ahorra tiempo en tu próximo viaje</p>
            <a href="/pre-registro" class="btn btn-primary btn-lg">
                <i class="bi bi-file-text"></i> Comenzar Pre-registro
            </a>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- FOOTER -->
    <!-- ============================================ -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>
                        <i class="bi bi-shield-check"></i> Latido Andino
                    </h5>
                    <p>Modernizando el control fronterizo entre Chile y sus países vecinos.</p>
                </div>
                <div class="col-md-4">
                    <h6>Enlaces rápidos</h6>
                    <ul class="list-unstyled">
                        <li><a href="/pre-registro">Pre-registro</a></li>
                        <li><a href="/consulta-estado">Consultar estado</a></li>
                        <li><a href="/login">Acceso funcionarios</a></li>
                        <li><a href="/terminos">Términos y condiciones</a></li>
                        <li><a href="/privacidad">Política de privacidad</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contacto</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope"></i> contacto@latidoandino.cl</li>
                        <li><i class="bi bi-telephone"></i> +56 2 1234 5678</li>
                        <li><i class="bi bi-geo-alt"></i> Servicio Nacional de Aduanas, Chile</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center">
                <small>Servicio Nacional de Aduanas - Chile | Todos los derechos reservados © <?php echo date('Y'); ?></small>
            </div>
        </div>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>