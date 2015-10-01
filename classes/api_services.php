<?php
    require_once( plugin_dir_path( __FILE__ ) . 'db.php' );

    class BistriApiServices {

        protected $apiKey   = null;
        protected $dbAgents = null;
        protected $apiUrl   = 'https://api.bistri.com';

        public function __construct()
        {
            $settings = get_option( 'bistri_desk_settings', array(
                'api_key' => '',
                'chrome_extension_id' => '',
                'firefox_extension_id' => ''
            ) );

            $this->apiKey = $settings[ 'api_key' ];
            $this->dbAgents = new BistriDb( 'bistri_desk_agents' );
        }

        public function getAgents()
        {
            return $this->dbAgents->get( array( 
                'id',
                'uid',
                'login',
                'firstname',
                'lastname'
            ) );
        }

        public function registerAgent( $agentId, $apiKey = null )
        {
            if ( empty( $this->apiKey ) )
            {
                return true;
            }
            else
            {
                $url = $this->apiUrl . '/agent/save?id=' . $agentId . '&api_key=' . ( $apiKey ? $apiKey : $this->apiKey );
                $result = $this->callRemoteService( $url );
                return !$result->error;
            }
        }

        public function unregisterAgent( $agentId, $apiKey = null )
        {
            if ( empty( $this->apiKey ) )
            {
                return true;
            }
            else
            {
                $url = $this->apiUrl . '/agent/remove?id=' . $agentId . '&api_key=' . ( $apiKey ? $apiKey : $this->apiKey );
                $result = $this->callRemoteService( $url );
                return !$result->error;
            }
        }

        public function registerAllAgents( $apiKey = null )
        {
            $agents = $this->getAgents();
            foreach( $agents as $agent )
            {
                $this->registerAgent( $agent[ 'uid' ], $apiKey );
            }
        }

        public function unregisterAllAgents( $apiKey = null )
        {
            $agents = $this->getAgents();
            foreach( $agents as $agent )
            {
                $this->unregisterAgent( $agent[ 'uid' ], $apiKey );
            }
        }

        public function callRemoteService( $url )
        {
            if ( file_get_contents( __FILE__ ) && ini_get( 'allow_url_fopen' ) )
            {
                $data = json_decode( file_get_contents( $url ) );
                if( is_null( $data ) )
                {
                    echo 'file_get_contents error: unexpected answer from server (' . $this->apiUrl . '), json expected';
                    return NULL;
                }
                return $data;
            }
            else if ( function_exists( 'curl_version' ) )
            {
                $curl = curl_init();
                curl_setopt( $curl, CURLOPT_URL, $url );
                curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
                $content = curl_exec( $curl );
                curl_close( $curl );
                $data = json_decode( $content );

                if( is_null( $data ) )
                {
                    echo 'cUrl error: unexpected answer from server (' . $this->apiUrl . '), json expected';
                    return NULL;
                }
                return $data;
            }
            else
            {
                echo 'Error: you have neither cUrl installed nor allow_url_fopen activated. Please setup one of those!';
                return NULL;
            }
        }
    }
?>