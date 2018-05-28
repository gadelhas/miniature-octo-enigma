<?php

class Saucal_Retriever_Template {

	public function add_nicknames_to_menu( $items ) {
		$items["nicknames"] = __( "Nicknames", "saucal" );

		return $items;
	}

	public function add_endpoint_to_woocommerce() {
		add_rewrite_endpoint( "nicknames", EP_PAGES );
	}
}