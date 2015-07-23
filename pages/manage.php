<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/page.php' );

	class BistriManagePlan extends Page {

		public $template = 'manage.tpl';

		public function init()
		{
			$settings = get_option( 'bistri_desk_settings', array(
				'api_key' => '',
				'chrome_extension_id' => '',
				'firefox_extension_id' => ''
			) );

			$this->data[ 'plugin_id' ] = get_option( 'bistri_desk_plugin_id', 0 );
			$this->data[ 'api_key' ] = $settings[ 'api_key' ];
		}
	}

?>