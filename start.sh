#!/bin/bash

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo "🛃 Latido Andino - Inicializador"
echo "================================"
echo ""

echo "🚀 Levantando contenedores Docker..."
docker-compose up -d --build

echo "⏳ Esperando a que MongoDB esté listo..."
sleep 10

echo "📊 Inicializando base de datos con datos de ejemplo..."
docker exec -i latido_andino_db mongosh < init-db.js

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