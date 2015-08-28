<?php
    $bsd_svg_admin = new BSD_SVG_Admin ();

    define('BSD_SVG_ADMIN_TEMPLATES', BSD_SVG_PLUGIN_ROOT . '/library/templates' );

    class BSD_SVG_Admin {

        protected $documentation_slug = 'bsd-svg-documentation';

        function __construct ()
        {
            // Styles
            wp_enqueue_style( 'bsd-icons-admin', plugins_url('../assets/admin.css', __FILE__), false, '1.0', 'all' );

            // Filters
            add_filter( 'plugin_action_links_' . BSD_SVG_PLUGIN_BASENAME, array(&$this, 'add_action_links') );
            
            // Actions
            add_action ( 'admin_menu', array(&$this, 'register_submenu_page') );

        } // function

        public function register_submenu_page () {
            add_submenu_page( 
                'themes.php', 
                'SVG Icons Documentation', 
                'SVG Icons', 
                'read', 
                $this->documentation_slug, 
                array(&$this, 'documentation') 
            );
        } // funciton

        public function add_action_links ( $admin_links ) {
            $new_links = array(
                '<a href="' . admin_url( 'themes.php?page=' . $this->documentation_slug ) . '">Documentation</a>',
            );
            return array_merge( $new_links, $admin_links );
        } // function

        public function documentation () {
            require_once ( BSD_SVG_ADMIN_TEMPLATES . '/documentation.php' );
        } // function
    } // class

?>