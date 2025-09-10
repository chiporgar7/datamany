#!/bin/bash
set -e

# Esperar a que la base de datos esté lista
echo "Esperando a que PostgreSQL esté listo..."
until PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -d "$DB_DATABASE" -c '\q' 2>/dev/null; do
  echo "PostgreSQL no está listo - esperando..."
  sleep 2
done

echo "PostgreSQL está listo!"

# Generar key de aplicación si no existe
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Crear enlace simbólico de storage
echo "Creando enlace simbólico de storage..."
php artisan storage:link || true

# Instalar Breeze API si no está instalado
if [ ! -f "routes/auth.php" ]; then
    echo "Instalando Laravel Breeze API..."
    composer require laravel/breeze --dev
    php artisan breeze:install api
fi

# Optimizar la aplicación
echo "Optimizando la aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpiar cache si es necesario
php artisan cache:clear

echo "Inicialización completada!"

# Ejecutar el comando original
exec "$@"