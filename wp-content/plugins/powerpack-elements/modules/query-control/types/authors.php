<?php
namespace PowerpackElements\Modules\QueryControl\Types;

use PowerpackElements\Modules\QueryControl\Types\Type_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * \Modules\QueryControl\Types\Authors
 *
 * @since  1.4.13.1
 */
class Authors extends Type_Base {

	/**
	 * Get Name
	 *
	 * Get the name of the module
	 *
	 * @since  1.4.13.1
	 * @return string
	 */
	public function get_name() {
		return 'authors';
	}

	/**
	 * Gets autocomplete values
	 *
	 * @since  1.4.13.1
	 * @return array
	 */
	public function get_autocomplete_values( array $data ) {
		$results = [];

		$query_params = [
			'who'                   => 'authors',
			'has_published_posts'   => true,
			'fields'                => [
				'ID',
				'display_name',
			],
			'search'                => '*' . $data['q'] . '*',
			'search_columns'        => [
				'user_login',
				'user_nicename',
			],
		];

		$user_query = new \WP_User_Query( $query_params );

		foreach ( $user_query->get_results() as $author ) {
			$results[] = [
				'id'    => $author->ID,
				'text'  => $author->display_name,
			];
		}

		return $results;
	}

	/**
	 * Gets control values titles
	 *
	 * @since  1.4.13.1
	 * @return array
	 */
	public function get_value_titles( array $request ) {
		$ids = (array) $request['id'];
		$results = [];

		$query_params = [
			'who'                   => 'authors',
			'has_published_posts'   => true,
			'fields'                => [
				'ID',
				'display_name',
			],
			'include'               => $ids,
		];

		$user_query = new \WP_User_Query( $query_params );

		foreach ( $user_query->get_results() as $author ) {
			$results[ $author->ID ] = $author->display_name;
		}

		return $results;
	}
}
