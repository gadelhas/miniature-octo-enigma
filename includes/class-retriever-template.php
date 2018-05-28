<?php

class Saucal_Retriever_Template {

	public function add_nicknames_to_menu( $items ) {
		$items["nicknames"] = __( "Nicknames", "saucal" );

		return $items;
	}

	public function add_endpoint_to_woocommerce() {
		add_rewrite_endpoint( "nicknames", EP_PAGES );
	}

	public function show_nicknames_page() {

		if ( $_POST["_wp_nonce"] ) {
			call_user_func( array( "Saucal_Retriever_Main", "save_nickname_information" ) );
			?>
            <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
                <p>Information updated.</p>
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