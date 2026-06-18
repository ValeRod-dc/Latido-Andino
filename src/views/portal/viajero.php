<?php
// Los datos vienen de PortalController::viajero()
$stats = $stats ?? [
    'tramites_hoy' => 1247,
    'tiempo_promedio' => 18,
    'aprobados_online' => 94.2,
    'sistemas_integrados' => 7
];
$ultimoTramite = $ultimoTramite ?? null;
$qrData = $qrData ?? null;
?>

<section class="hero">
  <div class="hero-inner">
    <div>
      <h1>Cruza la frontera<br><span>sin esperas</span></h1>
      <p>Pre-registra tu información y documentos antes de llegar al Paso Los Libertadores. Obtén tu Pase Ágil QR y reduce el tiempo de espera en hasta un 50%.</p>
      <div class="hero-btns">
        <button class="btn-primario" onclick="window.location.href='/pre-registro'">Iniciar Pre-Registro</button>
        <button class="btn-secundario" onclick="window.location.href='/consulta-estado'">Consultar mi Trámite</button>
      </div>
    </div>
    <div class="hero-card">
      <h3>Pase Ágil QR</h3>
      <div class="qr-mockup">
        <div class="qr-grid" id="qrGrid"></div>
        <p class="qr-label">Escanear al llegar a frontera</p>
      </div>
      <div class="pase-info">
        <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></strong><br>
        RUT: <?= htmlspecialchars($_SESSION['user_rut'] ?? 'No registrado') ?> &nbsp;·&nbsp; Viajero
      </div>
      <div class="pase-info" style="margin-top:8px;">
        <small style="color:rgba(255,255,255,0.55);">Vigente hasta</small><br>
        <?= ($ultimoTramite && $ultimoTramite->estado === 'aprobado' && !empty($ultimoTramite->fecha_aprobacion)) ? date('d M Y', $ultimoTramite->fecha_aprobacion->toDateTime()->getTimestamp()) : 'No hay trámite activo' ?>      </div>
      <span class="badge-aprobado">✓ APROBADO</span>
    </div>
  </div>
</section>

<div class="stats-bar">
  <div class="stats-bar-inner">
    <div class="stat-item"><div class="stat-num"><?= $stats['tramites_hoy'] ?></div><div class="stat-label">Trámites Hoy</div></div>
    <div class="stat-item"><div class="stat-num"><?= $stats['tiempo_promedio'] ?> min</div><div class="stat-label">Tiempo Promedio</div></div>
    <div class="stat-item"><div class="stat-num"><?= $stats['aprobados_online'] ?>%</div><div class="stat-label">Aprobados en Línea</div></div>
    <div class="stat-item"><div class="stat-num"><?= $stats['sistemas_integrados'] ?></div><div class="stat-label">Sistemas Integrados</div></div>
  </div>
</div>

<main class="main">
  <div class="section-heading"><span>Servicios Disponibles</span></div>
  <p class="section-sub" style="margin-bottom:24px;">Selecciona el trámite que necesitas realizar</p>
  <div class="modulos-grid">
    <div class="modulo-card">
      <div class="modulo-icono"><svg fill="none" stroke="#1565C0" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
      <h3>Crear Cuenta</h3><p>Regístrate para acceder a todos los servicios de pre-cruce.</p><a href="#" class="modulo-link">Registrarme →</a>
    </div>
    <div class="modulo-card rojo">
      <div class="modulo-icono"><svg fill="none" stroke="#D92B2B" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
      <h3>Pre-Registro de Cruce</h3><p>Completa tu declaración e ingresa documentos de manera anticipada.</p><a href="#" class="modulo-link">Iniciar Pre-Registro →</a>
    </div>
    <div class="modulo-card">
      <div class="modulo-icono"><svg fill="none" stroke="#1565C0" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
      <h3>Consultar Estado</h3><p>Revisa el avance de tu trámite en tiempo real.</p><a href="#" class="modulo-link">Consultar Trámite →</a>
    </div>
    <div class="modulo-card verde">
      <div class="modulo-icono"><svg fill="none" stroke="#1B7A3C" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 4v1m6.364 1.636l-.707.707M20 12h-1M17.657 17.657l-.707-.707M12 20v-1M6.343 17.657l.707.707M4 12H3M6.343 6.343l.707.707"/></svg></div>
      <h3>Mi Pase Ágil QR</h3><p>Descarga y presenta tu código QR al llegar al paso fronterizo.</p><a href="#" class="modulo-link">Ver mi Pase →</a>
    </div>
    <div class="modulo-card">
      <div class="modulo-icono"><svg fill="none" stroke="#1565C0" stroke-width="2" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg></div>
      <h3>Historial de Cruces</h3><p>Accede al registro completo de tus pasos fronterizos.</p><a href="#" class="modulo-link">Ver Historial →</a>
    </div>
    <div class="modulo-card">
      <div class="modulo-icono"><svg fill="none" stroke="#1565C0" stroke-width="2" viewBox="0 0 24 24"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
      <h3>Ayuda y Normativa</h3><p>Consulta qué documentos necesitas según tu perfil.</p><a href="#" class="modulo-link">Consultar →</a>
    </div>
  </div>

  <div class="section-heading"><span>Pre-Registro</span></div>
  <p class="section-sub" style="margin-bottom:24px;">Ingresa tu información antes de llegar a frontera</p>
  <div class="preregistro-section">
    <div class="form-card">
      <div class="form-header">
        <div><h2>Formulario de Pre-Registro de Cruce</h2><p>Complete todos los campos requeridos. La validación cruzada se realiza automáticamente.</p></div>
      </div>
      <div class="form-body">
        <div class="form-tabs">
          <button class="tab active">1. Datos Personales</button>
          <button class="tab">2. Vehículo / Carga</button>
          <button class="tab">3. Documentos</button>
          <button class="tab">4. Declaración</button>
        </div>
        <!-- Aquí iría el formulario real, similar al HTML, pero con acción real -->
        <button class="btn-enviar" onclick="window.location.href='/pre-registro'">Ir al formulario completo</button>
      </div>
    </div>
    <div class="info-sidebar">
      <div class="info-box"><h4><svg fill="none" stroke="#1565C0" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Documentos Requeridos</h4><ul><li><span class="check">✓</span> Cédula de identidad o pasaporte vigente</li><li><span class="check">✓</span> Declaración de productos SAG (si aplica)</li><li><span class="check">✓</span> Permiso de circulación y revisión técnica</li><li><span class="check">✓</span> SOAP o seguro de vehículo</li><li><span class="check">✓</span> Carta de autorización para menores</li></ul></div>
      <div class="estado-card"><h4>Estado de mi Trámite</h4><div class="estado-step"><div class="paso-circulo completado">✓</div><div class="paso-texto"><strong>Datos ingresados</strong>Información personal registrada</div></div><div class="estado-step"><div class="paso-circulo completado">✓</div><div class="paso-texto"><strong>Documentos cargados</strong>3 de 4 documentos validados</div></div><div class="estado-step"><div class="paso-circulo activo">→</div><div class="paso-texto"><strong>Validación cruzada</strong>PDI, SAG, Carabineros en proceso...</div></div><div class="estado-step"><div class="paso-circulo pendiente">4</div><div class="paso-texto"><strong>Pase Ágil QR</strong>Pendiente de emisión</div></div></div>
    </div>
  </div>
</main>

<script>
  // Generar QR simulado
  const qrPattern = [1,1,1,0,1,1,1,1,0,1,0,1,0,1,1,1,1,1,0,1,1,0,1,0,1,0,0,1,1,1,1,0,1,1,1,0,0,1,1,0,1,0,1,0,0,1,1,1,1];
  const qrContainer = document.getElementById('qrGrid');
  if (qrContainer) {
    qrPattern.forEach(v => { let c = document.createElement('div'); c.className = 'qr-cell ' + (v ? 'qr-dark' : 'qr-light'); qrContainer.appendChild(c); });
  }
</script>