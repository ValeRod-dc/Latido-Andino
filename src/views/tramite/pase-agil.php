<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino - Pase Ágil</title>
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
                <a href="/portal/viajero" class="btn-home" style="text-decoration:none;">
                    <i class="bi bi-house-door"></i> Página principal
                </a>
            </nav>
        </div>
    </header>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="form-card text-center">
                    <div class="form-card-header">
                        <h4 class="mb-1"><i class="bi bi-qr-code"></i> Pase Ágil Aprobado</h4>
                        <p class="mb-0 opacity-75">Presenta este código QR en el control fronterizo</p>
                    </div>

                    <div class="form-card-body px-4 py-4">

                        <!-- ── QR + Datos lado a lado ──────────────────────── -->
                        <div style="
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            gap:32px;
                            flex-wrap:wrap;
                            margin-bottom:24px;
                        ">
                            <!-- QR con badge -->
                            <div class="position-relative d-inline-block">
                                <div style="
                                    background:#fff;
                                    border-radius:16px;
                                    padding:16px;
                                    box-shadow:0 4px 24px rgba(21,101,192,.15);
                                    display:inline-block;
                                ">
                                    <img src="<?= htmlspecialchars($qrUrl) ?>"
                                         alt="Código QR Pase Ágil"
                                         style="width:200px;height:200px;display:block;">
                                </div>
                                <span style="
                                    position:absolute;top:-12px;left:50%;transform:translateX(-50%);
                                    background:#1B7A3C;color:#fff;font-size:.72rem;font-weight:700;
                                    padding:3px 14px;border-radius:20px;letter-spacing:.05em;
                                    white-space:nowrap;
                                ">✓ VÁLIDO PARA CRUCE</span>
                            </div>

                            <!-- Datos del trámite -->
                            <div style="
                                background:#f0f6ff;
                                border-left:4px solid #1565C0;
                                border-radius:0 10px 10px 0;
                                padding:20px 24px;
                                text-align:left;
                                min-width:220px;
                            ">
                                <p class="mb-3">
                                    <span style="color:#555;font-weight:600;display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Viajero</span>
                                    <strong><?= htmlspecialchars($tramite->viajero_nombre ?? '') ?></strong>
                                </p>
                                <p class="mb-3">
                                    <span style="color:#555;font-weight:600;display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Paso fronterizo</span>
                                    <strong><?= htmlspecialchars($tramite->paso_fronterizo ?? '') ?></strong>
                                </p>
                                <p class="mb-0">
                                    <span style="color:#555;font-weight:600;display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Estado</span>
                                    <span style="
                                        background:#1B7A3C;color:#fff;
                                        padding:4px 14px;border-radius:20px;
                                        font-size:.78rem;font-weight:700;
                                        text-transform:uppercase;letter-spacing:.05em;
                                    "><?= htmlspecialchars($tramite->estado) ?></span>
                                </p>
                            </div>
                        </div>

                        <!-- ── Link alternativo + botón copiar ────────────── -->
                        <div style="
                            background:#f8faff;
                            border:1px solid #dce8ff;
                            border-radius:10px;
                            padding:12px 16px;
                            max-width:540px;
                            margin:0 auto 20px;
                            text-align:left;
                        ">
                            <div style="font-size:.8rem;color:#555;margin-bottom:6px;">
                                <i class="bi bi-link-45deg"></i>
                                <strong>¿No funciona el QR?</strong> Usa este enlace directo:
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <a href="<?= htmlspecialchars($urlVerificacion) ?>"
                                   target="_blank"
                                   style="
                                       font-size:.82rem;color:#1565C0;flex:1;
                                       text-decoration:none;border-bottom:1px dotted #1565C0;
                                       word-break:break-all;
                                   ">
                                    <?= htmlspecialchars($urlVerificacion) ?>
                                </a>
                                <button id="btnCopiar" onclick="copiarLink()" style="
                                    background:#1565C0;color:#fff;border:none;
                                    border-radius:7px;padding:5px 12px;
                                    font-size:.78rem;cursor:pointer;white-space:nowrap;
                                    flex-shrink:0;transition:background .2s;
                                ">
                                    <i class="bi bi-clipboard"></i> Copiar
                                </button>
                            </div>
                        </div>

                        <!-- ── Nota de vigencia ───────────────────────────── -->
                        <p style="font-size:.78rem;color:#888;margin-bottom:24px;">
                            <i class="bi bi-clock"></i>
                            Código generado el <?= date('d/m/Y H:i') ?> · Válido para presentar en frontera
                        </p>

                        <!-- ── Botón volver ───────────────────────────────── -->
                        <a href="/portal/viajero"
                           onmouseover="this.style.background='#0d47a1'"
                           onmouseout="this.style.background='#1565C0'"
                           style="
                               display:inline-flex;align-items:center;gap:8px;
                               background:#1565C0;color:#fff;text-decoration:none;
                               padding:10px 28px;border-radius:8px;font-weight:600;
                               font-size:.92rem;transition:background .2s;
                           ">
                            <i class="bi bi-house-door"></i> Volver al inicio
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    function copiarLink() {
        navigator.clipboard.writeText('<?= htmlspecialchars($urlVerificacion) ?>').then(() => {
            const btn = document.getElementById('btnCopiar');
            btn.innerHTML = '<i class="bi bi-clipboard-check"></i> ¡Copiado!';
            btn.style.background = '#1B7A3C';
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-clipboard"></i> Copiar';
                btn.style.background = '#1565C0';
            }, 2000);
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>