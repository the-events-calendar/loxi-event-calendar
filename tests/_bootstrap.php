<?php
//require_once '../vendor/bin/autoload.php';

// This is global bootstrap for autoloading
Codeception\Util\Autoload::addNamespace( 'Tribe\Loxi\Test', __DIR__ . '/_support' );
Codeception\Util\Autoload::addNamespace( 'Tribe\Loxi\Test', __DIR__ . '/_support/classes' );
Codeception\Util\Autoload::addNamespace( 'Tribe\Loxi\Test\Acceptance\Steps', __DIR__ . '/acceptance/_steps' );
