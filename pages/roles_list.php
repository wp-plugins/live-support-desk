<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/page.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/role.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/agents_roles.php' );

	class BistriRoles extends Page {

		public $template = 'roles_list.tpl';
		public $table    = 'bistri_desk_roles';
		public $data     = array(
			'roles'        => array(),
			'add_role_url' => null
		);

		public function init()
		{
			$this->loadRoles();
		}

		public function loadRoles()
		{
			$role = new Role();
			$this->data[ 'roles' ] = $role->get();
			$this->data[ 'add_role_url' ] = add_query_arg( array( 
				'page' => 'bistri_desk_role_add'
			), admin_url( 'admin.php' ) );

			foreach( $this->data[ 'roles' ] as $index => $role )
			{
				$this->data[ 'roles' ][ $index ][ 'agents' ] = $this->getAgents( $role[ 'id' ] );
			}
		}

		public function delete()
		{
			if( $this->validate( array(
				'id' => '00703' /* No role selected */
			) ) )
			{
				$this->processDelete( 
					is_array( $this->params[ 'id' ] ) ? 
						$this->params[ 'id' ] : array( $this->params[ 'id' ] )
				);
			}
			else
			{
				$this->loadRoles();
			}
		}

		public function processDelete( $ids, $isPlural = false )
		{
			$role = new Role();
			if( $role->delete( $ids[ 0 ] ) )
			{
				$agentsRoles = new AgentsRoles();
				if( gettype( $agentsRoles->deleteByRole( $ids[ 0 ] ) ) === 'integer' )
				{
					array_splice( $ids, 0, 1 );
					if( count( $ids ) ){
						$this->processDelete( $ids, true );
					}
					else{
						$this->messages[] = $isPlural ? '10703' /* Roles have been successfully deleted */ : '10704' /* Role has been successfully deleted */;
						$this->loadRoles();
					}
				}
				else
				{
					$this->errors[] = '00704'; /* Fail to remove associated agents */
				}
			}
		}

		public function added()
		{
			$this->messages[] = '10701'; /* Role has been successfully added */
			$this->loadRoles();
		}

		public function updated()
		{
			$this->messages[] = '10702'; /* Role has been successfully updated */
			$this->loadRoles();
		}

		private function getAgents( $id )
		{
			return $this->db->customGet( "SELECT login, firstname, lastname FROM wp_bistri_desk_agents a JOIN wp_bistri_desk_agents_roles b ON a.id=b.agentid WHERE b.roleid='$id'", ARRAY_A );
		}

	}

?>