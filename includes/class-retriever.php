<?php

class Saucal_WC_Retriever {

	/**
	 * Register the cronjob for the plugin
	 */
	public function activation_hooks() {
		if ( ! wp_next_scheduled( "saucal_retriever_get_new_data_event" ) ) {
			wp_schedule_event( time(), "hourly", "saucal_retriever_get_new_data_event" );
		}
	}

	/**
	 * Create the plugin init.
	 * Create all actions and filters here
	 */
	public function plugin_init() {
		add_action( "saucal_retriever_get_new_data_event", array( $this, "retrieve_data_from_api" ) );

		// Template stuff

	}

	/**
	 * Cronjob function that retrieves all the data from the API.
	 */
	public function retrieve_data_from_api(  ) {
		// get all users with _nickname_list
		$args = array(
			"meta_query" => array(
				array(
					"key" => "_nicknames_list",
					"value" => "",
					"compare" => "EXISTS"
				),
			)
		);

		$users = get_users( $args );

		// Iterate over every user to get information from API
		foreach ($users as $user) {
			$nicknames = get_user_meta( $user->ID, "_nicknames_list", true );
			$nicknames = json_encode( $nicknames );

			$results = wp_remote_post( "https://httpbin.org/post", array(
				"body" => array(
					"nicknames" => $nicknames,
				),
			) );

			// bail if server doesn't send us any information
			if ( empty( $results["headers"] ) ) {
				continue;
			}

			// Update our user meta and keep going to the next user.
			update_user_meta( $user->ID, "_nicknames_results", $results["headers"] );
		}
	}


}