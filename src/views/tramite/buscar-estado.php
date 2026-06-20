<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino - Consultar Estado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <div class="topbar">
        <div class="topbar-inner">
            <span>🇨🇱 &nbsp;Gobierno de Chile &nbsp;·&nbsp; Ministerio de Hacienda</span>
            <div>
                <a href="/terminos">Términos</a>
                <a href="/privacidad">Privacidad</a>
                <a href="/contacto">Contacto</a>
                <a href="/accesibilidad">Accesibilidad</a>
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
            <div class="col-lg-6">

                <div class="form-card">
                    <div class="form-card-header">
                        <h4 class="mb-1"><i class="bi bi-search"></i> Consultar Estado de Trámite</h4>
                        <p class="mb-0 opacity-75">Ingresa tu RUT para ver el avance de tus trámites</p>
                    </div>
                    <div class="form-card-body">
                        <form action="/consulta-estado" method="GET">
                            <label class="form-label fw-semibold">RUT del viajero</label>
                            <input type="text" name="rut" class="form-control mb-3"
                                   placeholder="12.345.678-9" required>
                            <button type="submit" class="btn-next w-100 border-0">
                                <i class="bi bi-search"></i> Consultar
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>