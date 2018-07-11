<?php
/**
 * Main Tribe Loxi class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Main Tribe Loxi class.
 *
 * @since 1.0
 */
class Tribe__Loxi__Main {

	/**
	 * Version number.
	 *
	 * @since 1.0
	 */
	const VERSION = '1.0';

	/**
	 * Domain name.
	 *
	 * @since 1.0
	 */
	const DOMAIN = 'loxi.io';

	/**
	 * Static Singleton Holder
	 *
	 * @var self
	 *
	 * @since 1.0
	 */
	protected static $instance;

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 *
	 * @return self
	 *
	 * @since 1.0
	 */
	public static function instance() {

		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Set up WordPress hooks/actions.
	 *
	 * @since 1.0
	 */
	protected function __construct() {

		add_action( 'init', array( $this, 'init' ) );

	}

	/**
	 * Initialize plugin functionality.
	 *
	 * @since 1.0
	 */
	public function init() {

		require_once TRIBE_LOXI_PLUGIN_DIR . '/src/Tribe/Shortcode.php';

		// Register shortcode.
		Tribe__Loxi__Shortcode::register();

		// Get oembed loxi domain.
		$loxi_domain = self::DOMAIN;

		// Set special domain if we are testing.
		if ( defined( 'TRIBE_LOXI_SUBDOMAIN' ) && TRIBE_LOXI_SUBDOMAIN ) {
			$loxi_domain = sanitize_key( TRIBE_LOXI_SUBDOMAIN ) . '.' . $loxi_domain;
		}

		// Add oembed provider.
		wp_oembed_add_provider( 'https://*.' . $loxi_domain, 'https://' . $loxi_domain . '/api/saas/v1/oembed' );
		wp_oembed_add_provider( 'https://*.' . $loxi_domain . '/*', 'https://' . $loxi_domain . '/api/saas/v1/oembed' );

	}

}
