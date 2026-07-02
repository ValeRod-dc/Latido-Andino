# 🛃 Latido Andino - Sistema de Gestión Aduanera Fronteriza

Sistema web para modernizar y agilizar el control fronterizo terrestre entre Chile y Argentina.  
Permite el pre-registro de viajeros, validación cruzada automatizada (simulada) y generación de Pase Ágil QR.

Desarrollado con PHP MVC, MongoDB, Bootstrap y Docker.

---

## 🚀 Características

- ✅ **Dockerizado** con hot-reload (edita y recarga)
- ✅ **Pre-registro de viajeros** con validación cruzada en paralelo (simulada con PDI, SAG, Interpol, Registro Civil)
- ✅ **Generación de Pase Ágil QR** para paso prioritario
- ✅ **Roles específicos**: Viajero, Aduanas, SAG, PDI, Administrador
- ✅ **Arquitectura MVC** simple y mantenible
- ✅ **Interfaz responsiva** con Bootstrap 5 y colores institucionales SNA
- ✅ **Base de datos MongoDB** con persistencia
- ✅ **Reportes estadísticos** (mock) y dashboard por rol

---

## 📁 Estructura del Proyecto

```
latido-andino/
├── docker-compose.yml          # Servicios web + MongoDB, puerto 8081:80
├── Dockerfile                  # PHP 8.2 + Apache + extensión MongoDB
├── apache-config.conf          # Configuración virtual host
├── init-db.js                  # Datos iniciales (usuarios, pasos fronterizos)
├── start.sh / start.bat        # 🚀 Iniciar aplicación
├── stop.sh / stop.bat          # 🛑 Detener aplicación
├── clean.sh / clean.bat        # 🧹 Limpieza completa Docker
├── .env.example                # Variables de entorno ejemplo
├── docs/                       # 📚 Documentación técnica detallada
└── src/
    ├── public/                 # Punto de entrada web
    │   ├── index.php           # Router principal
    │   ├── .htaccess           # Reescritura de URLs
    │   └── css/                # Estilos separados por página
    │       ├── style.css       # Variables y estilos globales
    │       ├── landing.css     # Estilos de la landing page
    │       ├── login.css       # Estilos de login
    │       ├── portal.css      # Estilos de los portales
    │       ├── pre-registro.css# Formulario multi-step
    │       ├── reportes.css    # Generación de reportes
    │       ├── verificar.css   # Página de verificación QR
    │       └── viajero-dashboard.css
    ├── core/                   # Núcleo del sistema
    │   └── Database.php        # Singleton para MongoDB
    ├── controllers/            # Controladores MVC
    │   ├── AuthController.php        # Login, logout, redirección por rol
    │   ├── HomeController.php        # Landing, términos, contacto
    │   ├── IncidenciaController.php  # Registro de incidencias
    │   ├── PortalController.php      # Dashboard por rol
    │   ├── ReporteController.php     # Generación de PDF/Excel
    │   ├── TramiteController.php     # Pre-registro, validación, QR
    │   └── VerificarController.php   # Página pública de verificación
    ├── models/                 # Modelos de datos
    │   ├── Tramite.php         # Trámites de ingreso/salida
    │   ├── User.php            # Usuarios y autenticación
    │   └── Vehiculo.php        # Registro de vehículos
    ├── services/               # Lógica de negocio externa
    │   ├── IntegracionMockService.php # Simulación de APIs (PDI, SAG, Interpol, RNV)
    │   └── ValidacionService.php      # Orquestación de validación cruzada
    └── views/                  # Vistas organizadas por sección
        ├── landing.php         # Página principal (carrusel + modal)
        ├── verificar.php       # Página pública de verificación QR
        ├── auth/               # Autenticación
        │   └── login.php       # (Redirige a landing)
        ├── portal/             # Dashboards por rol
        │   ├── base.php        # Plantilla común (navbar, footer)
        │   ├── viajero.php     # Dashboard del viajero
        │   ├── funcionario.php # Panel de funcionario (Aduanas, SAG, PDI)
        │   └── admin.php       # Panel de administrador
        ├── public/             # Páginas públicas informativas
        │   ├── accesibilidad.php
        │   ├── ayuda.php
        │   ├── contacto.php
        │   ├── privacidad.php
        │   └── terminos.php
        ├── reportes/           # Formulario de generación de reportes
        │   └── index.php
        └── tramite/            # Trámites
            ├── buscar-estado.php   # Buscador de estado
            ├── estado.php          # Listado de trámites por RUT
            ├── pase-agil.php       # Visualización del QR aprobado
            └── pre-registro.php    # Formulario multi-step
```

## 🛠️ Instalación y Uso

### Requisitos previos

- Docker Desktop (Windows/Mac) o Docker Engine + Docker Compose (Linux)
- Puerto **8081** libre (puedes cambiarlo en `docker-compose.yml`)

### Opción 1: Script Automatizado (Recomendado)

#### Linux / Mac:
```bash
chmod +x start.sh
./start.sh
```

#### Windows (CMD):
```bash
start.bat
```

#### Los scripts:
- Construyen e inician los contenedores.

- Esperan 10 segundos a que MongoDB esté listo.

- Inicializan la base de datos con usuarios y pasos fronterizos de ejemplo.

### Opción 2: Manual

#### 1. Levantar el proyecto con Docker

```bash
# Levantar contenedores
docker-compose up -d --build

# Ver logs
docker-compose logs -f
```

#### 2. Inicializar la base de datos

```bash
# Esperar 10 segundos para que MongoDB esté listo
sleep 10

# Ejecutar script de inicialización
docker exec -i latido_andino_db mongosh < init-db.js
```

#### 3. Acceder a la aplicación

Abre tu navegador en: **http://localhost:8081**

## 👥 Usuarios de Prueba

| Rol           | Email                    | Contraseña |
|---------------|--------------------------|------------|
| Viajero       | viajero@example.com      | 123456     |
| Aduanas       | aduanas@aduana.cl        | 123456     |
| SAG           | sag@sag.cl               | 123456     |
| PDI           | pdi@pdi.cl               | 123456     |
| Administrador | admin@latidoandino.cl    | 123456     |


    ℹ️ Todos los usuarios tienen la misma contraseña.
    El sistema redirige automáticamente al dashboard correspondiente según el rol.

## 🔥 Hot-Reload

El proyecto está configurado para **hot-reload automático**:

- Los cambios en archivos PHP se reflejan inmediatamente
- No necesitas reconstruir el contenedor
- Edita el código en `src/` y recarga el navegador
- Solo reconstruye la imagen si modificas `Dockerfile` o `docker-compose.yml`

## 🛑 Detener el Proyecto

### Opción 1: Script Automatizado (Recomendado) 🛑

#### Linux/Mac:
```bash
chmod +x stop.sh
./stop.sh
```

#### Windows:
```bash
stop.bat
```

**Este script:**
- ✅ Detiene todos los contenedores
- ✅ Mantiene los volúmenes intactos (no se pierden datos)
- ✅ Mantiene las imágenes descargadas
- ✅ Permite reiniciar rápidamente con `./start.sh`

### Opción 2: Manual

```bash
# Detener contenedores (mantiene volúmenes e imágenes)
docker-compose down
```

## 🧹 Limpieza Completa de Docker (Eliminar todo)

Si necesitas liberar espacio o hacer una limpieza completa del sistema Docker:

### Linux/Mac:
```bash
chmod +x clean.sh
./clean.sh
```

### Windows:
```bash
clean.bat
```

**⚠️ ADVERTENCIA: Este script eliminará:**
- ❌ Todos los contenedores detenidos
- ❌ Todas las redes no utilizadas
- ❌ Todos los volúmenes no utilizados (base de datos)
- ❌ Todas las imágenes no utilizadas
- ❌ Todo el caché de compilación

**Después de ejecutar clean, necesitarás:**
- Ejecutar `./start.sh` **nuevamente**
- Las imágenes se descargarán desde cero
- La base de datos se inicializará desde cero

## 📦 Comandos Útiles

```bash
# Ver logs en tiempo real
docker-compose logs -f

# Ver logs del contenedor web
docker-compose logs -f web

# Ver logs de MongoDB
docker-compose logs -f mongodb

# Acceder al contenedor web (bash)
docker exec -it latido_andino_web bash

# Acceder a MongoDB
docker exec -it latido_andino_db mongosh latido_andino

# Reiniciar servicios
docker-compose restart

# Reconstruir imagen sin caché
docker-compose build --no-cache web
docker-compose up -d
```

## 🌐 Accesos Rápidos
| Recurso                |                URL                    |
|------------------------|---------------------------------------|
| Aplicación             | http://localhost:8081/                |
| Login                  | http://localhost:8081/login           |
| Pre-registro (público) | http://localhost:8081/pre-registro    |
| Consulta de estado     | http://localhost:8081/consulta-estado |
|

## 🔧 Tecnologías

- **Backend**: PHP 8.2
- **Base de datos**: MongoDB 7.0
- **Frontend**: Bootstrap 5.3 + Icons, JavaScript (AJAX)
- **Servidor**: Apache 2.4
- **Contenedores**: Docker & Docker Compose
- **Patrón**: MVC (Model-View-Controller)

## 👨‍💻 Desarrollo

El proyecto está configurado para desarrollo rápido:

1. Edita archivos en `src/`
2. Recarga el navegador
3. Los cambios se reflejan automáticamente

No es necesario reiniciar Docker para cambios en el código.

## 📄 Licencia
Proyecto de demostración / educativo para el Servicio Nacional de Aduanas de Chile.
Puede ser utilizado como base para sistemas reales.


## ✨ Créditos
Desarrollado para la asignatura Ingeniería de Software – DUOC UC.
Basado en el caso de estudio "Proceso de Aduana" y los requerimientos del Sistema Latido Andino.

`¿Problemas? Revisa los logs con docker-compose logs -f.`