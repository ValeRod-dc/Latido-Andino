@echo off
echo ==========================================
echo   Limpieza Completa de Docker
echo ==========================================
echo.
echo ADVERTENCIA: Esta operacion eliminara:
echo    - Todos los contenedores detenidos
echo    - Todas las redes no utilizadas
echo    - Todos los volumenes no utilizados
echo    - Todas las imagenes no utilizadas
echo    - Todo el cache de compilacion
echo.
set /p confirm="Estas seguro de continuar? (s/n): "

if /i "%confirm%"=="s" (
    echo.
    echo Deteniendo y eliminando contenedores del proyecto...
    docker-compose down -v
    
    echo.
    echo Limpiando sistema Docker completo...
    docker system prune -a --volumes -f
    
    echo.
    echo Espacio liberado:
    docker system df
    
    echo.
    echo Limpieza completa finalizada
    echo.
    echo Para volver a iniciar el proyecto, ejecuta: start.bat
    echo (Se reconstruiran las imagenes y volumenes desde cero^)
    echo.
) else (
    echo.
    echo Operacion cancelada
    echo.
)

pause
