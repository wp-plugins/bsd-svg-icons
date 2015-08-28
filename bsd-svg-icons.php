<?php
    /*
        Plugin Name: SVG Icon Library
        Plugin URI: http://bigseadesign.com/
        Description: Built by designers, for designers. Add SVG images with ease, via a single function or shortcode. Comes with icons from icomoon!
        Version: 1.0.2
        Author: Big Sea
        Author URI: http://bigseadesign.com
    */

    define('BSD_SVG_PLUGIN_ROOT', dirname(__FILE__) );
    define('BSD_SVG_PLUGIN_BASENAME', plugin_basename(__FILE__) );
    require_once ( BSD_SVG_PLUGIN_ROOT . '/library/class.bsd_svg_icons.php' );

    if ( is_admin () ) {
        require_once ( BSD_SVG_PLUGIN_ROOT . '/library/admin.php' );
    }

    $bsd_svg_handler = false;

    add_action('init', 'bsd_svg_startup');
    if (!function_exists('bsd_svg_startup')) :
        /**
         *  bsd_svg_startup ()
         *
         *      Get this whole thing to start up at the start of Wordpress, because best time for it.
         *
         *  @param none
         *  @return none
         */
        function bsd_svg_startup ()
        {
            global $bsd_svg_handler;

            // Styles
            if ( !is_admin () ) wp_enqueue_style( 'bsdsvg-css', plugins_url('/assets/styles.css', __FILE__), false, '1.0', 'all' );

            if (!isset($bsd_svg_handler) || !is_object($bsd_svg_handler)) {
                $bsd_svg_handler = new BigSea_SVG_Icons ();
            }

            // Hooks
            add_action( 'wp_footer', array(&$bsd_svg_handler, 'wp_footer') );

            // Shortcodes
            add_shortcode( 'svg_icon', array(&$bsd_svg_handler, 'shortcode') );
        } // function
    endif;

    if (!function_exists('show_svg_icon')) :
        /**
         *  show_svg_icon ()
         *
         *      Get the icon requested by the user. Only technically front-facing function there is here that anyone should be using.
         *
         *  @param string $name
         *  @param mixed $library - string if specifying a library, true/leave blank if using default library, false if want to use root file
         *  @param bool $echo - true if echo (default), false if want the string returned.
         *  @return string if success, WP_Error otherwise
         */
        function show_svg_icon ( $name, $library=true, $incoming_args=array() )
        {
            $default_args = array (
                'echo' => true
            );
            $args = array_merge($default_args, $incoming_args);

            global $bsd_svg_handler;

            // This should NEVER happen, but it depends on if they bother to do this before the WP init call (impossible! [?])
            if ( !$bsd_svg_handler ) throw new WP_Error ( 'broke', 'Big Sea SVG Handler class is missing');

            // Let's give love a chance.
            $svg = $bsd_svg_handler->get( $name, $library );
            if ( $args['echo'] ) {
                echo $svg;
            }

            return $svg;
        } // function
    endif;