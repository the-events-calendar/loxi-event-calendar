<?php

namespace Tribe\Loxi;

use Codeception\TestCase\WPTestCase;
use Tribe__Loxi__Main;
use Tribe__Loxi__Shortcode;
use Spatie\Snapshots\MatchesSnapshots;
use tad\WPBrowser\Snapshot\WPHtmlOutputDriver;

/**
 * Class MainTest
 */
class MainTest extends WPTestCase {
    use MatchesSnapshots;

    public function setUp() {
        // before
        parent::setUp();

        // snapshots
        $this->driver = new WPHtmlOutputDriver( home_url(), 'http://tribe.dev' );
    }

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
	 * It should embed the url for single event
	 *
	 * @test
	 */
	public function it_should_embed_the_url_for_single_event() {
		$sut = $this->make_instance();

		$sut->init();

		$content = '
Some cool stuff below.

Here is the calendar, check it out:

https://wpshindig.loxi.io/wordpress-denver-happiness-hour-8-1264
		';

		$output = apply_filters( 'the_content', $content, 0 );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'subdomain="wpshindig"', $output );
	}

	/**
	 * It should embed the url for list
	 *
	 * @test
	 */
	public function it_should_embed_the_url_for_list() {
		$sut = $this->make_instance();

		$sut->init();

		$content = '
Some cool stuff below.

Here is the calendar, check it out:

https://wpshindig.loxi.io/list/2018/7
		';

		$output = apply_filters( 'the_content', $content, 0 );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'subdomain="wpshindig"', $output );
	}

	/**
	 * It should embed the url for home
	 *
	 * @test
	 */
	public function it_should_embed_the_url_for_home() {
		$sut = $this->make_instance();

		$sut->init();

		$content = '
Some cool stuff below.

Here is the calendar, check it out:

https://wpshindig.loxi.io
		';

		$output = apply_filters( 'the_content', $content, 0 );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'subdomain="wpshindig"', $output );
	}

	/**
	 * It should embed the url for home with slash
	 *
	 * @test
	 */
	public function it_should_embed_the_url_for_home_with_slash() {
		$sut = $this->make_instance();

		$sut->init();

		$content = '
Some cool stuff below.

Here is the calendar, check it out:

https://wpshindig.loxi.io/
		';

		$output = apply_filters( 'the_content', $content, 0 );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'subdomain="wpshindig"', $output );
	}

	/**
	 * @return Tribe__Loxi__Main
	 */
	protected function make_instance() {
		return Tribe__Loxi__Main::instance();
	}
}
