<?php 

class Note {

    /**
     * User ID
     */
	public $ID;

    /**
     * User data
     */
	public $data;

    /**
     * Class constructor
     */
	public function __construct( $note_id ) {

		global $db;

		if ( $note_id > 0 ) {
			$this->ID = $note_id;

			$note = $db->get_row( "SELECT * FROM notas WHERE ID = :ID", array( 'ID' => $this->ID ) );

			$this->data = $note;
		}

	}
}