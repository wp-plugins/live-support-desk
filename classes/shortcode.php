<?php

	require_once( plugin_dir_path( __FILE__ ) . 'template.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'queue.php' );

	class BistriShortcode {

		public $tpl       = null;
		public $use_queue = false;

	    /**
	     * Construct the object
	     */
	    public function __construct( $options )
	    {
			$this->tpl = new Template();
	        $this->use_queue = get_option( 'bistri_desk_use_queue', 0 );

	        $settings = get_option( 'bistri_desk_settings', array(
	            'api_key' => '',
	            'chrome_extension_id' => '',
	            'firefox_extension_id' => ''
	        ) );

			$url = get_bloginfo( 'url' );

	        $extra = array(
	            'appkey'       => $settings[ 'api_key' ],
	            'appid'        => get_option( "bistri_desk_plugin_id" ),
	            'chromeExtId'  => $settings[ 'chrome_extension_id' ],
	            'firefoxExtId' => $settings[ 'firefox_extension_id' ],
	            'usequeue'     => $this->use_queue ? 'true' : 'false',
	            'capacity'     => '2',
	            'wpurl'        => $url,
	            'debug'    => 'true'
	        );

			if( empty( $settings[ 'api_key' ] ) ){
				$this->errors[] = array_map( function( $n ){
					global $BISTRI_DESK_ERROR;
					return $BISTRI_DESK_ERROR[ $n ];
				}, '00304' );
			}

	        if( isset( $options[ 'client' ] ) and $options[ 'client' ] == 'agent' )
	        {
	        	$this->agentView( array_merge( $options, $extra ) );
	        }
	        else
	        {
	        	$this->customerView( array_merge( $options, $extra ) );
	        }
	    }

	    private function customerView( $options = array() )
	    {
			require_once( plugin_dir_path( __FILE__ ) . 'presence.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'agents_roles.php' );

	    	$page_id    = get_option( 'bistri_desk_use_page', 0 );
			$adminHash  = hash( 'md5', get_option( 'admin_email' ) );
			$presence   = new Presence();
	        $connected  = $presence->getConnected();
	        $filtered   = array();

	        if( in_array( $adminHash, $connected[ 'users' ] ) )
	        {
	        	$filtered[] = $adminHash;
	        }

	    	if( isset( $options[ 'role' ] ) )
	    	{
	    		$agentsRoles  = new AgentsRoles();
	    		$agentsGetByRole = $agentsRoles->getByRole( $options[ 'role' ] );
	    		foreach( $agentsGetByRole as $key => $agent )
	    		{
			        if( in_array( $agent[ 'uid' ], $connected[ 'users' ] ) )
			        {
			        	$filtered[] = $agent[ 'uid' ];
			        }
	    		}
	    	}

	        if( $connected[ 'errors' ] )
	        {
				$this->tpl->errors[] = array_map( function( $n ){
					global $BISTRI_DESK_ERROR;
					return $BISTRI_DESK_ERROR[ $n ];
				}, $connected[ 'errors' ] );
				$this->tpl->render( 'error.tpl' );
	        }
	        else
	        {
	        	if( count( $filtered ) )
	        	{
	        		if( is_user_logged_in() )
	        		{
	        			$options[ 'name' ] = wp_get_current_user()->display_name;
	        		}
	        		else
	        		{
	        			$options[ 'name' ] = __( 'visitor' );
	        		}
	        		if( $this->use_queue == 0 )
	        		{
	        			$options[ 'callees' ] = $filtered;
	        			$options[ 'room' ] = 'desk_' . hash( 'md5', time() );
	        			$options[ 'callRequestTimeout' ] = '30';
	        			$options[ 'endCallRequestsOnAnswer' ] = 'true';
	        		}
	        
					$this->tpl->header = '';
			        $this->tpl->layout = array_key_exists( 'layout', $options ) ? $this->tpl->render( 'widgets/customer/' . $options[ 'layout' ] . '.tpl', true ) : '';
			        $this->tpl->widget = 'widget.desk.customer';
			        $this->tpl->params = json_encode( $options );
			        $this->tpl->errors = array();
			        $this->tpl->render( 'widget.tpl' );
	        	}
	        	else
	        	{
	        		if( $page_id == 0 )
	        		{
			        	$this->tpl->render( 'contact.tpl' );
	        		}
	        		else
	        		{
	        			$page = get_post( $page_id ); 
						echo apply_filters( 'the_content', $page->post_content);
	        		}
	        	}
	        }
	    }

	    private function agentView( $options = array() )
	    {
        	global $BISTRI_DESK_ERROR;

			require_once( plugin_dir_path( __FILE__ ) . 'session.php' );

			$login   = '';
			$errors  = array();
			$session = new Session();

	    	if( isset( $_POST[ 'action' ] ) )
	    	{
	    		if( $_POST[ 'action' ] == 'login' )
	    		{
		    		if( empty( $_POST[ 'agent_login' ] ) )
		    		{
		    			$errors[] = $BISTRI_DESK_ERROR[ '00501' ]; /* Login is missing */
		    		}
		    		else{
		    			$login = $_POST[ 'agent_login' ];
			    		if( empty( $_POST[ 'agent_password' ] ) )
			    		{
			    			$errors[] = $BISTRI_DESK_ERROR[ '00502' ]; /* Password is missing */
			    		}
			    		else{
			    			if( !$session->login( $_POST[ 'agent_login' ], $_POST[ 'agent_password' ] ) )
			    			{
				    			$errors[] = $BISTRI_DESK_ERROR[ '00503' ]; /* Bad login and/or password */
			    			}
			    		}
		    		}
	    		}
	    	}
	    	if( isset( $_GET[ 'action' ] ) )
	    	{
	    		if( $_GET[ 'action' ] == 'logout' )
	    		{
	    			if( !$session->logout() )
	    			{
	    				$errors[] = $BISTRI_DESK_ERROR[ '00505' ]; /* An error occurred while logout */
	    			}
	    		}
	    	}

			if( $session->isLogged() ){

				$queue = new Queue();
				$user = $session->info();
				$queueCount = $queue->count();

		        $options[ 'name' ]             = $user[ 'displayname' ];
		        $options[ 'id' ]               = $user[ 'uid' ];
		        $options[ 'pending_requests' ] = $this->use_queue ? (string) $queueCount[ 'data' ][ 'count' ] : '0';

				$this->tpl->logout = add_query_arg( array( 'action' => 'logout' ), get_permalink() );
				$this->tpl->name   = $user[ 'displayname' ];
				$this->tpl->header = $this->tpl->render( 'widget_header.tpl', true );
				$this->tpl->layout = $this->tpl->render( 'widgets/support/' . ( array_key_exists( 'layout', $options ) ? $options[ 'layout' ] : 'conference-bar-right' ) . '.tpl', true );
				$this->tpl->widget = 'widget.desk.support';
				$this->tpl->params = json_encode( $options );
				$this->tpl->errors = $errors;
				$this->tpl->render( 'widget.tpl' );
			}
			else{
				$this->tpl->login  = $login;
				$this->tpl->errors = $errors;
				$this->tpl->action = get_permalink();
			    $this->tpl->render( 'login.tpl' );
			}
	    }
	}
?>