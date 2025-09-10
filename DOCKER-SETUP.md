# Docker Setup

## Requisitos
- Docker
- Docker Compose

## Configuración inicial

1. **Clonar el repositorio**
```bash
git clone [repository-url]
cd manydata
```

2. **Configurar variables de entorno**
```bash
cp .env.example .env
```

3. **Construir y levantar los contenedores**
```bash
docker-compose up -d --build
```

## Servicios incluidos

- **app**: Laravel API (puerto 8000)
- **postgres**: PostgreSQL database
- **redis**: Redis para colas y cache
- **queue-worker**: Worker para procesar jobs
- **reverb**: WebSocket server para notificaciones en tiempo real (puerto 8080)

## Comandos útiles

### Ver logs
```bash
docker-compose logs -f app
docker-compose logs -f queue-worker
```

### Ejecutar comandos artisan
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan queue:work
```

### Reiniciar servicios
```bash
docker-compose restart app
docker-compose restart queue-worker
```

### Detener todo
```bash
docker-compose down
```

### Limpiar todo (incluyendo volúmenes)
```bash
docker-compose down -v
```

## URLs de acceso

- API: http://localhost:8000
- WebSocket (Reverb): http://localhost:8080

## Solución de problemas

### Si las migraciones no se ejecutan automáticamente
```bash
docker-compose exec app php artisan migrate
```

### Si los archivos no tienen permisos correctos
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Si necesitas reinstalar dependencias
```bash
docker-compose exec app composer install
```
