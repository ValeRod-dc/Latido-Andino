# 📚 Índice de Documentación - Coffee Shop

Bienvenido al proyecto Coffee Shop E-commerce. Esta es tu guía para navegar toda la documentación.

> **📍 Ubicación:** Toda la documentación está en la carpeta `docs/`

---

## 🚀 Para Empezar

### 1. **[SUMMARY.md](SUMMARY.md)** ⭐ COMIENZA AQUÍ
- 📋 Resumen ejecutivo del proyecto
- ✅ Lo que se ha implementado
- 📊 Estadísticas y métricas
- 🎯 Estado actual

### 2. **[QUICKSTART.md](QUICKSTART.md)** 🏃‍♂️ INICIO RÁPIDO
- 🚀 3 pasos para levantar el proyecto
- 👥 Usuarios de prueba
- 🔥 Cómo funciona el hot-reload
- 🛑 Cómo detener el proyecto

### 3. **[../README.md](../README.md)** 📖 DOCUMENTACIÓN PRINCIPAL
- 🌟 Características del proyecto
- 📁 Estructura del proyecto
- 🛠️ Instalación completa
- 📱 Páginas implementadas
- 🔒 Sistema de sesiones

---

## 🔍 Para Entender el Proyecto

### 4. **[ARCHITECTURE.md](ARCHITECTURE.md)** 🏗️ ARQUITECTURA
- 📐 Patrón MVC explicado
- 🎯 Flujo de la aplicación
- 🗄️ Estructura de base de datos
- 🔄 Cómo funciona el hot-reload
- 🛣️ Rutas disponibles
- 🔒 Sistema de seguridad

### 5. **[PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md)** 📊 VISIÓN GENERAL
- 📂 Estructura visual
- 🎨 Capturas conceptuales
- 📊 Datos de ejemplo
- 🛠️ Stack tecnológico
- 📈 Métricas del proyecto
- 🎓 Casos de uso

---

## 🛠️ Para Desarrollar

### 6. **[COMMANDS.md](COMMANDS.md)** ⌨️ COMANDOS
- 🚀 Comandos básicos Docker
- 📊 Monitoreo y logs
- 🗄️ Comandos MongoDB
- 🐘 Comandos PHP
- 🌐 Comandos Apache
- 🔧 Desarrollo y debug
- 📦 Backup y restore
- ⌨️ Aliases útiles

### 7. **Scripts de Inicio**
- **[start.sh](start.sh)** - Script para Linux/Mac
- **[start.bat](start.bat)** - Script para Windows
- **[init-db.js](init-db.js)** - Inicializar base de datos

---

## ✅ Para Verificar

### 8. **[CHECKLIST.md](CHECKLIST.md)** ✔️ LISTA DE VERIFICACIÓN
- 🔍 Checklist de instalación
- 🧪 Tests de autenticación
- 🎨 Verificación visual
- 🔧 Checklist técnico
- 🐛 Errores comunes
- ✅ Verificación final

---

## 🆘 Para Resolver Problemas

### 9. **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** 🔧 SOLUCIÓN DE PROBLEMAS
- 🚨 Problemas comunes y soluciones
- 📊 Comandos de diagnóstico
- 🆘 Reseteo completo
- 🐛 Modo debug
- 📞 Cómo reportar problemas

---

## 📁 Archivos de Configuración

### Docker
- **[docker-compose.yml](docker-compose.yml)** - Orquestación de servicios
- **[Dockerfile](Dockerfile)** - Imagen PHP personalizada
- **[apache-config.conf](apache-config.conf)** - Configuración Apache

### Otros
- **[.gitignore](.gitignore)** - Archivos ignorados por Git
- **[.env.example](.env.example)** - Variables de entorno ejemplo

---

## 💻 Código Fuente

### 📂 Estructura del Código

```
src/
├── controllers/          # 🎮 Controladores
│   ├── AuthController.php
│   └── HomeController.php
│
├── models/              # 📊 Modelos
│   ├── User.php
│   └── Product.php
│
├── views/               # 🎨 Vistas
│   ├── login.php
│   └── home.php
│
├── core/                # ⚙️ Núcleo
│   └── Database.php
│
└── public/              # 🌐 Entrada
    ├── index.php
    └── .htaccess
```

---

## 🎯 Guías Según tu Objetivo

### 🆕 Soy nuevo, ¿por dónde empiezo?
1. Lee **[SUMMARY.md](SUMMARY.md)** para entender qué es el proyecto
2. Sigue **[QUICKSTART.md](QUICKSTART.md)** para levantarlo
3. Usa **[CHECKLIST.md](CHECKLIST.md)** para verificar que funciona

### 🔨 Quiero desarrollar features nuevas
1. Lee **[ARCHITECTURE.md](ARCHITECTURE.md)** para entender la estructura
2. Usa **[COMMANDS.md](COMMANDS.md)** para comandos útiles
3. Edita archivos en `src/` (hot-reload activo)

### 🐛 Tengo un problema
1. Revisa **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** primero
2. Verifica con **[CHECKLIST.md](CHECKLIST.md)**
3. Usa comandos de diagnóstico de **[COMMANDS.md](COMMANDS.md)**

### 📚 Quiero entender cómo funciona todo
1. Lee **[ARCHITECTURE.md](ARCHITECTURE.md)** para la arquitectura
2. Lee **[README.md](README.md)** para los detalles
3. Revisa **[PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md)** para la visión completa

### 🚀 Quiero ponerlo en producción
1. Lee la sección de seguridad en **[ARCHITECTURE.md](ARCHITECTURE.md)**
2. Revisa las mejores prácticas en **[README.md](README.md)**
3. Usa **[COMMANDS.md](COMMANDS.md)** para backup y monitoreo

---

## 📊 Mapeo Rápido

| Necesito... | Voy a... |
|-------------|----------|
| Empezar desde cero | [QUICKSTART.md](QUICKSTART.md) |
| Entender el proyecto | [SUMMARY.md](SUMMARY.md) |
| Ver la arquitectura | [ARCHITECTURE.md](ARCHITECTURE.md) |
| Resolver un problema | [TROUBLESHOOTING.md](TROUBLESHOOTING.md) |
| Comandos útiles | [COMMANDS.md](COMMANDS.md) |
| Verificar instalación | [CHECKLIST.md](CHECKLIST.md) |
| Documentación completa | [README.md](README.md) |
| Visión general | [PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md) |

---

## 🔗 Enlaces Rápidos

### Accesos Web
- 🌐 Aplicación: http://localhost:8080
- 🔐 Login: http://localhost:8080/login
- 🏠 Home: http://localhost:8080/home

### Comandos Más Usados
```bash
# Iniciar
docker-compose up -d --build

# Ver logs
docker-compose logs -f

# Detener
docker-compose down

# Reiniciar BD
docker exec -i coffee_shop_db mongosh < init-db.js
```

### Usuarios Demo
- 📧 admin@coffee.com | 🔑 123456
- 📧 trabajador@coffee.com | 🔑 123456
- 📧 cliente@coffee.com | 🔑 123456

---

## 📈 Nivel de Prioridad de Lectura

### 🔴 Prioridad Alta (Leer primero)
1. [SUMMARY.md](SUMMARY.md)
2. [QUICKSTART.md](QUICKSTART.md)
3. [CHECKLIST.md](CHECKLIST.md)

### 🟡 Prioridad Media (Leer después)
4. [README.md](README.md)
5. [ARCHITECTURE.md](ARCHITECTURE.md)
6. [COMMANDS.md](COMMANDS.md)

### 🟢 Prioridad Baja (Lectura opcional)
7. [PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md)
8. [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

---

## 🎨 Documentos por Tipo

### 📖 Documentación
- README.md
- ARCHITECTURE.md
- PROJECT_OVERVIEW.md

### 🚀 Guías de Inicio
- SUMMARY.md
- QUICKSTART.md

### 🛠️ Referencia Técnica
- COMMANDS.md
- TROUBLESHOOTING.md

### ✅ Verificación
- CHECKLIST.md

### 📜 Scripts
- start.sh
- start.bat
- init-db.js

---

## 🔍 Búsqueda Rápida

### ¿Cómo hacer...?

**¿Cómo levantar el proyecto?**
→ [QUICKSTART.md](QUICKSTART.md) sección "Iniciar el Proyecto"

**¿Cómo funciona el login?**
→ [ARCHITECTURE.md](ARCHITECTURE.md) sección "Sistema de Sesiones"

**¿Cómo agregar un producto?**
→ [COMMANDS.md](COMMANDS.md) sección "Manipulación de Datos"

**¿Cómo ver los logs?**
→ [COMMANDS.md](COMMANDS.md) sección "Monitoreo"

**¿Cómo reiniciar la BD?**
→ [COMMANDS.md](COMMANDS.md) sección "Reiniciar Base de Datos"

**¿Por qué no funciona el login?**
→ [TROUBLESHOOTING.md](TROUBLESHOOTING.md) sección "Login no funciona"

**¿Cómo verificar que todo funciona?**
→ [CHECKLIST.md](CHECKLIST.md) todas las secciones

**¿Qué es el hot-reload?**
→ [ARCHITECTURE.md](ARCHITECTURE.md) sección "Hot-Reload"

---

## 💡 Tips de Navegación

1. **Ctrl + F** para buscar dentro de un documento
2. Los enlaces internos te llevan directamente a la sección
3. Cada documento tiene un propósito específico
4. Empieza por SUMMARY.md para el panorama general
5. COMMANDS.md es tu referencia rápida constante

---

## 📞 ¿Necesitas Ayuda?

1. **Busca** en este índice tu necesidad
2. **Ve** al documento correspondiente
3. **Usa** Ctrl + F para buscar palabras clave
4. **Revisa** [TROUBLESHOOTING.md](TROUBLESHOOTING.md) si hay problemas
5. **Consulta** [COMMANDS.md](COMMANDS.md) para comandos específicos

---

## 🎯 Resumen Ultra-Rápido

```bash
# 1. Levantar proyecto
docker-compose up -d --build

# 2. Esperar 10 segundos
sleep 10

# 3. Inicializar BD
docker exec -i coffee_shop_db mongosh < init-db.js

# 4. Abrir navegador
# → http://localhost:8080

# 5. Login
# → admin@coffee.com / 123456

# ¡Listo! ☕
```

---

**📚 Este índice se actualiza automáticamente con el proyecto**

*Última actualización: 19 de octubre de 2025*
