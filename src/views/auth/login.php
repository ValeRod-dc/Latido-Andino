<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino - Acceso al Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="bi bi-shield-check"></i>
                <h2>Latido Andino</h2>
                <p class="mb-0 opacity-75">Sistema de Gestión Aduanera</p>
            </div>
            
            <div class="login-body">
                <div id="alertContainer"></div>
                
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Correo electrónico
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               required placeholder="usuario@dominio.cl">
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Contraseña
                        </label>
                        <input type="password" class="form-control" id="password" name="password" 
                               required placeholder="••••••••">
                    </div>
                    
                    <button type="submit" class="btn btn-login w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Ingresar al Sistema
                    </button>
                </form>
                
                <div class="demo-users">
                    <div class="text-center mb-2">
                        <i class="bi bi-info-circle"></i> Usuarios de prueba
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="role-badge role-viajero w-100 text-center">
                                <i class="bi bi-person"></i> Viajero
                            </div>
                            <small class="text-muted d-block text-center">viajero@example.com</small>
                        </div>
                        <div class="col-md-6">
                            <div class="role-badge role-aduanas w-100 text-center">
                                <i class="bi bi-building"></i> Aduanas
                            </div>
                            <small class="text-muted d-block text-center">aduanas@aduana.cl</small>
                        </div>
                        <div class="col-md-6">
                            <div class="role-badge role-sag w-100 text-center">
                                <i class="bi bi-tree"></i> SAG
                            </div>
                            <small class="text-muted d-block text-center">sag@sag.cl</small>
                        </div>
                        <div class="col-md-6">
                            <div class="role-badge role-pdi w-100 text-center">
                                <i class="bi bi-shield"></i> PDI
                            </div>
                            <small class="text-muted d-block text-center">pdi@pdi.cl</small>
                        </div>
                        <div class="col-12">
                            <div class="role-badge role-admin w-100 text-center">
                                <i class="bi bi-gear"></i> Administrador
                            </div>
                            <small class="text-muted d-block text-center">admin@latidoandino.cl</small>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">Contraseña para todos: <strong>123456</strong></small>
                    </div>
                </div>
                
                <div class="mt-3 text-center">
                    <a href="/" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Volver a la página principal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const alertContainer = document.getElementById('alertContainer');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Validando...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch('/auth/login', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> ${data.message}
                            <br><small>Redirigiendo a ${data.rol}...</small>
                        </div>
                    `;
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> ${data.message}
                        </div>
                    `;
                }
            } catch (error) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-wifi-off"></i> Error de conexión al servidor
                    </div>
                `;
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>