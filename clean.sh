#!/bin/bash

echo "=========================================="
echo "  Limpieza Completa de Docker - Latido Andino"
echo "=========================================="
echo ""
echo "⚠️  ADVERTENCIA: Esta operación eliminará:"
echo "   - Todos los contenedores detenidos"
echo "   - Todas las redes no utilizadas"
echo "   - Todos los volúmenes no utilizados (incluyendo latido_data)"
echo "   - Todas las imágenes no utilizadas"
echo "   - Todo el caché de compilación"
echo ""
read -p "¿Estás seguro de continuar? (s/n): " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Ss]$ ]]
then
    echo ""
    echo "🛑 Deteniendo y eliminando contenedores del proyecto..."
    docker-compose down -v
    
    echo ""
    echo "🧹 Limpiando sistema Docker completo..."
    docker system prune -a --volumes -f
    
    echo ""
    echo "📊 Espacio liberado:"
    docker system df
    
    echo ""
    echo "✅ Limpieza completa finalizada"
    echo ""
    echo "ℹ️  Para volver a iniciar el proyecto, ejecuta: ./start.sh"
    echo "   (Se reconstruirán las imágenes y volúmenes desde cero)"
    echo ""
else
    echo ""
    echo "❌ Operación cancelada"
    echo ""
fi
