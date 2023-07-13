<?php

/** Define ABSPATH as this file's directory */
define( 'ABSPATH', dirname(__FILE__) . '/' );

error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

/** Load config file */
if ( file_exists( ABSPATH . 'sys-config.php' ) ) {

	/** The config file resides in ABSPATH */
	require_once( ABSPATH . 'sys-config.php' );

} else {

	die( 'No configuration file found' );

}