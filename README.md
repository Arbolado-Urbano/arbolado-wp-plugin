# Arbolado Urbano WP Plugin

## Dependencias de desarrollo

- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/)

## Instalación para desarrollo

1. Ejectuar el comando `composer install` para instalar las dependencias del proyecto e instalar y configurar Wordpress.
2. Ejecutar el comando `composer start` para levantar un servidor PHP local en [http://localhost:2000](http://localhost:2000)

El código del plugin se encuentra en `wordpress/wp-content/plugins/arbolado-urbano`

Para detener el conatiner de Docker con la base de datos una vez finalizada una sesión de desarrollo ejecutar `composer stop`

## Empaquetación e instalación del proyecto

1. Ejectuar el comando `composer build` para empaquetar el plugin en un archivo `zip` en `./dist/arbolado-urbano.zip`
2. Instalar el plugin desde el panel de administración de WordPress en `plugins -> Agregar nuevo plugin -> Subir plugin` o copiando el archivo zip al directorio `wp-content/plugins` de la instalación de WordPress en el servidor.
3. Activar el plugin en el panel de administración de WordPress desde el listado de plugins.
4. Configurar el plugin seleccionando la opción `Ajustes` desde el listado de plugins.

## Uso del plugin

### Shortcode para listado de fuentes ordenadas por cantidad de aprotes

En cualquier página o publicación escribir `[arbolado-colaboraciones]`. Al momento de renderizar esta página o publicación el shortcode será reemplazado por el listado de fuentes.