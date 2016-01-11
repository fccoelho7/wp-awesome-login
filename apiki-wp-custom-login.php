<?php
/*
	Plugin Name: WP Awesome Login
	Plugin URI:
	Author: Accácio Franklin and Fabio Carvalho
	Version: 1.0
	Author URI:
	Description: Change the way that you login on WordPress.
*/

namespace Apiki\Login;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class App
{
	const PLUGIN_SLUG = 'apiki-wp-custom-login';

	public static function uses( $class_name, $location )
	{
		$locations = array(
			'Controller',
			'View',
			'Helper',
			'Widget',
			'Vendor',
		);

		$extension = 'php';

		if ( in_array( $location, $locations ) )
			$extension = strtolower( $location ) . '.php';

		include "{$location}/{$class_name}.{$extension}";
	}

	public static function plugins_url( $path )
	{
		return plugins_url( $path, __FILE__ );
	}

	public static function plugin_dir_path( $path )
	{
		return plugin_dir_path( __FILE__ ) . $path;
	}

	public static function filemtime( $path )
	{
		return filemtime( self::plugin_dir_path( $path ) );
	}
}

App::uses( 'core', 'Config' );

$core = new Core();

register_activation_hook( __FILE__, array( $core, 'activate' ) );