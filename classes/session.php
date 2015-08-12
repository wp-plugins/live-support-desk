<?php

	require_once( plugin_dir_path( __FILE__ ) . 'db.php' );

	class Session {

		protected $name = 'bistri_desk';
		protected $db   = null;

	    public function __construct()
	    {
      		$this->db = new BistriDb( 'bistri_desk_agents' );
	    }

		public function login( $login, $password )
		{
			if( $user = $this->db->find( array( 
				'login' => $login
			) ) )
			{
				if( $user[ 'password' ] == hash( 'md5', $password ) )
				{
					$_SESSION[ $this->name ] = $user;
					return $user;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}

		public function logout()
		{
			if( isset( $_SESSION[ $this->name ] ) )
			{
				unset( $_SESSION[ $this->name ] );
				return true;
			}
			else
			{
				return false;
			}
		}

		public function info()
		{
			if( isset( $_SESSION[ $this->name ] ) )
			{
				$session = $_SESSION[ $this->name ];
				return array(
					'id'          => $session[ 'id' ],
					'uid'         => $session[ 'uid' ],
					'firstname'   => $session[ 'firstname' ],
					'lastname'    => $session[ 'lastname' ],
					'displayname' => $session[ 'firstname' ] . " " . $session[ 'lastname' ]
				);
			}
			else
			{
				return false;
			}
		}

		public function isLogged()
		{
			if( isset( $_SESSION[ $this->name ] ) )
			{
				$session = $_SESSION[ $this->name ];
				if( $user = $this->db->find( array(
					'login'    => $session[ 'login' ],
					'password' => $session[ 'password' ]
				) ) )
				{
					return $user;
				}
				else
				{
					unset( $session );
					return false;
				}
			}
			else
			{
				return false;
			}
		}

	}
?>