# Arbolado Urbano WP Plugin

## Dependencia de desarrollo

- [Docker](https://www.docker.com/)

## Instalación para desarrollo

1. Ejectuar el comando `docker compose up -d` para iniciar los contenedores de Docker con WordPress y MySQL y auto-configurar WordPress.
3. Para acceder al panel de administración en [http://localhost:2000/wp-admin](http://localhost:2000/wp-admin) el usuario es `admin` y la contraseña es `123456`

Para detener los contenedores de Docker con la base de datos y WordPress una vez finalizada una sesión de desarrollo ejecutar `docker compose down`

## Empaquetación e instalación del proyecto

1. Ejectuar el comando `docker compose --profile build run --rm builder` para empaquetar el plugin en un archivo `zip` en `./dist/arbolado-urbano.zip`
2. Instalar el plugin desde el panel de administración del WordPress deseado en `plugins -> Agregar nuevo plugin -> Subir plugin` o copiando el archivo zip al directorio `wp-content/plugins` de la instalación de WordPress en el servidor.
3. Activar el plugin en el panel de administración de WordPress desde el listado de plugins.
4. Configurar el plugin seleccionando la opción `Ajustes` desde el listado de plugins.

## Uso del plugin

### Shortcode para listado de fuentes ordenadas por cantidad de aprotes

En cualquier página o publicación escribir `[arbolado-colaboraciones]`. Al momento de renderizar esta página o publicación el shortcode será reemplazado por el listado de fuentes.