# ✅ Checklist de Verificación

## 🔍 Antes de Empezar

Verifica que tienes instalado:

- [ ] Docker Desktop (Windows/Mac) o Docker Engine (Linux)
- [ ] Docker Compose
- [ ] Navegador web moderno
- [ ] Editor de código (VS Code recomendado)

## 🚀 Checklist de Instalación

### 1. Levantar Docker
```bash
docker-compose up -d --build
```

- [ ] Contenedor `coffee_shop_web` está UP
- [ ] Contenedor `coffee_shop_db` está UP
- [ ] Puerto 8080 está disponible
- [ ] Puerto 27017 está disponible

**Verificar:**
```bash
docker-compose ps
# Ambos contenedores deben mostrar "Up"
```

---

### 2. Inicializar Base de Datos
```bash
# Esperar 10 segundos para que MongoDB esté listo
docker exec -i coffee_shop_db mongosh < init-db.js
```

- [ ] Script ejecutado sin errores
- [ ] Mensaje: "✅ Base de datos inicializada correctamente"
- [ ] 3 usuarios creados
- [ ] 9 productos creados

**Verificar usuarios:**
```bash
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.countDocuments()"
# Debe mostrar: 3
```

**Verificar productos:**
```bash
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.countDocuments()"
# Debe mostrar: 9
```

---

### 3. Acceder a la Aplicación

- [ ] Abrir http://localhost:8080
- [ ] Página de login se carga correctamente
- [ ] Diseño con colores de cafetería visible
- [ ] Bootstrap Icons se cargan

---

### 4. Probar Autenticación

#### Test 1: Login Cliente
- [ ] Email: `cliente@coffee.com`
- [ ] Password: `123456`
- [ ] Click en "Iniciar Sesión"
- [ ] Mensaje de éxito aparece
- [ ] Redirección a /home
- [ ] Badge muestra "Cliente Demo (Cliente)"

#### Test 2: Logout
- [ ] Click en botón "Salir"
- [ ] Redirección a /login
- [ ] Sesión cerrada correctamente

#### Test 3: Login Trabajador
- [ ] Email: `trabajador@coffee.com`
- [ ] Password: `123456`
- [ ] Badge muestra "Trabajador Demo (Trabajador)"

#### Test 4: Login Admin
- [ ] Email: `admin@coffee.com`
- [ ] Password: `123456`
- [ ] Badge muestra "Administrador Demo (Administrador)"

---

### 5. Verificar Página Principal

- [ ] Navbar visible con logo "Coffee Shop"
- [ ] Link "Home" activo
- [ ] Usuario logueado muestra nombre y rol
- [ ] Botón "Salir" visible
- [ ] Hero section con título "Nuestros Cafés"
- [ ] 9 productos listados en grid
- [ ] Cada producto muestra:
  - [ ] Icono de Bootstrap
  - [ ] Nombre del café
  - [ ] Descripción
  - [ ] Precio
  - [ ] Tamaño (badge)
  - [ ] Botón "Agregar al Carrito"

---

### 6. Verificar Hot-Reload

#### Test de Hot-Reload:

1. **Editar título en home.php**
   - [ ] Abrir `src/views/home.php`
   - [ ] Cambiar "Nuestros Cafés" por "Nuestro Menú"
   - [ ] Guardar archivo
   - [ ] Recargar navegador (F5)
   - [ ] Cambio visible SIN reiniciar Docker ✨

2. **Volver al original**
   - [ ] Cambiar de vuelta a "Nuestros Cafés"
   - [ ] Guardar y recargar
   - [ ] Cambio visible inmediatamente

---

### 7. Verificar Protección de Rutas

#### Test sin sesión:
- [ ] Abrir ventana de incógnito
- [ ] Ir a http://localhost:8080/home
- [ ] Debe redirigir a /login automáticamente

#### Test con sesión:
- [ ] Iniciar sesión
- [ ] Intentar acceder a /login
- [ ] Debe redirigir a /home automáticamente

---

### 8. Verificar Responsividad

- [ ] Abrir DevTools (F12)
- [ ] Cambiar a vista móvil
- [ ] Navbar colapsa en hamburger menu
- [ ] Grid de productos se ajusta
- [ ] Botones y formularios legibles
- [ ] Todo funcional en mobile

---

## 🎨 Checklist Visual

### Colores correctos:
- [ ] Navbar: Degradado marrón oscuro
- [ ] Fondo general: Crema
- [ ] Botones: Degradado marrón café
- [ ] Tarjetas: Blanco con sombra
- [ ] Hero section: Degradado marrón

### Tipografía:
- [ ] Títulos legibles
- [ ] Descripciones de productos claras
- [ ] Precios destacados
- [ ] Iconos Bootstrap visibles

---

## 🔧 Checklist Técnico

### Logs limpios:
```bash
docker-compose logs web | grep -i error
docker-compose logs mongodb | grep -i error
```
- [ ] Sin errores críticos en logs
- [ ] MongoDB conectado correctamente
- [ ] Apache respondiendo en puerto 80

### Base de datos:
```bash
docker exec -it coffee_shop_db mongosh coffee_shop
```
- [ ] Conexión exitosa
- [ ] Colecciones `users` y `products` existen
- [ ] Datos correctos en ambas colecciones

### PHP:
```bash
docker exec -it coffee_shop_web php -v
docker exec -it coffee_shop_web php -m | grep mongodb
```
- [ ] PHP 8.2 instalado
- [ ] Extensión MongoDB habilitada

---

## 📊 Métricas de Performance

- [ ] Página de login carga en < 2 segundos
- [ ] Página principal carga en < 3 segundos
- [ ] Login AJAX responde en < 1 segundo
- [ ] Hot-reload funciona inmediatamente

---

## 🐛 Checklist de Errores Comunes

Si algo falla, revisar:

- [ ] Docker Desktop está corriendo
- [ ] Puertos 8080 y 27017 no están ocupados
- [ ] Volúmenes montados correctamente
- [ ] Extensión MongoDB instalada en PHP
- [ ] Base de datos inicializada
- [ ] Cookies habilitadas en navegador
- [ ] JavaScript habilitado

---

## ✅ Verificación Final

Todo está correcto si:

- ✅ Ambos contenedores corriendo
- ✅ Login funciona con 3 tipos de usuarios
- ✅ Página principal muestra 9 productos
- ✅ Hot-reload funciona
- ✅ Sesiones persistentes
- ✅ Diseño responsivo
- ✅ Sin errores en consola del navegador
- ✅ Sin errores en logs de Docker

---

## 🎉 ¡Proyecto Listo!

Si todos los checks están marcados, **¡tu proyecto está funcionando perfectamente!** 🚀

Ahora puedes:
1. **Desarrollar nuevas features**
2. **Personalizar el diseño**
3. **Agregar más productos**
4. **Implementar el carrito**

---

## 📝 Comandos de Verificación Rápida

```bash
# Estado general
docker-compose ps

# Logs en tiempo real
docker-compose logs -f

# Verificar usuarios en BD
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.find({}, {name:1, email:1, role:1}).pretty()"

# Verificar productos en BD
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.find({}, {name:1, price:1, size:1}).pretty()"

# Reiniciar todo
docker-compose restart

# Limpiar y reiniciar
docker-compose down && docker-compose up -d --build
```

---

**Última actualización:** 19 de octubre de 2025
