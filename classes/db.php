<?php
	class BistriDb {

		private $tableName = '';

	    /**
	     * Construct the object
	     */
	    public function __construct( $name )
	    {
	    	$this->tableName = $name;
	    }

		private function getPrefixedName()
		{
			global $wpdb;

			return $wpdb->prefix . $this->tableName;
		}

	    /**
	     * 
	     */  
		private function create( $data )
		{
			global $wpdb;

			return $wpdb->insert(
				$this->getPrefixedName(),
				$data
			) ? $wpdb->insert_id : false;
		}

	    /**
	     * 
	     */  
		private function update( $data, $conditions )
		{
			global $wpdb;

			return gettype ( $wpdb->update(
				$this->getPrefixedName(),
				$data,
				$conditions
			) ) === 'integer' ? true : false;
		}

	    /**
	     * 
	     */  
		public function save( $data, $conditions = null )
		{
			if( $conditions )
			{
				return $this->update( $data, $conditions );
			}
			else
			{
				return $this->create( $data );
			}
		}

	    /**
	     * 
	     */  
		public function delete( $id )
		{
			global $wpdb;

			return $wpdb->delete(
				$this->getPrefixedName(), array( 'id' => $id )
			);
		}

	    /**
	     * 
	     */  
		public function deleteAll( $conditions = array() )
		{
			global $wpdb;

			return $wpdb->delete(
				$this->getPrefixedName(), $conditions
			);
		}

	    /**
	     * 
	     */  
		public function get( $fields = array(), $conditions = array() )
		{

			global $wpdb;

			if( count( $fields ) )
			{
				$what = '';

				foreach( $fields as $field )
				{
					if( strlen( $what ) )
					{
						$what .= ' ,';
					}
					$what .= $field;
				}
			}
			else
			{
				$what = '*';
			}

			if( count( $conditions ) )
			{
				$where = '';

				foreach( $conditions as $key => $value )
				{
					if( strlen( $where ) )
					{
						$where .= ' and ';
					}
					if( is_array( $value ) )
					{
						$or = '';
						foreach( $value as $item )
						{
							if( strlen( $or ) )
							{
								$or .= ' or ';
							}
							$or .= $key . "='" . $item . "'";
						}
						$where .= '(' . $or . ')';
					}
					else
					{
						$where .= $key . "='" . $value . "'";
					}
				}
				$where = ' WHERE ' . $where;
			}
			else
			{
				$where = '';
			}
			return $wpdb->get_results( "SELECT " . $what . " FROM " . $this->getPrefixedName() . $where, ARRAY_A );
		}

	    /**
	     * 
	     */  
		public function find( $conditions = array() )
		{
			global $wpdb;

			$where = '';

			foreach( $conditions as $key => $value )
			{
				if( strlen( $where ) )
				{
					$where .= ' and ';
				}
				if( is_array( $value ) )
				{
					$or = '';
					foreach( $value as $item )
					{
						if( strlen( $or ) )
						{
							$or .= ' or ';
						}
						$or .= $key . "='" . $item . "'";
					}
					$where .= '(' . $or . ')';
				}
				else
				{
					$where .= $key . "='" . $value . "'";
				}
			}
			return $wpdb->get_row( "SELECT * FROM " . $this->getPrefixedName() . " WHERE " . $where, ARRAY_A );
		}

	    /**
	     * 
	     */  
		public function count( $conditions = array() )
		{
			global $wpdb;

			$where = '';

			foreach( $conditions as $key => $value )
			{
				if( strlen( $where ) )
				{
					$where .= ' and ';
				}
				if( is_array( $value ) )
				{
					$or = '';
					foreach( $value as $item )
					{
						if( strlen( $or ) )
						{
							$or .= ' or ';
						}
						$or .= $key . "='" . $item . "'";
					}
					$where .= '(' . $or . ')';
				}
				else
				{
					$where .= $key . "='" . $value . "'";
				}
			}
			return $wpdb->get_var( "SELECT COUNT(*) FROM " . $this->getPrefixedName() . " WHERE " . $where );
		}

		public function customGet( $sql )
		{
			global $wpdb;

			return $wpdb->get_results( $sql );
		}


	}
?>