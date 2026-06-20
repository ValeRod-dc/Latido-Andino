<?php
// Datos que vienen del PortalController::funcionario()
$tramitesPendientes = $tramitesPendientes ?? [];
$userName = $_SESSION['user_name'] ?? 'Funcionario';

// Helpers para badges
function badgeEstado(string $estado): string {
    return match($estado) {
        'aprobado'  => 'badge-verde',
        'pendiente' => 'badge-amarillo',
        default     => 'badge-rojo',
    };
}

// Datos estáticos de ejemplo (pueden venir de BD en el futuro)
$integraciones = [
    ['🔵', 'PDI',          'online', '0.8s'],
    ['🌿', 'SAG',          'slow',   '4.2s'],
    ['🇦🇷', 'Aduana AR',   'online', '1.1s'],
    ['🟢', 'Carabineros',  'online', '0.5s'],
    ['🪪', 'Reg. Civil',   'online', '0.9s'],
    ['🌐', 'Interpol',     'online', '1.3s'],
    ['💼', 'SII',          'online', '0.7s'],
];

$incidencias = [
    ['#INC-001', 'Juan Pérez',    'badge-rojo',    'Crítica', 'Documento vencido — pasaporte exp. 2023',  '09:15', 'badge-amarillo', 'En revisión'],
    ['#INC-002', 'María López',   'badge-amarillo', 'Aviso',  'Discrepancia de datos con Registro Civil', '09:38', 'badge-verde',   'Resuelta'],
    ['#INC-003', 'Carlos Muñoz',  'badge-rojo',    'Crítica', 'Alerta Interpol activa',                   '10:02', 'badge-rojo',    'Escalada'],
];

$flujoTipos = [
    ['Turistas',      'azul',    78],
    ['Transporte',    'rojo',    12],
    ['Diplomáticos',  'verde',    3],
    ['Resid. Front.', 'amarillo', 7],
];

$alertas = [
    ['critica', '🔴', 'Tiempo de espera superado',  'Ventanilla 5 lleva 35 min. sin atención.',    '09:42'],
    ['aviso',   '⚠️', 'API SAG con latencia alta',   'Tiempo de respuesta 4.2 seg.',                '09:38'],
    ['info',    'ℹ️', 'Alto flujo esperado',          '+400 cruces entre 11:00 y 14:00 hrs.',        '08:00'],
];
?>

<!-- ===== BANNER SUPERIOR ===== -->
<div class="banner-funcionario">
  <div class="banner-funcionario-inner">
    <div>
      <div class="cargo">Panel de Fiscalización</div>
      <div class="nombre"><?= htmlspecialchars($userName) ?> · Turno Activo</div>
    </div>
    <div style="display:flex; gap:12px; align-items:center;">
      <span style="font-size:13px; color:rgba(255,255,255,0.7);">Turno activo: 08:00 – 20:00 hrs &nbsp;·&nbsp; Ventanilla 3</span>
      <span class="badge-servicio">● EN SERVICIO</span>
    </div>
  </div>
</div>

<!-- ===== MAIN ===== -->
<main class="main">

  <!-- ===== TABS ===== -->
  <div class="panel-tabs">
    <?php
    $tabs = [
        'dashboard'   => 'Dashboard',
        'validar'     => 'Validar Documentos',
        'monitor'     => 'Monitor Tiempo Real',
        'incidencias' => 'Incidencias',
        'reportes'    => 'Reportes'
    ];
    foreach ($tabs as $id => $label):
    ?>
      <button class="ptab <?= $id === 'dashboard' ? 'active' : '' ?>" onclick="cambiarTab('<?= $id ?>', this)">
        <?= $label ?>
      </button>
    <?php endforeach; ?>
  </div>

  <!-- ========================================================== -->
  <!-- ===== TAB: DASHBOARD ===== -->
  <!-- ========================================================== -->
  <div class="tab-section" id="tab-dashboard">

    <!-- Estadísticas rápidas -->
    <div class="dashboard-grid">
      <div class="dash-card"><div class="dash-num">342</div><div class="dash-label">Trámites Pendientes</div></div>
      <div class="dash-card"><div class="dash-num verde">289</div><div class="dash-label">Aprobados Hoy</div></div>
      <div class="dash-card"><div class="dash-num rojo">14</div><div class="dash-label">Con Incidencia</div></div>
      <div class="dash-card"><div class="dash-num amarillo">39</div><div class="dash-label">En Revisión Manual</div></div>
    </div>

    <!-- Botones de acciones rápidas -->
    <div style="display:flex; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
      <a href="/reporte" class="btn-reporte" style="text-decoration:none; padding:10px 20px; display:inline-block;">
        📊 Generar Reportes
      </a>
      <button class="btn-primario" onclick="abrirModalIncidencia()">
        ⚠️ Registrar Incidencia
      </button>
    </div>

    <!-- Tabla de trámites pendientes -->
    <div class="tabla-tramites">
      <div class="tabla-header">
        <h3>Cola de Trámites Pendientes</h3>
        <div class="filtros">
          <select><option>Todos los tipos</option></select>
          <select><option>Todo estado</option></select>
          <input type="text" placeholder="Buscar RUT o nombre...">
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>N° Trámite</th>
            <th>Viajero</th>
            <th>RUT / Pasaporte</th>
            <th>Tipo</th>
            <th>Documentos</th>
            <th>Validación</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($tramitesPendientes)): ?>
            <tr>
              <td colspan="8" style="text-align:center; padding:30px; color:var(--gris-muted);">
                No hay trámites pendientes en este momento
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($tramitesPendientes as $t): ?>
            <tr>
              <td><strong>#<?= substr((string)$t->_id, -6) ?></strong></td>
              <td><?= htmlspecialchars($t->viajero_nombre ?? 'Sin nombre') ?></td>
              <td><?= htmlspecialchars($t->viajero_rut ?? 'N/A') ?></td>
              <td><span class="badge badge-azul"><?= ucfirst($t->tipo ?? 'Ingreso') ?></span></td>
              <td>✓ <?= isset($t->documentos) ? count((array)$t->documentos) : 0 ?>/4</td>
              <td>
                <?php
                  $estadoValidacion = $t->validacion_cruzada ?? null;
                  if ($estadoValidacion) {
                    echo '<span class="badge badge-verde">✓ 7/7 APIs</span>';
                  } else {
                    echo '<span class="badge badge-amarillo">⏳ Pendiente</span>';
                  }
                ?>
              </td>
              <td><span class="badge <?= badgeEstado($t->estado ?? 'pendiente') ?>"><?= ucfirst($t->estado ?? 'pendiente') ?></span></td>
              <td>
                <!-- 🟢 BOTONES DE REGISTRO DE FLUJO (RF-06) -->
                <button class="btn-accion" style="background:var(--verde);" onclick="registrarFlujo('<?= (string)$t->_id ?>', 'ingreso')">
                  🟢 Ingreso
                </button>
                <button class="btn-accion" style="background:var(--rojo);" onclick="registrarFlujo('<?= (string)$t->_id ?>', 'egreso')">
                  🔴 Egreso
                </button>
                <button class="btn-accion outline">Detalle</button>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div><!-- /tab-dashboard -->

  <!-- ========================================================== -->
  <!-- ===== TAB: VALIDAR DOCUMENTOS ===== -->
  <!-- ========================================================== -->
  <div class="tab-section" id="tab-validar" style="display:none;">
    <div class="tabla-tramites">
      <div class="tabla-header">
        <h3>Validación de Documentos</h3>
        <div class="filtros"><input type="text" placeholder="Buscar RUT o nombre..."></div>
      </div>
      <table>
        <thead>
          <tr><th>N° Trámite</th><th>Viajero</th><th>RUT / Pasaporte</th><th>Tipo</th><th>Estado</th><th>Acción</th></tr>
        </thead>
        <tbody>
          <?php foreach ($tramitesPendientes as $t): ?>
          <tr>
            <td><strong>#<?= substr((string)$t->_id, -6) ?></strong></td>
            <td><?= htmlspecialchars($t->viajero_nombre) ?></td>
            <td><?= htmlspecialchars($t->viajero_rut) ?></td>
            <td><span class="badge badge-azul"><?= ucfirst($t->tipo) ?></span></td>
            <td><span class="badge <?= badgeEstado($t->estado) ?>"><?= ucfirst($t->estado) ?></span></td>
            <td>
              <button class="btn-accion">✓ Aprobar</button>
              <button class="btn-accion outline">✕ Rechazar</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div><!-- /tab-validar -->

  <!-- ========================================================== -->
  <!-- ===== TAB: MONITOR TIEMPO REAL ===== -->
  <!-- ========================================================== -->
  <div class="tab-section" id="tab-monitor" style="display:none;">

    <div class="section-heading"><span>Monitoreo Tiempo Real</span></div>
    <p class="section-sub">Actualización automática cada 30 segundos</p>

    <div class="monitor-grid">
      <div class="monitor-card">
        <h4>Flujo por Tipo de Viajero (última hora)</h4>
        <div class="barras">
          <?php foreach ($flujoTipos as [$label, $color, $pct]): ?>
          <div class="barra-item">
            <span class="barra-label"><?= $label ?></span>
            <div class="barra-track"><div class="barra-fill <?= $color ?>" style="width:<?= $pct ?>%"></div></div>
            <span class="barra-val"><?= $pct ?>%</span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="monitor-card">
        <h4>Alertas Activas del Sistema</h4>
        <div class="alertas-lista">
          <?php foreach ($alertas as [$tipo, $icon, $titulo, $desc, $hora]): ?>
          <div class="alerta-item <?= $tipo ?>">
            <div><?= $icon ?></div>
            <div>
              <p><strong><?= $titulo ?>:</strong> <?= $desc ?></p>
              <div class="hora">Hoy, <?= $hora ?> hrs</div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Integraciones -->
    <div class="integraciones">
      <h3>Estado de Integraciones Interinstitucionales</h3>
      <div class="inst-grid">
        <?php foreach ($integraciones as [$icon, $nombre, $estado, $tiempo]): ?>
        <div class="inst-item">
          <div class="inst-icon"><?= $icon ?></div>
          <div class="inst-nombre"><?= $nombre ?></div>
          <div class="inst-dot <?= $estado ?>"></div>
          <div class="inst-estado"><?= $estado === 'online' ? 'Online' : 'Lento' ?> · <?= $tiempo ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div><!-- /tab-monitor -->

  <!-- ========================================================== -->
  <!-- ===== TAB: INCIDENCIAS ===== -->
  <!-- ========================================================== -->
  <div class="tab-section" id="tab-incidencias" style="display:none;">
    <div class="tabla-tramites">
      <div class="tabla-header"><h3>Registro de Incidencias</h3></div>
      <table>
        <thead>
          <tr><th>ID</th><th>Viajero</th><th>Severidad</th><th>Descripción</th><th>Hora</th><th>Estado</th></tr>
        </thead>
        <tbody>
          <?php foreach ($incidencias as [$id, $viajero, $badgeSev, $sev, $desc, $hora, $badgeEst, $est]): ?>
          <tr>
            <td><strong><?= $id ?></strong></td>
            <td><?= $viajero ?></td>
            <td><span class="badge <?= $badgeSev ?>"><?= $sev ?></span></td>
            <td><?= $desc ?></td>
            <td><?= $hora ?></td>
            <td><span class="badge <?= $badgeEst ?>"><?= $est ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div><!-- /tab-incidencias -->

  <!-- ========================================================== -->
  <!-- ===== TAB: REPORTES ===== -->
  <!-- ========================================================== -->
  <div class="tab-section" id="tab-reportes" style="display:none;">
    <div class="section-heading"><span>Resumen del Turno</span></div>
    <div class="dashboard-grid" style="grid-template-columns: repeat(3, 1fr);">
      <div class="dash-card"><div class="dash-num">342</div><div class="dash-label">Total Trámites</div></div>
      <div class="dash-card"><div class="dash-num verde">289</div><div class="dash-label">Aprobados</div></div>
      <div class="dash-card"><div class="dash-num rojo">14</div><div class="dash-label">Rechazados</div></div>
      <div class="dash-card"><div class="dash-num amarillo">8.4 min</div><div class="dash-label">Tiempo Promedio</div></div>
      <div class="dash-card"><div class="dash-num">3</div><div class="dash-label">Incidencias Activas</div></div>
      <div class="dash-card"><div class="dash-num verde">98.2%</div><div class="dash-label">APIs Operativas</div></div>
    </div>
  </div><!-- /tab-reportes -->

</main>

<!-- ========================================================== -->
<!-- ===== MODAL PARA REGISTRAR INCIDENCIA (RF-09) ===== -->
<!-- ========================================================== -->
<div id="modalIncidencia" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:999; backdrop-filter:blur(3px);">
  <div style="background:#fff; padding:30px; border-radius:12px; max-width:520px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation:modalEntrada 0.25s ease;">
    <h3 style="color:var(--azul); margin-bottom:20px;">⚠️ Registrar Incidencia</h3>
    <form id="formIncidencia">
      <div class="form-group">
        <label>Trámite asociado</label>
        <select name="tramite_id" id="incidencia_tramite_id" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:var(--radio);">
          <option value="">Seleccione un trámite...</option>
          <?php foreach ($tramitesPendientes as $t): ?>
            <option value="<?= htmlspecialchars((string)$t->_id) ?>">
              #<?= substr((string)$t->_id, -6) ?> — <?= htmlspecialchars($t->viajero_nombre) ?> (<?= htmlspecialchars($t->viajero_rut) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tipo de incidencia</label>
        <select name="tipo" required>
          <option value="documentacion_invalida">📄 Documentación inválida</option>
          <option value="alerta_sanitaria">⚠️ Alerta sanitaria</option>
          <option value="inconsistencia">📊 Inconsistencia en datos</option>
          <option value="otro">🔹 Otro</option>
        </select>
      </div>
      <div class="form-group">
        <label>Descripción detallada</label>
        <textarea name="descripcion" rows="4" placeholder="Describa la anomalía o situación..." required></textarea>
      </div>
      <div style="display:flex; gap:10px; margin-top:10px;">
        <button type="submit" class="btn-primario" style="border:none; cursor:pointer;">✅ Registrar Incidencia</button>
        <button type="button" onclick="cerrarModalIncidencia()" style="background:#ccc; border:none; padding:10px 20px; border-radius:var(--radio); font-weight:600; cursor:pointer;">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- ========================================================== -->
<!-- ===== JAVASCRIPT ===== -->
<!-- ========================================================== -->
<script>
// ===== CAMBIO DE TABS =====
function cambiarTab(id, btn) {
  document.querySelectorAll('.tab-section').forEach(s => s.style.display = 'none');
  document.querySelectorAll('.ptab').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id).style.display = 'block';
  btn.classList.add('active');
}

// ===== MODAL DE INCIDENCIAS =====
function abrirModalIncidencia() {
  document.getElementById('modalIncidencia').style.display = 'flex';
}
function cerrarModalIncidencia() {
  document.getElementById('modalIncidencia').style.display = 'none';
}

document.getElementById('formIncidencia').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  const submitBtn = this.querySelector('button[type="submit"]');
  const originalText = submitBtn.textContent;
  submitBtn.textContent = '⏳ Enviando...';
  submitBtn.disabled = true;

  try {
    const response = await fetch('/incidencia/registrar', { method: 'POST', body: formData });
    const data = await response.json();

    if (data.success) {
      alert('Incidencia ' + data.codigo + ' registrada correctamente');

      // Agregar fila a la tabla de incidencias sin recargar la página
      const select = document.getElementById('incidencia_tramite_id');
      const viajero = select.options[select.selectedIndex].text.split('—')[1]?.split('(')[0]?.trim() || '—';
      const tipoTexto = this.querySelector('select[name="tipo"]').selectedOptions[0].text;
      const hora = new Date().toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' });

      const tbody = document.querySelector('#tab-incidencias tbody');
      const fila = document.createElement('tr');
      fila.innerHTML = `
        <td><strong>${data.codigo}</strong></td>
        <td>${viajero}</td>
        <td><span class="badge badge-amarillo">Aviso</span></td>
        <td>${tipoTexto}</td>
        <td>${hora}</td>
        <td><span class="badge badge-amarillo">En revisión</span></td>
      `;
      tbody.prepend(fila);

      this.reset();
      cerrarModalIncidencia();
    } else {
      alert('❌ Error: ' + (data.message || ''));
    }
  } catch (error) {
    alert('❌ Error de conexión al servidor');
  } finally {
    submitBtn.textContent = originalText;
    submitBtn.disabled = false;
  }
});

// ===== REGISTRO DE FLUJO (RF-06) =====
async function registrarFlujo(tramiteId, tipo) {
  if (!confirm(`¿Registrar ${tipo === 'ingreso' ? 'INGRESO' : 'EGRESO'} para el trámite #${tramiteId.substring(0,6)}?`)) return;

  const formData = new FormData();
  formData.append('tramite_id', tramiteId);
  formData.append('tipo', tipo);

  try {
    const response = await fetch('/tramite/registrar-flujo', { method: 'POST', body: formData });
    const data = await response.json();
    alert(data.success ? '✅ Registro de flujo exitoso' : '❌ Error: ' + (data.message || ''));
    if (data.success) location.reload();
  } catch (error) {
    alert('❌ Error de conexión al servidor');
  }
}

// ===== CERRAR MODAL CON ESCAPE =====
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') cerrarModalIncidencia();
});

// ===== CERRAR MODAL HACIENDO CLICK FUERA =====
document.getElementById('modalIncidencia').addEventListener('click', function(e) {
  if (e.target === this) cerrarModalIncidencia();
});
</script>