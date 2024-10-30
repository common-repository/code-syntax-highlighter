<div class="wrap">
	<div id="briskjab_container">
		<div class="briskjab_syntax_card">
			<div class="title"><h1>Code Syntax Highlighter</h1></div>
		</div>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div class="main_content_area">
					<div id="tabs_briskjab_syntax">
					  <ul>
						<li><a href="#tabs-1">SETTING</a></li>
						<li><a href="#tabs-2">THEME</a></li>
						<!--<li><a href="#tabs-3">ADVANCE SETTING</a></li>-->
					  </ul>
					  <div id="tabs-1">
						<p style="font-size:14px;">SETTING</p>
						<hr/>
						<form action="options.php" method="post" enctype="multipart/form-data">
						<?php settings_fields( BRISKJAB_SYNTAX_SETTINGS . '_settings_group' ); ?>
							<table class="form-table">
                                <tbody><tr>
								<th scope="row">
									Enable plugin
								</th>
								<td>
									<?php
										$wp_options = get_option( BRISKJAB_SYNTAX_SETTINGS . '_settings' );
									?>
									<input type="checkbox" name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[enable]" id="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[enable]" <?php echo !empty( $wp_options['enable'] ) ? "checked='checked'" : ''; ?>/>
								</td>
							</tr><tr>
								<th scope="row">
									Default code language
								</th>
								<td>
									<select name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[mode]">
										<option value="">None</option>
										<?php
											$modes = briskjab_syntax_get_mode_data_from_json_file($wp_options);
											sort($modes);
											$defaultmode = strlen( $wp_options['mode'] ) > 0? esc_attr( $wp_options['mode'] ) : '';
											foreach ( $modes as $modekey=>$mode ){
												echo '<option value="'.$modekey.'"';
												if($defaultmode==$mode){
													echo 'selected="selected"';
												}
												echo '>'.$mode['name'].'</option>';
											}
										?>
									</select>
								</td>
							</tr><tr>
								<td></td>
								<td class="help-text">
									When default code language is set other than 'NONE', That language will be used when '[CODE]' shortcode is used.
								</td>
							</tr><tr>
								<th scope="row">
									Code block theme
								</th>
								<td>
									<select id="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[theme]" name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[theme]">
										<option value="default">Default</option>
										<?php
											$themes = briskjab_syntax_fetch_codemirror_all_theme();
											$defaulttheme = strlen( $wp_options['theme'] ) > 0? esc_attr( $wp_options['theme'] ) : '';
											foreach ( $themes as $theme ){
												echo '<option value="'.$theme.'"';
												if($defaulttheme==$theme){
													echo 'selected="selected"';
												}
												$themename = preg_replace(array('#-#','#_#', '#.css#'), array(' ',' ', ' '), $theme);
												echo '>'.$themename.'</option>';
											}
										?>
									</select>
								</td>
							</tr><tr>
								<th scope="row">
									Show line number
								</th>
								<td>
									<input id="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[line_number]" name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[line_number]" <?php echo !empty( $wp_options['line_number'] ) ? "checked='checked'" : ''; ?> type="checkbox">
								</td>
							</tr><tr>
								<td></td>
								<td class="help-text">
									Display the line numbers in the code block.
								</td>
							</tr><tr>
								<th scope="row">
									First line number
								</th>
								<td>
										<input name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[first_line_no]" id="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[first_line_no]" value="<?php echo strlen( $wp_options['first_line_no'] ) > 0? esc_attr( $wp_options['first_line_no'] ) : '1'; ?>" maxlength="4" type="number"> 
								</td>
							</tr><tr>
								<th scope="row">
									Automatic height of code block
								</th>
								<td>
									<input name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[automatic_height]" id="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[automatic_height]" type="checkbox" <?php echo !empty( $wp_options['automatic_height'] ) ? "checked='checked'" : ''; ?>>
								</td>
							</tr><tr>
								<td></td>
								<td class="help-text">
									Checked = Automatic height, Adjust height according to code. Not Checked = Fixed height, with scrollbar.
								</td>
							</tr><tr>
								<th scope="row">
									Height of code block 
								</th>
								<td>
										<input name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[block_height]" id="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[block_height]" value="<?php echo strlen( $wp_options['block_height'] ) > 0 ? esc_attr( $wp_options['block_height'] ) : '300'; ?>" maxlength="4" type="number">
								</td>
							</tr><tr>
								<td></td>
								<td class="help-text">
									The height (in pixels) of code block. Default is 300px. (Used if 'Automatic height of code block' is set to 'No')
								</td>
							</tr><tr>
								<th scope="row">
									Tab character size
								</th>
								<td>
										<input name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[tab_size]" id="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[tab_size]" value="<?php echo strlen( $wp_options['tab_size'] ) > 0 ? esc_attr( $wp_options['tab_size'] ) : '4'; ?>" maxlength="4" type="number">
											
								</td>
							</tr><tr>
								<td></td>
								<td class="help-text">
									The width (in spaces) of the Tab character. Default is 4.
								</td>
							</tr><tr>
								<th scope="row">
									Load limited language
								</th>
								<td>
									<input name="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[limited_lang]" id="<?php echo BRISKJAB_SYNTAX_SETTINGS; ?>_settings[limited_lang]" type="checkbox" <?php echo strlen( $wp_options['limited_lang'] ) > 0 ? "checked='checked'" : ''; ?>>
								</td>
							</tr><tr>
								<td></td>
								<td class="help-text">
									Instead of loading all languages, load only limited set of language.
								</td>
							</tr>                            </tbody></table>
							<hr/>
							<button class="bjbutton" name="submit" type="submit"><i class="demo-icon icon-floppy">&#xe801;</i> Save Changes</button>
						</form>
						</div>
						<!-- END setting area -->
						<!-- start theme area 
						<form action="options.php" method="post" enctype="multipart/form-data">
						<?php settings_fields( BRISKJAB_SYNTAX_SETTINGS . '_settings_group_tm' ); ?>-->
						<div id="tabs-2">
							<p style="font-size:14px;">THEME SETTING</p>
							<hr/>
							<table style="width:100%;">
								<tbody style="width:100%;">
									<tr style="width:100%;">
										<td style="width:50%;">
											<p style="font-size:14px;">INSTALLED THEME</p>
											<hr style="width:97%;margin:0 auto;"/>
											<p>*Deleted themes can not be recovered.</p>
											<table class="form-table" id="briskjab_syntax_theme_list_table">
											<tbody>
											<?php
												$themecount = 1;
												foreach ( $themes as $theme ){
													$themename = preg_replace(array('#-#','#_#', '#.css#'), array(' ',' ', ' '), $theme);
													$fontweight = 400;
													$defaulttext = '';
													$deletebuttonopacity = 1;
													$cursortype = "pointer";
													$disabled_delete_class = '';
													if($defaulttheme==$theme){
														$fontweight = 600;
														$defaulttext = '(default)';
														$deletebuttonopacity = 0.2;
														$cursortype = "default";
														$disabled_delete_class = 'notdelete';
													}
													echo '<tr> <th scope="row" style="width:80%;font-size:14px;padding:0px;font-weight:'.$fontweight.' !important;">'.$themename.$defaulttext;
													echo '</th><td style="width:20%;padding: 5px;" data-themename="'.$theme.'"><i class="demo-icon icon-trash delete_theme '.$disabled_delete_class.'" style="cursor:'.$cursortype.';opacity:'.$deletebuttonopacity.';">&#xe800;</i></td>';
													$themecount++;
												}
											?>
											</tbody>
											</table>
										</td>
										<td style="width:50%;" valign="top">
											<p style="font-size:14px;">NEW THEME</p>
											<hr style="width:97%;margin:0 auto;"/>
											<table class="form-table" >
											<tbody>
											<tr>
												<td>
													<div style="border-left: 2px solid #46b450;box-shadow: 1px 1px 1px 1px rgba(0,0,0,.1); padding: 5px;display:none;" id="briskjab_syntax_upload_new_theme_server_stat_area">
														<span id="briskjab_syntax_upload_new_theme_server_stat" style="padding-left:10px;">File uploaded..</span>
													</div>
													<br/>
													<input type="file" name="briskjab_syntax_new_theme_input_file" id="briskjab_syntax_new_theme_input_file"/>
													<button class="bjbutton" type="button" id="briskjab_syntax_upload_new_theme"><i class="demo-icon icon-floppy">&#xe801;</i> <span id="briskjab_syntax_upload_new_theme_btn_text">Upload</span></button>
													<br/>
													<br/>
													<p id="briskjab_syntax_upload_new_theme_stat"></p>
												</td>
											<tr>
											</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END theme area -->
						<!-- start advance area 
						<div id="tabs-3">
							<p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
							<p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
						</div>
						 END advance area -->
					</div>
						<!-- tab end -->
					</div>
					<!-- END main content area -->
				</div>
				<!-- END post body content area -->
				<!-- sidebar area -->
				<div class="postbox-container" id="postbox-container-1">
					<div class="main_content_area">
						<p style="font-size:14px;text-align:center;">SUPPORT</p>
						<hr/>
						<span style="text-align:center;">Support developer by donating.</span><br/>
						Every little contribution helps us to cover maintenance and costing of this plugin.<br/>
						<br/>
						<a href="https://www.paypal.me/kartikaykanojia" target="_blank" class="bjbutton" style="width:95%;text-decoration:none;margin-bottom:10px;" id="briskjab_syntax_donate_button">
							<span style="text-align:center;"><span class="dashicons dashicons-heart"></span> DONATE</span>
						</a>
					</div>
				</div>
				<!-- END sidebar area -->
			</div>
		</div>
	</div>
</div>
  <script>
  jQuery(document).ready(function($) {
    $( "#tabs_briskjab_syntax" ).tabs();
  } );
  </script>