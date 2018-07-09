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

		$output = do_shortcode( '[loxi subdomain="awesome"]' );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'https://awesome.loxi.io', $output );
	}

	/**
	 * It should render the [loxi] shortcode using calendar
	 *
	 * @test
	 */
	public function it_should_render_the_loxi_shortcode_using_calendar() {
		Tribe__Loxi__Shortcode::register();

		$output = do_shortcode( '[loxi calendar="awesome"]' );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'https://awesome.loxi.io', $output );
	}

	/**
	 * It should render the [loxi] shortcode with unsupported attributes
	 *
	 * @test
	 */
	public function it_should_render_the_loxi_shortcode_with_unsupported_attributes() {
		Tribe__Loxi__Shortcode::register();

		$output = do_shortcode( '[loxi subdomain="awesome" doesnotexist="1234"]' );

        $this->assertMatchesSnapshot( $output, $this->driver );

        $this->assertContains( 'https://awesome.loxi.io', $output );
        $this->assertNotContains( 'doesnotexist="1234"', $output );
	}

	/**
	 * It should build HTML attributes and sanitize them
	 *
	 * @test
	 */
	public function it_should_build_html_attributes_and_sanitize_them() {
		$attributes = array(
			'subdomain'        => 'awesome',
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
		$this->assertContains( 'subdomain="awesome"', $html_attributes );
		$this->assertContains( 'show_something="0"', $html_attributes );
		$this->assertContains( 'show_something2="0"', $html_attributes );
		$this->assertContains( 'whatever="like omg! &quot;totally&quot;"', $html_attributes );
		$this->assertContains( 'something2="with \\slashes/ and &quot; &#039; $ &amp; @ ! ∑´∆ £¢ special characters"', $html_attributes );
		$this->assertContains( 'whoa-there-mate="harsh"', $html_attributes );
		$this->assertNotContains( 'nothingtoseehere=""', $html_attributes );
		$this->assertNotContains( 'nopenopenope=""', $html_attributes );
	}
}
