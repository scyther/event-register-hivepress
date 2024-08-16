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
	public function add_header_button() {
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

	/**
	 * Renders family page.
	 *
	 * @return string
	 */
	public function render_family_page()
	{

		// Create listing query.
		$query = Models\Family_Member::query()->filter(
			[
				'family_member__in' => hivepress()->request->get_context('user_family_members'),
			]
		)->order(['created_date' => 'desc'])
			->limit(get_option('hp_listings_per_page'))
			->paginate(hivepress()->request->get_page_number());

		// Set request context.
		hivepress()->request->set_context(
			'post_query',
			$query->get_args()
		);

		// Render page template.
		return (
			new Blocks\Template(
				[
					'template' => 'user_family_panel_page',

					'context' => [
						'listings' => [],
					],
				]
			)
		)->render();
	}

	/**
	 * Follows or unfollows vendor.
	 *
	 * @param \WP_REST_Request $request API request.
	 * @return \WP_Rest_Response \WP_Rest_Response
	 */
	public function add_family_member($request)
	{

		// Check authentication.
		if (!is_user_logged_in()) {
			return hp\rest_error(401);
		}

		$userId = get_current_user_id();
		$name = $request->get_param('name');
		$age = $request->get_param('age');
		$relation = $request->get_param('relation');

		// Create model.
		$family_member = new Models\Family_Member(
			[
				'family_owner' => $userId,
				'member_name' => $name,
				'age' => $age,
				'relation' => $relation,
			]
		);

		// Save model.
		$family_member->save();
		return hp\rest_success(
			[
				'message' => esc_html__('Family member has been added', 'hivepress'),
			]
		);
	}
}