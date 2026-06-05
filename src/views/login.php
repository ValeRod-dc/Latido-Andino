<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="bi bi-cup-hot-fill"></i>
                <h2 class="mb-0">Coffee Shop</h2>
                <p class="mb-0 mt-2 opacity-75">Bienvenido de vuelta</p>
            </div>
            
            <div class="login-body">
                <div id="alert-container"></div>
                
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="tu@email.com">
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Contraseña
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                    </div>
                    
                    <button type="submit" class="btn btn-coffee w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </button>
                </form>
                
                <div class="mt-3 text-center">
                    <p class="mb-2">¿No tienes una cuenta?</p>
                    <a href="/register" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-person-plus"></i> Registrarse
                    </a>
                </div>
                
                <div class="mt-3 text-center">
                    <a href="/home" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Volver a la tienda
                    </a>
                </div>
                
                <div class="mt-4 text-center text-muted">
                    <small>
                        <i class="bi bi-info-circle"></i> Usuarios de prueba:<br>
                        <strong>Cliente:</strong> cliente@coffee.com<br>
                        <strong>Trabajador:</strong> trabajador@coffee.com<br>
                        <strong>Admin:</strong> admin@coffee.com<br>
                        <em>(Contraseña para todos: 123456)</em>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const alertContainer = document.getElementById('alert-container');
            
            try {
                const response = await fetch('/auth/login', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle"></i> ${data.message}
                        </div>
                    `;
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> ${data.message}
                        </div>
                    `;
                }
            } catch (error) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> Error de conexión
                    </div>
                `;
            }
        });
    </script>
</body>
</html>
