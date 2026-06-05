# 🏗️ Arquitectura del Proyecto

## 📐 Patrón MVC Simplificado

Este proyecto implementa un patrón MVC (Model-View-Controller) simplificado y fácil de mantener.

### 🎯 Flujo de la Aplicación

```
Usuario → Apache → index.php → Router → Controller → Model → MongoDB
                                            ↓
                                         View → Usuario
```

## 📁 Estructura Detallada

### `/public/index.php` - Punto de Entrada
- Inicia la sesión PHP
- Define constantes de configuración
- Autoload de clases
- **Router simple** que dirige las peticiones
- Middleware de autenticación

### `/core/Database.php` - Capa de Base de Datos
- **Singleton** para conexión a MongoDB
- Métodos: `find()`, `findOne()`, `insert()`, `update()`
- Manejo de errores y logging

### `/models/` - Modelos de Datos

#### `User.php`
```php
- findByEmail($email)      → Buscar usuario por email
- verifyPassword($pass, $hash) → Validar contraseña
- create($data)            → Crear nuevo usuario
```

#### `Product.php`
```php
- getAll()                 → Obtener todos los productos
- findById($id)            → Buscar producto por ID
- create($data)            → Crear nuevo producto
```

### `/controllers/` - Controladores

#### `AuthController.php`
- `login()` → Muestra formulario de login
- `processLogin()` → Procesa autenticación (AJAX)
- `logout()` → Cierra sesión

#### `HomeController.php`
- `index()` → Página principal con productos

### `/views/` - Vistas HTML

#### `login.php`
- Formulario de inicio de sesión
- JavaScript con AJAX para login sin recargar
- Bootstrap 5 con estilos inline

#### `home.php`
- Navbar con usuario logueado
- Grid de productos con Bootstrap
- Sistema de tarjetas responsivo

## 🔐 Sistema de Sesiones

### Flujo de Autenticación

1. **Usuario envía credenciales** → AJAX POST a `/auth/login`
2. **AuthController procesa:**
   - Valida email y contraseña
   - Busca usuario en MongoDB
   - Verifica contraseña hasheada
3. **Si es válido:**
   - Crea sesión PHP: `$_SESSION['user_id']`, `user_name`, `user_role`
   - Crea cookie persistente: `coffee_session` (7 días)
   - Retorna JSON con éxito
4. **Frontend redirige** → `/home`

### Middleware de Protección

En `index.php`, antes del router:

```php
if ($uri !== 'login' && $uri !== 'auth/login' && empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
```

## 🗄️ Base de Datos MongoDB

### Colección: `users`
```json
{
  "_id": ObjectId,
  "name": String,
  "email": String (único),
  "password": String (hash bcrypt),
  "role": String (cliente|trabajador|administrador),
  "created_at": Date
}
```

### Colección: `products`
```json
{
  "_id": ObjectId,
  "name": String,
  "description": String,
  "price": Number,
  "size": String,
  "icon": String (clase Bootstrap Icons),
  "active": Boolean,
  "is_new": Boolean,
  "created_at": Date
}
```

## 🔄 Hot-Reload

### Cómo Funciona

1. Docker monta `./src` como volumen en `/var/www/html`
2. Apache sirve archivos directamente desde el volumen
3. **Cualquier cambio** en `src/` se refleja inmediatamente
4. Solo necesitas recargar el navegador

### Lo que se puede editar en caliente:
- ✅ Código PHP (controllers, models, views)
- ✅ HTML/CSS inline en las vistas
- ✅ JavaScript en las vistas
- ✅ Configuración de Apache (requiere restart)

## 🛣️ Rutas Disponibles

| Ruta | Método | Descripción | Protegida |
|------|--------|-------------|-----------|
| `/` o `/home` | GET | Página principal | ✅ Sí |
| `/login` | GET | Formulario de login | ❌ No |
| `/auth/login` | POST | Procesar login (AJAX) | ❌ No |
| `/logout` | GET | Cerrar sesión | ✅ Sí |

## 🎨 Frontend - Bootstrap 5

### Componentes Usados
- **Grid System** → Layout responsivo
- **Cards** → Tarjetas de productos
- **Navbar** → Navegación principal
- **Forms** → Formularios con validación
- **Alerts** → Mensajes de error/éxito
- **Bootstrap Icons** → Iconografía

### Sin CSS adicional
Todo el estilo está en `<style>` dentro de cada vista usando:
- Variables CSS (`:root`)
- Flexbox y Grid
- Transiciones y hover effects
- Media queries de Bootstrap

## 🔧 Extensibilidad

### Agregar una nueva ruta:

1. **Crear el controlador** en `/controllers/`
2. **Agregar la ruta** en `index.php`:
```php
case 'mi-ruta':
    $controller = new MiController();
    $controller->index();
    break;
```
3. **Crear la vista** en `/views/`

### Agregar un nuevo modelo:

1. **Crear archivo** en `/models/`
2. **Extender funcionalidad**:
```php
class MiModelo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function miMetodo() {
        return $this->db->find('mi_coleccion');
    }
}
```

## 🐳 Docker

### Servicios

1. **web** (PHP 8.2 + Apache)
   - Extensión MongoDB
   - mod_rewrite habilitado
   - Puerto 8080 → 80

2. **mongodb** (Mongo 7.0)
   - Puerto 27017
   - Volumen persistente

### Volúmenes
- `./src` → `/var/www/html` (hot-reload)
- `mongodb_data` → `/data/db` (persistencia)

## 📊 Monitoreo

```bash
# Logs de Apache/PHP
docker-compose logs -f web

# Logs de MongoDB
docker-compose logs -f mongodb

# Estado de contenedores
docker-compose ps

# Uso de recursos
docker stats coffee_shop_web coffee_shop_db
```

## 🔒 Seguridad Implementada

- ✅ Contraseñas hasheadas (bcrypt)
- ✅ Sesiones PHP seguras
- ✅ Protección de rutas
- ✅ Validación de entrada (email, password)
- ✅ Escape de HTML en vistas (`htmlspecialchars`)
- ✅ Preparación de queries (MongoDB Driver)

## 🚀 Próximas Mejoras

### Backend
- [ ] Validación más robusta (librería)
- [ ] Sistema de roles granular
- [ ] API RESTful para frontend
- [ ] Rate limiting
- [ ] Logging estructurado

### Frontend
- [ ] Framework JS (Vue/React) opcional
- [ ] PWA capabilities
- [ ] Optimización de imágenes
- [ ] Lazy loading

### DevOps
- [ ] CI/CD pipeline
- [ ] Tests automatizados
- [ ] Backup automático de MongoDB
- [ ] Monitoring (Prometheus/Grafana)

---

**Arquitectura diseñada para ser simple, mantenible y escalable** 🎯
