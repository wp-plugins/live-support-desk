<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/page.php' );

	class BistriSettings extends Page {

		public $template = 'settings.tpl';

		public function init()
		{
			$settings = get_option( 'bistri_desk_settings', array(
				'api_key' => '',
				'chrome_extension_id' => '',
				'firefox_extension_id' => ''
			) );

			$this->data[ 'use_page' ] = get_option( 'bistri_desk_use_page', 0 );
			$this->data[ 'use_queue' ] = get_option( 'bistri_desk_use_queue', 0 );
			$this->data[ 'pages' ] = get_pages();
			$this->data[ 'api_key' ] = $settings[ 'api_key' ];
			$this->data[ 'chrome_extension_id' ] = $settings[ 'chrome_extension_id' ];
			$this->data[ 'firefox_extension_id' ] = $settings[ 'firefox_extension_id' ];
		}
	}

?>