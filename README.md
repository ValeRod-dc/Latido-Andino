# ☕ Coffee Shop - E-commerce de Cafetería

Aplicación web de e-commerce para una cafetería desarrollada con PHP MVC, MongoDB, y Bootstrap.

## 🚀 Características

- ✅ Dockerizado con hot-reload
- ✅ Sistema de autenticación con cookies
- ✅ 3 tipos de usuarios: Cliente, Trabajador, Administrador
- ✅ Arquitectura MVC simple y ordenada
- ✅ Bootstrap 5 con colores temáticos de cafetería
- ✅ MongoDB como base de datos
- ✅ AJAX para interacciones sin recargar página

## 📁 Estructura del Proyecto

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

### Opción 1: Script Automatizado (Recomendado) 🚀

#### Linux/Mac:
```bash
chmod +x start.sh
./start.sh
```

#### Windows:
```bash
start.bat
```

### Opción 2: Manual

#### 1. Levantar el proyecto con Docker

```bash
# Construir e iniciar los contenedores
docker-compose up -d --build

# Ver logs
docker-compose logs -f
```

#### 2. Inicializar la base de datos

```bash
# Esperar 10 segundos para que MongoDB esté listo
# Ejecutar script de inicialización
docker exec -i coffee_shop_db mongosh < init-db.js
```

#### 3. Acceder a la aplicación

Abre tu navegador en: **http://localhost:8080**

## 👥 Usuarios de Prueba

| Rol           | Email                    | Contraseña |
|---------------|--------------------------|------------|
| Cliente       | cliente@coffee.com       | 123456     |
| Trabajador    | trabajador@coffee.com    | 123456     |
| Administrador | admin@coffee.com         | 123456     |

## 🔥 Hot-Reload

El proyecto está configurado para **hot-reload automático**:

- Los cambios en archivos PHP se reflejan inmediatamente
- No necesitas reconstruir el contenedor
- Edita el código en `src/` y recarga el navegador

## 🎨 Colores de la Aplicación

La aplicación usa una paleta de colores inspirada en cafetería:

- **Brown Coffee**: `#6F4E37` - Marrón café principal
- **Light Coffee**: `#A67B5B` - Café claro
- **Cream**: `#ECE0D1` - Crema
- **Dark Coffee**: `#3E2723` - Café oscuro
- **Accent**: `#D4A574` - Acento dorado

## 📱 Páginas Implementadas

### Login (`/login`)
- Formulario de autenticación
- Validación con AJAX
- Mensajes de error/éxito
- Usuarios de prueba visibles

### Home (`/` o `/home`)
- Navbar con usuario logueado
- Listado de productos de café
- Tarjetas de productos con Bootstrap
- Protección de sesión (redirección si no está logueado)

## 🔒 Sistema de Sesiones

- Sesiones PHP estándar
- Cookies persistentes (7 días)
- Middleware de verificación automática
- Redirección a login si la sesión expira

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

## 🧹 Limpieza Completa de Docker

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
- Ejecutar `./start.sh` nuevamente
- Las imágenes se descargarán desde cero
- La base de datos se inicializará desde cero

## 📦 Comandos Útiles

```bash
# Ver logs del contenedor web
docker-compose logs -f web

# Ver logs de MongoDB
docker-compose logs -f mongodb

# Acceder al contenedor PHP
docker exec -it coffee_shop_web bash

# Acceder a MongoDB
docker exec -it coffee_shop_db mongosh coffee_shop

# Reiniciar servicios
docker-compose restart
```

## 🔧 Tecnologías

- **Backend**: PHP 8.2
- **Base de datos**: MongoDB 7.0
- **Frontend**: Bootstrap 5, JavaScript (AJAX)
- **Servidor**: Apache 2.4
- **Contenedores**: Docker & Docker Compose

## 📝 Próximas Funcionalidades

- 🛒 Carrito de compras
- 💳 Sistema de pagos
- 📦 Gestión de pedidos
- 👤 Panel de administración
- 📊 Reportes y estadísticas

## 👨‍💻 Desarrollo

El proyecto está configurado para desarrollo rápido:

1. Edita archivos en `src/`
2. Recarga el navegador
3. Los cambios se reflejan automáticamente

No es necesario reiniciar Docker para cambios en el código.

## 📚 Documentación Completa

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

### 🎯 Por Dónde Empezar

- **Nuevo en el proyecto?** → Lee [docs/QUICKSTART.md](docs/QUICKSTART.md)
- **Quieres entender la arquitectura?** → Lee [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)
- **Tienes un problema?** → Consulta [docs/TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md)
- **Necesitas comandos?** → Revisa [docs/COMMANDS.md](docs/COMMANDS.md)

---

**¡Disfruta tu café! ☕**
