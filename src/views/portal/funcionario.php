<?php
$tramitesPendientes = $tramitesPendientes ?? [];

// Helpers
function badgeEstado(string $estado): string {
    return match($estado) {
        'aprobado'  => 'badge-verde',
        'pendiente' => 'badge-amarillo',
        default     => 'badge-rojo',
    };
}

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

<!-- Banner -->
<div class="banner-funcionario">
  <div class="banner-funcionario-inner">
    <div>
      <div class="cargo">Panel de Fiscalización</div>
      <div class="nombre"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Inspector') ?> · Turno Activo</div>
    </div>
    <div style="display:flex; gap:12px; align-items:center;">
      <span style="font-size:13px; color:rgba(255,255,255,0.7);">Turno activo: 08:00 – 20:00 hrs &nbsp;·&nbsp; Ventanilla 3</span>
      <span class="badge-servicio">● EN SERVICIO</span>
    </div>
  </div>
</div>

<main class="main">

  <!-- Tabs -->
  <div class="panel-tabs">
    <?php
    $tabs = ['dashboard' => 'Dashboard', 'validar' => 'Validar Documentos', 'monitor' => 'Monitor Tiempo Real', 'incidencias' => 'Incidencias', 'reportes' => 'Reportes'];
    foreach ($tabs as $id => $label):
    ?>
      <button class="ptab <?= $id === 'dashboard' ? 'active' : '' ?>" onclick="cambiarTab('<?= $id ?>', this)">
        <?= $label ?>
      </button>
    <?php endforeach; ?>
  </div>

  <!-- ===== TAB: DASHBOARD ===== -->
  <div class="tab-section" id="tab-dashboard">

    <div class="dashboard-grid">
      <div class="dash-card"><div class="dash-num">342</div><div class="dash-label">Trámites Pendientes</div></div>
      <div class="dash-card"><div class="dash-num verde">289</div><div class="dash-label">Aprobados Hoy</div></div>
      <div class="dash-card"><div class="dash-num rojo">14</div><div class="dash-label">Con Incidencia</div></div>
      <div class="dash-card"><div class="dash-num amarillo">39</div><div class="dash-label">En Revisión Manual</div></div>
    </div>

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
            <th>N° Trámite</th><th>Viajero</th><th>RUT / Pasaporte</th>
            <th>Tipo</th><th>Documentos</th><th>Validación</th><th>Estado</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tramitesPendientes as $t): ?>
          <tr>
            <td><strong>#<?= substr((string)$t->_id, -6) ?></strong></td>
            <td><?= htmlspecialchars($t->viajero_nombre) ?></td>
            <td><?= htmlspecialchars($t->viajero_rut) ?></td>
            <td><span class="badge badge-azul"><?= ucfirst($t->tipo) ?></span></td>
            <td>✓ <?= count((array)$t->documentos) ?>/4</td>
            <td><span class="badge badge-verde">✓ 7/7 APIs</span></td>
            <td><span class="badge <?= badgeEstado($t->estado) ?>"><?= ucfirst($t->estado) ?></span></td>
            <td>
              <button class="btn-accion">Ver QR</button>
              <button class="btn-accion outline">Detalle</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div><!-- /tab-dashboard -->

  <!-- ===== TAB: VALIDAR DOCUMENTOS ===== -->
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

  <!-- ===== TAB: MONITOR TIEMPO REAL ===== -->
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

  <!-- ===== TAB: INCIDENCIAS ===== -->
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

  <!-- ===== TAB: REPORTES ===== -->
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

<script>
function cambiarTab(id, btn) {
  document.querySelectorAll('.tab-section').forEach(s => s.style.display = 'none');
  document.querySelectorAll('.ptab').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id).style.display = 'block';
  btn.classList.add('active');
}
</script>