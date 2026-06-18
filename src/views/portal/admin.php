<?php
$usuarios = [
  ['Ana Torres',    'aduanas', 'Activo',   'ana.torres@sna.cl'],
  ['Pedro Soto',    'sag',     'Activo',   'pedro.soto@sag.cl'],
  ['Luis Vera',     'pdi',     'Inactivo', 'luis.vera@pdi.cl'],
  ['Carmen Ríos',   'aduanas', 'Activo',   'carmen.rios@sna.cl'],
  ['Jorge Molina',  'admin',   'Activo',   'j.molina@sna.cl'],
];

$bitacora = [
  ['09:42', 'LOGIN',    'j.molina@sna.cl',    'Inicio de sesión exitoso',              'info'],
  ['09:38', 'APROBAR',  'ana.torres@sna.cl',  'Trámite #A3F91 aprobado',               'ok'],
  ['09:31', 'RECHAZAR', 'pedro.soto@sag.cl',  'Trámite #B2C44 rechazado — doc. falso', 'warn'],
  ['09:15', 'CREAR',    'j.molina@sna.cl',    'Usuario carmen.rios creado',            'info'],
  ['08:55', 'ALERTA',   'SISTEMA',            'API Interpol sin respuesta 2 min.',     'error'],
];

$apis = [
  ['PDI',         'online', '0.8s',  'https://api.pdi.cl/v2',       'Activa'],
  ['SAG',         'slow',   '4.2s',  'https://api.sag.gob.cl/v1',   'Activa'],
  ['Aduana AR',   'online', '1.1s',  'https://api.afip.gov.ar/paso', 'Activa'],
  ['Carabineros', 'online', '0.5s',  'https://api.carab.cl/v1',     'Activa'],
  ['Reg. Civil',  'online', '0.9s',  'https://api.srcei.cl/v3',     'Activa'],
  ['Interpol',    'online', '1.3s',  'https://api.interpol.int/v1', 'Activa'],
  ['SII',         'online', '0.7s',  'https://api.sii.cl/v2',       'Activa'],
];

$sla = [
  ['API PDI',         99.9, 'online'],
  ['API SAG',         97.2, 'slow'],
  ['API Aduana AR',   99.5, 'online'],
  ['Base de Datos',   100,  'online'],
  ['Servidor Web',    99.8, 'online'],
  ['Sistema de QR',   99.1, 'online'],
];

$tareas = [
  ['Respaldo BD',         'Diario  02:00', 'Completado', 'ok',   'Hoy 02:03'],
  ['Limpieza logs',       'Diario  03:00', 'Completado', 'ok',   'Hoy 03:01'],
  ['Sync Interpol',       'Cada 6h',       'Completado', 'ok',   'Hoy 06:00'],
  ['Reporte nocturno',    'Diario  01:00', 'Completado', 'ok',   'Hoy 01:05'],
  ['Actualiz. firmware',  'Semanal',       'Pendiente',  'warn', 'Dom 00:00'],
];
?>

<!-- Banner -->
<div class="banner-admin">
  <div class="banner-admin-inner">
    <div>
      <div class="cargo">Panel de Administración</div>
      <div class="nombre"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrador del Sistema') ?></div>
    </div>
    <span class="badge-restringido">ACCESO RESTRINGIDO</span>
  </div>
</div>

<main class="main">

  <!-- Grid de módulos -->
  <div class="modulos-grid">

    <?php
    $modulos = [
      ['usuarios',      '',      '#1565C0', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'Gestión de Usuarios y Roles',
        'Alta, baja y modificación de cuentas para funcionarios de Aduanas, SAG y PDI.',
        'Administrar Usuarios'],
      ['bitacora',      'rojo',  '#D92B2B', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        'Auditoría y Bitácora',
        'Trazabilidad completa de todas las operaciones del sistema.',
        'Ver Bitácora'],
      ['apis',          '',      '#1565C0', 'M13 10V3L4 14h7v7l9-11h-7z',
        'Configuración de Integraciones',
        'Gestión de conexiones con PDI, SAG, Aduana Argentina, Interpol y otros sistemas.',
        'Configurar APIs'],
      ['reportes',      'verde', '#1B7A3C', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'Reportes Estadísticos',
        'Generación de informes en PDF y Excel. Batch nocturno y exportación bajo demanda.',
        'Generar Reportes'],
      ['monitoreo',     '',      '#1565C0', 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'Monitoreo de Disponibilidad',
        'Estado en tiempo real del sistema y APIs. SLA 99.5% mensual.',
        'Ver Estado'],
      ['mantenimiento', '',      '#1565C0', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'Mantenimiento del Sistema',
        'Tareas batch, respaldos, actualizaciones y scheduler nocturno.',
        'Panel de Mantenimiento'],
    ];
    foreach ($modulos as [$id, $clase, $color, $path, $titulo, $desc, $link]):
    ?>
    <div class="modulo-card <?= $clase ?>" onclick="abrirModulo('<?= $id ?>')" style="cursor:pointer;">
      <div class="modulo-icono">
        <svg fill="none" stroke="<?= $color ?>" stroke-width="2" viewBox="0 0 24 24">
          <path d="<?= $path ?>"/>
        </svg>
      </div>
      <h3><?= $titulo ?></h3>
      <p><?= $desc ?></p>
      <span class="modulo-link"><?= $link ?> →</span>
    </div>
    <?php endforeach; ?>

  </div><!-- /modulos-grid -->

  <!-- ===== PANELES ===== -->
  <div id="admin-panel" style="display:none; margin-top:24px;">

    <!-- Usuarios -->
    <div class="admin-seccion" id="panel-usuarios">
      <div class="tabla-tramites">
        <div class="tabla-header">
          <h3>Gestión de Usuarios y Roles</h3>
          <div class="filtros">
            <select><option>Todos los roles</option><option>aduanas</option><option>sag</option><option>pdi</option><option>admin</option></select>
            <input type="text" placeholder="Buscar usuario...">
            <button class="btn-accion">+ Nuevo usuario</button>
          </div>
        </div>
        <table>
          <thead><tr><th>Nombre</th><th>Rol</th><th>Email</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php foreach ($usuarios as [$nombre, $rol, $estado, $email]): ?>
            <tr>
              <td><strong><?= $nombre ?></strong></td>
              <td><span class="badge badge-azul"><?= strtoupper($rol) ?></span></td>
              <td><?= $email ?></td>
              <td><span class="badge <?= $estado === 'Activo' ? 'badge-verde' : 'badge-rojo' ?>"><?= $estado ?></span></td>
              <td>
                <button class="btn-accion">Editar</button>
                <button class="btn-accion outline"><?= $estado === 'Activo' ? 'Desactivar' : 'Activar' ?></button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Bitácora -->
    <div class="admin-seccion" id="panel-bitacora">
      <div class="tabla-tramites">
        <div class="tabla-header">
          <h3>Auditoría y Bitácora del Sistema</h3>
          <div class="filtros">
            <select><option>Todos los eventos</option><option>LOGIN</option><option>APROBAR</option><option>RECHAZAR</option><option>ALERTA</option></select>
            <input type="text" placeholder="Buscar usuario o acción...">
          </div>
        </div>
        <table>
          <thead><tr><th>Hora</th><th>Acción</th><th>Usuario</th><th>Detalle</th><th>Tipo</th></tr></thead>
          <tbody>
            <?php foreach ($bitacora as [$hora, $accion, $usuario, $detalle, $tipo]): ?>
            <tr>
              <td><?= $hora ?></td>
              <td><strong><?= $accion ?></strong></td>
              <td><?= $usuario ?></td>
              <td><?= $detalle ?></td>
              <td><span class="badge <?= match($tipo) { 'ok'=>'badge-verde','warn'=>'badge-amarillo','error'=>'badge-rojo',default=>'badge-azul' } ?>"><?= strtoupper($tipo) ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- APIs -->
    <div class="admin-seccion" id="panel-apis">
      <div class="tabla-tramites">
        <div class="tabla-header"><h3>Configuración de Integraciones</h3></div>
        <table>
          <thead><tr><th>Sistema</th><th>Endpoint</th><th>Latencia</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php foreach ($apis as [$nombre, $estado, $latencia, $endpoint, $etiqueta]): ?>
            <tr>
              <td><strong><?= $nombre ?></strong></td>
              <td style="font-size:12px; color:var(--gris-texto);"><?= $endpoint ?></td>
              <td><?= $latencia ?></td>
              <td>
                <span class="inst-dot <?= $estado ?>" style="display:inline-block; margin-right:6px;"></span>
                <span class="badge <?= $estado === 'online' ? 'badge-verde' : 'badge-amarillo' ?>"><?= $estado === 'online' ? 'Online' : 'Lento' ?></span>
              </td>
              <td>
                <button class="btn-accion">Probar</button>
                <button class="btn-accion outline">Editar</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Reportes -->
    <div class="admin-seccion" id="panel-reportes">
      <div class="tabla-tramites">
        <div class="tabla-header"><h3>Reportes Estadísticos</h3></div>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; padding:20px 20px 0;">
          <div class="dash-card"><div class="dash-num">8.420</div><div class="dash-label">Trámites este mes</div></div>
          <div class="dash-card"><div class="dash-num verde">97.3%</div><div class="dash-label">Tasa de aprobación</div></div>
          <div class="dash-card"><div class="dash-num amarillo">7.2 min</div><div class="dash-label">Tiempo promedio</div></div>
        </div>
        <div style="padding:20px; display:flex; gap:12px; flex-wrap:wrap;">
          <button class="btn-accion">📄 Exportar PDF — Mes actual</button>
          <button class="btn-accion">📊 Exportar Excel — Mes actual</button>
          <button class="btn-accion outline">📅 Reporte personalizado</button>
        </div>
        <div style="padding:0 20px 20px; font-size:13px; color:var(--gris-texto);">
          Último batch nocturno: <strong>Hoy 01:05 hrs</strong> · Próximo: <strong>Mañana 01:00 hrs</strong>
        </div>
      </div>
    </div>

    <!-- Monitoreo -->
    <div class="admin-seccion" id="panel-monitoreo">
      <div class="section-heading"><span>Disponibilidad del Sistema — SLA mensual</span></div>
      <div class="tabla-tramites">
        <table>
          <thead><tr><th>Servicio</th><th>SLA</th><th>Disponibilidad</th><th>Estado</th></tr></thead>
          <tbody>
            <?php foreach ($sla as [$servicio, $pct, $estado]): ?>
            <tr>
              <td><strong><?= $servicio ?></strong></td>
              <td><?= $pct ?>%</td>
              <td>
                <div class="barra-track" style="width:180px; display:inline-block;">
                  <div class="barra-fill <?= $estado === 'online' ? 'azul' : 'rojo' ?>" style="width:<?= $pct ?>%;"></div>
                </div>
              </td>
              <td><span class="badge <?= $estado === 'online' ? 'badge-verde' : 'badge-amarillo' ?>"><?= $pct >= 99 ? 'Óptimo' : 'Degradado' ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Mantenimiento -->
    <div class="admin-seccion" id="panel-mantenimiento">
      <div class="tabla-tramites">
        <div class="tabla-header">
          <h3>Tareas de Mantenimiento</h3>
          <div class="filtros"><button class="btn-accion">▶ Ejecutar tarea manual</button></div>
        </div>
        <table>
          <thead><tr><th>Tarea</th><th>Frecuencia</th><th>Estado</th><th>Última ejecución</th><th>Acción</th></tr></thead>
          <tbody>
            <?php foreach ($tareas as [$tarea, $freq, $estado, $tipo, $ultima]): ?>
            <tr>
              <td><strong><?= $tarea ?></strong></td>
              <td><?= $freq ?></td>
              <td><span class="badge <?= $tipo === 'ok' ? 'badge-verde' : 'badge-amarillo' ?>"><?= $estado ?></span></td>
              <td><?= $ultima ?></td>
              <td><button class="btn-accion outline">▶ Ejecutar</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div><!-- /admin-panel -->

</main>

<script>
const paneles = ['usuarios','bitacora','apis','reportes','monitoreo','mantenimiento'];

function abrirModulo(id) {
  const wrapper = document.getElementById('admin-panel');
  const activo  = document.getElementById('panel-' + id);
  const yaVisible = wrapper.style.display !== 'none' && activo.style.display !== 'none';

  // Ocultar todos
  paneles.forEach(p => document.getElementById('panel-' + p).style.display = 'none');

  if (yaVisible) {
    wrapper.style.display = 'none'; // toggle: cerrar si ya estaba abierto
  } else {
    activo.style.display = 'block';
    wrapper.style.display = 'block';
    wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}
</script>