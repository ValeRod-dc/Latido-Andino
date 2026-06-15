#!/bin/bash

echo "======================================"
echo "  Deteniendo Latido Andino"
echo "======================================"
echo ""

echo "🛑 Deteniendo contenedores..."
docker-compose down

echo ""
echo "✅ Aplicación detenida exitosamente"
echo ""
echo "ℹ️  Los volúmenes, imágenes y datos se mantienen intactos"
echo "ℹ️  Para iniciar nuevamente, ejecuta: ./start.sh"
echo ""
