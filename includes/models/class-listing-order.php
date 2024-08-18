<?php
namespace HivePress\Models;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Model class.
 */
class Listing_Order extends Comment
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
						'_alias' => 'comment_karma',
					],
					'member_name' => [
						'type' => 'text',
						'max_length' => 256,
						'required' => true,
						'_alias' => 'comment_author',
					],
					'relation' => [
						'type' => 'text',
						'max_length' => 256,
						'required' => true,
						'_alias' => 'comment_author_IP',
					],
					'user' => [
						'type' => 'id',
						'required' => true,
						"_alias" => "user_id",
						"_model" => "user",
					],
				]
			],
			$args
		);

		parent::__construct($args);
	}

}