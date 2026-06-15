<?php
// Verificar que el usuario sea viajero
if ($_SESSION['user_role'] !== 'viajero') {
    header('Location: /' . $_SESSION['user_role'] . '/dashboard');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Latido Andino</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/viajero-dashboard.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-check"></i> Latido Andino
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="sidebar">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle fs-1" style="color: var(--sna-blue);"></i>
                        <h6 class="mt-2"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h6>
                        <span class="badge bg-primary">Viajero</span>
                    </div>
                    <hr>
                    <a href="/viajero/dashboard" class="sidebar-link active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="/pre-registro" class="sidebar-link">
                        <i class="bi bi-file-text"></i> Nuevo Trámite
                    </a>
                    <a href="/viajero/mis-tramites" class="sidebar-link">
                        <i class="bi bi-clock-history"></i> Mis Trámites
                    </a>
                    <a href="/viajero/perfil" class="sidebar-link">
                        <i class="bi bi-gear"></i> Mi Perfil
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Bienvenida -->
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Bienvenido al sistema Latido Andino. Aquí puedes gestionar tus trámites aduaneros.
                </div>
                
                <!-- QR Activo -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="qr-card">
                            <h6>
                                <i class="bi bi-qr-code"></i> Mi Pase Ágil
                            </h6>
                            <?php if (isset($paseActivo) && $paseActivo): ?>
                                <div class="bg-light p-3 rounded">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($paseActivo->codigo); ?>" 
                                         alt="QR Code" class="img-fluid">
                                    <p class="mt-2 mb-0 small">
                                        Vigente hasta: <?php echo date('d/m/Y H:i', strtotime($paseActivo->vencimiento)); ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No tiene trámites activos</p>
                                <a href="/pre-registro" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Iniciar trámite
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="qr-card">
                            <h6>
                                <i class="bi bi-graph-up"></i> Estadísticas
                            </h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h3 class="text-primary mb-0"><?php echo $totalTramites ?? 0; ?></h3>
                                    <small>Trámites totales</small>
                                </div>
                                <div class="col-6">
                                    <h3 class="text-success mb-0"><?php echo $tramitesAprobados ?? 0; ?></h3>
                                    <small>Aprobados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Últimos Trámites -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-clock-history"></i> Últimos Trámites
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($ultimosTramites)): ?>
                            <p class="text-muted text-center">No tiene trámites recientes</p>
                        <?php else: ?>
                            <?php foreach ($ultimosTramites as $tramite): ?>
                                <div class="tramite-card status-<?php echo $tramite->estado; ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>
                                                <i class="bi bi-<?php echo $tramite->tipo === 'ingreso' ? 'arrow-down' : 'arrow-up'; ?>"></i>
                                                <?php echo $tramite->tipo === 'ingreso' ? 'Ingreso a Chile' : 'Salida de Chile'; ?>
                                            </strong>
                                            <br>
                                            <small class="text-muted">
                                                Paso: <?php echo $tramite->paso_fronterizo; ?> | 
                                                Fecha: <?php echo date('d/m/Y H:i', $tramite->created_at->toDateTime()->getTimestamp()); ?>
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge bg-<?php 
                                                echo $tramite->estado === 'aprobado' ? 'success' : 
                                                    ($tramite->estado === 'pendiente' ? 'warning' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst($tramite->estado); ?>
                                            </span>
                                            <?php if ($tramite->estado === 'aprobado'): ?>
                                                <a href="/tramite/pase-agil/<?php echo $tramite->_id; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-qr-code"></i> Ver QR
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>