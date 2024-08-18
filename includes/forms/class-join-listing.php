<?php
/**
 * Listing update form.
 *
 * @package HivePress\Forms
 */

namespace HivePress\Forms;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Updates listing.
 */
class Join_Listing extends Model_Form
{

	/**
	 * Class initializer.
	 *
	 * @param array $meta Class meta values.
	 */
	public static function init($meta = [])
	{
		$meta = hp\merge_arrays(
			[
				'label' => 'Join',
				'captcha' => false,
				'model' => 'listing_order',
			],
			$meta
		);

		parent::init($meta);
	}

	/**
	 * Class constructor.
	 *
	 * @param array $args Form arguments.
	 */
	public function __construct($args = [])
	{
    
		$args = hp\merge_arrays(
			[
				'description' => hivepress()->translator->get_string('Select the family member you want to join the listing with', 'hivepress'),
				'message' => esc_html__('Application submitted successfully', 'hivepress'),
				'action' => hivepress()->router->get_url('add_family_member_action'),
				'fields' => [
          'family_member' => [
            'type' => 'select',
            'label' => esc_html__('Family Member', 'hivepress'),
            'options' => 'comments',
						'option_args' => [
							'user_id' => get_current_user_id(),
						],
            'required' => true,
          ],
				],

				'button' => [
					'label' => 'Join',
				],
			],
			$args
		);

		parent::__construct($args);
	}


}
