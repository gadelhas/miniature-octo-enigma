<?php
/*
 * Plugin name: Sau/Cal WooCommerce information Retriever
 * Description: This is a plugin made while testing for Sau/Cal
 * Plugin Author: Mario Santos
 * Version: 1.0.0
 */

/**
 * Saucal retriever autoloader
 *
 * @param $class_name
 */
function saucal_autoloader( $class_name ) {
	/**
	 * If the class being requested does not start with our prefix,
	 * we know it's not one in our project
	 */
	if ( 0 !== strpos( $class_name, 'Saucal_Retriever_' ) ) {
		return;
	}

	$file_name = str_replace( array( "Saucal_", "_" ),
		array( "", "-" ),
		$class_name );
	// Compile our path from the current location
	$file = dirname( __FILE__ ) . '/includes/class-' . $file_name . '.php';

	// If a file is found
	if ( file_exists( $file ) ) {
		// Then load it up!
		require( $file );
	}
}

spl_autoload_register( "saucal_autoloader" );

// This is an WooCommerce plugin, bail early if WC is not loaded.
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}
$saucal_retriever = new Saucal_Retriever_Main();

register_activation_hook( __FILE__, array( "Saucal_Retriever_Main", "activation_hooks" ) );

$saucal_retriever->plugin_init();