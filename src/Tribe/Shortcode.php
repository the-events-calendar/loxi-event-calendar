<?php
/**
 * Loxi Shortcode Class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Loxi Shortcode Class.
 *
 * @since 1.0
 */
class Tribe__Loxi__Shortcode {

	/**
	 * Shortcode tag for this shortcode.
	 *
	 * @since 1.0
	 */
	const SHORTCODE_TAG = 'loxi';

	/**
	 * Register shortcode.
	 *
	 * @since 1.0
	 */
	public static function register() {

		add_shortcode( self::SHORTCODE_TAG, array( get_called_class(), 'render' ) );

	}

	/**
	 * Render shortcode.
	 *
	 * @param array $attributes Shortcode attributes.
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	public static function render( $attributes = array() ) {

		$defaults = array(
			'calendar'              => null,
			'subdomain'             => null,
			'color'                 => null,
			'default-layout'        => null,
			'show-category-filter'  => null,
			'show-location-address' => null,
			'show-location-filter'  => null,
			'show-search-filter'    => null,
			'show-view-switcher'    => null,
			'categories'            => null,
			'venue'                 => null,
		);

		$attributes = shortcode_atts( $defaults, $attributes, self::SHORTCODE_TAG );

		// Alias calendar to subdomain
		if ( ! empty( $attributes['calendar'] ) ) {
			$attributes['subdomain'] = $attributes['calendar'];

			unset( $attributes['calendar'] );
		}

		if ( empty( $attributes['subdomain'] ) ) {
			return '';
		}

		$loxi_domain = Tribe__Loxi__Main::DOMAIN;

		// Set special domain if we are testing.
		if ( defined( 'TRIBE_LOXI_SUBDOMAIN' ) && TRIBE_LOXI_SUBDOMAIN ) {
			$loxi_domain = sanitize_key( TRIBE_LOXI_SUBDOMAIN ) . '.' . $loxi_domain;
		}

		$subdomain_url = sprintf(
			'https://%1$s.%2$s',
			sanitize_key( $attributes['subdomain'] ),
			$loxi_domain
		);

		$html_attributes = self::build_html_attributes( $attributes );

		// translators: View more events on Loxi.io
		$loxi_text = esc_html_x( '%1$s on %2$s', 'View more events on Loxi.io', 'loxi-event-calendar' );

		$view_more_text = __( 'View more events', 'loxi-event-calendar' );

		// Build view more text link.
		$view_more_text_link = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $subdomain_url ),
			esc_html( $view_more_text )
		);

		// Build loxi text link.
		$loxi_text_link = sprintf(
			$loxi_text,
			$view_more_text_link,
			'<a href="https://loxi.io">Loxi.io</a>'
		);

		$html_markup = '
			<p %1$s>
				%2$s
			</p>
			<script async src="%3$s"></script>
		';

		// Build final markup.
		$output = sprintf(
			$html_markup,
			implode( ' ', $html_attributes ),
			$loxi_text_link,
			esc_url( $subdomain_url . '/embed/client.js' )
		);

		/**
		 * Filter the shortcode output.
		 *
		 * @param string $output          Shortcode output.
		 * @param array  $attributes      Shortcode attributes.
		 * @param array  $html_attributes Attributes to render as HTML tag attributes.
		 *
		 * @since 1.0
		 */
		$output = apply_filters( 'tribe_loxi_shortcode_output', $output, $attributes, $html_attributes );

		return $output;

	}

	/**
	 * Build HTML data attributes from an array of attribute values.
	 *
	 * @param array $data_attributes Attributes to render as HTML data attributes.
	 *
	 * @return array HTML data attributes.
	 *
	 * @since 1.0
	 */
	public static function build_html_attributes( $data_attributes = array() ) {

		$html_attributes = array(
			'class="loxi"',
		);

		foreach ( $data_attributes as $attribute => $value ) {
			// Skip null values.
			if ( null === $value ) {
				continue;
			}

			// Clean up the text.
			$attribute = sanitize_text_field( $attribute );
			$value     = sanitize_text_field( $value );

			// Skip empty string values.
			if ( '' === $value ) {
				continue;
			}

			// Add attributes as data attributes.
			$html_attributes[] = sprintf(
				'data-%1$s="%2$s"',
				sanitize_title( $attribute ),
				esc_attr( $value )
			);
		}

		return $html_attributes;

	}

}
