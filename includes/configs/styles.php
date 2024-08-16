<?php
/**
 * Styles configuration.
 *
 * @package HivePress\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'event_registration_backend'  => [
		'handle'  => 'hivepress-event_registration-backend',
		'src'     => hivepress()->get_url( 'event_registration' ) . '/assets/css/backend.min.css',
		'version' => hivepress()->get_version( 'event_registration' ),
		'scope'   => 'backend',
	],

	'event_registration_frontend' => [
		'handle'  => 'hivepress-event_registration-frontend',
		'src'     => hivepress()->get_url( 'event_registration' ) . '/assets/css/frontend.min.css',
		'version' => hivepress()->get_version( 'event_registration' ),
		'scope'   => [ 'frontend', 'editor' ],
	],
];
