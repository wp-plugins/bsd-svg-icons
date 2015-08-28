<?php

/* 
    BigSea SVG Icons class

        Handles all of the display, verification, and output of the SVG icons.

    @package bsd-svg-icons
 */

define('BSD_SVG_PATH', BSD_SVG_PLUGIN_ROOT . '/svg' );

if (!defined('BSD_DEBUG')) {
    define( 'BSD_DEBUG', false );
}

class BigSea_SVG_Icons
{
    public $svgs;

    protected $default_icon;
    protected $default_library;
    protected $theme_path;

    /**
     *  __construct ()
     *
     *      Sets up the plugin at the basic level
     *
     *  @param none
     *  @return none
     */
    public function __construct ()
    {

        require_once ( BSD_SVG_PLUGIN_ROOT . '/library/class.svg_handler.php' );

        $default_icon_settings = array( 
            'name' => false, 
            'library' => true 
        );

        // Let's set up the basics.
        $this->svgs = array();

        // Let them have a default icon, and add their own icons.
        $this->default_library = apply_filters( 'bsd_svg_default_library', 'icomoon-free' );
        $this->default_icon = $this->validate_icon_default(apply_filters( 'bsd_svg_default_icon', $default_icon_settings ));
        
        // Check, by default, in the /svg path of their theme, but allow them to override
        $this->theme_path = apply_filters( 'bsd_svg_theme_path', get_template_directory() . '/svg' );

    } // function

    /**
     *  get ()
     *
     *      If the icon exists in either of the directories, display it. If not found, check for default, otherwise, error if debug mode turned on
     *
     *  @param string $name
     *  @return string
     */
    public function get ( $name, $library ) 
    {
        $output = false;

        $icon = $this->find( $name, $library );
        if ( !$icon && $this->default_icon ) {
            extract( $this->default_icon );
            $icon = $this->find( $name, $library );
        }

        $classes = apply_filters("bsd_svg_classes", "bsd-icon icon-".$name." icon-".$icon."");

        ob_start ();

            do_action ( 'bsd_pre_svg' );
   
            if ( $icon ) {
                $svg = new SVG_Handler( $this->svgs[$icon]['path'] );
                echo '
                <svg class="'.$classes.'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <use xlink:href="#bsd-icon-'.$icon.'" viewbox="'.$svg->get_viewBox().'"></use>
                </svg>
                ';
            }
            elseif ( BSD_DEBUG ) {
                echo '[bsd-svg] ERROR';
            }
            
            do_action ( 'bsd_after_svg' );

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    } // function

    /*
        WORDPRESS HOOKS/ACTIONS/SHORTCODES
    */

    /**
     *  shortcode ()
     *
     *      Handles the display of the shortcode, so this code can be used in the content
     *
     *  @uses wp_shortcode
     *  @param none
     *  @return string
     */
    public function shortcode ( $atts ) 
    {
        $atts = shortcode_atts( array(
            'name' => false,
            'library' => true
        ), $atts, 'svg_icon' );
        
        extract ( $atts );

        if ( !$name ) {
            if ( BSD_DEBUG ) return '[bsd-svg|shortcode] NOT FOUND';
            return '';
        }

        return $this->get ( $name, $library );
    } // function


    /**
     *  wp_footer
     *
     *      Hook for the wp_footer action. Outputs the SVG definitions for all icons used for this page.
     *
     *  @uses wp_action
     *  @param none
     *  @return none
     */
    public function wp_footer ()
    {
        if (count($this->svgs) == 0) return;
        // SVG Definitions

        ob_start(); 
        //style="position: absolute; width: 0; height: 0;" width="0" height="0"
        ?>
        <svg style="position: absolute; width: 0; height: 0;" width="0" height="0" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>

                <?php 
                    foreach ( $this->svgs as $hash => $data ) : 
                        $svg = new SVG_Handler( $data['path'] );
                        ?>
                        
                        <symbol id="bsd-icon-<?php echo $hash; ?>" viewbox="<?php echo $svg->get_viewBox(); ?>">
                            <title><?php echo $data['name']; ?></title>
                            <?php echo $svg->get_paths(); ?>
                        </symbol>
                        
                        <?php 
                    endforeach; 
                ?>

            </defs>
        </svg>
        <script>
            (function ($) {
                $('.bsd-icon use').each(function(){
                    $(this).attr('xlink:href',$(this).attr('xlink:href'));
                });
            })(jQuery);
        </script>
        <?php 

        $output = ob_get_contents();
        ob_end_clean ();
    
        echo $output;
    } // function

    /*
        PRIVATE FUNCTIONS
    */

    /**
     *  find ()
     *
     */
    private function find ( $name, $library ) {
        
        // If "library" is false (set by user), then just look for this file in the 'root' directory
        // User SPECIFICALLY wants the root file (so ignore default library)
        if ( !$library && ($path = $this->validate_icon( $name )) !== false ) {
            // File exists in the root of one of the theme folders.
            $this->add($name, false, $path);
            return $name;
        }

        // Now, let's try to verify this stuff.
        if ( !is_string($library) ) {
            $library = $this->default_library;
        }

        // check the default library's path
        if ( ($path = $this->validate_icon($library . '/' . $name)) !== false ) {
            $this->add( $name, $library, $path );
            return $library . '-' . $name;
        }
        
        // check the root.
        if ( ($path = $this->validate_icon($name)) !== false ) {
            $this->add( $name, false, $path );
            return $name;
        }

        return false;

    } // function

    /**
     *  add ()
     *
     *      Adds the icon to the list of active SVGs, if it hasn't already been added
     *
     *  @param string $name
     *  @return bool
     */
    private function add ( $name, $library, $path ) {
        $hash = $name;
        if ( $library ) $hash = $library . '-' . $name;

        if ( !isset( $this->svgs[$hash] ) ) {
           $this->svgs[$hash] = array(
                'name'      => $name,
                'library'   => $library,
                'path'      => $path,
            );
           return true;
        }

        return false;
    } // function

    /**
     *  validate_icon ()
     *
     *      Checks if the icon in question exists in either the theme path or in the internal path
     *
     *  @param string $name
     *  @return string if true, false otherwise
     */
    private function validate_icon ( $name )
    {
        // Theme always overrides 
        $file_name = $name . '.svg';
        $file_path = $this->theme_path . '/' . $file_name;
        if (is_readable($file_path)) {
            return $file_path;
        }

        $file_path = BSD_SVG_PATH . '/' . $file_name;
        if (is_readable($file_path)) {
            return $file_path;
        }

        return false;
    } // function

    /**
     *  validate_icon_default ()
     *
     *      Verify the default icon is valid
     *
     *  @param string $name
     *  @return string if true, false otherwise
     */
    private function validate_icon_default ( $icon ) {

        // Confirm that the default is valid
        if ( $icon['name'] == false ) {
            return false;
        }

        extract ( $icon );
        if ( !$this->find( $name, $library ) ) {
            return false;
        }

        return $icon;
    } // function

} // class