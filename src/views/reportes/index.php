<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Estadísticos · Latido Andino</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/reportes.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>

<div class="reporte-container">
    <div class="reporte-card">

        <div class="reporte-header">
            <h2>Generar Reporte</h2>
        </div>
        <div class="reporte-sub">
            Selecciona el período y formato para obtener un informe estadístico de los trámites aduaneros.
        </div>

        <form method="GET" action="/reporte/generar">
            <div class="form-grid">

                <!-- Fecha inicio -->
                <div class="form-group">
                    <label>Fecha inicio <span class="required">*</span></label>
                    <input type="date" name="fecha_inicio"
                           value="<?= date('Y-m-d', strtotime('-30 days')) ?>"
                           required>
                </div>

                <!-- Fecha fin -->
                <div class="form-group">
                    <label>Fecha fin <span class="required">*</span></label>
                    <input type="date" name="fecha_fin"
                           value="<?= date('Y-m-d') ?>"
                           required>
                </div>

                <!-- Paso fronterizo (ocupa toda la fila) -->
                <div class="form-group full-width">
                    <label>Paso fronterizo</label>
                    <select name="paso">
                        <option value="">Todos los pasos</option>
                        <?php foreach ($pasos as $p): ?>
                            <option value="<?= $p->nombre ?>"><?= $p->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Formato (ocupa toda la fila) -->
                <div class="form-group full-width">
                    <label>Formato de salida <span class="required">*</span></label>
                    <select name="formato">
                        <option value="pdf">📄 PDF (Descarga directa)</option>
                        <option value="excel">📊 Excel (.xlsx)</option>
                    </select>
                </div>

            </div>

            <button type="submit" class="btn-reporte">
                <i class="bi bi-file-earmark-arrow-down"></i> Generar Reporte
            </button>
        </form>

    </div>

    <!-- Enlace para volver -->
    <div class="back-link-wrap">
        <a href="/portal/funcionario">
            <i class="bi bi-arrow-left"></i> Volver al inicio
        </a>
    </div>
</div>

</body>
</html>