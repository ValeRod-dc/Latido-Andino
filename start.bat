@echo off
echo 🛃 Latido Andino - Inicializador
echo ================================
echo.

echo Levantando contenedores Docker...
docker-compose up -d --build

echo Esperando a que MongoDB este listo...
timeout /t 10 /nobreak >nul

echo Inicializando base de datos con datos de ejemplo...
docker exec -i latido_andino_db mongosh < init-db.js

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
