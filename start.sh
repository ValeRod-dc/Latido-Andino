#!/bin/bash

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

# En Linux/macOS esta variable no tiene efecto.
export MSYS_NO_PATHCONV=1

echo "🛃 Latido Andino - Inicializador"
echo "================================"
echo ""

echo "🚀 Levantando contenedores Docker..."
docker-compose up -d --build

echo "⏳ Esperando a que MongoDB esté listo..."

# Esperar hasta que el contenedor esté corriendo y mongosh responda
MAX_RETRIES=30
RETRY_COUNT=0
CONTAINER_NAME="latido_andino_db"

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    # Verificar si el contenedor está en estado "running"
    STATUS=$(docker inspect --format='{{.State.Status}}' "$CONTAINER_NAME" 2>/dev/null)
    if [ "$STATUS" = "running" ]; then
        # Intentar ejecutar un comando simple en mongosh
        if docker exec "$CONTAINER_NAME" mongosh --eval "db.runCommand({ping: 1})" > /dev/null 2>&1; then
            echo "✅ MongoDB está listo."
            break
        fi
    fi
    RETRY_COUNT=$((RETRY_COUNT + 1))
    echo "   Esperando... ($RETRY_COUNT/$MAX_RETRIES)"
    sleep 2
done

if [ $RETRY_COUNT -eq $MAX_RETRIES ]; then
    echo "❌ Error: MongoDB no está listo después de $MAX_RETRIES intentos."
    echo "   Revisa los logs con: docker-compose logs mongodb"
    exit 1
fi

echo "📊 Inicializando base de datos con datos de ejemplo..."
docker exec -i "$CONTAINER_NAME" mongosh < init-db.js

echo "📦 Instalando dependencias de Composer (PhpSpreadsheet, Dompdf)..."
docker exec -i latido_andino_web composer install --working-dir=/var/www/html --no-interaction

echo ""
echo "✅ ¡Sistema listo!"
echo ""
echo "🌐 Accede a la aplicación en: http://localhost:8081"
echo ""
echo "👥 Usuarios de prueba:"
echo "   Viajero:     viajero@example.com      (contraseña: 123456)"
echo "   Aduanas:     aduanas@aduana.cl        (contraseña: 123456)"
echo "   SAG:         sag@sag.cl               (contraseña: 123456)"
echo "   PDI:         pdi@pdi.cl               (contraseña: 123456)"
echo "   Admin:       admin@latidoandino.cl    (contraseña: 123456)"
echo ""
echo "🔥 Hot-reload activado: Edita archivos en src/ y recarga el navegador"
echo ""