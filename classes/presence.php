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

				$remoteUsers = $this->getRemotePresence( $url );

				foreach ( $remoteUsers as $user ) {
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

	    public function getRemotePresence( $url )
	    {
			if ( file_get_contents( __FILE__ ) && ini_get( 'allow_url_fopen' ) )
			{
				$data = json_decode( file_get_contents( $url ) );
				if( is_null( $data ) )
				{
					echo 'file_get_contents error: unexpected answer from server (api.bistri.com), json expected';
					return array();
				}
				return $data;
			}
			else if ( function_exists( 'curl_version' ) )
			{
			    $curl = curl_init();
			    curl_setopt( $curl, CURLOPT_URL, $url );
			    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			    $content = curl_exec( $curl );
			    curl_close( $curl );
				$data = json_decode( $content );

				if( is_null( $data ) )
				{
					echo 'cUrl error: unexpected answer from server (api.bistri.com), json expected';
					return array();
				}
				return $data;
			}
			else
			{
			    echo 'Error: you have neither cUrl installed nor allow_url_fopen activated. Please setup one of those!';
			    return array();
			}
	    }
	}
?>