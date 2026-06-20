<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino - Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="page-legal">
        <a href="/portal/viajero" class="back-link"><i class="bi bi-arrow-left"></i> Volver al inicio</a>

        <div class="legal-card">
            <h1>Contacto</h1>
            <p class="subtitle">¿Tienes dudas sobre tu trámite? Escríbenos.</p>

            <form id="form-contacto">
                <div class="mb-3">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mensaje *</label>
                    <textarea name="mensaje" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Enviar
                </button>
                <div id="contacto-resultado" class="mt-3"></div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('form-contacto').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const resultado = document.getElementById('contacto-resultado');
            try {
                const response = await fetch('/contacto/enviar', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                resultado.innerHTML = data.success
                    ? '<div class="alert alert-success">' + data.message + '</div>'
                    : '<div class="alert alert-danger">' + data.message + '</div>';
                if (data.success) e.target.reset();
            } catch (err) {
                resultado.innerHTML = '<div class="alert alert-danger">Error al enviar el mensaje</div>';
            }
        });
    </script>
</body>
</html>