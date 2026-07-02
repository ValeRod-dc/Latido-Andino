<?php
// Datos que vienen del PortalController::admin()
$tramitesPendientes = $tramitesPendientes ?? [];
$userName = $_SESSION['user_name'] ?? 'Administrador del Sistema';

// ===== OBTENER DATOS REALES DESDE LA BD =====
$db = Database::getInstance();

// 1. Usuarios reales
$usuarios = $db->find('usuarios', [], ['sort' => ['created_at' => -1]]);

// 2. Bitácora (simulada por ahora, pero con estructura real)
$bitacora = $db->find('bitacora', [], ['sort' => ['created_at' => -1], 'limit' => 20]);
if (empty($bitacora)) {
    // Si no hay registros, creamos algunos de ejemplo
    $bitacora = [
        (object) ['hora' => '09:42', 'accion' => 'LOGIN', 'usuario' => 'admin@latidoandino.cl', 'detalle' => 'Inicio de sesión exitoso', 'tipo' => 'info'],
        (object) ['hora' => '09:38', 'accion' => 'APROBAR', 'usuario' => 'aduanas@aduana.cl', 'detalle' => 'Trámite #A3F91 aprobado', 'tipo' => 'ok'],
        (object) ['hora' => '09:31', 'accion' => 'RECHAZAR', 'usuario' => 'sag@sag.cl', 'detalle' => 'Trámite #B2C44 rechazado — doc. falso', 'tipo' => 'warn'],
        (object) ['hora' => '09:15', 'accion' => 'CREAR', 'usuario' => 'admin@latidoandino.cl', 'detalle' => 'Usuario carmen.rios creado', 'tipo' => 'info'],
        (object) ['hora' => '08:55', 'accion' => 'ALERTA', 'usuario' => 'SISTEMA', 'detalle' => 'API Interpol sin respuesta 2 min.', 'tipo' => 'error'],
    ];
}

// 3. APIs / Integraciones (datos de ejemplo, pero dinámicos)
$apis = [
    ['PDI', 'online', '0.8s', 'https://api.pdi.cl/v2', 'Activa'],
    ['SAG', 'slow', '4.2s', 'https://api.sag.gob.cl/v1', 'Activa'],
    ['Aduana AR', 'online', '1.1s', 'https://api.afip.gov.ar/paso', 'Activa'],
    ['Carabineros', 'online', '0.5s', 'https://api.carab.cl/v1', 'Activa'],
    ['Reg. Civil', 'online', '0.9s', 'https://api.srcei.cl/v3', 'Activa'],
    ['Interpol', 'online', '1.3s', 'https://api.interpol.int/v1', 'Activa'],
    ['SII', 'online', '0.7s', 'https://api.sii.cl/v2', 'Activa'],
];

// 4. SLA / Monitoreo (simulado)
$sla = [
    ['API PDI', 99.9, 'online'],
    ['API SAG', 97.2, 'slow'],
    ['API Aduana AR', 99.5, 'online'],
    ['Base de Datos', 100, 'online'],
    ['Servidor Web', 99.8, 'online'],
    ['Sistema de QR', 99.1, 'online'],
];

// 5. Tareas de mantenimiento (simulado)
$tareas = [
    ['Respaldo BD', 'Diario 02:00', 'Completado', 'ok', 'Hoy 02:03'],
    ['Limpieza logs', 'Diario 03:00', 'Completado', 'ok', 'Hoy 03:01'],
    ['Sync Interpol', 'Cada 6h', 'Completado', 'ok', 'Hoy 06:00'],
    ['Reporte nocturno', 'Diario 01:00', 'Completado', 'ok', 'Hoy 01:05'],
    ['Actualiz. firmware', 'Semanal', 'Pendiente', 'warn', 'Dom 00:00'],
];

// Helpers para badges
function badgeEstado(string $estado): string {
    return match($estado) {
        'Activo', 'activo' => 'badge-verde',
        'Inactivo', 'inactivo' => 'badge-rojo',
        default => 'badge-gris',
    };
}

function badgeTipoBitacora(string $tipo): string {
    return match($tipo) {
        'ok' => 'badge-verde',
        'warn' => 'badge-amarillo',
        'error' => 'badge-rojo',
        default => 'badge-azul',
    };
}
?>

<!-- ===== BANNER ADMIN ===== -->
<div class="banner-admin">
    <div class="banner-admin-inner">
        <div>
            <div class="cargo">Panel de Administración</div>
            <div class="nombre"><?= htmlspecialchars($userName) ?></div>
        </div>
        <span class="badge-restringido">🔒 ACCESO RESTRINGIDO</span>
    </div>
</div>

<!-- ===== MAIN ===== -->
<main class="main">

    <!-- ===== GRID DE MÓDULOS ===== -->
    <div class="modulos-grid">

        <?php
        $modulos = [
            ['usuarios', '', '#1565C0', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                'Gestión de Usuarios y Roles',
                'Alta, baja y modificación de cuentas para funcionarios de Aduanas, SAG y PDI.',
                'Administrar Usuarios'],
            ['bitacora', 'rojo', '#D92B2B', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                'Auditoría y Bitácora',
                'Trazabilidad completa de todas las operaciones del sistema.',
                'Ver Bitácora'],
            ['apis', '', '#1565C0', 'M13 10V3L4 14h7v7l9-11h-7z',
                'Configuración de Integraciones',
                'Gestión de conexiones con PDI, SAG, Aduana Argentina, Interpol y otros sistemas.',
                'Configurar APIs'],
            ['reportes', 'verde', '#1B7A3C', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                'Reportes Estadísticos',
                'Generación de informes en PDF y Excel. Batch nocturno y exportación bajo demanda.',
                'Generar Reportes'],
            ['monitoreo', '', '#1565C0', 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'Monitoreo de Disponibilidad',
                'Estado en tiempo real del sistema y APIs. SLA 99.5% mensual.',
                'Ver Estado'],
            ['mantenimiento', '', '#1565C0', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
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

    <!-- ========================================================== -->
    <!-- ===== PANELES DINÁMICOS ===== -->
    <!-- ========================================================== -->
    <div id="admin-panel" style="display:none; margin-top:24px;">

        <!-- ===== PANEL: USUARIOS CON ACCIONES ===== -->
        <div class="admin-seccion" id="panel-usuarios" style="display:none;">
            <div class="tabla-tramites">
                <div class="tabla-header">
                    <h3>Gestión de Usuarios y Roles</h3>
                    <div class="filtros">
                        <select id="filtroRol" onchange="filtrarUsuarios()">
                            <option value="">Todos los roles</option>
                            <option value="viajero">Viajero</option>
                            <option value="aduanas">Aduanas</option>
                            <option value="sag">SAG</option>
                            <option value="pdi">PDI</option>
                            <option value="admin">Admin</option>
                        </select>
                        <input type="text" id="buscarUsuario" placeholder="Buscar usuario..." oninput="filtrarUsuarios()">
                        <button class="btn-accion" onclick="alert('Funcionalidad en desarrollo: Crear usuario')">+ Nuevo usuario</button>
                    </div>
                </div>
                <table id="tablaUsuarios">
                    <thead>
                        <tr><th>Nombre</th><th>Rol</th><th>Email</th><th>RUT</th><th>Estado</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr data-id="<?= (string)$u->_id ?>" data-rol="<?= $u->role ?? '' ?>" data-nombre="<?= strtolower($u->name ?? '') ?>">
                                <td><strong><?= htmlspecialchars($u->name ?? 'N/A') ?></strong></td>
                                <td><span class="badge badge-azul"><?= strtoupper($u->role ?? 'viajero') ?></span></td>
                                <td><?= htmlspecialchars($u->email ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($u->rut ?? 'N/A') ?></td>
                                <td><span class="badge <?= ($u->activo ?? true) ? 'badge-verde' : 'badge-rojo' ?> estado-badge"><?= ($u->activo ?? true) ? 'Activo' : 'Inactivo' ?></span></td>
                                <td>
                                    <button class="btn-accion" onclick="editarUsuario('<?= (string)$u->_id ?>')">Editar</button>
                                    <button class="btn-accion outline toggle-estado" data-id="<?= (string)$u->_id ?>" data-activo="<?= $u->activo ?? true ? '1' : '0' ?>" onclick="toggleEstado(this)">
                                        <?= ($u->activo ?? true) ? 'Desactivar' : 'Activar' ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== MODAL EDITAR USUARIO ===== -->
        <div id="modalEditarUsuario" class="modal-incidencia-overlay" style="display:none;">
            <div class="modal-incidencia-box" style="max-width:500px;">
                <div class="modal-incidencia-header">
                    <h3><i class="bi bi-pencil-square" style="color:var(--azul);"></i> Editar Usuario</h3>
                </div>
                <div class="modal-incidencia-body">
                    <form id="formEditarUsuario">
                        <input type="hidden" name="usuario_id" id="edit_usuario_id">
                        <div class="form-group">
                            <label>Nombre completo <span style="color:red;">*</span></label>
                            <input type="text" name="name" id="edit_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email <span style="color:red;">*</span></label>
                            <input type="email" name="email" id="edit_email" required>
                        </div>
                        <div class="form-group">
                            <label>RUT</label>
                            <input type="text" name="rut" id="edit_rut">
                        </div>
                        <div class="form-group">
                            <label>Rol</label>
                            <select name="role" id="edit_role">
                                <option value="viajero">Viajero</option>
                                <option value="aduanas">Aduanas</option>
                                <option value="sag">SAG</option>
                                <option value="pdi">PDI</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nacionalidad</label>
                            <select name="nacionalidad" id="edit_nacionalidad">
                                <option value="Chilena">Chilena</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Peruana">Peruana</option>
                                <option value="Boliviana">Boliviana</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                <input type="checkbox" name="activo" id="edit_activo" value="1" checked>
                                Usuario activo
                            </label>
                        </div>
                        <div class="modal-incidencia-actions">
                            <button type="submit" class="btn-incidencia-submit" style="background:var(--azul);"><i class="bi bi-save"></i> Guardar cambios</button>
                            <button type="button" class="btn-incidencia-cancel" onclick="cerrarModalEditar()">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ===== PANEL: BITÁCORA (CON FILTROS FUNCIONALES) ===== -->
        <div class="admin-seccion" id="panel-bitacora" style="display:none;">
            <div class="tabla-tramites">
                <div class="tabla-header">
                    <h3>Auditoría y Bitácora del Sistema</h3>
                    <div class="filtros">
                        <select id="filtroAccion" onchange="filtrarBitacora()">
                            <option value="">Todos los eventos</option>
                            <option value="LOGIN">LOGIN</option>
                            <option value="APROBAR">APROBAR</option>
                            <option value="RECHAZAR">RECHAZAR</option>
                            <option value="CREAR">CREAR</option>
                            <option value="ALERTA">ALERTA</option>
                        </select>
                        <input type="text" id="buscarBitacora" placeholder="Buscar usuario o acción..." oninput="filtrarBitacora()">
                    </div>
                </div>
                <table id="tablaBitacora">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Acción</th>
                            <th>Usuario</th>
                            <th>Detalle</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bitacora)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding:30px; color:var(--gris-muted);">
                                    No hay registros en la bitácora
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bitacora as $b): ?>
                                <tr 
                                    data-accion="<?= strtoupper($b->accion ?? '') ?>"
                                    data-usuario="<?= strtolower($b->usuario ?? '') ?>"
                                    data-detalle="<?= strtolower($b->detalle ?? '') ?>"
                                >
                                    <td><?= $b->hora ?? 'N/A' ?></td>
                                    <td><strong><?= $b->accion ?? 'N/A' ?></strong></td>
                                    <td><?= $b->usuario ?? 'N/A' ?></td>
                                    <td><?= $b->detalle ?? 'N/A' ?></td>
                                    <td><span class="badge <?= badgeTipoBitacora($b->tipo ?? '') ?>"><?= strtoupper($b->tipo ?? 'info') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== PANEL: APIS ===== -->
        <div class="admin-seccion" id="panel-apis" style="display:none;">
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
                                <button class="btn-accion" onclick="alert('Probando conexión a <?= $nombre ?>...')">Probar</button>
                                <button class="btn-accion outline">Editar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== PANEL: REPORTES ===== -->
        <div class="admin-seccion" id="panel-reportes" style="display:none;">
            <div class="tabla-tramites">
                <div class="tabla-header"><h3>📊 Reportes Estadísticos</h3></div>
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; padding:20px 20px 0;">
                    <div class="dash-card"><div class="dash-num">8.420</div><div class="dash-label">Trámites este mes</div></div>
                    <div class="dash-card"><div class="dash-num verde">97.3%</div><div class="dash-label">Tasa de aprobación</div></div>
                    <div class="dash-card"><div class="dash-num amarillo">7.2 min</div><div class="dash-label">Tiempo promedio</div></div>
                </div>
                <div style="padding:20px; display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="/reporte" class="btn-accion" style="text-decoration:none;">📄 Exportar PDF — Mes actual</a>
                    <a href="/reporte" class="btn-accion" style="text-decoration:none;">📊 Exportar Excel — Mes actual</a>
                </div>
                <div style="padding:0 20px 20px; font-size:13px; color:var(--gris-texto);">
                    Último batch nocturno: <strong>Hoy 01:05 hrs</strong> · Próximo: <strong>Mañana 01:00 hrs</strong>
                </div>
            </div>
        </div>

        <!-- ===== PANEL: MONITOREO (DINÁMICO CON ACTUALIZACIÓN CADA 2 MIN) ===== -->
        <div class="admin-seccion" id="panel-monitoreo" style="display:none;">
            <div class="section-heading">
              <span>Disponibilidad del Sistema — SLA mensual <small style="font-weight:400;color:var(--gris-muted);">(actualiza cada 5 segundos)</small></span>
            </div>
            <div style="font-size:12px; color:var(--gris-muted); margin-bottom:12px;">
              Última actualización: <span id="ultimaActualizacionSLA"><?= date('H:i:s') ?></span>
            </div>
            <div class="tabla-tramites">
                <table id="tablaSLA">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>SLA</th>
                            <th>Disponibilidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Datos iniciales para SLA
                        $serviciosSLA = [
                            ['API PDI', 99.9, 'online'],
                            ['API SAG', 97.2, 'slow'],
                            ['API Aduana AR', 99.5, 'online'],
                            ['Base de Datos', 100, 'online'],
                            ['Servidor Web', 99.8, 'online'],
                            ['Sistema de QR', 99.1, 'online'],
                        ];
                        foreach ($serviciosSLA as $index => [$servicio, $pct, $estado]):
                        ?>
                        <tr id="sla-row-<?= $index ?>" data-servicio="<?= $servicio ?>">
                            <td><strong><?= $servicio ?></strong></td>
                            <td class="sla-objetivo"><?= $pct ?>%</td>
                            <td>
                                <div class="barra-track" style="width:180px; display:inline-block;">
                                    <div class="barra-fill <?= $estado === 'online' ? 'azul' : 'rojo' ?>" id="sla-bar-<?= $index ?>" style="width:<?= $pct ?>%;"></div>
                                </div>
                                <span class="sla-valor" id="sla-valor-<?= $index ?>" style="margin-left:8px; font-weight:700; color:var(--azul);"><?= $pct ?>%</span>
                            </td>
                            <td>
                                <span class="badge <?= $estado === 'online' ? 'badge-verde' : 'badge-amarillo' ?>" id="sla-estado-<?= $index ?>">
                                    <?= $pct >= 99 ? 'Óptimo' : 'Degradado' ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== PANEL: MANTENIMIENTO ===== -->
        <div class="admin-seccion" id="panel-mantenimiento" style="display:none;">
            <div class="tabla-tramites">
                <div class="tabla-header">
                    <h3>Tareas de Mantenimiento</h3>
                    <div class="filtros"><button class="btn-accion" onclick="alert('Ejecutando tarea manual...')">▶ Ejecutar tarea manual</button></div>
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
                            <td><button class="btn-accion outline" onclick="alert('Ejecutando tarea: <?= $tarea ?>')">▶ Ejecutar</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /admin-panel -->

</main>

<!-- ========================================================== -->
<!-- ===== JAVASCRIPT PARA ADMIN ===== -->
<!-- ========================================================== -->
<script>
const paneles = ['usuarios', 'bitacora', 'apis', 'reportes', 'monitoreo', 'mantenimiento'];

function abrirModulo(id) {
    const wrapper = document.getElementById('admin-panel');
    const activo = document.getElementById('panel-' + id);
    const yaVisible = wrapper.style.display !== 'none' && activo.style.display !== 'none';

    // Ocultar todos los paneles
    paneles.forEach(p => document.getElementById('panel-' + p).style.display = 'none');

    if (yaVisible) {
        wrapper.style.display = 'none';
    } else {
        activo.style.display = 'block';
        wrapper.style.display = 'block';
        wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// ===== FILTRAR USUARIOS POR ROL Y NOMBRE =====
function filtrarUsuarios() {
    const rolFilter = document.getElementById('filtroRol').value.toLowerCase();
    const nombreFilter = document.getElementById('buscarUsuario').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaUsuarios tbody tr');

    rows.forEach(row => {
        const rol = row.getAttribute('data-rol')?.toLowerCase() || '';
        const nombre = row.getAttribute('data-nombre')?.toLowerCase() || '';
        const matchRol = !rolFilter || rol === rolFilter;
        const matchNombre = !nombreFilter || nombre.includes(nombreFilter);
        row.style.display = (matchRol && matchNombre) ? '' : 'none';
    });
}

// ===== EDITAR USUARIO =====
function editarUsuario(usuarioId) {
    fetch('/admin/usuario/obtener?id=' + usuarioId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const u = data.usuario;
                document.getElementById('edit_usuario_id').value = u.id;
                document.getElementById('edit_name').value = u.name;
                document.getElementById('edit_email').value = u.email;
                document.getElementById('edit_rut').value = u.rut || '';
                document.getElementById('edit_role').value = u.role || 'viajero';
                document.getElementById('edit_nacionalidad').value = u.nacionalidad || 'Chilena';
                document.getElementById('edit_activo').checked = u.activo !== false;
                document.getElementById('modalEditarUsuario').style.display = 'flex';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => alert('Error al cargar usuario'));
}

function cerrarModalEditar() {
    document.getElementById('modalEditarUsuario').style.display = 'none';
}

// Envío del formulario de edición
document.getElementById('formEditarUsuario').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = '⏳ Guardando...';
    submitBtn.disabled = true;

    try {
        const response = await fetch('/admin/usuario/actualizar', { method: 'POST', body: formData });
        const data = await response.json();
        if (data.success) {
            alert('✅ Usuario actualizado correctamente');
            cerrarModalEditar();
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    } catch (error) {
        alert('❌ Error de conexión');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// ===== ACTIVAR / DESACTIVAR USUARIO =====
async function toggleEstado(btn) {
    const usuarioId = btn.getAttribute('data-id');
    const activoActual = btn.getAttribute('data-activo') === '1';
    const nuevoEstado = !activoActual;
    const mensaje = activoActual ? '¿Desactivar este usuario?' : '¿Activar este usuario?';

    if (!confirm(mensaje)) return;

    const formData = new FormData();
    formData.append('usuario_id', usuarioId);
    formData.append('activo', nuevoEstado ? '1' : '0');

    try {
        const response = await fetch('/admin/usuario/cambiar-estado', { method: 'POST', body: formData });
        const data = await response.json();
        if (data.success) {
            alert('✅ Estado actualizado');
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    } catch (error) {
        alert('❌ Error de conexión');
    }
}

// ===== FILTRAR USUARIOS =====
function filtrarUsuarios() {
    const rolFilter = document.getElementById('filtroRol').value.toLowerCase();
    const nombreFilter = document.getElementById('buscarUsuario').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaUsuarios tbody tr');

    rows.forEach(row => {
        const rol = row.getAttribute('data-rol')?.toLowerCase() || '';
        const nombre = row.getAttribute('data-nombre')?.toLowerCase() || '';
        const matchRol = !rolFilter || rol === rolFilter;
        const matchNombre = !nombreFilter || nombre.includes(nombreFilter);
        row.style.display = (matchRol && matchNombre) ? '' : 'none';
    });
}

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalEditar();
        cerrarModalIncidencia();
    }
});

// ===== FILTRAR BITÁCORA POR ACCIÓN Y BÚSQUEDA =====
function filtrarBitacora() {
    const accionFilter = document.getElementById('filtroAccion').value.toUpperCase();
    const busqueda = document.getElementById('buscarBitacora').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaBitacora tbody tr');

    // Si no hay filas (porque no hay datos), salimos
    if (!rows.length) return;

    rows.forEach(row => {
        const accion = row.getAttribute('data-accion') || '';
        const usuario = row.getAttribute('data-usuario') || '';
        const detalle = row.getAttribute('data-detalle') || '';

        const matchAccion = !accionFilter || accion === accionFilter;
        const matchBusqueda = !busqueda || usuario.includes(busqueda) || detalle.includes(busqueda);

        row.style.display = (matchAccion && matchBusqueda) ? '' : 'none';
    });
}

// ===== SIMULAR ACTUALIZACIÓN DE SLA CADA 2 MINUTOS =====
function actualizarSLA() {
    const rows = document.querySelectorAll('#tablaSLA tbody tr');
    rows.forEach(row => {
        const index = row.id.replace('sla-row-', '');
        const bar = document.getElementById('sla-bar-' + index);
        const valorSpan = document.getElementById('sla-valor-' + index);
        const estadoSpan = document.getElementById('sla-estado-' + index);
        const objetivoSpan = row.querySelector('.sla-objetivo');
        const ahora = new Date();
          document.getElementById('ultimaActualizacionSLA').textContent =
              ahora.getHours().toString().padStart(2,'0') + ':' +
              ahora.getMinutes().toString().padStart(2,'0') + ':' +
              ahora.getSeconds().toString().padStart(2,'0');

        if (!bar || !valorSpan || !estadoSpan) return;

        // Generar nuevo valor entre 95% y 100% (simulando variación)
        let nuevoValor = 95 + Math.random() * 5; // 95.0 a 100.0
        nuevoValor = Math.min(100, Math.round(nuevoValor * 10) / 10); // Redondear a 1 decimal, máximo 100

        // Actualizar barra
        bar.style.width = nuevoValor + '%';

        // Actualizar número
        valorSpan.textContent = nuevoValor + '%';

        // Determinar estado
        const esOptimo = nuevoValor >= 99;
        estadoSpan.textContent = esOptimo ? 'Óptimo' : 'Degradado';
        estadoSpan.className = 'badge ' + (esOptimo ? 'badge-verde' : 'badge-amarillo');

        // Cambiar color de la barra si baja de 99%
        bar.className = 'barra-fill ' + (esOptimo ? 'azul' : 'rojo');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Primera actualización después de 5 segundos
    setTimeout(actualizarSLA, 5000);
    setInterval(actualizarSLA, 5000);
});
</script>