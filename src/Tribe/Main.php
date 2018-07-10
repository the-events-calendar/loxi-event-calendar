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
 * @since TBD
 */
class Tribe__Loxi__Main {

	/**
	 * Version number.
	 *
	 * @since TBD
	 */
	const VERSION = '0.1.0';

	/**
	 * Domain name.
	 *
	 * @since TBD
	 */
	const DOMAIN = 'loxi.io';

	/**
	 * Static Singleton Holder
	 *
	 * @var self
	 *
	 * @since TBD
	 */
	protected static $instance;

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 *
	 * @return self
	 *
	 * @since TBD
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
	 * @since TBD
	 */
	protected function __construct() {

		add_action( 'init', array( $this, 'init' ) );

	}

	/**
	 * Initialize plugin functionality.
	 *
	 * @since TBD
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
