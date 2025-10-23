<?php
/**
 * Plugin Name: jQuery Defer Safe for Elementor
 * Plugin URI: https://github.com/ibernabel/defer-jquery-elementor
 * Description: Safely defers jQuery loading only for public visitors to improve performance without breaking functionality
 * Version: 1.1.0
 * Author: Idequel Bernabel
 * Author URI: https://github.com/ibernabel
 * License: MIT
 * Text Domain: defer-jquery-elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Defer_jQuery_Safe {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Only apply defer for public visitors
        if ( $this->should_apply_defer() ) {
            add_filter( 'script_loader_tag', array( $this, 'defer_jquery_script' ), 10, 3 );
        }
    }
    
    /**
     * Check if we should apply defer to jQuery
     * 
     * @return bool
     */
    private function should_apply_defer() {
        // Never defer in admin
        if ( is_admin() ) {
            return false;
        }
        
        // Never defer for logged-in users (admin bar needs jQuery sync)
        if ( is_user_logged_in() ) {
            return false;
        }
        
        // Never defer in Elementor preview/edit mode
        if ( isset( $_GET['elementor-preview'] ) || isset( $_GET['elementor_library'] ) ) {
            return false;
        }
        
        // Never defer in customizer
        if ( is_customize_preview() ) {
            return false;
        }
        
        // Never defer in AJAX requests
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Add defer attribute to jQuery scripts
     * 
     * @param string $tag    The script tag
     * @param string $handle The script handle
     * @param string $src    The script source URL
     * @return string Modified script tag
     */
    public function defer_jquery_script( $tag, $handle, $src ) {
        // List of jQuery-related handles to defer
        $jquery_handles = array(
            'jquery-core',
            'jquery-migrate'
        );
        
        if ( in_array( $handle, $jquery_handles ) ) {
            // Add defer attribute
            $tag = str_replace( ' src', ' defer src', $tag );
        }
        
        return $tag;
    }
}

// Initialize plugin
function defer_jquery_safe_init() {
    return Defer_jQuery_Safe::get_instance();
}
add_action( 'plugins_loaded', 'defer_jquery_safe_init' );