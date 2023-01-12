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

### Esquema de base de datos

![Esquema de base de datos!](/docs/database.png "Esquema de base de datos")

### Endpoints

```bash
  GET|HEAD        / .............................................................................................. home
  POST            api/login ...................................................................... AuthController@login
  POST            api/logout .................................................................... AuthController@logout
  GET|HEAD        api/me ............................................................................ AuthController@me
  GET|HEAD        api/permissions ..................................... permissions.index › PermissionsController@index
  POST            api/permissions ..................................... permissions.store › PermissionsController@store
  GET|HEAD        api/permissions/{permission} .......................... permissions.show › PermissionsController@show
  PUT|PATCH       api/permissions/{permission} ...................... permissions.update › PermissionsController@update
  DELETE          api/permissions/{permission} .................... permissions.destroy › PermissionsController@destroy
  POST            api/refresh .................................................................. AuthController@refresh
  POST            api/register ................................................................ AuthController@register
  GET|HEAD        api/roles ....................................................... roles.index › RolesController@index
  POST            api/roles ....................................................... roles.store › RolesController@store
  GET|HEAD        api/roles/{role} .................................................. roles.show › RolesController@show
  PUT|PATCH       api/roles/{role} .............................................. roles.update › RolesController@update
  DELETE          api/roles/{role} ............................................ roles.destroy › RolesController@destroy
  GET|HEAD        api/users ....................................................... users.index › UsersController@index
  POST            api/users ....................................................... users.store › UsersController@store
  GET|HEAD        api/users/{user} .................................................. users.show › UsersController@show
  PUT|PATCH       api/users/{user} .............................................. users.update › UsersController@update
  DELETE          api/users/{user} ............................................ users.destroy › UsersController@destroy
  POST            api/users/{user}/permissions .............. users.permissions.store › UserPermissionsController@store
  DELETE          api/users/{user}/permissions ...................................... UserPermissionsController@destroy
  PATCH           api/users/{user}/permissions ....................................... UserPermissionsController@update
  POST            api/users/{user}/roles ................................ users.roles.store › UserRolesController@store
  DELETE          api/users/{user}/roles .................................................. UserRolesController@destroy
  PATCH           api/users/{user}/roles ................................................... UserRolesController@update
```

### Documentación

La documentación de la API se puede ver en cualquier visor de documentación de swagger, para ver la documentación en VS Code se puede usar la extensión [Swagger Viewer](https://marketplace.visualstudio.com/items?itemName=Arjun.swagger-viewer)
