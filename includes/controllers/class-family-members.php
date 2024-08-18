<?php
namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Blocks;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Controller class.
 */
final class Family_Members extends Controller
{

	/**
	 * Class constructor.
	 *
	 * @param array $args Controller arguments.
	 */
	public function __construct($args = [])
	{
		$args = hp\merge_arrays(
			[
				'routes' => [
					'user_family_panel_page' => [
						'title' => esc_html__('Family', 'Panel'),
						'base' => 'user_account_page',
						'path' => '/family',
						'redirect' => [$this, 'redirect_family_page'],
						'action' => [$this, 'render_family_page'],
						'paginated' => true,
					],
					'add_family_member_action' => [
						'base' => 'user_account_page',
						'path' => '/add-family-member',
						'method' => 'POST',
						'action' => [$this, 'add_family_member'],
						'rest' => true,
					],
					'get_family_members_action' => [
						'base' => 'user_account_page',
						'path' => '/get-family-members',
						'method' => 'GET',
						'action' => [$this, 'get_family_members'],
						'rest' => true,
					],

				],

			],
			$args
		);

		parent::__construct($args);
	}

	// Implement the route actions here.

	/**
	 * Redirects listing feed page.
	 *
	 * @return mixed
	 */
	public function redirect_family_page()
	{

		// Check authentication.
		if (!is_user_logged_in()) {
			return hivepress()->router->get_return_url('user_login_page');
		}

		// // Check followed vendors.
		// if (!hivepress()->request->get_context('vendor_follow_ids')) {
		// 	return hivepress()->router->get_url('user_account_page');
		// }

		return false;
	}

	/**
	 * Renders listing feed page.
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
					  'family_members' => $query->get(),
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
		$member_name = $request->get_param('member_name');
		$age = $request->get_param('age');
		$relation = $request->get_param('relation');


		// Get Members.
		$members = Models\Family_Member::query()->filter(
			[
				'family_owner' => $userId,
				'member_name' => $member_name,
				'age' => $age,
			]
		)->get();

		if ($members->count()) {
			return hp\rest_error(400, 'Member already exists');
		} else {

			// Add new follow.
			$member = (new Models\Family_Member())->fill(
				[
					'member_name' => $member_name,
					'age' => $age,
					'family_owner' => $userId,
					'relation' => $relation,
				]
			);

			if (!$member->save()) {
				return hp\rest_error(400, $member->_get_errors());
			}
		}

		return hp\rest_response(
			200,
			[
				'id' => $member->get_id(),
			]
		);
	}

	/**
	 * Get family members.
	 *
	 * @param \WP_REST_Request $request API request.
	 * @return \WP_Rest_Response \WP_Rest_Response
	 */
	public function get_family_members($request)
	{

		// Check authentication.
		if (!is_user_logged_in()) {
			return hp\rest_error(401);
		}

		$userId = get_current_user_id();

		// Get Members.
		$members = Models\Family_Member::query()->filter(
			[
				'family_owner' => $userId,
			]
		)->get();

		return hp\rest_response(
			200,
			[
				'members' => $members,
			]
		);
	}


}