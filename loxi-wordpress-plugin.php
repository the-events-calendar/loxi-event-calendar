<?php
/*
Plugin Name: Loxi WordPress Plugin
Description: Loxi is awesome.
Version: 0.1
Author: Modern Tribe, Inc.
Author URI: https://loxi.io
Text Domain: loxi-wordpress-plugin
License: GPLv2 or later
*/

/*
Copyright 2018 by Modern Tribe Inc and the contributors

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( class_exists( 'Tribe__Loxi__Main' ) ) {
	return;
}

define( 'TRIBE_LOXI_PLUGIN_FILE', __FILE__ );
define( 'TRIBE_LOXI_PLUGIN_DIR', __DIR__ );

// The main plugin class.
require_once TRIBE_LOXI_PLUGIN_DIR . '/src/Tribe/Main.php';

Tribe__Loxi__Main::instance();
