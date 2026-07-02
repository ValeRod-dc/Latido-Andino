<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación Pase Ágil - Latido Andino</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- CSS del proyecto -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/verificar.css">
</head>
<body>
    <div class="verificar-container">
        <div class="verificar-card">
            <div class="card-header">
                <svg class="escudo" viewBox="0 0 90 50" xmlns="http://www.w3.org/2000/svg">
                    <text x="2" y="38" font-family="Arial, sans-serif" font-size="30" font-weight="700" fill="white" letter-spacing="-0.5">Chile</text>
                    <polygon points="62,4 63.8,9.5 69.5,9.5 64.9,12.8 66.6,18.3 62,15 57.4,18.3 59.1,12.8 54.5,9.5 60.2,9.5" fill="white"/>
                    <polygon points="71,8 72.4,12.4 77,12.4 73.3,15 74.7,19.4 71,16.8 67.3,19.4 68.7,15 65,12.4 69.6,12.4" fill="white"/>
                </svg>
                <h2>Latido Andino</h2>
                <p>SERVICIO NACIONAL DE ADUANAS · PASO LOS LIBERTADORES</p>
            </div>
            <div class="card-body">
                <span class="badge-aprobado"><i class="bi bi-check-circle"></i> APROBADO</span>

                <div class="info-item">
                    <strong><i class="bi bi-person"></i> Viajero</strong>
                    <span><?= htmlspecialchars($tramite->viajero_nombre ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <strong><i class="bi bi-person-badge"></i> RUT</strong>
                    <span><?= htmlspecialchars($tramite->viajero_rut ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <strong><i class="bi bi-geo-alt"></i> Paso fronterizo</strong>
                    <span><?= htmlspecialchars($tramite->paso_fronterizo ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <strong><i class="bi bi-calendar-check"></i> Fecha aprobación</strong>
                    <span><?= isset($tramite->fecha_aprobacion) ? date('d/m/Y H:i', $tramite->fecha_aprobacion->toDateTime()->getTimestamp()) : 'N/A' ?></span>
                </div>
                <div class="info-item">
                    <strong><i class="bi bi-qr-code"></i> Código</strong>
                    <span><?= htmlspecialchars($tramite->pase_agil_qr ?? '') ?></span>
                </div>

                <a href="/" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver al inicio</a>
            </div>
            <div class="card-footer">
                Documento válido para el control fronterizo · Verificar en aduana
            </div>
        </div>
    </div>
</body>
</html>