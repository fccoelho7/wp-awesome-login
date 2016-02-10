<?php
namespace WPA\Login;

if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class Setting
{
	/**
	 * Color Primary Hexa
	 *
	 * @since 1.0
	 * @var string
	 */
	private $color_primary;

	/**
	 * Color Secondary Hexa
	 *
	 * @since 1.0
	 * @var string
	 */
	private $color_secondary;

	/**
	 * Color Tertiary Hexa
	 *
	 * @since 1.0
	 * @var string
	 */
	private $color_tertiary;

	/**
	 * Branding
	 *
	 * @since 1.0
	 * @var int
	 */
	private $branding;

	/**
	 * Branding Height
	 *
	 * @since 1.0
	 * @var int
	 */
	private $branding_height;

	/**
	 * Nonces
	 *
	 * @since 1.0
	 * @var string
	 */
	const NONCE_GENERAL_ACTION = 'wpal-setting-general-action';

	const NONCE_GENERAL_NAME   = 'wpal-setting-general-name';

	/**
	 * Options
	 *
	 * @since 1.0
	 * @var string
	 */
	const OPTION_COLOR_PRIMARY = 'wpal-color-primary';

	const OPTION_COLOR_SECONDARY = 'wpal-color-secondary';

	const OPTION_COLOR_TERTIARY = 'wpal-color-tertiary';

	const OPTION_BRANDING = 'wpal-branding';

	const OPTION_BRANDING_HEIGHT = 'wpal-branding-height';

	/**
	 * Methods
	 *
	 * @since 1.0
	 * @var string
	 */
	public function __get( $prop_name )
	{
		return $this->_get_property( $prop_name );
	}

	public function __set( $prop_name, $value )
	{
		return $this->_set_property( $prop_name, $value );
	}

	public function get_branding_url( $size = 'full' )
	{
		return Utils_Helper::get_thumbnail_url( $this->_get_property( 'branding' ), $size );
	}

	private function _set_property( $prop_name, $value )
	{
		switch ( $prop_name ) {
			case 'color_primary' :
				update_option( self::OPTION_COLOR_PRIMARY, $value );
				break;

			case 'color_secondary' :
				update_option( self::OPTION_COLOR_SECONDARY, $value );
				break;

			case 'color_tertiary' :
				update_option( self::OPTION_COLOR_TERTIARY, $value );
				break;

			case 'branding' :
				update_option( self::OPTION_BRANDING, $value );
				break;

			case 'branding_height' :
				update_option( self::OPTION_BRANDING_HEIGHT, $value );
				break;
		}
	}

	private function _get_property( $prop_name )
	{
		switch ( $prop_name ) :

			case 'color_primary' :
				if ( ! isset( $this->color_primary ) )
					$this->color_primary = get_option( self::OPTION_COLOR_PRIMARY, '#eeeeee' );
				break;

			case 'color_secondary' :
				if ( ! isset( $this->color_secondary ) )
					$this->color_secondary = get_option( self::OPTION_COLOR_SECONDARY, '#00a0d2' );
				break;

			case 'color_tertiary' :
				if ( ! isset( $this->color_tertiary ) )
					$this->color_tertiary = get_option( self::OPTION_COLOR_TERTIARY, '#777' );
				break;

			case 'branding' :
				if ( ! isset( $this->branding ) )
					$this->branding = get_option( self::OPTION_BRANDING );
				break;

			case 'branding_height' :
				if ( ! isset( $this->branding_height ) )
					$this->branding_height = get_option( self::OPTION_BRANDING_HEIGHT, 81 );
				break;

			default :
				return false;
				break;
		endswitch;

		return $this->$prop_name;
	}
}
