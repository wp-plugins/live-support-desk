<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/service.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/session.php' );

	class BistriAuth extends Service {

		public function user_login()
		{
			if( $this->validate( array(
				'login'    => '00501', /* Login is missing */
				'password' => '00502'  /* Password is missing */
			) ) )
			{
				$session = new Session();
				if( $result = $session->login(
					$this->params[ 'login' ],
					$this->params[ 'password' ]
				) )
				{
					$this->data[ 'user' ] = array(
						'id'        => $result[ 'userid' ],
						'login'     => $result[ 'login' ],
						'firstname' => $result[ 'firstname' ],
						'lastname'  => $result[ 'lastname' ]
					);
				}
				else
				{
					$this->errors[] = '00503'; /* Bad login and/or password */
				}
			}
		}

		public function user_islogged()
		{
			$session = new Session();
			if( $result = $session->isLogged() )
			{
				$this->data[ 'user' ] = array(
					'id'        => $result[ 'userid' ],
					'login'     => $result[ 'login' ],
					'firstname' => $result[ 'firstname' ],
					'lastname'  => $result[ 'lastname' ]
				);
			}
			else
			{
				$this->errors[] = '00504'; /* No session found */
			}
		}

		public function user_logout()
		{
			$session = new Session();
			if( $session->logout() )
			{
				$this->data = true;
			}
			else
			{
				$this->errors[] = '00504'; /* No session found */
			}
		}
	}

?>