<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/page.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/role.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/agents_roles.php' );

	class BistriAddAgent extends Page {

		public $template = 'add_agent.tpl';
		public $table    = 'bistri_desk_agents';
		public $data     = array(
			'id'        => null,
			'uid'       => null,
			'login'     => null,
			'firstname' => null,
			'lastname'  => null,
			'password'  => null,
			'roles'     => null
		);

		public function init()
		{
			$this->loadRoles();
		}

		public function edit()
		{
		    $user = $this->db->find( array( 'id' => $this->params[ 'id' ] ) );
		    if( $user ){
		    	foreach( $user as $key => $value )
		    	{
		    		$this->data[ $key ] = $user[ $key ];
		    	}
		    }
			$this->loadRoles();
		}

		public function loadRoles()
		{
			$agentsRoles = new AgentsRoles();
			$role = new Role();
			$this->data[ 'all_roles' ] = $role->get();
			$this->data[ 'agent_roles' ] = isset( $this->data[ 'id' ] ) ? 
				$agentsRoles->getByAgent( $this->data[ 'id' ] ) : array();
		}

		public function add()
		{
			$this->loadRoles();
			if( $this->validate( array(
				'login'       => '00601', /* Login is missing */
				'firstname'   => '00602', /* First name is missing */
				'lastname'    => '00603', /* Last name is missing */
				'password'    => '00604', /* Password is missing */
				're_password' => '00607', /* Password confirmation is missing */
				'roles'       => '00610'  /* No role selected */
			) ) )
			{
				if( $this->params[ 'password' ] != $this->params[ 're_password' ] )
				{
					$this->errors[] = '00608'; /* Passwords missmatch */
					require_once( ABSPATH . 'wp-admin/admin-header.php' );
					return;
				}
				$this->save( array(
					'login'     => $this->params[ 'login' ],
					'uid'       => hash( 'md5', time() ),
					'firstname' => $this->params[ 'firstname' ],
					'lastname'  => $this->params[ 'lastname' ],
					'password'  => hash( 'md5', $this->params[ 'password' ] )
				), $this->params[ 'roles' ], 'added' );
			}
			else
			{
				require_once( ABSPATH . 'wp-admin/admin-header.php' );
			}
		}

		public function update()
		{
			$this->loadRoles();
			if( $this->validate( array(
				'id'        => '00605', /* Id is missing */
				'uid'       => '00606', /* User id is missing */
				'login'     => '00601', /* Login is missing */
				'firstname' => '00602', /* First name is missing */
				'lastname'  => '00603',  /* Last name is missing */
				'roles'     => '00610'  /* No role selected */
			) ) )
			{
				$data = array(
					'id'        => $this->params[ 'id' ],
					'login'     => $this->params[ 'login' ],
					'uid'       => $this->params[ 'uid' ],
					'firstname' => $this->params[ 'firstname' ],
					'lastname'  => $this->params[ 'lastname' ]
				);

				if( !empty( $this->params[ 'password' ] ) )
				{
					if( $this->params[ 'password' ] != $this->params[ 're_password' ] )
					{
						$this->errors[] = '00608'; /* Passwords missmatch */
						require_once( ABSPATH . 'wp-admin/admin-header.php' );
						return;
					}
					$data[ 'password' ] = hash( 'md5', $this->params[ 'password' ] );
				}
				$this->save( $data, $this->params[ 'roles' ], 'updated' );
			}
			else
			{
				require_once( ABSPATH . 'wp-admin/admin-header.php' );
			}
		}

		public function save( $fields = array(), $roles = array(), $action )
		{
			if( $newid = $this->db->save(
					$fields,
					isset( $fields[ 'id' ] ) ? 
						array( 'id' => $fields[ 'id' ] ) : null
				)
			)
			{
				$agentsRoles = new AgentsRoles();
				if( $agentsRoles->save( $roles, isset( $fields[ 'id' ] ) ?  $fields[ 'id' ] : $newid, $fields[ 'uid' ] ) )
				{
					wp_safe_redirect( add_query_arg( array( 
						'page' => 'bistri_desk_agents',
						'action' => $action
					), admin_url( 'admin.php' ) ) );
					exit;
				}
			}
		}
	}
?>