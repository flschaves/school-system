<?php
/**
 * Load the database class file and instantiate the `$db` global.
 */
function require_db() {
	global $db;

	require_once( ABSPATH . CORE . '/classes/class-db.php' );

	if ( isset( $db ) )
		return;

	$db = new DB( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
}