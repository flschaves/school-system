<?php

if ( ! $current_user ) {
	load_template( ABSPATH . TEMPLATEPATH . '/index.php' );
	exit;
}

$page = $_GET[ 'page' ] ?: false;

$template = false;
if     ( $page == 'view-users' ) : $template = '/view-users.php';
elseif ( $page == 'edit-user' ) : $template = '/edit-user.php';
elseif ( $page == 'edit-course' ) : $template = '/edit-course.php';
elseif ( $page == 'view-courses' ) : $template = '/view-courses.php';
elseif ( $page == 'edit-note' ) : $template = '/edit-note.php';
elseif ( $page == 'view-notes' ) : $template = '/view-notes.php';
else :
	$template = '/main-menu.php';
endif;

if ( $template )
	load_template( ABSPATH . TEMPLATEPATH . $template );

return;