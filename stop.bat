@echo off
echo ======================================
echo   Deteniendo Latido Andino
echo ======================================
echo.

echo Deteniendo contenedores...
docker-compose stop

echo.
echo Aplicacion detenida exitosamente
echo.
echo Los volumenes, imagenes y datos se mantienen intactos
echo Para iniciar nuevamente, ejecuta: start.bat
echo.
pause
