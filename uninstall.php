<?php
/**
 * Relay uninstall.
 *
 * Removes the API key and cached data when the plugin is deleted.
 *
 * @package Relay
 * @author  Verdant Studio
 * @since   1.6.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_multisite() ) {
	delete_site_option( 'relay_api_key' );

	// The REST endpoint can be called on any subsite, so each one may have its own cached directory sizes.
	$relay_site_ids = get_sites(
		array(
			'number'     => 0,
			'fields'     => 'ids',
			'network_id' => get_current_network_id(),
		)
	);

	foreach ( $relay_site_ids as $relay_site_id ) {
		switch_to_blog( $relay_site_id );
		delete_option( 'relay_api_key' );
		delete_transient( 'relay_directory_sizes' );
		restore_current_blog();
	}
} else {
	delete_option( 'relay_api_key' );
	delete_transient( 'relay_directory_sizes' );
}
