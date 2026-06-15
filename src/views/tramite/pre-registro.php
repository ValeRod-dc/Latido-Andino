<!DOCTYPE html>
<html lang="es">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino - Pre-registro Aduanero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/pre-registro.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-shield-check"></i> Latido Andino
            </a>
            <div class="ms-auto">
                <a href="/login" class="btn btn-outline-light">
                    <i class="bi bi-person-badge"></i> Funcionarios
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="step" id="step1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Datos Personales</div>
                    </div>
                    <div class="step" id="step2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Viaje y Vehículo</div>
                    </div>
                    <div class="step" id="step3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Declaración SAG</div>
                    </div>
                    <div class="step" id="step4">
                        <div class="step-circle">4</div>
                        <div class="step-label">Confirmación</div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h4 class="mb-0">
                            <i class="bi bi-file-text"></i> Pre-registro Aduanero
                        </h4>
                        <p class="mb-0 opacity-75 mt-1">Complete el formulario para agilizar su paso por la frontera</p>
                    </div>
                    
                    <div class="form-card-body">
                        <div id="alertContainer"></div>
                        
                        <form id="preRegistroForm">
                            <!-- Sección 1: Datos Personales -->
                            <div id="section1" class="form-section active">
                                <h5 class="mb-3">
                                    <i class="bi bi-person-badge"></i> Datos del Viajero
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">RUT / Pasaporte *</label>
                                        <input type="text" class="form-control" name="rut" 
                                               placeholder="12.345.678-9" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nacionalidad *</label>
                                        <select class="form-select" name="nacionalidad" required>
                                            <option value="Chilena">Chilena</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Peruana">Peruana</option>
                                            <option value="Boliviana">Boliviana</option>
                                            <option value="Otra">Otra</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nombres *</label>
                                        <input type="text" class="form-control" name="nombre" 
                                               placeholder="Juan" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Apellidos *</label>
                                        <input type="text" class="form-control" name="apellido" 
                                               placeholder="Pérez González" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" name="fecha_nacimiento">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teléfono de contacto</label>
                                        <input type="tel" class="form-control" name="telefono" 
                                               placeholder="+56 9 1234 5678">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" 
                                           placeholder="correo@ejemplo.com" required>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    Si viaja con menores de edad, deberá presentar autorización notarial en el control.
                                </div>
                                
                                <div class="text-end">
                                    <button type="button" class="btn-next" onclick="nextSection(2)">
                                        Siguiente <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Sección 2: Viaje y Vehículo -->
                            <div id="section2" class="form-section">
                                <h5 class="mb-3">
                                    <i class="bi bi-geo-alt"></i> Datos del Viaje
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tipo de trámite *</label>
                                        <select class="form-select" name="tipo_tramite" id="tipoTramite" required>
                                            <option value="ingreso">Ingreso a Chile</option>
                                            <option value="salida">Salida de Chile</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Paso fronterizo *</label>
                                        <select class="form-select" name="paso_fronterizo" required>
                                            <option value="Los Libertadores">Los Libertadores (Región de Valparaíso)</option>
                                            <option value="Cardenal Samoré">Cardenal Samoré (Región de Los Lagos)</option>
                                            <option value="Chungará">Chungará (Región de Arica)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div id="destinoFields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">País de destino</label>
                                            <input type="text" class="form-control" name="destino" 
                                                   value="Argentina" placeholder="País destino">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Fecha estimada de retorno</label>
                                            <input type="date" class="form-control" name="fecha_retorno">
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <h5 class="mb-3">
                                    <i class="bi bi-car-front"></i> Datos del Vehículo (si aplica)
                                </h5>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tieneVehiculo" name="tiene_vehiculo">
                                        <label class="form-check-label" for="tieneVehiculo">
                                            Viajo en vehículo particular
                                        </label>
                                    </div>
                                </div>
                                
                                <div id="vehiculoFields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Patente</label>
                                            <input type="text" class="form-control" name="patente" 
                                                   placeholder="AB1234" style="text-transform: uppercase">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Marca</label>
                                            <select class="form-select" name="marca">
                                                <option value="Toyota">Toyota</option>
                                                <option value="Hyundai">Hyundai</option>
                                                <option value="Chevrolet">Chevrolet</option>
                                                <option value="Ford">Ford</option>
                                                <option value="Nissan">Nissan</option>
                                                <option value="Kia">Kia</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Modelo</label>
                                            <input type="text" class="form-control" name="modelo" 
                                                   placeholder="Corolla, Tucson, etc.">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Año</label>
                                            <input type="number" class="form-control" name="anio" 
                                                   placeholder="2020" min="1990" max="2025">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Color</label>
                                            <input type="text" class="form-control" name="color" 
                                                   placeholder="Blanco, Negro, Rojo">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn-prev" onclick="prevSection(1)">
                                        <i class="bi bi-arrow-left"></i> Anterior
                                    </button>
                                    <button type="button" class="btn-next" onclick="nextSection(3)">
                                        Siguiente <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Sección 3: Declaración SAG -->
                            <div id="section3" class="form-section">
                                <h5 class="mb-3">
                                    <i class="bi bi-tree"></i> Declaración Jurada SAG
                                </h5>
                                
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    La información falsa en esta declaración puede derivar en multas y decomiso de productos.
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tieneMascota" name="tiene_mascota">
                                        <label class="form-check-label" for="tieneMascota">
                                            Viajo con mascota(s)
                                        </label>
                                    </div>
                                </div>
                                
                                <div id="mascotaFields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="vacuna_antirrabica">
                                                <label class="form-check-label">
                                                    Vacuna antirrábica vigente
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="microchip">
                                                <label class="form-check-label">
                                                    Microchip instalado
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="certificado_salud">
                                                <label class="form-check-label">
                                                    Certificado de salud
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        ¿Trae productos de origen animal o vegetal?
                                    </label>
                                    <select class="form-select" name="productos[]" multiple size="5">
                                        <option value="frutas_frescas">Frutas frescas</option>
                                        <option value="verduras">Verduras / Hortalizas</option>
                                        <option value="carnes_frescas">Carnes frescas</option>
                                        <option value="embutidos">Embutidos / Jamones</option>
                                        <option value="lacteos">Lácteos (quesos, leche)</option>
                                        <option value="miel">Miel y derivados</option>
                                        <option value="semillas">Semillas / Frutos secos</option>
                                    </select>
                                    <small class="text-muted">Ctrl+Click para seleccionar múltiples</small>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="productosProhibidos">
                                        <label class="form-check-label" for="productosProhibidos">
                                            Declaro no traer productos prohibidos (manzanas, naranjas, productos cárnicos sin etiqueta)
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn-prev" onclick="prevSection(2)">
                                        <i class="bi bi-arrow-left"></i> Anterior
                                    </button>
                                    <button type="button" class="btn-next" onclick="nextSection(4)">
                                        Siguiente <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Sección 4: Confirmación -->
                            <div id="section4" class="form-section">
                                <h5 class="mb-3">
                                    <i class="bi bi-check-circle"></i> Confirmación y Envío
                                </h5>
                                
                                <div class="alert alert-success">
                                    <i class="bi bi-info-circle"></i>
                                    Revise los datos antes de enviar. Recibirá un comprobante por email.
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="declaracionJurada" required>
                                        <label class="form-check-label" for="declaracionJurada">
                                            Declaro bajo juramento que toda la información proporcionada es verdadera y correcta
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terminosCondiciones" required>
                                        <label class="form-check-label" for="terminosCondiciones">
                                            Acepto los términos y condiciones del servicio y el tratamiento de mis datos personales según Ley 19.628
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-qr-code"></i>
                                    Al completar el proceso, recibirá un código QR que deberá presentar en el control fronterizo.
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn-prev" onclick="prevSection(3)">
                                        <i class="bi bi-arrow-left"></i> Anterior
                                    </button>
                                    <button type="submit" class="btn-submit">
                                        <i class="bi bi-send"></i> Enviar Pre-registro
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> Tiempo estimado: 3 minutos | 
                        <i class="bi bi-shield-check"></i> Datos seguros y confidenciales
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSection = 1;
        const totalSections = 4;
        
        function updateProgress() {
            for (let i = 1; i <= totalSections; i++) {
                const step = document.getElementById(`step${i}`);
                step.classList.remove('active', 'completed');
                if (i < currentSection) {
                    step.classList.add('completed');
                } else if (i === currentSection) {
                    step.classList.add('active');
                }
            }
        }
        
        function nextSection(section) {
            // Validar sección actual
            if (currentSection === 1) {
                const rut = document.querySelector('input[name="rut"]').value;
                const nombre = document.querySelector('input[name="nombre"]').value;
                const email = document.querySelector('input[name="email"]').value;
                if (!rut || !nombre || !email) {
                    showAlert('Por favor complete los datos personales', 'warning');
                    return;
                }
            }
            
            document.getElementById(`section${currentSection}`).classList.remove('active');
            currentSection = section;
            document.getElementById(`section${currentSection}`).classList.add('active');
            updateProgress();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function prevSection(section) {
            document.getElementById(`section${currentSection}`).classList.remove('active');
            currentSection = section;
            document.getElementById(`section${currentSection}`).classList.add('active');
            updateProgress();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) alert.remove();
            }, 3000);
        }
        
        // Mostrar/ocultar campos según tipo de trámite
        document.getElementById('tipoTramite').addEventListener('change', function() {
            const destinoFields = document.getElementById('destinoFields');
            destinoFields.style.display = this.value === 'salida' ? 'block' : 'none';
        });
        
        // Mostrar/ocultar campos de vehículo
        document.getElementById('tieneVehiculo').addEventListener('change', function() {
            const vehiculoFields = document.getElementById('vehiculoFields');
            vehiculoFields.style.display = this.checked ? 'block' : 'none';
        });
        
        // Mostrar/ocultar campos de mascota
        document.getElementById('tieneMascota').addEventListener('change', function() {
            const mascotaFields = document.getElementById('mascotaFields');
            mascotaFields.style.display = this.checked ? 'block' : 'none';
        });
        
        // Envío del formulario
        document.getElementById('preRegistroForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Procesando...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch('/api/tramite/procesar', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    if (data.estado === 'aprobado') {
                        setTimeout(() => {
                            window.location.href = `/tramite/pase-agil/${data.tramite_id}`;
                        }, 2000);
                    } else {
                        setTimeout(() => {
                            window.location.href = `/consulta-estado?rut=${formData.get('rut')}`;
                        }, 2000);
                    }
                } else {
                    showAlert(data.message, 'danger');
                }
            } catch (error) {
                showAlert('Error de conexión. Intente nuevamente.', 'danger');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>