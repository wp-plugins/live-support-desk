<?php

	require_once( plugin_dir_path( __FILE__ ) . 'db.php' );

	class Presence {

		protected $db          = null;
		protected $apiPresence = 'https://api.bistri.com/onlineusers/';

	    public function __construct()
	    {
      		$this->db = new BistriDb( 'bistri_desk_agents' );
	    }

	    public function getConnected()
	    {
	    	$settings = get_option( 'bistri_desk_settings' );
	    	$answer     = array(
	    		'users'  => array(),
	    		'errors' => array()
	    	);

			if( !$settings or empty( $settings[ 'api_key' ] ) )
			{
				$answer[ 'errors' ][] = '00301'; /* Plugin settings are missing */
				return;
			}

			//if( $users = $this->db->get( array( 'uid' ) ) )
			//{
				$users = $this->db->get( array( 'uid' ) );
				$adminEmail = get_option( 'admin_email' );
				$query = empty( $adminEmail ) ? '' : 'id=' . hash( 'md5', $adminEmail );
				foreach( $users as $user )
				{
					if( strlen( $query ) )
					{
						$query .= '&';
					}
					$query .= 'id=' . $user[ 'uid' ];
				}

				$url = $this->apiPresence . get_option( "bistri_desk_plugin_id" ) . "/" . $settings[ 'api_key' ] . "/?" . $query;

				foreach ( json_decode( file_get_contents( $url ) ) as $user ) {
					if( $user->presence == 'online' ){
						$answer[ 'users' ][] = $user->id;
					}
				}
			//}
			//else
			//{
			//	$answer[ 'errors' ][] = '00401'; /* No support agent defined */
			//}
			return $answer;
	    }
	}
?>