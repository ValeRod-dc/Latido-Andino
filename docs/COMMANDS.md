# 🛠️ Comandos de Desarrollo

## 🚀 Comandos Básicos

### Iniciar proyecto
```bash
# Construcción e inicio
docker-compose up -d --build

# Solo inicio (si ya está construido)
docker-compose up -d

# Ver logs mientras inicia
docker-compose up
```

### Detener proyecto
```bash
# Detener contenedores (datos persisten)
docker-compose down

# Detener y eliminar volúmenes (limpieza completa)
docker-compose down -v

# Detener y eliminar imágenes
docker-compose down --rmi all
```

---

## 📊 Monitoreo

### Ver estado
```bash
# Estado de contenedores
docker-compose ps

# Procesos en ejecución
docker-compose top

# Uso de recursos (CPU, RAM)
docker stats coffee_shop_web coffee_shop_db
```

### Ver logs
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

# Logs desde hace 10 minutos
docker-compose logs --since 10m
```

---

## 🗄️ MongoDB - Comandos

### Conectar a MongoDB
```bash
# Conectar a la base de datos
docker exec -it coffee_shop_db mongosh coffee_shop

# Ejecutar comando directo
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.find()"
```

### Consultas de Usuarios
```bash
# Ver todos los usuarios
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.find().pretty()"

# Contar usuarios
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.countDocuments()"

# Buscar usuario por email
docker exec -it coffee_shop_db mongosh coffee_shop --eval 'db.users.findOne({email: "admin@coffee.com"})'

# Ver solo emails y roles
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.find({}, {email:1, role:1, name:1})"
```

### Consultas de Productos
```bash
# Ver todos los productos
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.find().pretty()"

# Contar productos
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.countDocuments()"

# Ver solo nombres y precios
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.find({}, {name:1, price:1, size:1})"

# Productos nuevos
docker exec -it coffee_shop_db mongosh coffee_shop --eval 'db.products.find({is_new: true})'

# Productos por precio (menor a $5)
docker exec -it coffee_shop_db mongosh coffee_shop --eval 'db.products.find({price: {$lt: 5}})'
```

### Manipulación de Datos
```bash
# Agregar un usuario
docker exec -it coffee_shop_db mongosh coffee_shop --eval '
db.users.insertOne({
  name: "Nuevo Usuario",
  email: "nuevo@coffee.com",
  password: "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
  role: "cliente",
  created_at: new Date()
})'

# Agregar un producto
docker exec -it coffee_shop_db mongosh coffee_shop --eval '
db.products.insertOne({
  name: "Irish Coffee",
  description: "Café con whisky y crema",
  price: 6.50,
  size: "Grande",
  icon: "bi bi-cup-hot-fill",
  active: true,
  is_new: true,
  created_at: new Date()
})'

# Actualizar un producto
docker exec -it coffee_shop_db mongosh coffee_shop --eval '
db.products.updateOne(
  {name: "Espresso"},
  {$set: {price: 3.75}}
)'

# Eliminar un producto
docker exec -it coffee_shop_db mongosh coffee_shop --eval '
db.products.deleteOne({name: "Irish Coffee"})'
```

### Reiniciar Base de Datos
```bash
# Ejecutar script de inicialización
docker exec -i coffee_shop_db mongosh < init-db.js

# Limpiar colecciones
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.deleteMany({})"
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.deleteMany({})"
```

---

## 🐘 PHP - Comandos

### Ejecutar PHP en el contenedor
```bash
# Versión de PHP
docker exec -it coffee_shop_web php -v

# Módulos instalados
docker exec -it coffee_shop_web php -m

# Verificar MongoDB
docker exec -it coffee_shop_web php -m | grep mongodb

# Información de PHP
docker exec -it coffee_shop_web php -i

# Ejecutar código PHP
docker exec -it coffee_shop_web php -r "echo 'Hello from PHP';"

# Probar conexión a MongoDB
docker exec -it coffee_shop_web php -r "try { new MongoDB\Driver\Manager('mongodb://mongodb:27017'); echo 'MongoDB conectado'; } catch(Exception \$e) { echo 'Error: ' . \$e->getMessage(); }"
```

### Verificar configuración
```bash
# Ver php.ini activo
docker exec -it coffee_shop_web php --ini

# Ver errores de PHP
docker exec -it coffee_shop_web php -r "error_reporting(E_ALL); ini_set('display_errors', 1);"
```

---

## 🌐 Apache - Comandos

### Verificar Apache
```bash
# Versión de Apache
docker exec -it coffee_shop_web apache2 -v

# Módulos de Apache
docker exec -it coffee_shop_web apache2ctl -M

# Verificar mod_rewrite
docker exec -it coffee_shop_web apache2ctl -M | grep rewrite

# Test de configuración
docker exec -it coffee_shop_web apache2ctl configtest

# Reiniciar Apache
docker exec -it coffee_shop_web apache2ctl graceful
```

### Ver archivos de log
```bash
# Error log
docker exec -it coffee_shop_web tail -f /var/log/apache2/error.log

# Access log
docker exec -it coffee_shop_web tail -f /var/log/apache2/access.log

# Últimas 50 líneas de error
docker exec -it coffee_shop_web tail -50 /var/log/apache2/error.log
```

---

## 🔧 Desarrollo

### Acceder a los contenedores
```bash
# Acceder al contenedor web (Bash)
docker exec -it coffee_shop_web bash

# Acceder al contenedor MongoDB (Bash)
docker exec -it coffee_shop_db bash

# Salir del contenedor
exit
```

### Inspeccionar contenedores
```bash
# Ver toda la información del contenedor
docker inspect coffee_shop_web

# Ver solo IP
docker inspect coffee_shop_web | grep IPAddress

# Ver volúmenes montados
docker inspect coffee_shop_web | grep -A 10 Mounts

# Ver variables de entorno
docker inspect coffee_shop_web | grep -A 20 Env
```

### Copiar archivos
```bash
# Copiar archivo DEL contenedor A local
docker cp coffee_shop_web:/var/www/html/index.php ./index.php

# Copiar archivo DE local AL contenedor
docker cp ./index.php coffee_shop_web:/var/www/html/

# Copiar carpeta
docker cp coffee_shop_web:/var/www/html ./backup
```

---

## 🔄 Reiniciar servicios

### Reiniciar servicios específicos
```bash
# Reiniciar web
docker-compose restart web

# Reiniciar MongoDB
docker-compose restart mongodb

# Reiniciar todo
docker-compose restart
```

### Reconstruir
```bash
# Reconstruir imagen web
docker-compose build web

# Reconstruir sin caché
docker-compose build --no-cache web

# Reconstruir y reiniciar
docker-compose up -d --build
```

---

## 🧹 Limpieza

### Limpiar contenedores
```bash
# Detener todo
docker-compose down

# Detener y eliminar volúmenes
docker-compose down -v

# Detener y eliminar imágenes
docker-compose down --rmi all

# Detener, eliminar todo
docker-compose down -v --rmi all
```

### Limpiar Docker general
```bash
# Limpiar contenedores detenidos
docker container prune

# Limpiar imágenes sin usar
docker image prune

# Limpiar volúmenes sin usar
docker volume prune

# Limpiar redes sin usar
docker network prune

# Limpiar TODO (¡CUIDADO!)
docker system prune -a --volumes
```

---

## 🔍 Debug y Troubleshooting

### Verificar conectividad
```bash
# Ping entre contenedores
docker exec coffee_shop_web ping mongodb -c 4

# Curl a MongoDB
docker exec coffee_shop_web curl mongodb:27017

# Verificar puerto abierto
docker exec coffee_shop_web nc -zv mongodb 27017
```

### Ver procesos
```bash
# Procesos en contenedor web
docker exec coffee_shop_web ps aux

# Procesos de Apache
docker exec coffee_shop_web ps aux | grep apache

# Procesos de PHP
docker exec coffee_shop_web ps aux | grep php
```

### Verificar archivos
```bash
# Listar archivos en /var/www/html
docker exec coffee_shop_web ls -la /var/www/html

# Ver contenido de index.php
docker exec coffee_shop_web cat /var/www/html/public/index.php

# Verificar permisos
docker exec coffee_shop_web ls -la /var/www/html/public
```

---

## 📦 Backup y Restore

### Backup de MongoDB
```bash
# Exportar base de datos completa
docker exec coffee_shop_db mongodump --db coffee_shop --out /tmp/backup

# Copiar backup a local
docker cp coffee_shop_db:/tmp/backup ./mongodb-backup

# Exportar colección específica
docker exec coffee_shop_db mongoexport --db coffee_shop --collection users --out /tmp/users.json

# Copiar a local
docker cp coffee_shop_db:/tmp/users.json ./users-backup.json
```

### Restore de MongoDB
```bash
# Copiar backup al contenedor
docker cp ./mongodb-backup coffee_shop_db:/tmp/restore

# Restaurar
docker exec coffee_shop_db mongorestore --db coffee_shop /tmp/restore/coffee_shop

# Importar colección
docker cp ./users-backup.json coffee_shop_db:/tmp/users.json
docker exec coffee_shop_db mongoimport --db coffee_shop --collection users --file /tmp/users.json
```

---

## 🎯 Comandos Útiles Combinados

### Setup completo desde cero
```bash
docker-compose down -v
docker-compose up -d --build
sleep 10
docker exec -i coffee_shop_db mongosh < init-db.js
docker-compose logs -f
```

### Ver todo el estado
```bash
docker-compose ps && \
docker stats --no-stream coffee_shop_web coffee_shop_db && \
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.users.countDocuments()" && \
docker exec -it coffee_shop_db mongosh coffee_shop --eval "db.products.countDocuments()"
```

### Debug completo
```bash
docker-compose logs --tail=50 && \
docker exec -it coffee_shop_web php -v && \
docker exec -it coffee_shop_web php -m | grep mongodb && \
docker exec coffee_shop_web ping mongodb -c 2
```

---

## ⌨️ Aliases útiles (opcional)

Agrega a tu `~/.bashrc` o `~/.zshrc`:

```bash
# Coffee Shop aliases
alias coffee-start="cd /c/Users/Fernando/Desktop/php-proyect && docker-compose up -d --build"
alias coffee-stop="cd /c/Users/Fernando/Desktop/php-proyect && docker-compose down"
alias coffee-logs="cd /c/Users/Fernando/Desktop/php-proyect && docker-compose logs -f"
alias coffee-restart="cd /c/Users/Fernando/Desktop/php-proyect && docker-compose restart"
alias coffee-web="docker exec -it coffee_shop_web bash"
alias coffee-db="docker exec -it coffee_shop_db mongosh coffee_shop"
alias coffee-init="cd /c/Users/Fernando/Desktop/php-proyect && docker exec -i coffee_shop_db mongosh < init-db.js"
```

Luego:
```bash
source ~/.bashrc  # o ~/.zshrc
coffee-start
coffee-logs
```

---

**💡 Tip:** Guarda tus comandos más usados en un archivo `my-commands.sh` para referencia rápida!
