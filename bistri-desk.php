<?php

/*
    Plugin Name: Live Support Desk
    Plugin URI: http://plugins.bistri.com
    Description: Create a video conference in your posts
    Version: 1.3.5
    Author: Bistri
    Author URI: http://plugins.bistri.com
*/

/**
 * BistriDesk plugin class
 */

require_once( plugin_dir_path( __FILE__ ) . 'resources/messages.php' );

class BistriDesk {

    private static $dbTables = Array(
        'bistri_desk_agents' => Array(
            'version' => '0.5',
            'fields'  => '
                id mediumint(10) NOT NULL AUTO_INCREMENT,
                uid char(32) NOT NULL,
                login char(128) NOT NULL,
                firstname char(128) NOT NULL,
                lastname char(128) NOT NULL,
                password char(32) NOT NULL,
                UNIQUE KEY id (id)
            '
        ),
        'bistri_desk_roles' => Array(
            'version' => '0.5',
            'fields'  => '
                id mediumint(10) NOT NULL AUTO_INCREMENT,
                name char(128) NOT NULL,
                UNIQUE KEY id (id)
            '
        ),
        'bistri_desk_agents_roles' => Array(
            'version' => '0.8',
            'fields'  => '
                id mediumint(10) NOT NULL AUTO_INCREMENT,
                roleid mediumint(10) NOT NULL,
                agentid mediumint(10) NOT NULL,
                uid char(32) NOT NULL,
                UNIQUE KEY id (id)
            '
        ),
        'bistri_desk_queue' => Array(
            'version' => '0.6',
            'fields'  => '
                id mediumint(10) NOT NULL AUTO_INCREMENT,
                customerid char(32) NOT NULL,
                role mediumint(10) NOT NULL,
                status int(1) DEFAULT \'1\' NOT NULL,
                pushed datetime DEFAULT \'0000-00-00 00:00:00\' NOT NULL,
                popped datetime NULL,
                url char(255) NOT NULL,
                UNIQUE KEY id (id)
            '
        ),
        'bistri_desk_messages' => Array(
            'version' => '0.5',
            'fields'  => '
                id mediumint(10) NOT NULL AUTO_INCREMENT,
                customerid char(32) NOT NULL,
                message text NOT NULL,
                date datetime DEFAULT \'0000-00-00 00:00:00\' NOT NULL,
                status int(1) DEFAULT \'1\' NOT NULL,
                UNIQUE KEY id (id)
            '
        )
    );

    /**
     * Construct the plugin object
     */
    public function __construct()
    {
        /* Admin + settings */
        add_action( 'admin_init', array( $this, 'addSettings' ) );
        add_action( 'admin_menu', array( $this, 'addMenus' ) );
        add_action( 'plugins_loaded', array( $this, 'updateDbCheck' ) );

        /* Post/Page Edit  */
        add_action( 'media_buttons_context', array( $this, 'addEditorButton' ) );
        add_action( 'edit_form_after_editor', array( $this, 'addThickbox' ) );
    
        /* Rest api management */
        add_filter( 'query_vars', array( $this, 'addQueryVars' ) );
        add_action( 'parse_request', array( $this, 'sniffRequests' ) );
        add_action( 'init', array( $this, 'addEndpoint' ) );

        /* Start session earlier as possible */
        add_action( 'init', array( $this, 'registerSession' ) );

        /* Load resources: JS, CSS */
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueHTMLResources' ) );

        /* declare shortcodes */
        add_shortcode( 'bistridesk', array( $this, 'shortcodeHandler' ) );
    }

    /**
     * When plugin is activated
     */
    public static function onActivate()
    {
        global $wpdb;

        foreach( self::$dbTables as $name => $table )
        {
            self::setTable( $name, $table );
        }
        if( !get_option( "bistri_desk_data_inserted", false ) )
        {
            $wpdb->insert( 
                $wpdb->prefix . 'bistri_desk_roles', 
                array( 
                    'name' => 'Support'
                )
            );
            update_option( "bistri_desk_data_inserted", true );
        }
        if( !get_option( "bistri_desk_plugin_id" ) )
        {
            update_option( "bistri_desk_plugin_id", uniqid() );
        }
    }

    /**
     * when plugin is desactivated
     */     
    public static function onDesactivate()
    {
    }

    /**
     * when plugin is uninstalled
     */     
    public static function onUninstall()
    {
        global $wpdb;
        delete_option( 'bistri_desk_settings' );
        delete_option( 'bistri_desk_use_page' );
        delete_option( 'bistri_desk_use_queue' );
        delete_option( 'bistri_desk_data_inserted' );   
        foreach( self::$dbTables as $key => $value ){
            $wpdb->query( "DROP TABLE IF EXISTS $wpdb->prefix$key" );
        }
    }

    /**
     * 
     */    
    public function addMenus()
    {
        add_options_page( 'Live Support Desk', 'Live Support Desk', 'manage_options', 'bistri_desk_settings', array( $this, 'settingsPage' ) );

        add_menu_page( __( 'Live Support Desk', 'bistridesk' ), 'Live Support Desk', 'manage_options', 'bistri_desk', array( $this, 'supportPage' ), plugins_url( 'live-support-desk/images/icon_desk_bell_white_20x14.png' ) );

        add_submenu_page( 'bistri_desk', __( 'Support Desk', 'bistridesk' ), __( 'Support Desk', 'bistridesk' ), 'manage_options', 'bistri_desk', array( $this, 'supportPage' ) );

        add_submenu_page( 'bistri_desk', __( 'Manage Agents', 'bistridesk' ), __( 'Manage Agents', 'bistridesk' ), 'manage_options', 'bistri_desk_agents', array( $this, 'listAgentsPage' ) );
        add_submenu_page( 'bistri_desk', __( 'Add Agent', 'bistridesk' ), __( 'Add Agent', 'bistridesk' ), 'manage_options', 'bistri_desk_agent_add', array( $this, 'addAgentPage' ) );

        add_submenu_page( 'bistri_desk', __( 'Manage Roles', 'bistridesk' ), __( 'Manage Roles', 'bistridesk' ), 'manage_options', 'bistri_desk_roles', array( $this, 'listRolesPage' ) );
        add_submenu_page( 'bistri_desk', __( 'Add Role', 'bistridesk' ), __( 'Add Role', 'bistridesk' ), 'manage_options', 'bistri_desk_role_add', array( $this, 'addRolePage' ) );

        add_submenu_page( 'bistri_desk', __( 'Manage Account', 'bistridesk' ), __( 'Manage Account', 'bistridesk' ), 'manage_options', 'bistri_desk_manage_plan', array( $this, 'managePlanPage' ) );
    }

    public function updateDbCheck()
    {
        foreach( self::$dbTables as $name => $table )
        {
            if ( get_option( $name . '_version' ) != $table[ 'version' ] )
            {
               self::setTable( $name, $table );
            }
        }
    }

    public function registerSession()
    {
        if( !session_id() )
        {
            session_start();
        }
    }

    public function addSettings()
    {
        register_setting( 'bistri_desk', 'bistri_desk_settings', array( $this, 'sanitizeSetting' ) );
        register_setting( 'bistri_desk', 'bistri_desk_use_page' );
        register_setting( 'bistri_desk', 'bistri_desk_use_queue' );
    }

    /**
     * 
     */  
    private static function setTable( $name, $table )
    {
        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $prefixedTableName = $wpdb->prefix . $name;
        $charsetCollate    = $wpdb->get_charset_collate();
        dbDelta( "CREATE TABLE $prefixedTableName (" . $table[ 'fields' ] . ") $charsetCollate;" );
        update_option( $name . "_version", $table[ 'version' ] );
    }

    /**
     * 
     */ 
    function enqueueHTMLResources( $suffix )
    {
        switch( $suffix )
        {
            case 'post.php':
            case 'post-new.php':
                wp_enqueue_script( 'bistri_desk_editor_js', plugins_url( '/js/admin/configurator.js', __FILE__ ) );
                wp_enqueue_style( 'bistri_desk_editor_css', plugins_url( '/css/admin/configurator.css', __FILE__ ) );
                $settings = get_option( 'bistri_desk_settings', array(
                    'api_key' => '',
                    'chrome_extension_id' => '',
                    'firefox_extension_id' => ''
                ) );
                echo "
                <script type=\"text/javascript\">
                    window.addEventListener( \"load\", function(){
                        new BD_shortCodeGenerator( {
                            apiKey : \"{$settings[ 'api_key' ]}\",
                            chromeExtId  : \"{$settings[ 'chrome_extension_id' ]}\",
                            firefoxExtId  : \"{$settings[ 'firefox_extension_id' ]}\"
                        } );
                    } );
                </script>";
                break;
        }
    }

    /**
     *********************************************************************************
     */

    public function addEditorButton( $context ) {
        $title = __( 'Live Support Desk', 'bistridesk' );
        $context .= "<a href=\"#TB_inline?width=600&height=475&inlineId=bistri_desk_configurator\" class=\"button thickbox bistri-desk-bt\" title=\"$title\"><span></span>Live Support Desk</a>";
        return $context;
    }

    public function addThickbox() {
        require_once( plugin_dir_path( __FILE__ ) . 'pages/configurator.php' );
        $page = new BistriConfigurator();
        $page->render();
    }

    /**
     *********************************************************************************
     */

    public function shortcodeHandler( $options )
    {
        require_once( plugin_dir_path( __FILE__ ) . 'classes/shortcode.php' );
        new BistriShortcode( $options );
    }

    /**
     *********************************************************************************
     */

    /**
     * 
     */     
    public function listAgentsPage()
    {
        if( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        require_once( plugin_dir_path( __FILE__ ) . 'pages/agents_list.php' );
        $page = new BistriAgents();
        $page->render();
    }

    /**
     * 
     */     
    public function addAgentPage()
    {
        if( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        require_once( plugin_dir_path( __FILE__ ) . 'pages/add_agent.php' );
        $page = new BistriAddAgent();
        $page->render();
    }

    /**
     * 
     */     
    public function listRolesPage()
    {
        if( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        require_once( plugin_dir_path( __FILE__ ) . 'pages/roles_list.php' );
        $page = new BistriRoles();
        $page->render();
    }

    /**
     * 
     */     
    public function addRolePage()
    {
        if( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        require_once( plugin_dir_path( __FILE__ ) . 'pages/add_role.php' );
        $page = new BistriAddRole();
        $page->render();
    }

    /**
     * 
     */     
    public function supportPage()
    {
        if( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        require_once( plugin_dir_path( __FILE__ ) . 'pages/support.php' );
        $page = new BistriSupport();
        $page->render();
    }

    /**
     * 
     */     
    public function settingsPage()
    {
        if( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        require_once( plugin_dir_path( __FILE__ ) . 'pages/settings.php' );
        $page = new BistriSettings();
        $page->render();
    }

    /**
     * 
     */ 
    public function managePlanPage()
    {
        if( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        require_once( plugin_dir_path( __FILE__ ) . 'pages/manage.php' );
        $page = new BistriManagePlan();
        $page->render();
    }

    /**
     * 
     */ 
    public function sanitizeSetting( $input )
    {
        global $BISTRI_DESK_ERROR;

        $output = get_option( 'bistri_desk_settings', array(
            'api_key' => '',
            'chrome_extension_id' => '',
            'firefox_extension_id' => ''
        ) );

        foreach( $input as $key => $value )
        {
            if( isset( $input[ $key ] ) )
            {
                $value = preg_replace( '/\s+/', '', $value );
                //if ( $key == 'api_key' and strlen( $value ) != 32 )
                //{
                //    add_settings_error( 'bistri_desk', 'invalid-value', $BISTRI_DESK_ERROR[ '00302' ] ); /* You have entered an invalid api key. */
                //}
                //else
                //{
                    $output[ $key ] = $value;
                //}
            }
        }
        return $output;
    }

    /**
     *********************************************************************************
     */

    /** Add public query vars
    *   @param array $vars List of current public query vars
    *   @return array $vars 
    */
    public function addQueryVars( $vars ){
        $vars[] = '_bistri_desk';
        $vars[] = 'action';
        return $vars;
    }
    
    /** Add API Endpoint
    *   This is where the magic happens - brush up on your regex skillz
    *   @return void
    */
    public function addEndpoint(){
        add_rewrite_rule( '^bistri-desk/?([0-9]+)?/?', 'index.php?_bistri_desk=1&action=$matches[1]', 'top' );
    }

    /** Sniff Requests
    *   This is where we hijack all API requests
    *   If $_GET['__api'] is set, we kill WP and serve up pug bomb awesomeness
    *   @return die if API request
    */
    public function sniffRequests(){
        global $wp;
        if( isset( $wp->query_vars[ '_bistri_desk' ] ) )
        {
            $this->restRequestHandler();
            exit;
        }
    }

    protected function restRequestHandler(){

        global $wp, $BISTRI_DESK_ERROR;

        if( $action = $wp->query_vars[ 'action' ] )
        {
            switch( $action )
            {
                case 'user_login':
                case 'user_logout':
                case 'user_islogged':
                    require_once( plugin_dir_path( __FILE__ ) . 'services/auth.php' );
                    $service = new BistriAuth();
                    $this->sendResponse( $service->getJSON() );
                    break;
                case 'queue_add':
                case 'queue_abort':
                case 'queue_next':
                case 'queue_count':
                    require_once( plugin_dir_path( __FILE__ ) . 'services/queue.php' );
                    $service = new BistriQueue();
                    $this->sendResponse( $service->getJSON() );
                    break;
                case 'presence_get':
                    require_once( plugin_dir_path( __FILE__ ) . 'services/presence.php' );
                    $service = new BistriPresence();
                    $this->sendResponse( $service->getJSON() );
                    break;
                case 'message_get':
                case 'message_send':
                    require_once( plugin_dir_path( __FILE__ ) . 'services/message.php' );
                    $service = new BistriMessage();
                    $this->sendResponse( $service->getJSON() );
                    break;
                default:
                    $this->sendResponse( '{ "data": false, "errors":[ { "text": "' . $BISTRI_DESK_ERROR[ '20002' ] . '", code: "00002" } ] }' ); /* unknown action */
                    break;
            }
        }
        else
        {
            $this->sendResponse( '{ "data": false, "errors":[ { "text": "' . $BISTRI_DESK_ERROR[ '20001' ] . '", code: "00001" } ] }' ); /* missing action */
        }
    }
    
    protected function sendResponse( $response )
    {
        header( 'content-type: application/json; charset=utf-8' );
        header( 'Access-Control-Allow-Origin: *' );
        echo $response . "\n";
        exit;
    }
}

register_activation_hook( __FILE__, array( 'BistriDesk', 'onActivate' ) );
register_deactivation_hook( __FILE__, array( 'BistriDesk', 'onDesactivate' ) );
register_uninstall_hook( __FILE__, array( 'BistriDesk', 'onUninstall' ) );


$bistriDesk = new BistriDesk();
?>