<?php
/**
 * Plugin Name: Code Syntax Highlighter
 * Plugin URI: http://briskjab.com
 * Description: This plugin adds theme based highlighter around your code.
 * Version: 1.0.0
 * Author: Kartikay Kanojia
 * Author URI: http://fb.com/kartikaykanojia
 * License: GPL3
 * Text Domain: briskjab-syntax-highlighter
 *
 * Copyright 2014-2018 Briskjab
 *
 *
 *
888888b.   8888888b.  8888888 .d8888b.  888    d8P 888888        d8888 888888b.   
888  "88b  888   Y88b   888  d88P  Y88b 888   d8P    "88b       d88888 888  "88b  
888  .88P  888    888   888  Y88b.      888  d8P      888      d88P888 888  .88P  
8888888K.  888   d88P   888   "Y888b.   888d88K       888     d88P 888 8888888K.  
888  "Y88b 8888888P"    888      "Y88b. 8888888b      888    d88P  888 888  "Y88b 
888    888 888 T88b     888        "888 888  Y88b     888   d88P   888 888    888 
888   d88P 888  T88b    888  Y88b  d88P 888   Y88b    88P  d8888888888 888   d88P 
8888888P"  888   T88b 8888888 "Y8888P"  888    Y88b   888 d88P     888 8888888P"  
                                                    .d88P                         
                                                  .d88P"                          
                                                 888P"                            
 */

 defined( 'ABSPATH' ) or die( "Restricted access!" );
 // Get some info from this file author info template.
 $plugin_data = get_file_data( __FILE__,array('name' => 'Plugin Name','version' => 'Version','text'    => 'Text Domain'));
 
 // C style 'define'.
 function define_constants( $constant_name, $value ) {
    $constant_name = 'BRISKJAB_SYNTAX_' . $constant_name;
    if ( !defined( $constant_name ) )
        define( $constant_name, $value );
}

// Used in iterating over filesystem.
define_constants( 'PATH', plugin_dir_path( __FILE__ ) );

// WP SLUG.
define_constants( 'SLUG', dirname( plugin_basename( __FILE__ ) ) );

// Base name of the plugin.
define_constants( 'BASE', plugin_basename( __FILE__ ) );

// Name of the plugin.
define_constants( 'NAME', $plugin_data['name'] );

// Version of the plugin.
define_constants( 'VERSION', $plugin_data['version'] );
define_constants( 'TEXT', $plugin_data['text'] );

// Setting prefix.
define_constants( 'SETTINGS', 'briskjab_syntax' );

// Prefix.
define_constants( 'PREFIX', 'briskjab_syntax' );

// Base url of the plugin dir.
define_constants( 'BASE_URL', plugin_dir_url( __FILE__ ) );

//Current plugin base file name
define_constants( 'FILE', __FILE__ );
/** Global variables */
$global_all_theme = null;
$global_all_mode = null;

// Array containes only name of modes available.
$global_all_mode_array_name_from_json = null;
// Array contains modes data from json file.
$global_all_mode_array_data_from_json = null;


function briskjab_syntax_logger($data){
	$filee = fopen(BRISKJAB_SYNTAX_PATH.'inc/src/debugloge.txt',"w");
				fwrite($filee,$data);
				fclose($filee);
}

/********* Attaching logic files. **/
// Admin area.
 require_once plugin_dir_path(__FILE__) . 'inc/src/landing.php';
 
 // Front end area.
 require_once plugin_dir_path(__FILE__) . 'inc/src/rendrer.php';