# 🔧 Troubleshooting - Solución de Problemas

## 🚨 Problemas Comunes

### 1. No puedo acceder a http://localhost:8080

**Síntomas:**
- El navegador muestra "No se puede acceder al sitio"
- Error de conexión

**Soluciones:**

```bash
# Verificar que los contenedores estén corriendo
docker-compose ps

# Deberías ver:
# coffee_shop_web   Up   0.0.0.0:8080->80/tcp
# coffee_shop_db    Up   0.0.0.0:27017->27017/tcp

# Si no están corriendo, levántalos
docker-compose up -d

# Ver logs para identificar errores
docker-compose logs -f web
```

**Puerto ocupado:**
```bash
# Si el puerto 8080 ya está en uso, cámbialo en docker-compose.yml
ports:
  - "8081:80"  # Usa 8081 en lugar de 8080
```

---

### 2. Error: "No se puede conectar a MongoDB"

**Síntomas:**
- Página en blanco
- Error de conexión en logs

**Soluciones:**

```bash
# 1. Verificar que MongoDB esté corriendo
docker-compose ps

# 2. Ver logs de MongoDB
docker-compose logs -f mongodb

# 3. Reiniciar MongoDB
docker-compose restart mongodb

# 4. Esperar 10 segundos y recargar
sleep 10
```

**Verificar conexión:**
```bash
# Conectarse a MongoDB manualmente
docker exec -it coffee_shop_db mongosh coffee_shop

# Dentro de mongosh:
show collections
db.users.find()
db.products.find()
```

---

### 3. No hay productos en la página principal

**Síntomas:**
- La página carga pero está vacía
- Mensaje "No hay productos disponibles"

**Soluciones:**

```bash
# Inicializar la base de datos
docker exec -i coffee_shop_db mongosh < init-db.js

# Verificar que se crearon los productos
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.countDocuments()"
# Debería mostrar: 9

# Ver los productos
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.find().pretty()"
```

---

### 4. Login no funciona / Credenciales inválidas

**Síntomas:**
- Siempre dice "Credenciales inválidas"
- No puedo iniciar sesión

**Soluciones:**

```bash
# Verificar que existan usuarios
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.find({}, {email:1, role:1})"

# Debería mostrar:
# { "_id": ..., "email": "cliente@coffee.com", "role": "cliente" }
# { "_id": ..., "email": "trabajador@coffee.com", "role": "trabajador" }
# { "_id": ..., "email": "admin@coffee.com", "role": "administrador" }

# Si no hay usuarios, inicializa la BD
docker exec -i coffee_shop_db mongosh < init-db.js
```

**Verificar extensión MongoDB en PHP:**
```bash
docker exec -it coffee_shop_web php -m | grep mongodb
# Debería mostrar: mongodb
```

---

### 5. Cambios en el código no se reflejan

**Síntomas:**
- Edito archivos pero no veo cambios
- La página muestra código viejo

**Soluciones:**

```bash
# 1. Verificar que el volumen esté montado correctamente
docker inspect coffee_shop_web | grep -A 5 Mounts

# Debería mostrar:
# "Source": "C:\\Users\\Fernando\\Desktop\\php-proyect\\src"
# "Destination": "/var/www/html"

# 2. Limpiar caché del navegador
# Ctrl + Shift + R (Chrome/Edge)
# Ctrl + F5 (Firefox)

# 3. Reiniciar Apache dentro del contenedor
docker-compose restart web

# 4. Si nada funciona, reconstruir
docker-compose down
docker-compose up -d --build
```

**Para cambios en Dockerfile o docker-compose.yml:**
```bash
# Siempre reconstruir
docker-compose down
docker-compose up -d --build
```

---

### 6. Error de permisos en archivos

**Síntomas:**
- "Permission denied"
- No se pueden crear archivos

**Soluciones (Linux/Mac):**
```bash
# Dar permisos al directorio src/
chmod -R 777 src/

# O cambiar propietario
sudo chown -R $USER:$USER src/
```

**Soluciones (Windows):**
- Asegúrate de que Docker Desktop tenga acceso a la carpeta
- Settings → Resources → File Sharing → Agregar la ruta

---

### 7. Sesión se cierra constantemente

**Síntomas:**
- Me redirige al login cada vez
- No mantiene la sesión

**Soluciones:**

```bash
# Verificar logs de PHP
docker-compose logs -f web

# Buscar errores relacionados con sesiones
```

**Verificar cookies en el navegador:**
1. Abrir DevTools (F12)
2. Application → Cookies → http://localhost:8080
3. Debería haber una cookie `PHPSESSID` y `coffee_session`

**Limpiar cookies:**
1. DevTools → Application → Clear storage
2. Clear site data
3. Recargar y volver a iniciar sesión

---

### 8. Error "Class 'MongoDB\Driver\Manager' not found"

**Síntomas:**
- Error fatal de PHP
- Extensión MongoDB no encontrada

**Soluciones:**

```bash
# Verificar extensión MongoDB
docker exec -it coffee_shop_web php -m | grep mongodb

# Si no aparece, reconstruir imagen
docker-compose down
docker-compose build --no-cache web
docker-compose up -d
```

---

### 9. Docker no inicia / Error al construir

**Síntomas:**
- `docker-compose up` falla
- Errores de construcción

**Soluciones:**

```bash
# Limpiar todo Docker
docker-compose down -v
docker system prune -a

# Reconstruir desde cero
docker-compose up -d --build

# Ver qué está usando puertos
netstat -ano | findstr :8080
netstat -ano | findstr :27017
```

---

## 📊 Comandos de Diagnóstico

### Estado general
```bash
# Ver todos los contenedores
docker ps -a

# Ver uso de recursos
docker stats

# Ver redes
docker network ls
```

### Logs detallados
```bash
# Todos los logs
docker-compose logs

# Últimas 50 líneas
docker-compose logs --tail=50

# Seguir logs en tiempo real
docker-compose logs -f

# Logs de un servicio específico
docker-compose logs -f web
docker-compose logs -f mongodb
```

### Verificar conectividad
```bash
# Ping entre contenedores
docker exec coffee_shop_web ping mongodb -c 4

# Verificar puertos abiertos
docker exec coffee_shop_web netstat -tulpn

# Probar conexión a MongoDB desde PHP
docker exec coffee_shop_web php -r "try { new MongoDB\Driver\Manager('mongodb://mongodb:27017'); echo 'OK'; } catch(Exception \$e) { echo \$e->getMessage(); }"
```

---

## 🆘 Reseteo Completo

Si todo falla, resetea completamente:

```bash
# 1. Detener y eliminar todo
docker-compose down -v

# 2. Limpiar imágenes
docker rmi php-proyect-web

# 3. Limpiar sistema (opcional - elimina TODO de Docker)
# docker system prune -a --volumes

# 4. Reconstruir
docker-compose up -d --build

# 5. Esperar 10 segundos
sleep 10

# 6. Inicializar BD
docker exec -i coffee_shop_db mongosh < init-db.js

# 7. Verificar
docker-compose ps
```

---

## 🐛 Modo Debug

### Habilitar errores de PHP

Edita `src/public/index.php` y agrega al inicio:

```php
<?php
// Modo debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// ... resto del código
```

### Ver consultas a MongoDB

En `src/core/Database.php`, agrega logs:

```php
public function find($collection, $filter = [], $options = []) {
    error_log("MongoDB Query: " . json_encode($filter));
    // ... resto del código
}
```

---

## 📞 Obtener Ayuda

### Información útil para reportar problemas:

```bash
# 1. Versión de Docker
docker --version
docker-compose --version

# 2. Sistema operativo
# Windows: winver
# Linux: uname -a
# Mac: sw_vers

# 3. Logs completos
docker-compose logs > logs.txt

# 4. Estado de contenedores
docker-compose ps

# 5. Inspeccionar contenedor
docker inspect coffee_shop_web
docker inspect coffee_shop_db
```

---

**¿Aún tienes problemas?** Revisa los logs: `docker-compose logs -f`
