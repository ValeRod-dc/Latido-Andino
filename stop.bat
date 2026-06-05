@echo off
echo ======================================
echo   Deteniendo Coffee Shop Application
echo ======================================
echo.

REM Detener los contenedores sin eliminar volúmenes ni imágenes
echo Deteniendo contenedores...
docker-compose down

echo.
echo Aplicacion detenida exitosamente
echo.
echo Los volumenes, imagenes y datos se mantienen intactos
echo Para iniciar nuevamente, ejecuta: start.bat
echo.
pause
