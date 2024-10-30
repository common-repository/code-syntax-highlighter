(function() {
     tinymce.create('tinymce.plugins.briskjab_syntax_code_shortcode', {
          init : function(ed, url) {
			   ed.addButton('listviewmy',{
							type: 'listbox',
                            label: 'Select :',
                            onselect: function(e) {
								selected = tinyMCE.activeEditor.selection.getContent();
								console.log(e.control.settings.value);
                            if( selected ){
                                content =  '['+e.control.settings.value+']'+selected+'[/'+e.control.settings.value+']';
                            }else{
                                content =  '['+e.control.settings.value+'][/'+e.control.settings.value+']';
                            }

                            tinymce.execCommand('mceInsertContent', false, content);
                            },
                            'values': briskjab_syntax_tinymce_plugin_data.shortcodes
			   });
          },
          createControl : function(n, cm) {
               return null;
          },
     });
     tinymce.PluginManager.add( 'briskjab_syntax_my_button_script', tinymce.plugins.briskjab_syntax_code_shortcode );
})();