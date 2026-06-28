@echo off
echo 🛃 Latido Andino - Inicializador
echo ================================
echo.

echo Levantando contenedores Docker...
docker-compose up -d --build

echo Esperando a que MongoDB esté listo...

set MAX_RETRIES=30
set RETRY_COUNT=0
set CONTAINER_NAME=latido_andino_db

:loop
set /a RETRY_COUNT+=1
if %RETRY_COUNT% gtr %MAX_RETRIES% (
    echo ❌ Error: MongoDB no esta listo despues de %MAX_RETRIES% intentos.
    echo    Revisa los logs con: docker-compose logs mongodb
    pause
    exit /b 1
)

REM Verificar estado del contenedor
for /f "usebackq tokens=*" %%i in (`docker inspect --format="{{.State.Status}}" %CONTAINER_NAME% 2^>nul`) do set STATUS=%%i
if "%STATUS%"=="running" (
    REM Intentar hacer ping a MongoDB
    docker exec %CONTAINER_NAME% mongosh --eval "db.runCommand({ping: 1})" >nul 2>&1
    if errorlevel 0 (
        echo ✅ MongoDB esta listo.
        goto :ready
    )
)
echo    Esperando... (%RETRY_COUNT%/%MAX_RETRIES%)
timeout /t 2 /nobreak >nul
goto loop

:ready
echo Inicializando base de datos con datos de ejemplo...
docker exec -i %CONTAINER_NAME% mongosh < init-db.js

echo.
echo ✅ ¡Sistema listo!
echo.
echo 🌐 Accede a la aplicacion en: http://localhost:8081
echo.
echo 👥 Usuarios de prueba:
echo    Viajero:     viajero@example.com      (contrasena: 123456)
echo    Aduanas:     aduanas@aduana.cl        (contrasena: 123456)
echo    SAG:         sag@sag.cl               (contrasena: 123456)
echo    PDI:         pdi@pdi.cl               (contrasena: 123456)
echo    Admin:       admin@latidoandino.cl    (contrasena: 123456)
echo.
echo 🔥 Hot-reload activado: Edita archivos en src/ y recarga el navegador
echo.
pause