<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/page.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/agents_roles.php' );

	class BistriAgents extends Page {

		public $template = 'agents_list.tpl';
		public $table    = 'bistri_desk_agents';
		public $data     = array(
			'agents'        => array(),
			'add_agent_url' => null
		);

		public function init()
		{
			$this->loadAgents();
		}

		public function loadAgents()
		{
			$this->data[ 'agents' ] = $this->db->get( array( 
				'id',
				'login',
				'firstname',
				'lastname'
			) );
			$this->data[ 'add_agent_url' ] = add_query_arg( array( 
				'page' => 'bistri_desk_agent_add'
			), admin_url( 'admin.php' ) );

			foreach( $this->data[ 'agents' ] as $index => $agent )
			{
				$this->data[ 'agents' ][ $index ][ 'roles' ] = $this->getRoles( $agent[ 'id' ] );
			}
		}

		public function delete()
		{
			if( $this->validate( array(
				'id' => '00609' /* No user selected */
			) ) )
			{
				$this->processDelete( 
					is_array( $this->params[ 'id' ] ) ? 
						$this->params[ 'id' ] : array( $this->params[ 'id' ] )
				);
			}
			else
			{
				$this->loadAgents();
			}
		}

		public function processDelete( $ids, $isPlural = false )
		{
			if( $this->db->delete( $ids[ 0 ] ) )
			{
				$agentsRoles = new AgentsRoles();
				if( gettype( $agentsRoles->deleteByAgent( $ids[ 0 ] ) ) === 'integer' )
				{
					array_splice( $ids, 0, 1 );
					if( count( $ids ) ){
						$this->processDelete( $ids, true );
					}
					else{
						$this->messages[] = $isPlural ? '10603' /* Users have been successfully deleted */ : '10604' /* User has been successfully deleted */;
						$this->loadAgents();
					}
				}
				else
				{
					$this->errors[] = '00611'; /* Fail to remove associated roles */
				}
			}
		}

		public function added()
		{
			$this->messages[] = '10601'; /* User has been successfully added */
			$this->loadAgents();
		}

		public function updated()
		{
			$this->messages[] = '10602'; /* User has been successfully updated */
			$this->loadAgents();
		}

		private function getRoles( $id )
		{
			return $this->db->customGet( "SELECT name FROM wp_bistri_desk_agents_roles a JOIN wp_bistri_desk_roles b ON a.roleid=b.id WHERE a.agentid='$id'", ARRAY_A );
		}
	}

?>