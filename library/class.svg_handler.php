<?php

/* 
    BigSea SVG Handler class

        Processes anything with the original SVG files

    @package bsd-svg-icons
    @sub-package BSD_SVG class
 */

class SVG_Handler
{

    /**
     *  __construct ()
     *
     */
    function __construct ( $file ) {
        $this->file = $file;

        $this->dom = new DOMDocument();
        $status = $this->dom->load( realpath($this->file) );

        if ( false ) {//!$status ) {
            // @todo throw error?
            return false;
        }

        $this->svg = $this->dom->getElementsByTagName('svg')->item(0);

    } // function


    /**
     *  get_paths ()
     *
     *      Returns the <path> data from the XML for SVG files.
     *
     *  @param none
     *  @return string
     */
    public function get_paths ()
    {
        // If I've gotten to this point, I've confirmed that the file exists... so let's go into the processing side of things
        $paths = new DOMDocument();

        // Get all of the paths
        $svg_paths = $this->dom->getElementsByTagName('path');
        for ($i = 0; $i < $svg_paths->length; $i ++) :
            $curr_path = $svg_paths->item($i);

            $new_path = $paths->importNode($curr_path, true);
            $new_path->removeAttribute('xmlns');
            $new_path->removeAttribute('fill');
            $new_path->setAttribute('class', 'path' . ($i+1));
            
            $paths->appendChild($new_path);
        endfor;

        // Return paths, as string.
        $svg_wrapper = $this->dom->getElementsByTagName('svg')->item(0);
        return str_replace('xmlns="http://www.w3.org/2000/svg"', '', $paths->saveHTML());
    } // function


    /**
     *  get_width ()
     *
     *      returns the width attribute of the SVG
     *
     *  @param none
     *  @return string
     */
    public function get_width () 
    {
        return $this->svg->getAttribute('width');
    } // function


    /**
     *  get_height ()
     *
     *      returns the height attribute of the SVG
     *
     *  @param none
     *  @return string
     */
    public function get_height () 
    {
        return $this->svg->getAttribute('height');
    } // function


    /**
     *  get_viewBox ()
     *
     *      returns the viewBox attribute of the SVG, falls back to width/height if viewBox not set (can happen!)
     *
     *  @param none
     *  @return string
     */
    public function get_viewBox () 
    {
        if ( !($viewBox = $this->svg->getAttribute('viewBox')) ) {
            $viewBox = '0 0 '.$this->get_width().' '.$this->get_height().'';
        }

        return $viewBox;
    } // function
} // class