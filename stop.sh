#!/bin/bash

echo "======================================"
echo "  Deteniendo Coffee Shop Application"
echo "======================================"
echo ""

# Detener los contenedores sin eliminar volúmenes ni imágenes
echo "🛑 Deteniendo contenedores..."
docker-compose down

echo ""
echo "✅ Aplicación detenida exitosamente"
echo ""
echo "ℹ️  Los volúmenes, imágenes y datos se mantienen intactos"
echo "ℹ️  Para iniciar nuevamente, ejecuta: ./start.sh"
echo ""
