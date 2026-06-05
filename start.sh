#!/bin/bash

# Obtener el directorio del script
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo "☕ Coffee Shop - Inicializador"
echo "=============================="
echo ""

# Levantar contenedores
echo "🚀 Levantando contenedores Docker..."
docker-compose up -d --build

# Esperar a que MongoDB esté listo
echo "⏳ Esperando a que MongoDB esté listo..."
sleep 10

# Inicializar base de datos
echo "📊 Inicializando base de datos con datos de ejemplo..."
docker exec -i coffee_shop_db mongosh < init-db.js

echo ""
echo "✅ ¡Todo listo!"
echo ""
echo "🌐 Accede a la aplicación en: http://localhost:8080"
echo ""
echo "👥 Usuarios de prueba:"
echo "   Cliente:     cliente@coffee.com      (contraseña: 123456)"
echo "   Trabajador:  trabajador@coffee.com   (contraseña: 123456)"
echo "   Admin:       admin@coffee.com        (contraseña: 123456)"
echo ""
echo "🔥 Hot-reload activado: Edita archivos en src/ y recarga el navegador"
echo ""
