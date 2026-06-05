@echo off
echo Coffee Shop - Inicializador
echo ==============================
echo.

REM Levantar contenedores
echo Levantando contenedores Docker...
docker-compose up -d --build

REM Esperar a que MongoDB este listo
echo Esperando a que MongoDB este listo...
timeout /t 10 /nobreak >nul

REM Inicializar base de datos
echo Inicializando base de datos con datos de ejemplo...
docker exec -i coffee_shop_db mongosh < init-db.js

echo.
echo Todo listo!
echo.
echo Accede a la aplicacion en: http://localhost:8080
echo.
echo Usuarios de prueba:
echo    Cliente:     cliente@coffee.com      (contrasena: 123456)
echo    Trabajador:  trabajador@coffee.com   (contrasena: 123456)
echo    Admin:       admin@coffee.com        (contrasena: 123456)
echo.
echo Hot-reload activado: Edita archivos en src/ y recarga el navegador
echo.
pause
