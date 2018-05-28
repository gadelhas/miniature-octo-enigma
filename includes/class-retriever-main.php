<?php

class Saucal_Retriever_Main {
	protected static $instance = null;

	/**
	 * Access plugin instance. You can create further instances by calling
	 * the constructor directly.
	 *
	 * @wp-hook wp_loaded
	 * @return  object T5_Spam_Block
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

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
		add_action( "init", array( Saucal_Retriever_Template::get_instance(), "add_endpoint_to_woocommerce" ) );
		add_action( "woocommerce_account_nicknames_endpoint",
			array( Saucal_Retriever_Template::get_instance(), "show_nicknames_page" ) );
		add_filter( "woocommerce_account_menu_items",
			array( Saucal_Retriever_Template::get_instance(), "add_nicknames_to_menu" ) );
	}

	/**
	 * Cronjob function that retrieves all the data from the API.
	 */
	public function retrieve_data_from_api() {
		// get all users with _nickname_list
		$args = array(
			"meta_key"     => "_nicknames_list",
			"meta_compare" => "EXISTS",
		);

		$users = get_users( $args );

		// Iterate over every user to get information from API
		foreach ( $users as $user ) {
			$nicknames = get_user_meta( $user->ID, "_nicknames_list", true );
			$nicknames = json_encode( $nicknames );

			$results = wp_remote_post( "https://httpbin.org/post",
				array(
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


	public function save_nickname_information() {
		$request  = $_POST;
		$wp_nonce = $request["_wpnonce"];

		// Check if nonce is correct and exists.
		if ( ! isset( $wp_nonce ) || ! wp_verify_nonce( $wp_nonce, "saucal_retriever" ) ) {
			return "Oops! No WP_Nonce not valid!";
		}

		$nickname_list = isset( $request["nicknames_list"] ) ? $request["nicknames_list"] : "";

		$nickname_list = sanitize_textarea_field( $nickname_list );

		// If input has no values, delete user meta for this key, and bail early.
		// We make sure we delete, so when we get all users with this key in the cronjob we don't get empty meta values.
		if ( strlen( $nickname_list ) == 0 ) {
			delete_user_meta( get_current_user_id(), "_nicknames_list" );

			return "Nickname list deleted successfully!";
		}

		// Explode each line to an array element.
		$elements = explode( "\n", str_replace( "\r", "", $nickname_list ) );

		update_user_meta( get_current_user_id(), "_nicknames_list", $elements );

		return "Nickname list updated successfully!";
	}
}