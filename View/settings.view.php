<?php
namespace WPA\Login;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class Settings_View
{
	public static function render_general()
	{
		$model = new Setting();

		?>
		<div class="wrap">
			<h2>WP Awesome Login</h2>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-component="form">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="wpal-field-primary"><?php _e( 'Background Color', App::PLUGIN_SLUG ); ?></label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#eeeeee"
				   		               type="text" id="wpal-field-primary"
								       name="<?php echo Setting::OPTION_COLOR_PRIMARY; ?>"
									   value="<?php echo esc_html( $model->color_primary ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpal-field-secondary"><?php _e( 'Color Button', App::PLUGIN_SLUG ); ?></label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#00a0d2"
				   		               type="text" id="wpal-field-secondary"
								       name="<?php echo Setting::OPTION_COLOR_SECONDARY; ?>"
									   value="<?php echo esc_html( $model->color_secondary ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpal-field-secondary"><?php _e( 'Color Button Text', App::PLUGIN_SLUG ); ?></label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#ffffff"
				   		               type="text" id="wpal-color-button-txt"
								       name="<?php echo Setting::OPTION_COLOR_BUTTON_TXT; ?>"
									   value="<?php echo esc_html( $model->color_button_txt ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpal-field-secondary"><?php _e( 'Color Text', App::PLUGIN_SLUG ); ?></label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#00a0d2"
				   		               type="text" id="wpal-color-txt"
								       name="<?php echo Setting::OPTION_COLOR_TXT; ?>"
									   value="<?php echo esc_html( $model->color_txt ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpal-field-secondary"><?php _e( 'Color Form Text', App::PLUGIN_SLUG ); ?></label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#00a0d2"
				   		               type="text" id="wpal-color-form-txt"
								       name="<?php echo Setting::OPTION_COLOR_FORM_TXT; ?>"
									   value="<?php echo esc_html( $model->color_form_txt ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpal-field-tertiary"><?php _e( 'Border Color', App::PLUGIN_SLUG ); ?></label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#777"
				   		               type="text" id="wpal-field-tertiary"
								       name="<?php echo Setting::OPTION_COLOR_TERTIARY; ?>"
									   value="<?php echo esc_html( $model->color_tertiary ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label><?php _e( 'Logo Image', App::PLUGIN_SLUG ); ?></label>
							</th>
							<td>
								<button data-component="upload"
								        data-attr-hidden-name="<?php echo Setting::OPTION_BRANDING; ?>"
								        data-attr-hidden-value="<?php echo esc_attr( $model->branding ); ?>"
								        data-attr-image-src="<?php echo esc_url( $model->get_branding_url( 'medium' ) ); ?>"
							            data-attr-image-position="before"
										data-attr-remove-text="<?php echo esc_attr_e( 'Remove Logo', App::PLUGIN_SLUG ); ?>"
								        class="button" type="button"><?php _e( 'Set Image', App::PLUGIN_SLUG ); ?></button>

								<p class="description"><?php _e( 'The image should be the size of 320px wide for a better use of space.', App::PLUGIN_SLUG ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpal-field-branding-height"><?php _e( 'Image Height', App::PLUGIN_SLUG ); ?></label>
							</th>
							<td>
								<input type="number" id="wpal-field-branding-height"
								       name="<?php echo Setting::OPTION_BRANDING_HEIGHT; ?>"
									   value="<?php echo intval( $model->branding_height ); ?>">

								<p class="description"><?php printf( __( 'Set the height of the image according to your logo, the value is defined in %spixels%s.', App::PLUGIN_SLUG  ), '<strong>', '</strong>' ) ?></p>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit">
					<?php
						wp_nonce_field(
							Setting::NONCE_GENERAL_ACTION,
							Setting::NONCE_GENERAL_NAME
						);
					?>

					<input type="hidden" name="action" value="general_save_settings">
					<input type="submit" class="button button-primary" value="<?php echo esc_attr_e( 'Save', App::PLUGIN_SLUG ); ?>" data-element="submit">
				</p>
			</form>
		</div>
		<?php
	}

	public static function render_config_css_inline()
	{
		$model               = new Setting();
		$color_primary       = htmlentities( $model->color_primary );
		$color_secondary     = htmlentities( $model->color_secondary );
		$color_button_txt    = htmlentities( $model->color_button_txt );
		$color_txt           = htmlentities( $model->color_txt );
		$color_form_txt      = htmlentities( $model->color_form_txt );
		$color_tertiary      = htmlentities( $model->color_tertiary );
		$branding_url        = htmlentities( $model->get_branding_url() );
		$branding_height     = htmlentities( $model->branding_height );
		$margin_top          = round( $branding_height / 3, 2 ) * -1;

		echo "
		<style type=\"text/css\">
			html, body {
				background-color: {$color_primary} !important;
			}

			#lostpasswordform p label,			
			#loginform p label,
			#loginform p.forgetmenot label,
			#login p#nav a,
			#login p#backtoblog a,
			#loginform p.cptch_block {
				color: {$color_txt} !important;
			}

			#lostpasswordform input#user_login,
			#loginform input#user_login,
			#loginform input#user_pass, 
			#login form {
				color: {$color_form_txt} !important;
			}

			#lostpasswordform input#user_login,
			#loginform input#user_login,
			#loginform input#user_pass,
			#loginform p.forgetmenot label:before {
				border-color: {$color_tertiary} !important;
			}

			#lostpasswordform p.submit input#wp-submit,
			#loginform p.submit input#wp-submit {
				background-color: {$color_secondary} !important;
				border-color: {$color_secondary} !important;
				color: {$color_button_txt} !important;
			}

			#lostpasswordform p.submit input#wp-submit:hover,
			#loginform p.submit input#wp-submit:hover {
				color: {$color_secondary} !important;
			}
		</style>
		";

		if ( $branding_url ) :
			echo "
			<style type=\"text/css\">
				body.login #login h1 a {
					background: url({$branding_url}) no-repeat scroll center top transparent;
					width: 320px;
					height: {$branding_height}px;
					margin: {$margin_top}px auto 30px;
					background-size: auto 100%;
				}
			</style>
			";
		endif;
	}
}