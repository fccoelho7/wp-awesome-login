<?php
namespace Apiki\Login;

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
			<h2>Configurar Tela de Login</h2>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-component="form">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="apiki-field-primary">Cor de Fundo</label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#eeeeee"
				   		               type="text" id="apiki-field-primary"
								       name="<?php echo Setting::OPTION_COLOR_PRIMARY; ?>"
									   value="<?php echo esc_html( $model->color_primary ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="apiki-field-secondary">Cor do Botão e Textos</label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#00a0d2"
				   		               type="text" id="apiki-field-secondary"
								       name="<?php echo Setting::OPTION_COLOR_SECONDARY; ?>"
									   value="<?php echo esc_html( $model->color_secondary ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="apiki-field-tertiary">Cor da Bordas</label>
							</th>
							<td>
								<input data-component="color-picker"
				   		               data-default-color="#777"
				   		               type="text" id="apiki-field-tertiary"
								       name="<?php echo Setting::OPTION_COLOR_TERTIARY; ?>"
									   value="<?php echo esc_html( $model->color_tertiary ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label>Imagem de Logo</label>
							</th>
							<td>
								<button data-component="upload"
								        data-attr-hidden-name="<?php echo Setting::OPTION_BRANDING; ?>"
								        data-attr-hidden-value="<?php echo esc_attr( $model->branding ); ?>"
								        data-attr-image-src="<?php echo esc_url( $model->get_branding_url( 'medium' ) ); ?>"
							            data-attr-image-position="before"
								        class="button" type="button">Configurar Imagem</button>

								<p class="description">A imagem deve ter o tamanho de 320px de largura para um melhor aproveitamento do espaço.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="apiki-field-branding-height">Altura da Imagem</label>
							</th>
							<td>
								<input type="number" id="apiki-field-branding-height"
								       name="<?php echo Setting::OPTION_BRANDING_HEIGHT; ?>"
									   value="<?php echo intval( $model->branding_height ); ?>">

								<p class="description">Defina a altura da imagem de acordo com sua logo, o valor será definido em <strong>pixel</strong>.</p>
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
					<input type="submit" class="button button-primary" value="Salvar" data-element="submit">
				</p>
			</form>
		</div>
		<?php	
	}

	public static function render_config_css_inline()
	{
		$model           = new Setting();
		$color_primary   = htmlentities( $model->color_primary );
		$color_secondary = htmlentities( $model->color_secondary );
		$color_tertiary  = htmlentities( $model->color_tertiary );
		$branding_url    = htmlentities( $model->get_branding_url() );
		$branding_height = htmlentities( $model->branding_height );
		$margin_top      = round( $branding_height / 3, 2 ) * -1;

		echo "
		<style type=\"text/css\">
			html, body {
				background-color: {$color_primary} !important;
			}
			
			#lostpasswordform p label,
			#lostpasswordform input#user_login,
			#loginform p label,
			#loginform input#user_login,
			#loginform input#user_pass,
			#loginform p.forgetmenot label,
			#login p#nav a,
			#login p#backtoblog a,
			#login form#loginform p.cptch_block {
				color: {$color_secondary} !important;
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
