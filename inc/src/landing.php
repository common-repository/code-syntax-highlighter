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

/** Print additional links to plugin meta row */
function briskjab_syntax_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, BRISKJAB_SYNTAX_TEXT . '.php' ) !== false ) {
        $new_links = array('donate' => '<a href="https://www.briskjab.com/donate.php" target="_blank"><span class="dashicons dashicons-heart"></span> ' . __( 'Donate', BRISKJAB_SYNTAX_TEXT ) . '</a>');
        $links = array_merge( $links, $new_links );
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'briskjab_syntax_plugin_row_meta', 10, 2 );

/** Adding link to setting sub menu. */
function briskjab_syntax_plugin_menu() {
	add_options_page( BRISKJAB_SYNTAX_NAME.' Setting', BRISKJAB_SYNTAX_NAME, 'manage_options', BRISKJAB_SYNTAX_SLUG, 'briskjab_syntax_plugin_options' );
}

/** Clicked on menu. */
function briskjab_syntax_plugin_options() {
	//Check if user is allowed to access this page.
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	//Attaching html page.
	require_once('acp-page.php');
}
add_action( 'admin_menu', 'briskjab_syntax_plugin_menu' );

/** Register settings */
function briskjab_syntax_register_settings() {
    register_setting( BRISKJAB_SYNTAX_SETTINGS . '_settings_group', BRISKJAB_SYNTAX_SETTINGS . '_settings' );
	register_setting( BRISKJAB_SYNTAX_SETTINGS . '_settings_group_tm', BRISKJAB_SYNTAX_SETTINGS . '_settings_tm' );
	register_setting( BRISKJAB_SYNTAX_SETTINGS . '_settings_group_version', BRISKJAB_SYNTAX_SETTINGS . '_settings_group_version' );
}
add_action( 'admin_init', 'briskjab_syntax_register_settings' );
/**  Delete options on uninstall */
function briskjab_syntax_uninstall() {
    delete_option( BRISKJAB_SYNTAX_SETTINGS . '_settings_group', BRISKJAB_SYNTAX_SETTINGS . '_settings' );
	delete_option( BRISKJAB_SYNTAX_SETTINGS . '_settings_group', BRISKJAB_SYNTAX_SETTINGS . '_settings_tm' );
	delete_option( BRISKJAB_SYNTAX_SETTINGS . '_settings_group', BRISKJAB_SYNTAX_SETTINGS . '_settings_group_version' );
}
register_uninstall_hook( BRISKJAB_SYNTAX_FILE, 'briskjab_syntax_uninstall' );

/**
 * Print direct link to plugin admin page
 *
 * Fetches array of links generated by WordPress Plugin admin page ( Deactivate | Edit )
 * and inserts a link to the plugin admin page
 */
function briskjab_syntax_settings_link( $links ) {
    $page = '<a href="' . admin_url( 'options-general.php?page=' . BRISKJAB_SYNTAX_SLUG ) . '">' . __( 'Settings', BRISKJAB_SYNTAX_TEXT ) . '</a>';
    array_unshift( $links, $page );
    return $links;
}
add_filter( 'plugin_action_links_' . BRISKJAB_SYNTAX_BASE, 'briskjab_syntax_settings_link' );


/** Load admin page. */
function load_custom_wp_admin_style($hook) {
        // Load only on our plugin page
        if($hook != 'settings_page_'.BRISKJAB_SYNTAX_TEXT) {
                return;
        }
		
		// Embed jquery.
		wp_enqueue_script( 'jquery' );
		
		// Embed jquery UI
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-dialog');
		
		// Embed Css into header of admin page.
		wp_enqueue_style( BRISKJAB_SYNTAX_TEXT.'_font_awsome_css', plugins_url('lib/font-awesome/css/font-awesome.css', __FILE__) );
		wp_enqueue_style( BRISKJAB_SYNTAX_TEXT.'_jquery_ui_css', plugins_url('lib/jquery/jquery-ui.min.css', __FILE__) );
        wp_enqueue_style( BRISKJAB_SYNTAX_TEXT.'_admin_css', plugins_url('css/acp_style.css', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

/** Change setting footer. */
function brisjab_syntax_admin_footer_text() {

    // Get current screen data
    $current_screen = get_current_screen();

    // Return if the page is not a settings page of this plugin
    $settings_page = 'settings_page_' . BRISKJAB_SYNTAX_TEXT;
    if ( $settings_page != $current_screen->id ) return;

    // Filter footer text
    function briskjab_syntax_new_admin_footer_text() {
        $year = date('Y');
        return 'Copyright &copy; 2014 - ' . $year . '  <span class="dashicons dashicons-heart"></span><a href="https://www.briskjab.com" target="_blank">Briskjab</a>';
    }
    add_filter( 'admin_footer_text', 'briskjab_syntax_new_admin_footer_text', 11 );
}
add_action( 'current_screen', 'brisjab_syntax_admin_footer_text' );

/* Theme deleting jquery and ajax script */ 
add_action( 'admin_footer', 'briskjab_syntax_add_delete_theme_mode_script' );
function briskjab_syntax_add_delete_theme_mode_script() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		var loadingthemedelete = false;
		$('body').on('click','.delete_theme',function(){
			if($(this).hasClass('notdelete')){
				return;
			}
			if(loadingthemedelete){
				return;
			}
			var _this = $(this);
			$('<div></div>').appendTo('body')
			.html('<div><h6>Are you sure you want to delete this theme!</h6></div>')
			.dialog({
				modal: true, title: 'Delete message', zIndex: 10000, autoOpen: true,
				width: 'auto', resizable: false,
				buttons: {
					Yes: function () {
						$(this).dialog("close");
						loadingthemedelete = true;
						var filename = _this.closest('td').attr('data-themename');
						var _tr = _this.closest('tr');
						var _th = _tr.find('th');
						_th.addClass('strike');
						var data = {
							'action': 'briskjab_syntax_delete_theme',
							'filename': filename
						};
						// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
						jQuery.post(ajaxurl, data, function(response) {
							//console.log('Got this from the server: ' + response);
							_th.removeClass('strike');
							if(response=='1'){
								$('#<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[theme] option[value="'+filename+'"]').remove();
								_tr.fadeOut(function(){
									_tr.remove();
								});
							}
							loadingthemedelete = false;
						});
					},
					No: function () {                                                                 
						$(this).dialog("close");
					}
				},
				close: function (event, ui) {
					$(this).remove();
				}
			});
		});
	});
	</script> <?php
}
add_action( 'wp_ajax_briskjab_syntax_delete_theme', 'briskjab_syntax_delete_theme' );
/** Theme deleting backend script */
function briskjab_syntax_delete_theme() {
	global $wpdb; // this is how you get access to the database
	$filename =  $_POST['filename'];
	if(unlink(BRISKJAB_SYNTAX_PATH . 'inc/src/engine/codemirror/theme/'.$filename)){
		echo '1';
	} else{
		print_r(error_get_last());
	}
	wp_die(); // this is required to terminate immediately and return a proper response
}
/* Theme uploading jquery and ajax script */
add_action( 'admin_footer', 'briskjab_syntax_add_upload_theme_script' );
function briskjab_syntax_add_upload_theme_script() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		var uploadingnewthemeflag = false;
		$('#briskjab_syntax_upload_new_theme').click(function(){
			if(uploadingnewthemeflag){
				return;
			}
			$('#briskjab_syntax_upload_new_theme_server_stat_area').finish().slideUp();
			var themefile = document.getElementById('briskjab_syntax_new_theme_input_file').files[0];
			if(themefile==null){
				$('#briskjab_syntax_upload_new_theme_server_stat').html('<span style="color:#F44336;">Please select theme to upload.</span>');
				$('#briskjab_syntax_upload_new_theme_server_stat_area').slideDown(function(){
					$('#briskjab_syntax_upload_new_theme_server_stat_area').delay(6000).slideUp();
				});
				return;
			}
			uploadingnewthemeflag = true;
			var _this = $(this);
			var form_data = new FormData();
			form_data.append('themefile', themefile);
            form_data.append('action', 'briskjab_syntax_upload_theme');
			$('#briskjab_syntax_upload_new_theme_stat').addClass('strike');
			var uploadbuttontext = $('#briskjab_syntax_upload_new_theme_btn_text').text();
			$('#briskjab_syntax_upload_new_theme_btn_text').text('Please wait');
			jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    console.log(response);
					var objresponse = JSON.parse(response);
					if(objresponse.status==1){
						$('#briskjab_syntax_upload_new_theme_server_stat').html('\'<strong>'+themefile['name']+'</strong>\' theme uploaded.');
						document.getElementById("briskjab_syntax_new_theme_input_file").value = null;
						var newname = themefile['name'].replace(new RegExp('-', 'g'), ' ');
						newname = newname.replace(new RegExp('_', 'g'), ' ');
						newname = newname.replace(new RegExp('.css', 'g'), '');
						var newrowcontent =  '<tr> <th scope="row" style="width:80%;font-size:14px;padding:0px;font-weight:600 !important;">'+ newname +' (new)';
						newrowcontent += '</th><td style="width:20%;padding: 5px;" data-themename="'+themefile['name']+'"><i class="demo-icon icon-trash delete_theme" style="cursor:pointer;opacity:1;">&#xe800;</i></td>';
								
						$("#briskjab_syntax_theme_list_table tbody").append(newrowcontent);
						$('#<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[theme]').append('<option value="'+themefile['name']+'">'+newname+'</option>');
					} else {
						$('#briskjab_syntax_upload_new_theme_server_stat').html('<span style="color:#F44336;">'+objresponse.error+'</span>');
					}
                },
                error: function (response) {
                    console.log('error');
                },
				complete: function(xhr) {
					$('#briskjab_syntax_upload_new_theme_stat').removeClass('strike');
					$('#briskjab_syntax_upload_new_theme_btn_text').text(uploadbuttontext);
					
					$('#briskjab_syntax_upload_new_theme_server_stat_area').slideDown(function(){
						$('#briskjab_syntax_upload_new_theme_server_stat_area').delay(6000).slideUp();
					});
					uploadingnewthemeflag = false;
				}
            });
		});
	});
	</script> <?php
}

/** Adding theme uploading javascript */
add_action( 'wp_ajax_briskjab_syntax_upload_theme', 'briskjab_syntax_upload_theme' );
function briskjab_syntax_upload_theme() {
	global $wpdb; // this is how you get access to the database
	if(basename($_FILES["themefile"]["name"])=='.htaccess'){
		$datatosend['status'] = 0;
		$datatosend['error'] = 'Theme already exist.';
		echo json_encode($datatosend);
	} else {
		if(preg_match('#^.*\.(css|eot|ttf|svg|woff)$#i', $_FILES["themefile"]["name"])){
			$target_file = BRISKJAB_SYNTAX_PATH . 'inc/src/engine/codemirror/theme/' . basename($_FILES["themefile"]["name"]);
			if (file_exists($target_file)){
				$datatosend['status'] = 0;
				$datatosend['error'] = 'Theme already exist.';
				echo json_encode($datatosend);
			} else {
				$filename = basename($_FILES["themefile"]["name"]);
				if (move_uploaded_file($_FILES["themefile"]["tmp_name"], $target_file)){
					$datatosend['status'] = 1;
					$datatosend['error'] = 'Theme uploaded.';
					echo json_encode($datatosend);
				} else {
					$datatosend['status'] = 0;
					$datatosend['error'] = 'Sorry, there was an error uploading theme.';
					echo json_encode($datatosend);
				}
			}
		} else {
			$datatosend['status'] = 0;
			$datatosend['error'] = 'Sorry, there was an error uploading theme..';
			echo json_encode($datatosend);
		}
	}
	wp_die(); // this is required to terminate immediately and return a proper response
}

/** adding tinymce pulgin */
add_action( 'admin_init', 'briskjab_syntax_my_tinymce_button' );

/** If page is new post or edit post then add plugin */
function briskjab_syntax_my_tinymce_button() {
     if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		 add_action( 'admin_head', 'my_admin_head' );
		 // Register buttons in tinymce 
          add_filter( 'mce_buttons', 'briskjab_syntax_my_register_tinymce_button' );
		  // Register plugin
          add_filter( 'mce_external_plugins', 'briskjab_syntax_my_add_tinymce_button' );
     }
}

/** add button into tinymce */
function briskjab_syntax_my_register_tinymce_button( $buttons ) {
     array_push( $buttons, "listviewmy");
     return $buttons;
}

/** add plugin js file */
function briskjab_syntax_my_add_tinymce_button( $plugin_array ) {
     $plugin_array['briskjab_syntax_my_button_script'] = BRISKJAB_SYNTAX_BASE_URL.'inc/src/js/briskjabsyntaxadminpost.js' ;
     return $plugin_array;
}
/**  Add shortcode array */
function my_admin_head() {
	$wp_options = get_option( BRISKJAB_SYNTAX_SETTINGS . '_settings' );
    $doblau = briskjab_syntax_get_mode_name_from_json_file($wp_options);
    ?>
<!-- Briskjab Code Syntax Highlighter Shortcode Plugin -->
<script type='text/javascript'>
var briskjab_syntax_tinymce_plugin_data = {
    'shortcodes': [
	<?php
		$ei = 0;
		foreach($doblau as $valva){
			if($ei!=0){
				echo ',';
			}
			echo "{text:'".$valva."',value:'".$valva."'}";
			$ei++;
		}
	?>
    ],
};
</script>
<!-- End Briskjab Code Syntax Highlighter Shortcode Plugin -->
    <?php
}

/** inTineMce text mode '<' is saved as it is in DB. Due to worpdress shortcode code function, on encounter of '<' wp-include/shortcode.php do not execute full code. this causes our plugin to behave incorrectly. So before saving into DB we are checking if insode shortcode if '<' is found we are converting it into ascii html code.*/
function briskjab_syntax_before_post_saving( $data , $postarr ) {
	//$pattern = "#\[[^>]+\](.*)\[\/[^>]+\]#Us";
	//$pt = "#<[^>]+>(.*)</[^>]+>#Us";
	$pattern = '#\\[(.*?)\\](.*?)\\[\\/(.*?)\\]#si';
	//preg_match_all($pattern,$data['post_content'],$match,PREG_PATTERN_ORDER);
	$data['post_content'] = preg_replace_callback($pattern,function(&$m){
		return '['.$m[1].']'.preg_replace(array('#<#'),array('&#60;'),$m[2]).'[/'.$m[1].']';
	},$data['post_content']);
  return $data;
}

add_filter( 'wp_insert_post_data', 'briskjab_syntax_before_post_saving', '99', 2 );
/** before showing to editor page we are scanning for ascii html code of '&#60;' inside shortcode and converting it into '<'. */
function briskjab_syntax_before_editor_content( $content, $post_id ) {
	$pattern = '#\\[(.*?)\\](.*?)\\[\\/(.*?)\\]#si';
	$content = preg_replace_callback($pattern,function(&$m){
		return '['.$m[1].']'.preg_replace(array('#&\#60;#'),array('<'),$m[2]).'[/'.$m[1].']';
	},$content);
    return $content;
}
add_filter( 'content_edit_pre', 'briskjab_syntax_before_editor_content', 10, 2  );