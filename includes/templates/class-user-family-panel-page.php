<?php
namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Template class.
 */
class User_Family_Panel_Page extends User_Account_Page
{

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct($args = [])
	{
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_content' => [
						'blocks' => [
							'family_members' => [
								'type' => 'part',
								'path' => 'user-account/family-members',
								'_order' => 10,
							],
							'add_member_form' => [
								'type' => 'form',
								'form' => 'add_family_member',
							],
						],
					],
				],
			],
			$args
		);

		parent::__construct($args);
	}
}