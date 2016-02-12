<?php
/*
	Plugin Name: WP Awesome Login
	Plugin URI: https://github.com/fccoelho7/WP-Awesome-Login
	Author: Accácio Franklin, Fabio Carvalho and Elvis Henrique
	Version: 0.2.0
	Description: Change the way you log in to WordPress with a flat design and colors of your choice.
	Text Domain: wp-awesome-login
	Domain Path: /languages
*/

namespace WPA\Login;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class App
{
	const PLUGIN_SLUG = 'wp-awesome-login';

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

	public static function plugin_basename_file()
	{
		return plugin_basename( __FILE__ );
	}

	public static function load_textdomain()
	{
		load_plugin_textdomain( self::PLUGIN_SLUG, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
}

App::uses( 'core', 'Config' );

$core = new Core();

register_activation_hook( __FILE__, array( $core, 'activate' ) );
