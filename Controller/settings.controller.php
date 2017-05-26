<?php
namespace WPA\Login;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

App::uses( 'settings', 'View' );
App::uses( 'setting', 'Model' );

class Settings_Controller
{
	public function __construct()
	{
		add_action( 'admin_menu', array( &$this, 'menu' ) );
		add_action( 'wp_ajax_general_save_settings', array( &$this, 'save' ) );
		add_filter( 'upload_mimes', array( &$this, 'available_svg_upload' ) );
		add_action( 'login_head', array( 'WPA\Login\Settings_View', 'render_config_css_inline' ) );
		add_filter( 'login_headerurl', array( &$this, 'custom_login_header_url' ) );
		add_filter( 'plugin_action_links_' . App::plugin_basename_file(), array( &$this, 'add_action_links' ) );
	}

	public function add_action_links( $links )
	{
		$links[] = '<a href="' . admin_url( 'themes.php?page=wpal-settings-theme' ) . '">' . __( 'Settings', App::PLUGIN_SLUG ) . '</a>';
		return $links;
	}

	public function custom_login_header_url( $url ) {
		return home_url( '/' );
	}

	public function available_svg_upload( $svg_mime )
	{
    	$svg_mime['svg'] = 'image/svg+xml';
    	return $svg_mime;
	}

	public function menu()
	{
		add_theme_page(
			App::PLUGIN_NAME,
			App::PLUGIN_NAME,
			'manage_options',
			Setting::PAGE_SLUG,
			array( __NAMESPACE__ . '\Settings_View', 'render_general' )
		);
	}

	public function save()
	{
		if ( ! Utils_Helper::verify_nonce_post( Setting::NONCE_GENERAL_NAME, Setting::NONCE_GENERAL_ACTION ) ) :
			Utils_Helper::error_server_json( 'not_permission_nonce' );
			http_response_code( 511 );
			exit(0);
		endif;

		$this->save_fields();

		Utils_Helper::success_server_json( 'config_save_success', 'Operação realizada com sucesso.' );
		exit(1);
	}

	public function save_fields()
	{
		$model                   = new Setting();
		$model->color_primary    = Utils_Helper::post( Setting::OPTION_COLOR_PRIMARY, false, 'esc_html' );
		$model->color_secondary  = Utils_Helper::post( Setting::OPTION_COLOR_SECONDARY, false, 'esc_html' );
		$model->color_button_txt = Utils_Helper::post( Setting::OPTION_COLOR_BUTTON_TXT, false, 'esc_html' );
		$model->color_txt        = Utils_Helper::post( Setting::OPTION_COLOR_TXT, false, 'esc_html' );
		$model->color_form_txt   = Utils_Helper::post( Setting::OPTION_COLOR_FORM_TXT, false, 'esc_html' );
		$model->color_tertiary   = Utils_Helper::post( Setting::OPTION_COLOR_TERTIARY, false, 'esc_html' );
		$model->branding         = Utils_Helper::post( Setting::OPTION_BRANDING, false, 'intval' );
		$model->branding_height  = Utils_Helper::post( Setting::OPTION_BRANDING_HEIGHT, false, 'intval' );
	}
}
