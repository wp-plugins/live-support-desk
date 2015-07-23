<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/service.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/presence.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/agents_roles.php' );

	class BistriPresence extends Service {

		public function presence_get()
		{
			$presence      = new Presence();
	        $agent         = new AgentsRoles();
	        $online_agents = $presence->getConnected();
	        $agents_list   = $agent->getByRole( $this->params[ 'id' ] );
	        $agents        = array();

	        foreach( $agents_list as $key => $agent )
	        {
		        if( in_array( $agent[ 'uid' ], $online_agents ) )
		        {
		        	$agents[] = $agent[ 'uid' ];
		        }
	        }

			$this->users  = $agents;
			$this->errors = $online_agents[ 'errors' ];
		}
	}

?>
