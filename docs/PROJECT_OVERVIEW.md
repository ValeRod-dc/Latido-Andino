# ☕ Coffee Shop - Resumen del Proyecto

## 🎯 ¿Qué es este proyecto?

Un **e-commerce de cafetería** completo y funcional con:
- 🐳 Docker con hot-reload
- 🔐 Sistema de autenticación
- 👥 3 tipos de usuarios
- 🎨 Diseño elegante con Bootstrap
- 📦 MongoDB como base de datos
- 🚀 Listo para producción

---

## 📂 Estructura Visual

```
coffee-shop/
│
├── 🐳 Docker Files
│   ├── docker-compose.yml       # Orquestación de servicios
│   ├── Dockerfile               # Imagen PHP + Apache + MongoDB
│   └── apache-config.conf       # Configuración de Apache
│
├── 📚 Documentación
│   ├── README.md               # Documentación principal
│   ├── QUICKSTART.md           # Inicio rápido
│   ├── ARCHITECTURE.md         # Arquitectura técnica
│   └── TROUBLESHOOTING.md      # Solución de problemas
│
├── 🛠️ Scripts
│   ├── start.sh                # Iniciar (Linux/Mac)
│   ├── start.bat               # Iniciar (Windows)
│   └── init-db.js              # Datos iniciales MongoDB
│
└── 📁 src/                      # ⭐ CÓDIGO PRINCIPAL
    ├── controllers/            # 🎮 Lógica de negocio
    │   ├── AuthController.php  # Login, logout, sesiones
    │   └── HomeController.php  # Página principal
    │
    ├── models/                 # 📊 Modelos de datos
    │   ├── User.php           # Usuarios
    │   └── Product.php        # Productos de café
    │
    ├── views/                  # 🎨 Interfaz de usuario
    │   ├── login.php          # Página de login
    │   └── home.php           # Página principal
    │
    ├── core/                   # ⚙️ Núcleo del sistema
    │   └── Database.php       # Conexión MongoDB
    │
    └── public/                 # 🌐 Punto de entrada web
        ├── index.php          # Router principal
        └── .htaccess          # Reescritura de URLs
```

---

## 🚀 Inicio Rápido (3 pasos)

### 1️⃣ Levantar Docker
```bash
docker-compose up -d --build
```

### 2️⃣ Inicializar BD (después de 10 seg)
```bash
docker exec -i coffee_shop_db mongosh < init-db.js
```

### 3️⃣ Abrir navegador
```
http://localhost:8080
```

**Usuario:** `admin@coffee.com` | **Pass:** `123456`

---

## 🎨 Capturas Conceptuales

### 🔐 Página de Login
```
┌─────────────────────────────────────┐
│         ☕ Coffee Shop              │
│      Bienvenido de vuelta           │
├─────────────────────────────────────┤
│  📧 Email                           │
│  [ tu@email.com              ]      │
│                                     │
│  🔒 Contraseña                      │
│  [ ••••••••                  ]      │
│                                     │
│  [ 🚪 Iniciar Sesión ]              │
│                                     │
│  ℹ️ Usuarios de prueba:             │
│  Cliente: cliente@coffee.com        │
│  Trabajador: trabajador@coffee.com  │
│  Admin: admin@coffee.com            │
│  (Contraseña: 123456)               │
└─────────────────────────────────────┘
```

### 🏠 Página Principal
```
┌───────────────────────────────────────────────────┐
│ ☕ Coffee Shop    [ Home ]  👤 Usuario (Rol) 🚪  │
├───────────────────────────────────────────────────┤
│                                                   │
│         ☕ Nuestros Cafés                          │
│   Descubre nuestra selección de cafés            │
│                                                   │
├──────────┬──────────┬──────────┬──────────────────┤
│  ☕      │  ☕      │  ☕      │                  │
│ Espresso │Cappuccino│  Latte   │                  │
│ Intenso  │  Cremoso │  Suave   │                  │
│ $3.50    │  $4.50   │  $4.75   │ ...más productos │
│ [🛒 Add] │ [🛒 Add] │ [🛒 Add] │                  │
└──────────┴──────────┴──────────┴──────────────────┘
```

---

## 📊 Datos de Ejemplo

### 👥 Usuarios (3)
- **Cliente** → `cliente@coffee.com`
- **Trabajador** → `trabajador@coffee.com`
- **Administrador** → `admin@coffee.com`

### ☕ Productos (9)
1. Espresso - $3.50
2. Cappuccino - $4.50
3. Latte - $4.75
4. Americano - $3.75
5. Mocha - $5.25
6. Caramel Macchiato - $5.50
7. Flat White - $4.25
8. Cold Brew - $4.50
9. Affogato - $5.75

---

## 🔥 Características Clave

### ✅ Implementado
- [x] Login con validación AJAX
- [x] Sistema de sesiones + cookies
- [x] 3 tipos de usuarios (roles)
- [x] Protección de rutas
- [x] Listado de productos
- [x] Diseño responsivo Bootstrap
- [x] Colores temáticos cafetería
- [x] Hot-reload automático
- [x] Base de datos MongoDB

### 📋 Por Implementar (Futuro)
- [ ] Carrito de compras
- [ ] Proceso de checkout
- [ ] Sistema de pagos
- [ ] Panel de administración
- [ ] CRUD de productos
- [ ] Gestión de pedidos
- [ ] Reportes y estadísticas

---

## 🎨 Paleta de Colores

```css
🟤 Coffee Brown:  #6F4E37  /* Principal */
🟫 Coffee Light:  #A67B5B  /* Secundario */
🟨 Coffee Cream:  #ECE0D1  /* Fondo */
⬛ Coffee Dark:   #3E2723  /* Texto */
🟡 Coffee Accent: #D4A574  /* Acentos */
```

---

## 🛠️ Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| **Frontend** | Bootstrap 5, JavaScript (AJAX) |
| **Backend** | PHP 8.2 |
| **Base de datos** | MongoDB 7.0 |
| **Servidor Web** | Apache 2.4 |
| **Containerización** | Docker + Docker Compose |
| **Patrón** | MVC Simplificado |

---

## 📈 Métricas del Proyecto

```
📊 Estadísticas:
├── Archivos PHP: 7
├── Vistas: 2
├── Controladores: 2
├── Modelos: 2
├── Líneas de código: ~800
├── Usuarios demo: 3
├── Productos demo: 9
└── Tiempo de setup: ~2 minutos
```

---

## 🔄 Flujo de Trabajo

### Desarrollo
```mermaid
1. Editar código en src/
2. Guardar archivo
3. Recargar navegador
4. Ver cambios inmediatamente ✨
```

### Autenticación
```
Usuario → Login Form → AJAX POST → AuthController
   ↓                                      ↓
Redirect ← JSON Response ← Validate ← MongoDB
   ↓
Home Page
```

---

## 🎯 Casos de Uso

### Como Cliente
1. Iniciar sesión
2. Ver catálogo de cafés
3. [Futuro] Agregar al carrito
4. [Futuro] Realizar pedido

### Como Trabajador
1. Iniciar sesión
2. Ver productos
3. [Futuro] Ver pedidos
4. [Futuro] Actualizar estados

### Como Administrador
1. Iniciar sesión
2. Ver productos
3. [Futuro] Gestionar usuarios
4. [Futuro] Ver reportes

---

## 🚀 Comandos Esenciales

```bash
# Iniciar proyecto
docker-compose up -d --build

# Ver logs
docker-compose logs -f

# Detener proyecto
docker-compose down

# Resetear BD
docker exec -i coffee_shop_db mongosh < init-db.js

# Acceder a MongoDB
docker exec -it coffee_shop_db mongosh coffee_shop

# Limpiar todo
docker-compose down -v
```

---

## 📱 Accesos Rápidos

- 🌐 Aplicación: http://localhost:8080
- 🔐 Login: http://localhost:8080/login
- 🏠 Home: http://localhost:8080/home
- 📊 MongoDB: mongodb://localhost:27017

---

## 🎓 Aprendizaje

Este proyecto es ideal para:
- ✅ Aprender Docker y contenedores
- ✅ Entender arquitectura MVC
- ✅ Trabajar con MongoDB
- ✅ Implementar autenticación
- ✅ Diseñar con Bootstrap
- ✅ Desarrollar con hot-reload

---

## 📞 Soporte

| Problema | Solución |
|----------|----------|
| 🐛 Bug | Ver `TROUBLESHOOTING.md` |
| 📖 Documentación | Ver `README.md` |
| 🏗️ Arquitectura | Ver `ARCHITECTURE.md` |
| 🚀 Inicio rápido | Ver `QUICKSTART.md` |

---

## 📄 Licencia

Este es un proyecto educativo/demo. Úsalo como base para tus proyectos.

---

## 🎉 ¡Listo para Desarrollar!

```bash
# Ejecuta y empieza a programar:
./start.sh  # Linux/Mac
start.bat   # Windows

# O manualmente:
docker-compose up -d --build
sleep 10
docker exec -i coffee_shop_db mongosh < init-db.js
```

**🌐 Abre:** http://localhost:8080
**📧 User:** admin@coffee.com
**🔑 Pass:** 123456

---

**¡Disfruta tu café y feliz coding! ☕👨‍💻**
