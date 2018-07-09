<?php
/**
 * Main Tribe Loxi class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__Loxi__Main' ) ) {

	/**
	 * Main Tribe Loxi class.
	 */
	class Tribe__Loxi__Main {

		/**
		 * Version number.
		 */
		const VERSION = '0.1.0';

		/**
		 * Static Singleton Holder
		 *
		 * @var self
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
			$loxi_domain = 'loxi.io';

			// Set special domain if we are testing.
			if ( defined( 'TRIBE_LOXI_SUBDOMAIN' ) && TRIBE_LOXI_SUBDOMAIN ) {
				$loxi_domain = sanitize_key( TRIBE_LOXI_SUBDOMAIN ) . '.loxi.io';
			}

			// Add oembed provider.
			wp_oembed_add_provider( 'https://*.' . $loxi_domain, 'https://' . $loxi_domain . '/api/saas/v1/oembed' );
			wp_oembed_add_provider( 'https://*.' . $loxi_domain . '/*', 'https://' . $loxi_domain . '/api/saas/v1/oembed' );

		}

	}

} // end if !class_exists Tribe__Loxi__Main
