<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latido Andino - Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>

<div class="topbar">
  <div class="topbar-inner">
    <span>🇨🇱 &nbsp;Gobierno de Chile &nbsp;·&nbsp; Ministerio de Hacienda</span>
    <div>
      <a href="#">Contacto</a>
      <a href="#">Accesibilidad</a>
      <a href="#">English</a>
    </div>
  </div>
</div>

<header class="header">
  <div class="header-inner">
    <svg class="escudo" viewBox="0 0 90 50" xmlns="http://www.w3.org/2000/svg">
      <text x="2" y="38" font-family="Arial, sans-serif" font-size="30" font-weight="700" fill="#1565C0" letter-spacing="-0.5">Chile</text>
      <polygon points="62,4 63.8,9.5 69.5,9.5 64.9,12.8 66.6,18.3 62,15 57.4,18.3 59.1,12.8 54.5,9.5 60.2,9.5" fill="#1565C0"/>
      <polygon points="71,8 72.4,12.4 77,12.4 73.3,15 74.7,19.4 71,16.8 67.3,19.4 68.7,15 65,12.4 69.6,12.4" fill="#1565C0"/>
    </svg>
    <div class="logo-text">
      <span class="nombre-sistema">Latido Andino</span>
      <span class="subtitulo">Servicio Nacional de Aduanas · Paso Los Libertadores</span>
    </div>
    <nav class="header-nav">
      <?php
        $rol = $_SESSION['user_role'] ?? 'viajero';
        $rolLabel = ['viajero'=>'Viajero','aduanas'=>'Aduanas','sag'=>'SAG','pdi'=>'PDI','admin'=>'Admin'][$rol] ?? ucfirst($rol);
        $rolClass = in_array($rol,['aduanas','sag','pdi']) ? 'funcionario' : $rol;
      ?>
      <span class="header-rol <?= $rolClass ?>"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
      <button class="btn-logout" onclick="window.location.href='/logout'">Cerrar sesión</button>
    </nav>
  </div>
</header>

<?php
// Incluir la subvista correspondiente al rol
$rol = $_SESSION['user_role'] ?? 'viajero';
if ($rol === 'viajero') {
    include __DIR__ . '/viajero.php';
} elseif (in_array($rol, ['aduanas', 'sag', 'pdi'])) {
    include __DIR__ . '/funcionario.php';
} elseif ($rol === 'admin') {
    include __DIR__ . '/admin.php';
} else {
    echo '<p>Rol no reconocido</p>';
}
?>

<footer class="footer">
  <div class="footer-top">
    <div class="footer-brand">
      <div style="font-family:'Roboto Slab',serif; font-size:18px; font-weight:700; color:#fff;">Latido Andino</div>
      <p>Sistema de Gestión Aduanera Fronteriza para el Paso Los Libertadores Chile-Argentina.</p>
      <p style="margin-top:8px; font-size:12px; color:rgba(255,255,255,0.4);">Desarrollado por Equipo Latido Andino<br>Escuela de Informática y Telecomunicaciones · DUOC UC</p>
    </div>
    <div class="footer-col">
      <h5>Portal Viajero</h5>
      <a href="#">Crear Cuenta</a><a href="#">Pre-Registro de Cruce</a><a href="#">Consultar Estado de Trámite</a><a href="#">Mi Pase Ágil QR</a><a href="#">Historial de Cruces</a>
    </div>
    <div class="footer-col">
      <h5>Institucional</h5>
      <a href="https://www.aduana.cl" target="_blank">Aduana Chile →</a><a href="https://www.pdichile.cl" target="_blank">PDI Chile →</a><a href="#">Ministerio de Hacienda</a><a href="#">Política de Privacidad (Ley 19.628)</a><a href="#">Términos de Uso</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2025 Servicio Nacional de Aduanas · Gobierno de Chile</span>
    <div><a href="#">Mapa del sitio</a><a href="#">Contacto</a><a href="#">Glosario</a></div>
  </div>
</footer>

<script>
  // Para script adicional, agregar aquí <:
</script>
</body>
</html>