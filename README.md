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

## 📁 Estructura del Proyecto (EN DESARROLLO)

```
php-proyect/
├── docker-compose.yml          # Configuración Docker
├── Dockerfile                  # Imagen PHP con extensión MongoDB
├── apache-config.conf          # Configuración Apache
├── init-db.js                  # Script de inicialización de BD
├── start.sh / start.bat        # 🚀 Iniciar aplicación
├── stop.sh / stop.bat          # 🛑 Detener aplicación
├── clean.sh / clean.bat        # 🧹 Limpieza completa Docker
├── docs/                       # 📚 Documentación completa
│   ├── INDEX.md               # Índice de documentación
│   ├── QUICKSTART.md          # Guía de inicio rápido
│   ├── ARCHITECTURE.md        # Arquitectura técnica
│   ├── COMMANDS.md            # Comandos útiles
│   ├── TROUBLESHOOTING.md     # Solución de problemas
│   ├── CHECKLIST.md           # Lista de verificación
│   └── ...más documentos
└── src/
    ├── controllers/            # Controladores MVC
    │   ├── AuthController.php
    │   └── HomeController.php
    ├── models/                 # Modelos
    │   ├── User.php
    │   └── Product.php
    ├── views/                  # Vistas
    │   ├── login.php
    │   └── home.php
    ├── core/                   # Núcleo del sistema
    │   └── Database.php
    └── public/                 # Punto de entrada web
        ├── index.php
        └── .htaccess
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

#### Windows:
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
|

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

## 📚 Documentación Completa (EN DESARROLLO)

Toda la documentación está organizada en la carpeta `docs/`:

| Documento | Descripción |
|-----------|-------------|
| [INDEX.md](docs/INDEX.md) | 📑 Índice general de toda la documentación |
| [QUICKSTART.md](docs/QUICKSTART.md) | 🚀 Guía de inicio rápido (3 pasos) |
| [ARCHITECTURE.md](docs/ARCHITECTURE.md) | 🏗️ Arquitectura técnica detallada |
| [COMMANDS.md](docs/COMMANDS.md) | ⌨️ Lista completa de comandos útiles |
| [TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md) | 🔧 Solución de problemas comunes |
| [CHECKLIST.md](docs/CHECKLIST.md) | ✅ Lista de verificación del proyecto |
| [STATUS.md](docs/STATUS.md) | 📊 Estado actual del proyecto |
| [SUMMARY.md](docs/SUMMARY.md) | 📋 Resumen ejecutivo |
| [PROJECT_OVERVIEW.md](docs/PROJECT_OVERVIEW.md) | 🎯 Visión general del proyecto |
|

### 🎯 Por Dónde Empezar (EN DESARROLLO)

- **Nuevo en el proyecto?** → Lee [docs/QUICKSTART.md](docs/QUICKSTART.md)
- **Quieres entender la arquitectura?** → Lee [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)
- **Tienes un problema?** → Consulta [docs/TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md)
- **Necesitas comandos?** → Revisa [docs/COMMANDS.md](docs/COMMANDS.md)


## 📄 Licencia
Proyecto de demostración / educativo para el Servicio Nacional de Aduanas de Chile.
Puede ser utilizado como base para sistemas reales.


## ✨ Créditos
Desarrollado para la asignatura Ingeniería de Software – DUOC UC.
Basado en el caso de estudio "Proceso de Aduana" y los requerimientos del Sistema Latido Andino.

`¿Problemas? Revisa los logs con docker-compose logs -f.`