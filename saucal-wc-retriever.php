<?php

function saucal_autoloader( $class_name ) {
	/**
	 * If the class being requested does not start with our prefix,
	 * we know it's not one in our project
	 */
	if ( 0 !== strpos( $class_name, 'Saucal_WC_' ) ) {
		return;
	}

	$file_name = str_replace( "Saucal_WC_", "", $class_name );

	// Compile our path from the current location
	$file = dirname( __FILE__ ) . '/includes/class-' . $file_name . '.php';

	// If a file is found
	if ( file_exists( $file ) ) {
		// Then load it up!
		require( $file );
	}
}

$saucal_retriever = new Saucal_WC_Retriever();