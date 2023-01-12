## Levantar en local con Docker

Copiar el contenido del archivo .env_db.example dentro de un archivo .env_db.

Copiar el contenido del archivo .env.example dentro de un archivo .env, en este archivo se deben especificar los datos de conexión a la base de datos, si se copia intacto el contenido del archivo de ejemplo se creará una base de datos con el nombre `api-appyweb-db`. Es importante que estas credenciales coincidan con las credenciales del archivo `.env_db`

Primero se debe construir las imágenes de docker

```bash
docker-compose build
```

Luego se debe levantar el proyecto con docker-compose, esto va a poner a ejecutar una instancia de postgres y una instancia de la app

```bash
docker-compose up -d
```

Por último se deben correr las migraciones dentro del contenedor de la app

```bash
docker exec -it api-appyweb-app sh
php artisan migrate
```

Para detener la ejecución del proyecto, se debe ejecutar el siguiente comando en la raiz del proyecto

```bash
docker-compose down
```
