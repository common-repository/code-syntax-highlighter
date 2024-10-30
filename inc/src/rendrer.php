<?php
/**
 * Author: Kartikay Kanojia
 * Author URI: http://briskjab.com
 
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
//Addons to use.
$addons = array('display' => array( 'autorefresh' ), 'edit' => array( 'matchbrackets' ), 'selection' => array( 'active-line' ));
$briskjab_syntax_code_setting_top_area = '';
$briskjab_syntax_code_setting =  array();
/**---------------------------------------------------------------------------------------
 * 	HOOKING TO ENQUEUE OF SCRIPTS 
 *   SCRIPTS REGISTERING, USER SETTING FETCH
 * ---------------------------------------------------------------------------------------*/
/**
 * Load scripts and style sheet for front end of website
 */
function briskjab_syntax_scripts_frontend() {

    $prefix = BRISKJAB_SYNTAX_PREFIX;
    $url = BRISKJAB_SYNTAX_BASE_URL;
    $settings = BRISKJAB_SYNTAX_SETTINGS;
    $version = BRISKJAB_SYNTAX_VERSION;

    // Read wp_options from database
    $wp_options = get_option( $settings . '_settings' );

    // If the "Enable Plugin" option is on
    if ( strlen( $wp_options['enable'] ) > 0 && $wp_options['enable'] == "on" ) {
        // Register codemirror scripts in wp.
        briskjab_syntax_register_codemirror_scripts( $wp_options, $prefix, $url, $version );

        // Localize codemirror options, user setting from database.
        briskjab_syntax_load_codemirror_setting_wp_options( $wp_options, $prefix );
    }
}
add_action( 'wp_enqueue_scripts', 'briskjab_syntax_scripts_frontend' );

/**
 * Register codemirror scripts in wp.
 */
function briskjab_syntax_register_codemirror_scripts($wp_options, $prefix, $url, $version){
	// Register main files of the CodeMirror library
    wp_register_style( $prefix . '-codemirror-css', $url . 'inc/src/engine/codemirror/lib/codemirror.css', array(), $version, 'all' );
    wp_register_script( $prefix . '-codemirror-js', $url . 'inc/src/engine/codemirror/lib/codemirror.js', array(), $version, false );

    // Register settings file
    wp_register_script( $prefix . '-codemirror-settings-js', $url . 'inc/src/js/briskjabsyntax.js', array(), $version, true );

    // Register addons
    global $addons;
    foreach ( $addons as $addons_group_name => $addons_group ) {
        foreach ( $addons_group as $addon ) {
            wp_register_script( $prefix . '-codemirror-addon-' . $addon . '-js', $url . 'inc/src/engine/codemirror/addon/' . $addons_group_name . '/' . $addon . '.js', array(), $version, false );
        }
    }
	
    // Register theme
    $theme = strlen( $wp_options['theme'] ) > 0 ? $wp_options['theme'] : 'default';
    if ( $theme != "default" ) {
        wp_register_style( $prefix . '-codemirror-theme-css', $url . 'inc/src/engine/codemirror/theme/' . $theme, array(), $version, 'all' );
    }
}

/**
 * Localize codemirror options, user setting from database.
 */
function briskjab_syntax_load_codemirror_setting_wp_options( $wp_options, $prefix ) {

    // Get settings and put them in variables
    $theme = strlen( $wp_options['theme'] ) > 0 ? $wp_options['theme'] : 'default';
    if ((strlen( $wp_options['line_number'] ) > 0 && ( $wp_options['line_number'] == "on" ))) {
        $line_number = "true";
    } else {
        $line_number = "false";
    }
    $first_line_no = strlen( $wp_options['first_line_no'] ) > 0 ? $wp_options['first_line_no'] : '0';
    $tab_size = strlen( $wp_options['tab_size'] ) > 0 ? $wp_options['tab_size'] : '4';

    // Create an array (JS object) with all the settings
	$theme = preg_replace('#.css#','',$theme);
	global $briskjab_syntax_code_setting_top_area;
	$briskjab_syntax_code_setting_top_area = "var codemirrortheme = '$theme'; var line_numbers = $line_number; var first_line_number = $first_line_no; var tab_size = $tab_size; ";
}

/**
 * Inject user defined/setting css property.
 */
function briskjab_syntax_inject_user_setting_css( $wp_options, $prefix ) {

    // Get settings and put them in variables
    if ( strlen( $wp_options['automatic_height'] ) > 0 && ( $wp_options['automatic_height'] == "on" ) ) {
        $block_height = "100%";
    } elseif ( strlen( $wp_options['block_height'] ) > 0 ) {
        $block_height = $wp_options['block_height'] . "px";
    } else {
        $block_height = "300px";
    }

    // Create an array with all the settings (CSS code)
    $custom_css = ".CodeMirror {height: " . $block_height . " !important;}";

    // Inject the array into the stylesheet
    wp_add_inline_style( $prefix . '-style-css', $custom_css );
}

/** Mode scanner inside mode folder */
function briskjab_syntax_fetch_codemirror_all_mode() {
	global $global_all_mode;
	if($global_all_mode!=null){
		return $global_all_mode;
	}
    $currdir = BRISKJAB_SYNTAX_PATH . 'inc/src/engine/codemirror/mode/';
    $modes = array_filter( glob( $currdir . '*' ), 'is_dir' );
	$global_all_mode = array_map( 'basename', $modes );
    return $global_all_mode;
}
/** Theme scanner inside mode folder */
function briskjab_syntax_fetch_codemirror_all_theme() {
	global $global_all_theme;
	if($global_all_theme!=null){
		return $global_all_theme;
	}
    $currdir = BRISKJAB_SYNTAX_PATH . 'inc/src/engine/codemirror/theme/';
    $themes = array_filter( glob( $currdir . '*.css' ));
	$global_all_theme = array_map( 'basename', $themes );
    return $global_all_theme;
}

/**-----------------------------------------------------------------------------------------
 * SHOWING CODEMIRROR BLOCK AT FRONT END
--------------------------------------------------------------------------------------------*/
/**
 * Shortcode finder in post and expand it.
 */
function briskjab_syntax_shortcode_scanner( $content ) {

	// Read wp_options from database
    $wp_options = get_option( BRISKJAB_SYNTAX_SETTINGS . '_settings' );
	if ( strlen( $wp_options['enable'] )<1 ){
		return $content;
	}
	/*$filee = fopen(BRISKJAB_SYNTAX_PATH.'inc/src/debugloge.txt',"w");
				fwrite($filee,$testmatch);
				fclose($filee);*/
    // Get the shortcode names
    $shortcodes_names = briskjab_syntax_get_mode_name_from_json_file($wp_options);
    // Create different shortcodes
    foreach ( $shortcodes_names as $shortcode_name ) {
        add_shortcode( $shortcode_name, 'briskjab_syntax_shortcode_expander' );
    }
	
    // Run the do shortcodes function on the content.
    $content = do_shortcode( $content );
	//Add custome script at end.
	add_action( 'wp_footer', 'briskjab_syntax_embed_custom_codemirror_script', 25 );
    return $content;
}
add_filter( 'the_content', 'briskjab_syntax_shortcode_scanner', 7 );


/**
 * Short code expander.
 */
function briskjab_syntax_shortcode_expander( $atts, $content = null, $lang ) {
    $prefix = BRISKJAB_SYNTAX_PREFIX;
    $wp_options = get_option( BRISKJAB_SYNTAX_SETTINGS . '_settings' );
	// Load all mode data.
	$global_all_mode_array_data_from_json_loc = briskjab_syntax_get_mode_data_from_json_file($wp_options);
	
    // Default language for the [code] shortcode
    if ( $lang == "code" ) {
		$lang = strlen( $wp_options['mode'] ) > 0 ? $wp_options['mode'] : 'code';
    }

	// Enqueue jquery
	wp_enqueue_script( 'jquery' );
	
	// Enqueue custom css
	wp_enqueue_style( $prefix . '-style-css' ,BRISKJAB_SYNTAX_BASE_URL . 'inc/src/css/style.css', array(), $version, 'all');
	// Inject setting css value.
	briskjab_syntax_inject_user_setting_css( $wp_options, $prefix );
    // Enqueue CodeMirror library
    wp_enqueue_style( $prefix . '-codemirror-css' );
    wp_enqueue_script( $prefix . '-codemirror-js' );
	
    // Enqueue CodeMirror addons
	global $addons;
    foreach ( $addons as $addons_group_name => $addons_group ) {
        foreach ( $addons_group as $addon ) {
            //wp_enqueue_script( $prefix . '-codemirror-addon-' . $addon . '-js' );
			wp_enqueue_script( $prefix . '-codemirror-addon-' . $addon . '-js', BRISKJAB_SYNTAX_BASE_URL . 'inc/src/engine/codemirror/addon/' . $addons_group_name . '/' . $addon . '.js', array(), $version, false );
        }
    }
	
	//Attach extra addon if required by the mode
	if(isset($global_all_mode_array_data_from_json_loc[$lang]['mime'][0]['addon'])){
		foreach($global_all_mode_array_data_from_json_loc[$lang]['mime'][0]['addon'] as $extraaddonvalue){
			//print_r($extraaddonvalue);
			wp_enqueue_script( $prefix . '-codemirror-addon-' . $extraaddonvalue['name'] . '-js', BRISKJAB_SYNTAX_BASE_URL . 'inc/src/engine/codemirror/addon/' . $extraaddonvalue['folder'] . '/' . $extraaddonvalue['name'], array(), $version, false );
		}
	}
	// Enqueue CodeMirror modes
	foreach($global_all_mode_array_data_from_json_loc[$lang]['mime'][0]['scripts'] as $modevalue){
		wp_enqueue_script( $prefix . '-codemirror-mode-' . $modevalue . '-js', BRISKJAB_SYNTAX_BASE_URL . 'inc/src/engine/codemirror/mode/' . $modevalue . '/' . $modevalue . '.js', array(), $version, true );
	}

    // Enqueue CodeMirror theme
    $theme = strlen( $wp_options['theme'] ) > 0 ? $wp_options['theme'] : 'default';
    if ( $theme != "default" ) {
        wp_enqueue_style( $prefix . '-codemirror-theme-css' );
    }
	// Clean shortcode names for html
	$domlang = str_replace("+", "p", $lang);
	$domlang = str_replace(".", "dot", $lang);
	$domlang = str_replace("#", "sharp", $lang);
	$domlang = str_replace("-", "neg", $lang);
	$domlang = preg_replace("/[^a-zA-Z0-9]+/", "", $lang);
	global $briskjab_syntax_code_setting;
	//Preparing codemirror object
	$local_setting = 'var editor = CodeMirror.fromTextArea($(".bjsh_mode_'.$domlang.'")[0], {
            lineNumbers: line_numbers,
			styleActiveLine: true,
            firstLineNumber: first_line_number,
            matchBrackets: true,
            indentUnit: tab_size,
            readOnly: true,
            theme: codemirrortheme,
            mode: "'.$global_all_mode_array_data_from_json_loc[$lang]['mime'][0]['name'].'",
            autoRefresh: true
        });';
		// Pushing codemirror object into array
	array_push($briskjab_syntax_code_setting,$local_setting);
	// Trimming content for extra spacing
    $content = rtrim( $content );
	// Modify content if setting is on.
    if ( strlen( $wp_options['enable'] ) > 0 && $wp_options['enable'] == "on" ) {
        return '<div class="briskjab-syntax-highlighter"><pre><textarea class="bjsh_textarea bjsh_mode_'.$domlang.'" name="bjsh_textarea_'.$domlang.'" >' . $content . '</textarea></pre></div>';
    } else {
        return $content;
    }
}

/* Get Mode all data from json */
function briskjab_syntax_get_mode_data_from_json_file($wp_options,$force = false){
	global $global_all_mode_array_name_from_json;
	global $global_all_mode_array_data_from_json;
	if($global_all_mode_array_data_from_json!=null && $force==false){
		return $global_all_mode_array_data_from_json;
	}
	$filetoload = 'mastermode.allcopy.json';
	// If limited option is set in setting then load minimal mode
	if ( strlen( $wp_options['limited_lang'] ) > 0 && $wp_options['limited_lang'] == "on" ){
		$filetoload = 'smallmode.json';
	}
	//Read mode data from json
	$mode_info = file_get_contents(BRISKJAB_SYNTAX_PATH.'inc/src/engine/codemirror/'.$filetoload);
	// Decode json
	$global_all_mode_array_data_from_json = json_decode($mode_info,true);
	// Creating empty array of mode name
	$global_all_mode_array_name_from_json = array();
	// If json file is empty return empty array.
	if($global_all_mode_array_data_from_json==null){
		$global_all_mode_array_data_from_json = array();
		return $global_all_mode_array_name_from_json;
	}
	// Iterating over all mode data.
	if(count($global_all_mode_array_data_from_json)>0){
		foreach($global_all_mode_array_data_from_json as $key=>$value){
			array_push($global_all_mode_array_name_from_json,$key);
		}
	}
	return $global_all_mode_array_data_from_json;
}

/** Get mode names only from json file */
function briskjab_syntax_get_mode_name_from_json_file($wp_options,$force = false){
	global $global_all_mode_array_name_from_json;
	global $global_all_mode_array_data_from_json;
	if($global_all_mode_array_name_from_json!=null && $force==false){
		return $global_all_mode_array_name_from_json;
	}
	$filetoload = 'mastermode.allcopy.json';
	// If limited option is set in setting then load minimal mode
	if ( strlen( $wp_options['limited_lang'] ) > 0 && $wp_options['limited_lang'] == "on" ){
		$filetoload = 'smallmode.json';
	}
	//Read mode data from json
	$mode_info = file_get_contents(BRISKJAB_SYNTAX_PATH.'inc/src/engine/codemirror/'.$filetoload);
	
	$global_all_mode_array_data_from_json = json_decode($mode_info,true);
	// Creating empty array of mode name
	$global_all_mode_array_name_from_json = array();
	
	// If json data is not found then return empty array.
	if($global_all_mode_array_data_from_json==null){
		return $global_all_mode_array_name_from_json;
	}
	// Iterating over all mode data.
	if(count($global_all_mode_array_data_from_json)>0){
		foreach($global_all_mode_array_data_from_json as $key=>$value){
			array_push($global_all_mode_array_name_from_json,$key);
		}
	}
	return $global_all_mode_array_name_from_json;
}

// Embeding modemirror setting object script at footer
function briskjab_syntax_embed_custom_codemirror_script() {
	global $briskjab_syntax_code_setting;
	global $briskjab_syntax_code_setting_top_area;
?>
<script type="text/javascript">
  jQuery(document).ready(function($) {
	  "use strict";
    <?php echo $briskjab_syntax_code_setting_top_area; foreach($briskjab_syntax_code_setting as $valloc){ echo $valloc; }?>
  });
</script>
<?php
}

?>