<?php

namespace Tribe\Loxi;

use Codeception\TestCase\WPTestCase;
use Tribe__Loxi__Main;
use Tribe__Loxi__Shortcode;

/**
 * Class MainTest
 */
class MainTest extends WPTestCase {
	/**
	 * It should hook into the init action
	 * @test
	 */
	public function it_should_hook_into_the_init_action() {
		$sut = $this->make_instance();

		$this->assertNotFalse( has_action( 'init', array( $sut, 'init' ) ) );
	}

	/**
	 * It should register the [loxi] shortcode
	 *
	 * @test
	 */
	public function it_should_register_the_loxi_shortcode() {
		$sut = $this->make_instance();

		$sut->init();

		$this->assertTrue( shortcode_exists( Tribe__Loxi__Shortcode::SHORTCODE_TAG ) );
	}

	/**
	 * It should add the oembed provider
	 *
	 * @test
	 */
	public function it_should_add_the_oembed_provider() {
		$sut = $this->make_instance();

		$sut->init();

		$provider_existed = wp_oembed_remove_provider( 'https://*.loxi.io/*' );

		$this->assertTrue( $provider_existed );
	}

	/**
	 * @return Tribe__Loxi__Main
	 */
	protected function make_instance() {
		return Tribe__Loxi__Main::instance();
	}
}
