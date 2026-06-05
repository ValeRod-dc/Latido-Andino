# ☕ Coffee Shop - Guía Rápida de Inicio

## 🚀 Iniciar el Proyecto

### Opción 1: Script automático (Windows)
```bash
start.bat
```

### Opción 2: Script automático (Linux/Mac)
```bash
chmod +x start.sh
./start.sh
```

### Opción 3: Manual
```bash
# 1. Levantar contenedores
docker-compose up -d --build

# 2. Esperar 10 segundos

# 3. Inicializar base de datos
docker exec -i coffee_shop_db mongosh < init-db.js
```

## 🌐 Acceder a la Aplicación

**URL:** http://localhost:8080

## 👥 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| cliente@coffee.com | 123456 | Cliente |
| trabajador@coffee.com | 123456 | Trabajador |
| admin@coffee.com | 123456 | Administrador |

## 🔥 Hot-Reload

Los cambios en el código se reflejan automáticamente:

1. Edita archivos en `src/`
2. Guarda los cambios
3. Recarga el navegador

**No necesitas reiniciar Docker** 🎉

## 📂 Dónde Editar

```
src/
├── controllers/     → Lógica de negocio
├── models/         → Interacción con MongoDB
├── views/          → Páginas HTML con PHP
├── core/           → Clases base (Database)
└── public/         → Punto de entrada (index.php)
```

## 🛑 Detener el Proyecto

```bash
docker-compose down
```

## 🔧 Comandos Útiles

```bash
# Ver logs en tiempo real
docker-compose logs -f

# Reiniciar servicios
docker-compose restart

# Acceder al contenedor PHP
docker exec -it coffee_shop_web bash

# Acceder a MongoDB
docker exec -it coffee_shop_db mongosh coffee_shop

# Ver todos los usuarios en BD
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.find().pretty()"

# Ver todos los productos en BD
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.find().pretty()"
```

## ✨ Características Implementadas

- ✅ Login con validación AJAX
- ✅ Sistema de sesiones con cookies
- ✅ Protección de rutas (redirección si no hay sesión)
- ✅ 3 tipos de usuarios
- ✅ Página principal con listado de productos
- ✅ Navbar responsivo
- ✅ Diseño con colores de cafetería
- ✅ Bootstrap 5 sin CSS adicional

## 🎨 Paleta de Colores

```css
--coffee-brown: #6F4E37;   /* Marrón café */
--coffee-light: #A67B5B;   /* Café claro */
--coffee-cream: #ECE0D1;   /* Crema */
--coffee-dark: #3E2723;    /* Café oscuro */
--coffee-accent: #D4A574;  /* Dorado */
```

## 📝 Próximos Pasos

1. Implementar carrito de compras
2. Sistema de pedidos
3. Panel de administración
4. Gestión de productos (CRUD)
5. Sistema de pagos

---

**¿Problemas?** Revisa los logs con: `docker-compose logs -f`
