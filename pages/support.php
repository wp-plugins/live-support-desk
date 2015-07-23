<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/page.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/template.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/queue.php' );

	class BistriSupport extends Page {

		public $template = 'support.tpl';

		public function init()
		{
			global $display_name, $user_identity;

			$tpl = new Template();
			$queue = new Queue();
			$queueCount = $queue->count();

			$url = get_bloginfo( 'url' );
			$adminEmail = get_option( 'admin_email' );
 			$use_queue = get_option( 'bistri_desk_use_queue', 0 );
	        $settings = get_option( 'bistri_desk_settings', array(
	            'api_key' => '',
	            'chrome_extension_id' => '',
	            'firefox_extension_id' => ''
	        ) );

	        $options = array(
	        	'name'             => $display_name ? $display_name : $user_identity,
	        	'chat'             => 'true',
	        	'media_controls'   => 'true',
	        	'screen_sharing'   => 'true',
	        	'device'           => '640x480',
	        	'capacity'         => '2',
	        	'id'               => hash( 'md5', $adminEmail ),
	            'appkey'           => $settings[ 'api_key' ],
	            'appid'            => get_option( "bistri_desk_plugin_id" ),
	            'chromeExtId'      => $settings[ 'chrome_extension_id' ],
	            'firefoxExtId'     => $settings[ 'firefox_extension_id' ],
	            'usequeue'         => $use_queue ? 'true' : 'false',
	            'pending_requests' => $use_queue ? (string) $queueCount[ 'data' ][ 'count' ] : '0',
	            'wpurl'            => $url,
	            'debug'            => 'true'
	        );

			//$this->data[ 'layout' ] = $use_queue ? $tpl->render( 'widgets/support/conference-bar-right.tpl', true ) : '';
			$this->data[ 'layout' ] = $tpl->render( 'widgets/support/conference-bar-right.tpl', true );
			$this->data[ 'widget' ] = 'widget.desk.support';
			$this->data[ 'params' ] = json_encode( $options );

			if( empty( $settings[ 'api_key' ] ) ){
				$this->errors[] = '00304';
			}
		}
	}
?>