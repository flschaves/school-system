<?php

// Login
if ( isset( $_REQUEST['login'] ) ) {

	$data = array(
		'username' => $_POST['username'],
		'userpass' => md5( $_POST['userpass'] )
	);

	$user_id = $db->get_var( "SELECT ID FROM usuarios WHERE usuario = :username AND senha = :userpass", $data );

	if ( $user_id > 0 ) {
		$_SESSION['user_id'] = $user_id;
	} else {
		$errors = array( 'invalid_user' => 'Usuário não encontrado' );
	}
}

// Logout
if ( isset( $_REQUEST['logout'] ) ) {
	unset( $_SESSION['user_id'] );
	redirect();
}

// Set current user
if ( isset( $_SESSION['user_id'] ) and ! empty( $_SESSION['user_id'] ) ) {
	$current_user = new User( $_SESSION['user_id'] );
}