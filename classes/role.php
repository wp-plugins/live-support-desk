<?php

	require_once( plugin_dir_path( __FILE__ ) . 'db.php' );

	class Role {

		protected $db = null;

	    public function __construct()
	    {
      		$this->db = new BistriDb( 'bistri_desk_roles' );
	    }

	    public function get()
	    {
	    	return $this->db->get( array( 
				'id',
				'name'
			) );
	    }

	    public function edit( $id )
	    {
			return $this->db->find( array( 'id' => $id ) );
	    }

	    public function save( $fields, $id )
	    {
			return $this->db->save( $fields, $id );
	    }

	    public function delete( $id )
	    {
	    	return $this->db->delete( $id );
	    }
	}
?>