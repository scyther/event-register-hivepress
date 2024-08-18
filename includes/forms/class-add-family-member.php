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
class Add_Family_Member extends Model_Form
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
				'label' => 'Add Family Member',
				'captcha' => false,
				'model' => 'family_member',
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
				'description' => hivepress()->translator->get_string('Enter member details'),
				'message' => esc_html__('Family Member added Successfully'),
				'action' => hivepress()->router->get_url('add_family_member_action'),
				'fields' => [
					'member_name' => [
						'label' => 'Name',
						'required' => true,
						'type' => 'text',
						'_order' => 10,
					],
					'age' => [
						'label' => 'Age',
						'required' => true,
						'placeholder' => 'Age',
						'_order' => 10,
					],
					'relation' => [
						'label' => 'Relation',
						'required' => true,
						'_order' => 10,
						'type' => 'select',
						'options' => [
							'' => 'Select Relation',
							'Son' => 'Son',
							'Daughter' => 'Daughter',
							'Wife' => 'Wife',
							'Husband' => 'Husband',
						],
					],
					'family_owner' => [
						'type' => 'hidden',
						'_order' => 10,
					],
				],

				'button' => [
					'label' => 'Add Family Member',
				],
			],
			$args
		);

		parent::__construct($args);
	}


}
