<?php
namespace HivePress\Components;

use HivePress\Blocks\Part;
use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Emails;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Component class.
 */
final class Family_Members extends Component
{

	/**
	 * Class constructor.
	 *
	 * @param array $args Component arguments.
	 */
	public function __construct($args = [])
	{

		add_filter('hivepress/v1/components/request/context', [$this, 'set_request_context']);
		add_filter('hivepress/v1/menus/user_account', [$this, 'add_menu_item']);
		add_filter('hivetheme/v1/areas/site_header', [$this, 'add_header_button']);
		// add_filter('hivepress/v1/styles', [$this, 'add_styles']);
		// add_filter( 'hivepress/v1/templates/listing_view_block', [ $this, 'alter_listing_view_block' ] );
		add_filter('hivepress/v1/templates/listing_view_page', [$this, 'alter_listing_view_page']);
		parent::__construct($args);
	}

	// Implement the attached functions here.
	/**
	 * Sets request context for pages.
	 *
	 * @param array $context Context values.
	 * @return array
	 */
	public function set_request_context($context)
	{

		// Get user ID.
		$user_id = get_current_user_id();

		// Get cached vendor IDs.
		$family_members = hivepress()->cache->get_user_cache($user_id, 'user_family_members', 'models/family_member');
		$member_ids = [];
		if (is_null($family_members)) {

			// Get follows.
			$members = Models\Family_Member::query()->filter(
				[
					'family_owner' => $user_id,
				]
			)->get();



			foreach ($members as $member) {
				$member_ids[] = $member->get_id();
			}

			// Cache vendor IDs.
			hivepress()->cache->set_user_cache($user_id, 'user_family_members', 'models/family_member', $member_ids);
		}

		// Set request context.
		$context['user_family_members'] = $member_ids;

		return $context;
	}

	/**
	 * Adds menu item to user account.
	 *
	 * @param array $menu Menu arguments.
	 * @return array
	 */
	public function add_menu_item($menu)
	{
		// if (hivepress()->request->get_context('user_family_members')) {
		$menu['items']['listings_feed'] = [
			'label' => esc_html__('Family Members', 'hivepress'),
			'_order' => 20,
			'route' => 'user_family_panel_page',
			// 'url' => hivepress()->router->get_url('user_family_page'),
		];
		// }

		return $menu;
	}


	/**
	 * Adds header button.
	 *
	 * @return string
	 */
	public function add_header_button()
	{
		return (
			new Part(
				[
					'type' => 'part',
					'path' => 'navbar/register-family-link',
					'_order' => 10,
				]
			)
		)->render();
	}


	// public function add_styles($styles)
	// {
	// 	$styles['family_members'] = [
	// 		'path' => 'family-members.css',
	// 		'dependencies' => [],
	// 	];

	// 	return $styles;
	// }



	// /**
	//  * Alters listing view block.
	//  *
	//  * @param array $template Template arguments.
	//  * @return array
	//  */
	// public function alter_listing_view_block( $template ) {
	// 	return hp\merge_trees(
	// 		$template,
	// 		[
	// 			'blocks' => [
	// 				'listing_actions_primary' => [
	// 					'blocks' => [
	// 						// 'message_send_modal' => [
	// 						// 	'type'        => 'modal',
	// 						// 	'model'       => 'listing',
	// 						// 	'title'       => hivepress()->translator->get_string( 'reply_to_listing' ),
	// 						// 	'_capability' => 'read',
	// 						// 	'_order'      => 5,

	// 						// 	'blocks'      => [
	// 						// 		'message_send_form' => [
	// 						// 			'type'   => 'message_send_form',
	// 						// 			'_order' => 10,
	// 						// 		],
	// 						// 	],
	// 						// ],

	// 						'message_send_link'  => [
	// 							'type'   => 'part',
	// 							'path'   => 'listing/join-lisitng-link',
	// 							'_order' => 10,
	// 						],
	// 					],
	// 				],
	// 			],
	// 		]
	// 	);
	// }

	/**
	 * Alters listing view page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_listing_view_page($template)
	{
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'listing_actions_primary' => [
						'blocks' => [
							// 'message_send_modal' => [
							// 	'type'        => 'modal',
							// 	'model'       => 'listing',
							// 	'title'       => hivepress()->translator->get_string( 'reply_to_listing' ),
							// 	'_capability' => 'read',
							// 	'_order'      => 5,

							// 	'blocks'      => [
							// 		'message_send_form' => [
							// 			'type'   => 'message_send_form',
							// 			'_order' => 10,
							// 		],
							// 	],
							// ],
							'join_listing_form' => [
								'type' => 'form',
								'form' => 'join_listing',
							],

							// 'message_send_link'  => [
							// 	'type'   => 'part',
							// 	'path'   => 'listing/join-lisitng-link',
							// 	'_order' => 10,
							// ],
						],
					],
				],
			]
		);
	}

}