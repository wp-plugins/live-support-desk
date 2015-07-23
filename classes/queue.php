<?php
	require_once( plugin_dir_path( __FILE__ ) . 'db.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'message.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'session.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'agents_roles.php' );

	class Queue {

		protected $db = null;

	    public function __construct()
	    {
      		$this->db = new BistriDb( 'bistri_desk_queue' );
	    }

		public function add( $id, $role, $referrer = '' )
		{
	    	$answer = array(
	    		'data'  => array(),
	    		'errors' => array()
	    	);

			if( $this->db->find( array( 
				'customerid' => $id,
				'status'     => 1,
				'role'       => $role
			) ) )
			{
				$answer[ 'errors' ][] = '00101'; /* Request already push in queue */
			}
			else
			{
				if( $result = $this->db->save( array(
					'customerid' => $id,
					'role'       => $role,
					'pushed'     => date( "Y-m-d H:m:s" ),
					'url'        => $referrer
				) ) )
				{
					if( $count = $this->db->count( array(
						'status' => 1,
						'role'   => $role
					) ) )
					{
						$answer[ 'data' ] = array( 'queue' => ( $count - 1 ) );
					}
					else
					{
						$answer[ 'errors' ][] = '00102'; /* Fail to count pending requests */
					}
				}
				else
				{
					$answer[ 'errors' ][] = '00103'; /* Fail to push request in queue */
				}
			}
			return $answer;
		}

		public function abort( $id )
		{
	    	$answer = array(
	    		'data'  => array(),
	    		'errors' => array()
	    	);

			if( $this->db->save( array(
					'popped'     => date( "Y-m-d H:m:s" ),
					'status'     => 3
				), array( 
					'customerid' => $id,
					'status'     => 1
				)
			) )
			{
				$answer[ 'data' ] = true;
			}
			else
			{
				$answer[ 'errors' ][] = '00105'; /* Fail to remove requests from queue */
			}
			return $answer;
		}

		public function next()
		{
			$session = new Session();
	    	$answer  = array(
	    		'data'  => array(),
	    		'errors' => array()
	    	);

    		if ( $session->isLogged() )
    		{
    			$agentRoles = new AgentsRoles();
    			$user       = $session->info();
    			$roleids    = array();
    			$roles      = $agentRoles->getByAgent( $user[ 'id' ] );

    			foreach( $roles as $key => $value ){
    				$roleids[] = $value[ 'roleid' ];
    			}

		    	$customer   = $this->db->find( array(
					'status' => 1,
					'role'   => $roleids
				) );
    		}
    		else
    		{
		    	if ( is_user_logged_in() )
		    	{
			    	$customer = $this->db->find( array(
						'status' => 1
					) );
		    	}
		    	else
		    	{
    				$customer = false;
	    		}
	    	}

			if( $customer )
			{
				if( $this->db->save( array(
						'popped'     => date( "Y-m-d H:m:s" ),
						'status'     => 2
					), array( 
						'customerid' => $customer[ 'customerid' ],
						'status'     => 1
					)
				) )
				{
					$messages = new Message();
					$messagesGet = $messages->get( $customer[ 'customerid' ] );
					$answer[ 'data' ][ 'count' ] = $this->db->count( array( 'status' => 1 ) );
					$answer[ 'data' ][ 'customer' ] = array(
						'id'       => $customer[ 'customerid' ],
						'referrer' => $customer[ 'url' ],
						'messages' => $messagesGet[ 'data' ]
					);
				}
				else
				{
					$answer[ 'errors' ][] = '00104'; /* Fail to mark request as processed */
				}
			}
			else
			{
				$answer[ 'data' ][ 'customer' ] = false;
			}
			return $answer;
		}

		public function count()
		{
			$session = new Session();
	    	$answer  = array(
	    		'data'  => array(),
	    		'errors' => array()
	    	);

    		if ( $session->isLogged() )
    		{
    			$agentRoles = new AgentsRoles();
    			$user       = $session->info();
    			$roleids    = array();
    			$roles      = $agentRoles->getByAgent( $user[ 'id' ] );

    			foreach( $roles as $key => $value ){
    				$roleids[] = $value[ 'roleid' ];
    			}

		    	$count   = $this->db->count( array(
					'status' => 1,
					'role'   => $roleids
				) );
    		}
    		else
    		{
		    	if ( is_user_logged_in() )
		    	{
			    	$count = $this->db->count( array(
						'status' => 1
					) );
		    	}
		    	else
		    	{
		    		$count = false;
		    	}
    		}

			if( $count )
			{
				$answer[ 'data' ][ 'count' ] = $count;
			}
			else
			{
				$answer[ 'data' ][ 'count' ] = 0;
			}
			return $answer;
		}

	}
?>