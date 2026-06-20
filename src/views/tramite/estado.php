<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino - Estado de Trámite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/portal.css">
</head> 

<body>
    <div class="topbar">
        <div class="topbar-inner">
            <span>🇨🇱 &nbsp;Gobierno de Chile &nbsp;·&nbsp; Ministerio de Hacienda</span>
            <div>
                <a href="/contacto">Contacto</a>
                <a href="/accesibilidad">Accesibilidad</a>
                <a href="/terminos">Términos</a>
                <a href="/privacidad">Privacidad</a>
            </div>
        </div>
    </div>

    <header class="header">
        <div class="header-inner">
            <svg class="escudo" viewBox="0 0 90 50" xmlns="http://www.w3.org/2000/svg">
                <text x="2" y="38" font-family="Arial, sans-serif" font-size="30" font-weight="700" fill="#1565C0" letter-spacing="-0.5">Chile</text>
                <polygon points="62,4 63.8,9.5 69.5,9.5 64.9,12.8 66.6,18.3 62,15 57.4,18.3 59.1,12.8 54.5,9.5 60.2,9.5" fill="#1565C0"/>
                <polygon points="71,8 72.4,12.4 77,12.4 73.3,15 74.7,19.4 71,16.8 67.3,19.4 68.7,15 65,12.4 69.6,12.4" fill="#1565C0"/>
            </svg>
            <div class="logo-text">
                <span class="nombre-sistema">Latido Andino</span>
                <span class="subtitulo">Servicio Nacional de Aduanas · Paso Los Libertadores</span>
            </div>
            <nav class="header-nav">
                <a href="/" class="btn-home" style="text-decoration:none;">
                    <i class="bi bi-house-door"></i> Página principal
                </a>
            </nav>
        </div>
    </header>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <a href="/pre-registro" class="btn btn-link mb-3 ps-0">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>

                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <h4 class="mb-1"><i class="bi bi-clipboard-check"></i> Estado de tu trámite</h4>
                        <p class="mb-0 opacity-75">Consulta el resultado de tus trámites recientes</p>
                    </div>
                    <div class="form-card-body">

                        <?php if (empty($tramites)): ?>
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                No se encontraron trámites para el RUT consultado.
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($tramites as $t): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                                        <div>
                                            <strong class="text-capitalize"><?= htmlspecialchars($t->tipo ?? '') ?></strong>
                                            <span class="text-muted">— <?= htmlspecialchars($t->paso_fronterizo ?? '') ?></span>
                                            <?php if (!empty($t->observaciones)): ?>
                                                <div class="small text-muted mt-1"><?= htmlspecialchars($t->observaciones) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <?php
                                                $estado = $t->estado ?? 'pendiente';
                                                $badgeClass = $estado === 'aprobado' ? 'bg-success' : ($estado === 'rechazado' ? 'bg-danger' : 'bg-secondary');
                                            ?>
                                            <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 text-uppercase">
                                                <?= htmlspecialchars($estado) ?>
                                            </span>
                                            <?php if ($estado === 'aprobado'): ?>
                                                <a class="btn btn-sm btn-next" style="padding:8px 16px;" href="/tramite/pase-agil/<?= urlencode((string)$t->_id) ?>">
                                                    Ver Pase Ágil
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>