# Prueba IT - PHP, JQuery, Bootstrap, MySQL

### Esta aplicación sirve para manejar usuarios.

## Pasos para ejecutar la aplicación:

### Requerimientos:

- Utilizar un stack XAMPP ([XAMPP](https://www.apachefriends.org/es/download.html) o [Laragon](https://laragon.org/download/)) con las siguientes versiones de las tecnologías utilizadas:
  - PHP ^8.3.12
  - MySQL ^8.0.30
  - Bootstrap 5 y jQuery ya están incluidos en el proyecto, no es necesario agregarlos.
- Se requiere la opción `AllowRewrite All` en el archivo `httpd.conf` (en la sección `<Directory "/htdocs">` o `<Directory "/www">` dependiendo del manejador) de Apache para que se maneje correctamente la configuración del archivo `.htaccess` (ruteo de la aplicación). También debe estar descomentada la línea `LoadModule rewrite_module modules/mod_rewrite.so` (sin un `#` al inicio de la línea).

### Nota importante

Esta aplicación cuenta con un router personalizado, por lo que dependiendo del stack XAMPP que utilices, deberás almacenarla de forma que el archivo `.htaccess` funcione correctamente.

### Pasos para ejecutar:

- Utilizando **Laragon**:
  1. Coloca el contenido del repositorio en la carpeta `/pruebait` dentro del directorio `laragon/www`.
  2. Inicia el servidor y verifica que se haya creado el Pretty URL `pruebait.test`.
  3. Ejecuta los comandos SQL del archivo `db/users.sql` en tu manejador de MySQL para migrar la base de datos que utiliza la aplicación.
  4. Si fue correcto, puedes comenzar a utilizar la aplicación.

- Utilizando **XAMPP**:
  1. Elimina el contenido del directorio `xampp/htdocs`.
  2. Coloca el contenido del repositorio en la raíz (htdocs).
  3. Inicia los servidores Apache y MySQL.
  4. Ejecuta los comandos SQL del archivo `db/users.sql` en tu manejador de MySQL para migrar la base de datos que utiliza la aplicación.
  5. Verifica que [localhost](http://localhost) esté corriendo correctamente la aplicación para comenzar a utilizarla.

# Rutas API REST

## Usuarios

- **`/api/users`**:
  - **GET**  
    Regresa el listado de usuarios (excluyendo las contraseñas) con el siguiente formato:

    ```json
    {
      "status": 200,
      "data": [
        {
          "id": "1",
          "username": "test",
          "first_name": "test",
          "last_name": "test",
          "created_at": "2024-10-20 12:00:00",
          "updated_at": "2024-10-20 12:00:01"
        }
      ]
    }
    ```

  - **POST**  
    Captura datos de un nuevo usuario en la tabla `users` de la base de datos. Recibe un *form data* con los siguientes campos:
    - `username`: string
    - `email`: string
    - `password`: string
    - `first_name`: string
    - `last_name`: string

    Regresa un JSON con el siguiente formato:

    ```json
    {
      "status": 200,
      "data": {
        "id": "2",
        "username": "omar",
        "password": "admin",
        "email": "omar@mail.com",
        "first_name": "Omar",
        "last_name": "Martinez",
        "created_at": "2024-10-21 22:08:00",
        "updated_at": "2024-10-21 22:08:00"
      }
    }
    ```

    Errores posibles:

    - Al insertar un nombre de usuario o correo ya existentes:

      ```json
      {
        "status": 500,
        "error": "Un usuario con este correo o nombre de usuario ya existe"
      }
      ```

    - Al faltar datos:

      ```json
      {
        "status": 500,
        "error": "Todos los campos son obligatorios"
      }
      ```

- **`/api/users/:id`**:
  - **GET**
  