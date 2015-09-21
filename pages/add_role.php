<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/page.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/role.php' );

	class BistriAddRole extends Page {

		public $template = 'add_role.tpl';
		public $table    = 'bistri_desk_roles';
		public $data     = array(
			'id'        => null,
			'name'    => null,
		);

		public function edit()
		{
			$role = new Role();
		    if( $roleEdit = $role->edit( $this->params[ 'id' ] ) ){
		    	foreach( $roleEdit as $key => $value )
		    	{
		    		$this->data[ $key ] = $roleEdit[ $key ];
		    	}
		    }
		}

		public function add()
		{
			if( $this->validate( array(
				'name' => '00701', /* Name is missing */
			) ) )
			{
				$this->save( array(
					'name' => $this->params[ 'name' ],
				), 'added' );
			}
			else
			{
				require_once( ABSPATH . 'wp-admin/admin-header.php' );
			}
		}

		public function update()
		{
			if( $this->validate( array(
				'id'   => '00702', /* Id is missing */
				'name' => '00701', /* Name is missing */
			) ) )
			{
				$data = array(
					'id'   => $this->params[ 'id' ],
					'name' => $this->params[ 'name' ],
				);
				$this->save( $data, 'updated' );
			}
			else
			{
				require_once( ABSPATH . 'wp-admin/admin-header.php' );
			}
		}

		public function save( $fields = array(), $action )
		{
			$role = new Role();
			if( $role->save( 
				$fields,
				isset( $this->params[ 'id' ] ) ?
					array( 'id' => $this->params[ 'id' ] ) : null )
			)
			{
				wp_safe_redirect( add_query_arg( array( 
					'page' => 'bistri_desk_roles',
					'action' => $action
				), admin_url( 'admin.php' ) ) );
				exit;
			}
		}
	}
?>