<?php

class Saucal_Retriever_Template {

	protected static $instance = null;

	protected static $endpoint = "nicknames";

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

	public function add_nicknames_to_menu( $items ) {
		$items[ self::$endpoint ] = __( "Nicknames", "saucal" );

		return $items;
	}

	public function change_title_of_nickname_page( $title, $id ) {
		global $wp_query;

		if ( is_wc_endpoint_url( self::$endpoint ) ) {
			$title = __( "Nicknames", "saucal" );
		}

		return $title;
	}

	public function add_endpoint_to_woocommerce() {
		add_rewrite_endpoint( self::$endpoint, EP_PAGES );
	}

	public function add_nicknames_to_endpoint_list( $vars ) {
		$vars[ self::$endpoint ] = self::$endpoint;

		return $vars;
	}

	public function show_nicknames_page() {

		if ( $_POST ) {
			$result = call_user_func( array( Saucal_Retriever_Main::get_instance(), "save_nickname_information" ),
				$_POST );
			?>
            <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
				<?php echo $result; ?>
            </div>
			<?php
		}
		// Retrieve all the API results already saved.
		$nickname_results = get_user_meta( get_current_user_id(), "_nicknames_results", true );
		$nickname_results = is_array( $nickname_results ) ? $nickname_results : array(); // make sure it is an array.

		// Also retrieve information about nicknames we have
		$nicknames = get_user_meta( get_current_user_id(), "_nicknames_list", true );
		$nicknames = is_array( $nicknames ) ? $nicknames : array(); // make sure it is an array.
		$nicknames = implode( "\n", $nicknames );


		include __DIR__ . "/../template/nicknames_page.php";
	}
}