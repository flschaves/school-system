<?php 

class Course {

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
	public function __construct( $course_id ) {

		global $db;

		if ( $course_id > 0 ) {
			$this->ID = $course_id;

			$course = $db->get_row( "SELECT * FROM cursos WHERE ID = :ID", array( 'ID' => $this->ID ) );

			$this->data = $course;
		}

	}
}