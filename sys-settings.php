<?php

// Start session
session_start();

// Set and reset global variables
global $db, $current_user, $errors;
$db = $current_user = $errors = null;

// Define the core path
define( 'CORE', 'core' );

// Define the templates path
define( 'TEMPLATEPATH', 'templates' );

// Include files required for initialization.
require( ABSPATH . CORE . '/load.php' );
require( ABSPATH . CORE . '/functions.php' );
require( ABSPATH . CORE . '/constants.php' );

// Include main classes
require( ABSPATH . CORE . '/classes/class-user.php' );
require( ABSPATH . CORE . '/classes/class-course.php' );
require( ABSPATH . CORE . '/classes/class-note.php' );

// Set timezone
date_default_timezone_set( 'UTC' );
setlocale( LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese' );

// Include and connect DB
require_db();

// Set internal encoding.
if ( function_exists( 'mb_internal_encoding' ) ) {
	mb_internal_encoding( 'UTF-8' );
}

// Include user login and logout actions
require( ABSPATH . CORE . '/user.php' );