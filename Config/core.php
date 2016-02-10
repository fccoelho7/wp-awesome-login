<?php
namespace WPA\Login;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

App::uses( 'utils', 'Helper' );
App::uses( 'settings', 'Controller' );

class Core
{
	public function __construct()
	{
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts_admin' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'styles_admin' ) );
		add_action( 'login_enqueue_scripts', array( &$this, 'scripts_login' ) );
		add_action( 'login_head', array( &$this, 'styles_login' ) );
		add_action( 'plugins_loaded', array( 'WPA\Login\App', 'load_textdomain' ) );

		$settings = new Settings_Controller();
	}

	public function activate()
	{

	}

	public function styles_login()
	{
		$url = App::plugins_url( '/login.style.css' ) . '?ver=' . App::filemtime( 'login.style.css' );

		echo '<link rel="stylesheet" id="custom-login-css" href="' . esc_url( $url ) . '" type="text/css" media="all" />';
	}

	public function scripts_login()
	{
		wp_enqueue_script(
			'custom-login-' . App::PLUGIN_SLUG,
			App::plugins_url( '/assets/javascripts/login.built.js' ),
			array(),
			App::filemtime( 'assets/javascripts/login.built.js' ),
			true
		);
	}

	public function scripts_admin()
	{
		$this->load_wp_media();

		wp_enqueue_script(
			'admin-script-' . App::PLUGIN_SLUG,
			App::plugins_url( '/assets/javascripts/admin.built.js' ),
			array( 'jquery', 'wp-color-picker' ),
			App::filemtime( 'assets/javascripts/admin.built.js' ),
			true
		);
	}

	public function styles_admin()
	{
		wp_enqueue_style(
			'admin-style-' . App::PLUGIN_SLUG,
			App::plugins_url( '/admin.style.css' ),
			array(),
			App::filemtime( 'admin.style.css' )
		);
	}

	protected function load_wp_media()
	{
		global $pagenow;

		if ( did_action( 'wp_enqueue_media' ) )
			return;

		if ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'themes.php' ) ) )
			wp_enqueue_media();
	}
}
