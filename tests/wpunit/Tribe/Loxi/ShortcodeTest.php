<?php

namespace Tribe\Loxi;

use Codeception\TestCase\WPTestCase;
use Tribe__Loxi__Shortcode;
use Spatie\Snapshots\MatchesSnapshots;
use tad\WPBrowser\Snapshot\WPHtmlOutputDriver;

/**
 * Class ShortcodeTest
 */
class ShortcodeTest extends WPTestCase {
    use MatchesSnapshots;

    public function setUp() {
        // before
        parent::setUp();

        // snapshots
        $this->driver = new WPHtmlOutputDriver( home_url(), 'http://tribe.dev' );
    }

	/**
	 * It should register the [loxi] shortcode
	 *
	 * @test
	 */
	public function it_should_register_the_loxi_shortcode() {
		Tribe__Loxi__Shortcode::register();

		$this->assertTrue( shortcode_exists( Tribe__Loxi__Shortcode::SHORTCODE_TAG ) );
	}

	/**
	 * It should render the [loxi] shortcode with invalid attributes
	 *
	 * @test
	 */
	public function it_should_render_the_loxi_shortcode_with_invalid_attributes() {
		Tribe__Loxi__Shortcode::register();

		$output = do_shortcode( '[loxi]' );

		$this->assertEquals( '', $output );
	}

	/**
	 * It should render the [loxi] shortcode
	 *
	 * @test
	 */
	public function it_should_render_the_loxi_shortcode() {
		Tribe__Loxi__Shortcode::register();

		$output = do_shortcode( '[loxi subdomain="wpshindig"]' );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'https://wpshindig.loxi.io', $output );
        $this->assertContains( 'data-subdomain="wpshindig"', $output );
	}

	/**
	 * It should render the [loxi] shortcode using calendar
	 *
	 * @test
	 */
	public function it_should_render_the_loxi_shortcode_using_calendar() {
		Tribe__Loxi__Shortcode::register();

		$output = do_shortcode( '[loxi calendar="wpshindig"]' );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'https://wpshindig.loxi.io', $output );
        $this->assertContains( 'data-subdomain="wpshindig"', $output );
	}

	/**
	 * It should render the [loxi] shortcode using calendar not subdomain
	 *
	 * @test
	 */
	public function it_should_render_the_loxi_shortcode_using_calendar_not_subdomain() {
		Tribe__Loxi__Shortcode::register();

		$output = do_shortcode( '[loxi calendar="wpshindig" subdomain="somethingelse"]' );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'https://wpshindig.loxi.io', $output );
        $this->assertContains( 'data-subdomain="wpshindig"', $output );
        $this->assertNotContains( 'data-subdomain="somethingelse"', $output );
	}

	/**
	 * It should render the [loxi] shortcode using all attributes
	 *
	 * @test
	 */
	public function it_should_render_the_loxi_shortcode_using_all_attributes() {
		Tribe__Loxi__Shortcode::register();

		$shortcode = '[loxi calendar="wpshindig"'
			. ' color="#F7F7F7"'
			. ' default-layout="list"'
			. ' show-category-filter="0"'
			. ' show-location-address="1"'
			. ' show-location-filter="1"'
			. ' show-search-filter="1"'
			. ' show-view-switcher="1"'
			. ' categories="all"'
			. ' venue="all"]';

		$output = do_shortcode( $shortcode );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'https://wpshindig.loxi.io', $output );
        $this->assertContains( 'data-subdomain="wpshindig"', $output );
        $this->assertContains( 'data-color="#F7F7F7"', $output );
        $this->assertContains( 'data-default-layout="list"', $output );
        $this->assertContains( 'data-show-category-filter="0"', $output );
        $this->assertContains( 'data-show-location-address="1"', $output );
        $this->assertContains( 'data-show-location-filter="1"', $output );
        $this->assertContains( 'data-show-search-filter="1"', $output );
        $this->assertContains( 'data-show-view-switcher="1"', $output );
        $this->assertContains( 'data-categories="all"', $output );
        $this->assertContains( 'data-venue="all"', $output );
	}

	/**
	 * It should render the [loxi] shortcode with unsupported attributes
	 *
	 * @test
	 */
	public function it_should_render_the_loxi_shortcode_with_unsupported_attributes() {
		Tribe__Loxi__Shortcode::register();

		$output = do_shortcode( '[loxi subdomain="wpshindig" doesnotexist="1234"]' );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'https://wpshindig.loxi.io', $output );
        $this->assertContains( 'data-subdomain="wpshindig"', $output );
        $this->assertNotContains( 'data-doesnotexist="1234"', $output );
	}

	/**
	 * It should build HTML attributes and sanitize them
	 *
	 * @test
	 */
	public function it_should_build_html_attributes_and_sanitize_them() {
		$attributes = array(
			'subdomain'        => 'wpshindig',
			'show_something'   => '0',
			'show_something2'  => 0,
			'whatever'         => 'like omg! "totally"',
			'something2'       => 'with \\slashes/ and " \' $ & @ ! ∑´∆ £¢ special characters',
			'whoa there mate!' => 'harsh',
			'nothingtoseehere' => null,
			'nopenopenope'     => '',
		);

		$html_attributes = Tribe__Loxi__Shortcode::build_html_attributes( $attributes );

		$this->assertContains( 'class="loxi"', $html_attributes );
		$this->assertContains( 'data-subdomain="wpshindig"', $html_attributes );
		$this->assertContains( 'data-show_something="0"', $html_attributes );
		$this->assertContains( 'data-show_something2="0"', $html_attributes );
		$this->assertContains( 'data-whatever="like omg! &quot;totally&quot;"', $html_attributes );
		$this->assertContains( 'data-something2="with \\slashes/ and &quot; &#039; $ &amp; @ ! ∑´∆ £¢ special characters"', $html_attributes );
		$this->assertContains( 'data-whoa-there-mate="harsh"', $html_attributes );
		$this->assertNotContains( 'data-nothingtoseehere=""', $html_attributes );
		$this->assertNotContains( 'data-nopenopenope=""', $html_attributes );
	}
}
