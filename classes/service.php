<?php

	require_once( plugin_dir_path( __FILE__ ) . 'db.php' );

	class Service {

		public $template = null;
		public $table    = null;
		public $db       = null;
		public $params   = array();
		public $data     = array();
		public $errors   = array();
		public $json     = array(
			'data'   => null,
			'errors' => null
		);

	    public function __construct()
	    {
			$this->db  = new BistriDb( $this->table );

	    	switch( $_SERVER['REQUEST_METHOD'] )
	    	{
	    		case "POST":
					foreach( $_POST as $key => $value )
					{
						$this->params[ $key ] = $value;
					}
	    			break;
	    		case "GET":
					foreach( $_GET as $key => $value )
					{
						$this->params[ $key ] = $value;
					}
	    			break;
	    	}

	    	if( isset( $this->params[ 'action' ] ) && !empty( $this->params[ 'action' ] ) )
	    	{
	    		if( method_exists( $this, $this->params[ 'action' ] ) )
	    		{
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

	    public function init()
	    {

	    }

		public function getJSON()
		{
			return json_encode( $this->getData() );
		}

		public function getData()
		{
        	global $BISTRI_DESK_ERROR;

        	$errors = array();
        	foreach( $this->errors as $code )
        	{
        		$errors[] = array( 
        			'code' => $code,
        			'text' => $BISTRI_DESK_ERROR[ $code ]
        		);
        	}
			$this->json[ 'data' ] = count( $this->errors ) ? false : $this->data;
			$this->json[ 'errors' ] = count( $this->errors ) ? $errors : false;
			return $this->json;
		}


	}
?>