<?php 

class User {

    /**
     * User ID
     */
	public $ID;

    /**
     * User data
     */
	public $data;

    /**
     * User type
     */
	public $type;

    /**
     * Class constructor
     */
	public function __construct( $user_id ) {

		global $db;

		if ( $user_id > 0 ) {
			$this->ID = $user_id;

			$user = $db->get_row( "SELECT * FROM usuarios WHERE ID = :ID", array( 'ID' => $this->ID ) );

			if ( $user['tipo'] != '' ) {
				$this->type = $user['tipo'];
			}

			unset( $user['senha'] );

			$this->data = $user;
			
		}

	}
}