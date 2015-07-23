<?php

	require_once( plugin_dir_path( __FILE__ ) . '../classes/service.php' );
	require_once( plugin_dir_path( __FILE__ ) . '../classes/queue.php' );

	class BistriQueue extends Service {

		public function queue_add()
		{
			if( $this->validate( array(
				'customerid' => '00003'
			) ) )
			{
				$queue        = new Queue();
				$addToQueue   = $queue->add( $this->params[ 'customerid' ], $this->params[ 'role' ], isset( $_SERVER[ 'HTTP_REFERER' ] ) ? $_SERVER[ 'HTTP_REFERER' ] : "" );
				$this->data   = $addToQueue[ 'data' ];
				$this->errors = $addToQueue[ 'errors' ];
			}
		}

		public function queue_abort()
		{
			if( $this->validate( array(
				'customerid' => '00003'
			) ) )
			{
				$queue        = new Queue();
				$queueAbort   = $queue->abort( $this->params[ 'customerid' ] );
				$this->data   = $queueAbort[ 'data' ];
				$this->errors = $queueAbort[ 'errors' ];
			}
		}

		public function queue_next()
		{
			$queue        = new Queue();
			$queueNext    = $queue->next();
			$this->data   = $queueNext[ 'data' ];
			$this->errors = $queueNext[ 'errors' ];
		}

		public function queue_count()
		{
			$queue        = new Queue();
			$queueCount   = $queue->count();
			$this->data   = $queueCount[ 'data' ];
			$this->errors = $queueCount[ 'errors' ];
		}

	}

?>