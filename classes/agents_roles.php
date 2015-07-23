<?php

	require_once( plugin_dir_path( __FILE__ ) . 'db.php' );

	class AgentsRoles {

		protected $db = null;

	    public function __construct()
	    {
      		$this->db = new BistriDb( 'bistri_desk_agents_roles' );
	    }

	    public function getByRole( $id = null )
	    {
	    	return $this->db->get( array( 
	    		'id',
	    		'agentid',
				'uid'
			), array(
				'roleid' => $id
			) );
	    }

	    public function getByAgent( $id = null )
	    {
	    	return $this->db->get( array( 
	    		'id',
				'uid',
				'roleid'
			), array(
				'agentid' => $id
			) );
	    }

	    public function deleteByRole( $id )
	    {
	    	return $this->db->deleteAll( array( 
	    		'roleid' => $id
	    	) );
	    }

	    public function deleteByAgent( $id )
	    {
	    	return $this->db->deleteAll( array( 
	    		'agentid' => $id
	    	) );
	    }

	    public function save( $roles, $agentid, $uid )
	    {
	    	$result = true;
	    	if( $agent_roles = $this->getByAgent( $agentid ) ){

	    		foreach( $agent_roles as $role )
	    		{
	    			$index = array_search( $role[ 'roleid' ], $roles );
	    			if( gettype( $index ) === 'integer' )
	    			{
	    				unset( $roles[ $index ] );
	    			}
	    			else
	    			{
						$this->db->delete( $role[ 'id' ] );
	    			}
	    		}
	    	}
    		foreach( $roles as $id )
    		{
		    	if( !$this->db->save( array(
		    		'roleid'  => $id,
		    		'agentid' => $agentid,
		    		'uid'     => $uid
		    	) ) )
		    	{
		    		$result = false;
		    	}
	   		}
	   		return $result;
	    }
	}
?>