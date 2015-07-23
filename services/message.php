<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/service.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/message.php' );

	class BistriMessage extends Service {

		public function message_get()
		{
			if( $this->validate( array(
				'customerid' => '00003' /* customerid param is missing */
			) ) )
			{
				$message      = new Message();
				$messageGet   = $message->get( $this->params[ 'customerid' ] );
				$this->data   = $messageGet[ 'data' ];
				$this->errors = $messageGet[ 'errors' ];
			}
		}

		public function message_send()
		{
			if( $this->validate( array(
				'customerid' => '00003', /* customerid param is missing */
				'message'    => '00004'  /* message param is missing */
			) ) )
			{
				$message      = new Message();
				$messageSend  = $message->send( $this->params[ 'customerid' ], $this->params[ 'message' ] );
				$this->data   = $messageSend[ 'data' ];
				$this->errors = $messageSend[ 'errors' ];
			}
		}
	}

?>