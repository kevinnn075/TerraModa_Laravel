@echo off
setlocal
cd /d "%~dp0"
echo ==========================================
echo   TERRA MODA - Preparacion e inicio
echo ==========================================
where php >nul 2>nul
if errorlevel 1 (
  echo ERROR: PHP no esta en PATH. Abre la terminal de XAMPP o agrega C:\xampp\php al PATH.
  pause
  exit /b 1
)
where composer >nul 2>nul
if errorlevel 1 (
  echo ERROR: Composer no esta en PATH. Instala Composer y vuelve a ejecutar este archivo.
  pause
  exit /b 1
)
if not exist ".env" (
  copy ".env.example" ".env" >nul
  echo Se creo .env desde .env.example. Revisa los datos de MySQL.
)
if not exist "vendor\autoload.php" (
  echo Instalando dependencias de Laravel...
  composer install
  if errorlevel 1 goto error
)
findstr /C:"APP_KEY=base64:" .env >nul
if errorlevel 1 php artisan key:generate
echo.
echo IMPORTANTE: crea la base de datos terra_moda en MySQL/phpMyAdmin y confirma las credenciales de .env.
set /p MIGRATE=Ejecutar migraciones y datos de demostracion ahora? (S/N):
if /I "%MIGRATE%"=="S" (
  php artisan migrate --seed
  if errorlevel 1 goto error
)
echo Iniciando Laravel en http://127.0.0.1:8000
php artisan serve
goto end
:error
echo Ocurrio un error. Revisa el mensaje anterior y el archivo .env.
pause
:end
endlocal
