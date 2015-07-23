<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/page.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/role.php' );

	class BistriConfigurator extends Page {

		public $template = 'configurator.tpl';

		public function init()
		{
			$this->loadRoles();
		}

		public function edit()
		{
			$this->loadRoles();
		}

		private function loadRoles()
		{
			$role = new Role();
			$this->data[ 'roles' ] = $role->get();
		}
	}
?>