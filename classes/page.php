<?php

	require_once( plugin_dir_path( __FILE__ ) . 'db.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'template.php' );

	class Page {

		public $template = null;
		public $table    = null;
		public $tpl      = null;
		public $db       = null;
		public $data     = array();
		public $errors   = array();
		public $messages = array();
		public $params   = array();

	    public function __construct()
	    {
			$this->db  = new BistriDb( $this->table );
			$this->tpl = new Template();

	    	switch( $_SERVER['REQUEST_METHOD'] )
	    	{
	    		case "POST":
					foreach( $_POST as $key => $value )
					{
						if( preg_match( '/action[0-9]?/', $key ) and !empty( $value ) )
						{
							$this->params[ 'action' ] = $value;
						}
						else
						{
							$this->params[ $key ] = $value;
						}
					}
	    			break;
	    		case "GET":
					foreach( $_GET as $key => $value )
					{
						$this->params[ $key ] = $value;
					}
	    			break;
	    	}

	    	foreach( $this->data as $key => $value )
	    	{
	    		if( isset( $this->params[ $key ] ) )
	    		{
	    			$this->data[ $key ] = $this->params[ $key ];
	    		}
	    	}

			$this->tpl->form_action = isset( $_GET[ 'page' ] ) ?
				add_query_arg( array( 'page' => $_GET[ 'page' ]  ), admin_url( 'admin.php' ) ) : null;

	    	if( isset( $this->params[ 'action' ] ) && !empty( $this->params[ 'action' ] ) )
	    	{
	    		$this->tpl->action = $this->params[ 'action' ];
	    		if( method_exists( $this, $this->params[ 'action' ] ) ){
	    			call_user_func( array( $this, $this->params[ 'action' ] ) );
	    		}
	    	}
	    	else
	    	{
	    		$this->init();
	    	}

	    }

		public function validate( $expect = array() )
		{
			foreach( $expect as $key => $value )
			{
				if( !isset( $this->params[ $key ] ) or ( isset( $this->params[ $key ] ) and empty( $this->params[ $key ] ) ) )
				{
					$this->errors[] = $value;
				}
			}
			return count( $this->errors ) ? false : true;
		}

		public function prepareRender()
		{
			$messages = array();

			foreach( $this->data as $key => $value )
			{
				$this->tpl->$key = $value;
			}

			$this->tpl->errors = array_map( function( $code ){
				global $BISTRI_DESK_ERROR;
				return $BISTRI_DESK_ERROR[ $code ];
			},  $this->errors );

			$this->tpl->messages = array_map( function( $code ){
				global $BISTRI_DESK_MESSAGE;
				return $BISTRI_DESK_MESSAGE[ $code ];
			},  $this->messages );
		}

	    public function init()
	    {

	    }

		public function getPage()
		{
			$this->prepareRender();
			return $this->tpl->render( $this->template );
		}

		public function render()
		{
			$this->prepareRender();
			$this->tpl->render( $this->template );
		}

	}
?>