<?php
/**
 * Arbolado Urbano
 * php version 8.3
 *
 * @category Arbolado-Urbano
 * @package  Arbolado-Urbano/arbolado-wp-plugin
 * @author   Fermín Ares <fermin@ares.uy>
 * @version  GIT: 1.0.0
 * @link     https://github.com/Arbolado-Urbano/arbolado-wp-plugin/
 */

/*
Plugin Name: Arbolado Urbano
Plugin URI: https://github.com/Arbolado-Urbano/arbolado-wp-plugin/
Description: Plugin para Arbolado Urbano.
Version: 1.0.0
Author: Fermín Ares
Author URI: https://ares.uy/
Text Domain: arbolado
*/

// Impedir la carga de este archivo por fuera de WordPress.
if ( ! function_exists( 'add_shortcode' ) ) {
	exit;
}

define( 'ARBOLADO__ASSETS_URL', WP_PLUGIN_URL . '/arbolado-urbano/assets' );

wp_register_style( 'arbolado_colaboraciones', ARBOLADO__ASSETS_URL . '/css/colaboraciones.css', array(), '1' );

/**
 * Crear menú de configuración del plugin
 */
function arbolado_add_settings_menu() {
	add_options_page(
		'Arbolado Urbano Settings',
		'Arbolado Urbano',
		'manage_options',
		'arbolado-settings',
		'arbolado_settings_page'
	);
}
add_action( 'admin_menu', 'arbolado_add_settings_menu' );

/**
 * Registrar opciones del menú de configuración
 */
function arbolado_register_settings() {
	register_setting( 'arbolado-settings-group', 'arbolado_db_user' );
	register_setting( 'arbolado-settings-group', 'arbolado_db_pass' );
	register_setting( 'arbolado-settings-group', 'arbolado_db_name' );
	register_setting( 'arbolado-settings-group', 'arbolado_db_host' );
}
add_action( 'admin_init', 'arbolado_register_settings' );

/**
 * Renderizado del menú de configuración
 */
function arbolado_settings_page() {
	?>
		<div class="wrap">
			<h1>Arbolado Urbano</h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'arbolado-settings-group' ); ?>
				<?php do_settings_sections( 'arbolado-settings-group' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Usuario</th>
						<td><input type="text" name="arbolado_db_user" value="<?php echo esc_attr( get_option( 'arbolado_db_user' ) ); ?>" /></td>
					</tr>
						<tr valign="top">
						<th scope="row">Contraseña</th>
						<td><input type="password" name="arbolado_db_pass" value="<?php echo esc_attr( get_option( 'arbolado_db_pass' ) ); ?>" /></td>
					</tr>
						<tr valign="top">
						<th scope="row">Base de datos</th>
						<td><input type="text" name="arbolado_db_name" value="<?php echo esc_attr( get_option( 'arbolado_db_name' ) ); ?>" /></td>
					</tr>
						<tr valign="top">
						<th scope="row">Servidor</th>
						<td><input type="text" name="arbolado_db_host" value="<?php echo esc_attr( get_option( 'arbolado_db_host' ) ); ?>" /></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
}

/**
 * Mostrar link a la página de configuración del plugin
 *
 * @param array $links Enlaces por defecto.
 */
function arbolado_settings_link( $links ) {
	$settings_link = '<a href="options-general.php?page=arbolado-settings">' . __( 'Settings' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'arbolado_settings_link' );

/**
 * Shortcode para listar a los colaboradores del proyecto.
 */
function arbolado_colaboraciones_sc() {
	wp_enqueue_style( 'arbolado_colaboraciones' );
	$db_user = get_option( 'arbolado_db_user' );
	$db_pass = get_option( 'arbolado_db_pass' );
	$db_name = get_option( 'arbolado_db_name' );
	$db_host = get_option( 'arbolado_db_host' );
	if ( ( ! $db_user ) || ( ! $db_pass ) || ( ! $db_name ) || ( ! $db_host ) ) {
		return '<p><b>[Error: Por favor, revise la confiugración del plugin "Arbolado Urbano"]</b></p>';
	}
	$db   = new wpdb( $db_user, $db_pass, $db_name, $db_host );
	$rows = $db->get_results( 'SELECT nombre, twitter, facebook, instagram, url, slug, COUNT(r.id) AS cantidad_aportes FROM fuentes f LEFT JOIN registros r ON f.id = r.fuente_id GROUP BY nombre, twitter, instagram, f.url, slug HAVING cantidad_aportes > 0 ORDER BY cantidad_aportes DESC' );
	if ( ! $rows ) {
		return '<p><b>[Error: Por favor, revise la confiugración del plugin "Arbolado Urbano"]</b></p>';
	}
	$result  = '<table><thead><tr>';
	$result .= '<th>Fuente</th>';
	$result .= '<th>Cantidad de árboles</th>';
	$result .= '<th>Links</th>';
	$result .= '<th>Mapa</th>';
	$result .= '</tr></thead><tbody>';
	foreach ( $rows as $obj ) {
		$result .= '<tr>';
		$result .= '<td>' . $obj->nombre . '</td>';
		$result .= '<td class="number">' . $obj->cantidad_aportes . '</td>';
		$result .= '<td><span class="links">';
		if ( $obj->url ) {
			$result .= '<a target="_blank" rel="noreferrer noopener nofollow" href="' . $obj->url . '"><img width="20" height="20" src="' . ARBOLADO__ASSETS_URL . '/icons/web.svg" alt="Sitio web" /></a>';
		}
		if ( $obj->twitter ) {
			$result .= '<a target="_blank" rel="noreferrer noopener nofollow" href="' . $obj->twitter . '"><img width="20" height="20" src="' . ARBOLADO__ASSETS_URL . '/icons/x.svg" alt="Twitter/X" /></a>';
		}
		if ( $obj->facebook ) {
			$result .= '<a target="_blank" rel="noreferrer noopener nofollow" href="' . $obj->facebook . '"><img width="20" height="20" src="' . ARBOLADO__ASSETS_URL . '/icons/facebook.svg" alt="Facebook" /></a>';
		}
		if ( $obj->instagram ) {
			$result .= '<a target="_blank" rel="noreferrer noopener nofollow" href="' . $obj->instagram . '"><img width="20" height="20" src="' . ARBOLADO__ASSETS_URL . '/icons/instagram.svg" alt="Instagram" /></a>';
		}
		$result .= '</span></td>';
		if ( $obj->cantidad_aportes < 15000 ) {
			$result .= '<td><span class="map-link">';
			$result .= '<a target="_blank" href="https://arboladourbano.com/fuente/' . $obj->slug . '"><img width="25" height="25" src="' . ARBOLADO__ASSETS_URL . '/icons/marker.png" alt="Ver árboles" /></a>';
			$result .= '<span></td>';
		} else {
			$result .= '<td></td>';
		}
		$result .= '</tr>';
	}
	$result .= '</tbody></table>';
	return $result;
}

add_shortcode( 'arbolado-colaboraciones', 'arbolado_colaboraciones_sc' );
