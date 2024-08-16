<?php
namespace HivePress\Models;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Model class.
 */
class Family_Member extends Post
{

	/**
	 * Class constructor.
	 *
	 * @param array $args Model arguments.
	 */
	public function __construct($args = [])
	{
		$args = hp\merge_arrays(
			[
				'fields' => [
					'age' => [
						'type' => 'number',
						'required' => true,
						'_alias' => 'age',
						'_model' => 'family_member',
					],
					'member_name' => [
						'type' => 'string',
						'required' => true,
						'_alias' => 'member_name',
						'_model' => 'family_member',
					],
					'relation' => [
						'type' => 'string',
						'required' => true,
						'_alias' => 'relation',
						'_model' => 'family_member',
					],
					'family_owner' => [
						'type' => 'id',
						'required' => true,
						"_alias" => "family_owner_ID",
						"_model" => "user",
					],
				]
			],
			$args
		);

		parent::__construct($args);
	}

final public function get_Name(){
	return $this->get_field_name("member_name");
}
}