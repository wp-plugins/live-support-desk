<?php
	require_once( plugin_dir_path( __FILE__ ) . 'db.php' );

	class Message {

		protected $db = null;

	    public function __construct()
	    {
      		$this->db = new BistriDb( 'bistri_desk_messages' );
	    }

		public function get( $customerid = null )
		{
	    	$answer = array(
	    		'data'  => array(),
	    		'errors' => array()
	    	);

			if( $result = $this->db->get( array(
				'customerid',
				'message',
				'date'
			), array(
				'customerid' => $customerid,
				'status'     => 1
			) ) )
			{
				if( $this->db->save( array(
					'status' => 2
				), array(
					'customerid' => $customerid,
					'status'     => 1
				) ) )
				{
					$answer[ 'data' ] = $result;
				}
				else
				{
					$answer[ 'errors' ][] = '00202'; /* Fail to update message(s) status */
				}
			}
			else
			{
				$answer[ 'data' ] = array();
			}
			return $answer;
		}


		public function send( $id, $message )
		{
	    	$answer = array(
	    		'data'  => array(),
	    		'errors' => array()
	    	);

			if( $result = $this->db->save( array(
				'customerid' => $id,
				'message'    => $message,
				'date'       => date( "Y-m-d H:m:s" )
			) ) )
			{
				$answer[ 'data' ] = true;
			}
			else
			{
				$answer[ 'errors' ][] = '00201'; /* Fail to store message */
			}
			return $answer;
		}
	}
?>