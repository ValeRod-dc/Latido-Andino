<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino – Servicio Nacional de Aduanas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/landing.css">
</head>
<body>

<!-- ===== MODAL LOGIN / REGISTRO ===== -->
<div class="modal-overlay" id="modalAuth">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-logo">
        <svg width="36" height="36" viewBox="0 0 90 50" xmlns="http://www.w3.org/2000/svg">
          <text x="2" y="38" font-family="Arial, sans-serif" font-size="30" font-weight="700" fill="white" letter-spacing="-0.5">Chile</text>
          <polygon points="62,4 63.8,9.5 69.5,9.5 64.9,12.8 66.6,18.3 62,15 57.4,18.3 59.1,12.8 54.5,9.5 60.2,9.5" fill="white"/>
          <polygon points="71,8 72.4,12.4 77,12.4 73.3,15 74.7,19.4 71,16.8 67.3,19.4 68.7,15 65,12.4 69.6,12.4" fill="white"/>
        </svg>
        <div>
          <span id="modal-titulo">Iniciar Sesión</span>
          <span class="modal-logo-sub">Servicio Nacional de Aduanas · Chile</span>
        </div>
      </div>
      <button class="modal-cerrar" onclick="cerrarModal()">✕</button>
    </div>

    <div class="modal-body">
      <!-- PANEL LOGIN -->
      <div class="modal-panel activo" id="panel-login">
        <div class="modal-aviso">🔑 Acceso seguro mediante cifrado AES-256. Sus datos están protegidos conforme a la Ley 19.628.</div>
        <div class="modal-form-group">
          <label>Correo Electrónico</label>
          <div class="modal-input-icon">
            <span class="icono-campo">👤</span>
            <input type="email" id="login-email" placeholder="usuario@ejemplo.cl">
          </div>
        </div>
        <div class="modal-form-group">
          <label>Contraseña</label>
          <div class="modal-input-icon">
            <span class="icono-campo">🔒</span>
            <input type="password" id="login-password" placeholder="Ingresa tu contraseña">
          </div>
        </div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
          <label style="display:flex; align-items:center; gap:7px; font-size:13px; color:var(--gris-texto); cursor:pointer;">
            <input type="checkbox" id="remember"> Recordar sesión
          </label>
          <a href="#" class="modal-link" style="margin:0; font-size:13px;">¿Olvidaste tu contraseña?</a>
        </div>
        <button class="btn-modal-submit" onclick="procesarLogin()">Ingresar al Portal</button>
        <div class="modal-divider">o continúa con</div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
          <button style="border:1px solid var(--gris-borde); background:#fff; border-radius:var(--radio); padding:10px; font-size:13px; font-weight:600; cursor:pointer;">🆔 ClaveÚnica</button>
          <button style="border:1px solid var(--gris-borde); background:#fff; border-radius:var(--radio); padding:10px; font-size:13px; font-weight:600; cursor:pointer;">📱 Pasaporte Digital</button>
        </div>
        <a class="modal-link" onclick="switchModalTab('registro')">¿No tienes cuenta? Regístrate aquí →</a>
      </div>

      <!-- PANEL REGISTRO -->
      <div class="modal-panel" id="panel-registro">
        <div style="margin-bottom:14px;">
          <div class="modal-form-group">
            <label>Tipo de usuario</label>
            <select id="reg-tipo" onchange="seleccionarTipo(this)">
              <option value="viajero">Viajero / Turista</option>
              <option value="transportista">Transportista</option>
              <option value="pdi">Funcionario PDI</option>
              <option value="sag">Funcionario SAG</option>
              <option value="aduanas">Funcionario Aduanas</option>
              <option value="admin">Administrador</option>
            </select>
          </div>
        </div>
        <div class="modal-row">
          <div class="modal-form-group"><label>Nombres *</label><input type="text" id="reg-nombres" placeholder="Nombres"></div>
          <div class="modal-form-group"><label>Apellidos *</label><input type="text" id="reg-apellidos" placeholder="Apellidos"></div>
        </div>
        <div style="height:12px;"></div>
        <div class="modal-row">
          <div class="modal-form-group"><label>RUT o Pasaporte *</label><input type="text" id="reg-rut" placeholder="12.345.678-9"></div>
          <div class="modal-form-group"><label>Nacionalidad *</label><select id="reg-nacionalidad"><option>Chilena</option><option>Argentina</option><option>Otra</option></select></div>
        </div>
        <div style="height:12px;"></div>
        <div class="modal-form-group">
          <label>Correo Electrónico *</label>
          <div class="modal-input-icon">
            <span class="icono-campo">✉️</span>
            <input type="email" id="reg-email" placeholder="correo@ejemplo.cl">
          </div>
        </div>
        <div class="modal-row">
          <div class="modal-form-group"><label>Contraseña *</label><input type="password" id="reg-password" placeholder="Mínimo 6 caracteres"></div>
          <div class="modal-form-group"><label>Confirmar contraseña*</label><input type="password" id="reg-confirm"></div>
        </div>
        <div style="height:14px;"></div>
        <label class="checkbox-label">
          <input type="checkbox" id="reg-terminos" name="terminos" required>
          <span>Acepto los <a href="#">Términos de Uso</a> y autorizo el tratamiento de mis datos conforme a la <a href="#">Ley 19.628</a>.</span>
        </label>
        <button class="btn-modal-submit rojo" onclick="procesarRegistro()">Crear Cuenta</button>
        <a class="modal-link" onclick="switchModalTab('login')">← Ya tengo cuenta, iniciar sesión</a>
      </div>
    </div>
  </div>
</div>

<!-- ===== LANDING PAGE (CARRUSEL) ===== -->
<div id="landing-page">
  <div class="carrusel-wrap">
    <div class="landing-header">
      <a class="landing-logo" href="#">
        <svg width="90" height="50" viewBox="0 0 90 50" xmlns="http://www.w3.org/2000/svg">
          <text x="2" y="38" font-family="Arial, sans-serif" font-size="30" font-weight="700" fill="white" letter-spacing="-0.5">Chile</text>
          <polygon points="62,4 63.8,9.5 69.5,9.5 64.9,12.8 66.6,18.3 62,15 57.4,18.3 59.1,12.8 54.5,9.5 60.2,9.5" fill="white"/>
          <polygon points="71,8 72.4,12.4 77,12.4 73.3,15 74.7,19.4 71,16.8 67.3,19.4 68.7,15 65,12.4 69.6,12.4" fill="white"/>
        </svg>
        <div class="landing-logo-txt">
          <span class="nombre">Latido Andino</span>
          <span class="sub">Servicio Nacional de Aduanas · Paso Los Libertadores</span>
        </div>
      </a>
      <nav class="landing-nav">
        <button class="landing-nav-link">Inicio</button>
        <button class="landing-nav-link" onclick="document.getElementById('sobre-sistema').scrollIntoView({ behavior: 'smooth' });">
          <i class="bi bi-info-circle"></i> Sobre el sistema
        </button>
        <button class="btn-landing-login" onclick="abrirModal('login')">Iniciar sesión</button>
        <button class="btn-landing-registro" onclick="abrirModal('registro')">Registrarse</button>
      </nav>
    </div>

    <!-- Slides (igual que en el HTML original) -->
    <div class="slide activo" id="slide-0">
      <img class="slide-img" src="/img/Cordillera.png" alt="Paso Los Libertadores - Cordillera de los Andes">
      <div class="slide-overlay"></div>
      <div class="slide-contenido">
          <span class="slide-tag">🇨🇱 Paso Los Libertadores · Chile – Argentina</span>
          <h1>Cruza la frontera<br>sin <em>esperas</em></h1>
          <p>Pre-registra tu información y documentos antes de llegar. Obtén tu Pase Ágil QR y reduce el tiempo de espera hasta en un 50%.</p>
          <div class="slide-btns">
              <button class="btn-cta-primario" onclick="abrirModal('registro')">Iniciar Pre-Registro</button>
              <button class="btn-cta-secundario" onclick="abrirModal('login')">Consultar mi Trámite</button>
          </div>
      </div>
    </div>
    <div class="slide" id="slide-1">
      <img class="slide-img" src="/img/Camion.png" alt="Transporte de carga en frontera">
      <div class="slide-overlay"></div>
      <div class="slide-contenido">
          <span class="slide-tag">🚛 Para Transportistas y Carga</span>
          <h1>Tu carga cruza<br>más <em>rápido</em></h1>
          <p>Transportistas y empresas pueden pre-declarar su carga, documentación de vehículos y permisos SAG con anticipación, evitando demoras en ventanilla.</p>
          <div class="slide-btns">
              <button class="btn-cta-primario" onclick="abrirModal('registro')">Registrar Empresa</button>
              <button class="btn-cta-secundario" onclick="abrirModal('login')">Ingresar al Portal</button>
          </div>
      </div>
    </div>
    <div class="slide" id="slide-2">
      <img class="slide-img" src="/img/Validacion.png" alt="Validación cruzada interinstitucional">
      <div class="slide-overlay"></div>
      <div class="slide-contenido">
          <span class="slide-tag">🔗 Validación Cruzada Interinstitucional</span>
          <h1>7 organismos,<br>una sola <em>validación</em></h1>
          <p>Nuestro sistema valida tu información simultáneamente con PDI, SAG, Carabineros, Registro Civil, Interpol, SII y Aduana Argentina en menos de 2 segundos.</p>
          <div class="slide-btns">
              <button class="btn-cta-primario" onclick="abrirModal('registro')">Comenzar Ahora</button>
              <button class="btn-cta-secundario">Conocer más</button>
          </div>
      </div>
    </div>
    <div class="slide" id="slide-3">
      <img class="slide-img" src="/img/QR.png" alt="Pase Ágil QR - Aprobación en el bolsillo">
      <div class="slide-overlay"></div>
      <div class="slide-contenido">
          <span class="slide-tag">✅ Pase Ágil QR</span>
          <h1>Tu aprobación<br>en el <em>bolsillo</em></h1>
          <p>Una vez validados tus documentos, recibes un código QR en tu teléfono. Preséntalo en frontera y pasa directo, sin filas ni papeleo.</p>
          <div class="slide-btns">
              <button class="btn-cta-primario" onclick="abrirModal('registro')">Obtener mi Pase QR</button>
              <button class="btn-cta-secundario" onclick="abrirModal('login')">Ya tengo cuenta</button>
          </div>
      </div>
    </div>

    <button class="carrusel-prev" onclick="moverSlide(-1)">‹</button>
    <button class="carrusel-next" onclick="moverSlide(1)">›</button>
    <div class="carrusel-banda">
      <div class="carrusel-stats">
        <div class="cstat"><div class="cstat-num">126K+</div><div class="cstat-label">Viajeros en Feb.</div></div>
        <div class="cstat"><div class="cstat-num">-50%</div><div class="cstat-label">Tiempo de Espera</div></div>
        <div class="cstat"><div class="cstat-num">7</div><div class="cstat-label">Instituciones</div></div>
        <div class="cstat"><div class="cstat-num">&lt;2 seg</div><div class="cstat-label">Validación</div></div>
      </div>
    </div>
    <div class="carrusel-dots">
      <button class="dot activo" onclick="irASlide(0)"></button>
      <button class="dot" onclick="irASlide(1)"></button>
      <button class="dot" onclick="irASlide(2)"></button>
      <button class="dot" onclick="irASlide(3)"></button>
    </div>
    <div class="carrusel-progreso" id="barraProgreso"></div>
  </div>
</div>

<!-- ===== SECCIÓN: SOBRE EL SISTEMA ===== -->
<section id="sobre-sistema" class="sobre-section">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Sobre el Sistema</h2>
            <p class="section-subtitle">Conoce cómo Latido Andino transforma el control fronterizo</p>
        </div>

        <div class="sobre-grid">
            <div class="sobre-card">
                <span class="icono">🎯</span>
                <h5>Misión</h5>
                <p>Fiscalizar y facilitar el comercio exterior, contribuyendo a la recaudación fiscal, el desarrollo económico y la protección del país.</p>
            </div>
            <div class="sobre-card">
                <span class="icono">👁️</span>
                <h5>Visión</h5>
                <p>Ser reconocidos como un Servicio de gestión pública de excelencia, líderes en la protección y desarrollo del comercio internacional de Chile.</p>
            </div>
            <div class="sobre-card">
                <span class="icono">⚡</span>
                <h5>Objetivo</h5>
                <p>Reducir tiempos de espera en frontera mediante pre-registro y validación cruzada automatizada con 7 instituciones.</p>
            </div>
        </div>

        <div class="instituciones-card">
            <h6>🏛️ Instituciones integradas</h6>
            <span class="badge-inst pdi">PDI</span>
            <span class="badge-inst sag">SAG</span>
            <span class="badge-inst interpol">Interpol</span>
            <span class="badge-inst registro">Reg. Civil</span>
            <span class="badge-inst rnv">RNV</span>
            <span class="badge-inst carabineros">Carabineros</span>
            <span class="badge-inst sii">SII</span>
        </div>
    </div>
</section>

<script>
// ===== LOGICA DE CARRUSEL =====
let slideActual = 0;
const totalSlides = 4;
let intervaloCarrusel;
let progresoVal = 0;
const DURACION = 5500;

function irASlide(n) {
  document.querySelectorAll('.slide').forEach((s,i) => s.classList.toggle('activo', i === n));
  document.querySelectorAll('.dot').forEach((d,i) => d.classList.toggle('activo', i === n));
  slideActual = n;
  reiniciarProgreso();
}
function moverSlide(dir) { irASlide((slideActual + dir + totalSlides) % totalSlides); }
function reiniciarProgreso() { progresoVal = 0; document.getElementById('barraProgreso').style.width = '0%'; }
function pararCarrusel() { clearInterval(intervaloCarrusel); }

intervaloCarrusel = setInterval(() => {
  progresoVal += (100 / (DURACION / 100));
  if (progresoVal >= 100) { moverSlide(1); progresoVal = 0; }
  document.getElementById('barraProgreso').style.width = progresoVal + '%';
}, 100);

// ===== MODAL =====
function abrirModal(tab) {
  document.getElementById('modalAuth').classList.add('abierto');
  if (tab === 'registro') switchModalTab('registro', document.querySelectorAll('.modal-tab')[1]);
  else switchModalTab('login', document.querySelectorAll('.modal-tab')[0]);
}
function cerrarModal() { document.getElementById('modalAuth').classList.remove('abierto'); }
document.getElementById('modalAuth').addEventListener('click', function(e) { if (e.target === this) cerrarModal(); });

function switchModalTab(tab) {
  document.querySelectorAll('.modal-panel').forEach(p => p.classList.remove('activo'));
  document.getElementById('panel-' + tab).classList.add('activo');
  document.getElementById('modal-titulo').textContent =
    tab === 'login' ? 'Iniciar Sesión' : 'Crear Cuenta';
}

function seleccionarTipo(select) {
  document.querySelectorAll('.tipo-btn').forEach(b => b.classList.remove('seleccionado'));
  btn.classList.add('seleccionado');
}

// ===== PETICIONES AJAX A PHP =====
async function procesarLogin() {
  const email = document.getElementById('login-email').value;
  const password = document.getElementById('login-password').value;
  const remember = document.getElementById('remember').checked;

  const formData = new FormData();
  formData.append('email', email);
  formData.append('password', password);
  if (remember) formData.append('remember', '1');

  const response = await fetch('/auth/login', { method: 'POST', body: formData });
  const data = await response.json();
  if (data.success) {
    window.location.href = data.redirect;
  } else {
    alert(data.message);
  }
}

async function procesarRegistro() {
  const nombre      = document.getElementById('reg-nombres').value.trim();
  const apellido    = document.getElementById('reg-apellidos').value.trim();
  const rut         = document.getElementById('reg-rut').value.trim();
  const nacionalidad= document.getElementById('reg-nacionalidad').value;
  const email       = document.getElementById('reg-email').value.trim();
  const password    = document.getElementById('reg-password').value;
  const confirm     = document.getElementById('reg-confirm').value;
  const terminosEl  = document.getElementById('reg-terminos');
  const rol         = document.getElementById('reg-tipo').value;

  if (!nombre || !apellido || !email || !password) {
    alert('Por favor completa todos los campos obligatorios'); return;
  }
  if (password !== confirm) { alert('Las contraseñas no coinciden'); return; }
  if (password.length < 6) { alert('La contraseña debe tener al menos 6 caracteres'); return; }
  if (!terminosEl || !terminosEl.checked) { alert('Debes aceptar los términos'); return; }

  const formData = new FormData();
  formData.append('name', nombre + ' ' + apellido);
  formData.append('email', email);
  formData.append('password', password);
  formData.append('rut', rut);
  formData.append('nacionalidad', nacionalidad);
  formData.append('role', rol);

  const btn = document.querySelector('#panel-registro .btn-modal-submit.rojo');
  const textoOriginal = btn.textContent;
  btn.textContent = '⏳ Creando cuenta...';
  btn.disabled = true;

  try {
    const response = await fetch('/auth/register', { method: 'POST', body: formData });
    const data = await response.json();
    if (data.success) {
      window.location.href = data.redirect;
    } else {
      alert('❌ ' + data.message);
      btn.textContent = textoOriginal;
      btn.disabled = false;
    }
  } catch (e) {
    alert('❌ Error de conexión al servidor');
    btn.textContent = textoOriginal;
    btn.disabled = false;
  }
}

// Cerrar modal con Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });
</script>

</body>
</html>