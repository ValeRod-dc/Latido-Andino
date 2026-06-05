<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="bi bi-person-plus-fill"></i>
                <h2 class="mb-0">Coffee Shop</h2>
                <p class="mb-0 mt-2 opacity-75">Crear nueva cuenta</p>
            </div>
            
            <div class="login-body">
                <div id="alert-container"></div>
                
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person"></i> Nombre completo
                        </label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Tu nombre completo">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="tu@email.com">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Contraseña
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Mínimo 6 caracteres" minlength="6">
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">
                            <i class="bi bi-lock-fill"></i> Confirmar contraseña
                        </label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Repite tu contraseña">
                    </div>
                    
                    <button type="submit" class="btn btn-coffee w-100">
                        <i class="bi bi-person-plus"></i> Registrarse
                    </button>
                </form>
                
                <div class="mt-3 text-center">
                    <p class="mb-2">¿Ya tienes una cuenta?</p>
                    <a href="/login" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </a>
                </div>
                
                <div class="mt-3 text-center">
                    <a href="/home" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Volver a la tienda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const alertContainer = document.getElementById('alert-container');
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Validar que las contraseñas coincidan
            if (password !== confirmPassword) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> Las contraseñas no coinciden
                    </div>
                `;
                return;
            }
            
            try {
                const response = await fetch('/auth/register', {
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
                    }, 1000);
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
