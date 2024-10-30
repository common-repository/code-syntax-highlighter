//NOT IN USE
jQuery(document).ready(function($) {

    "use strict";

    // Get values for variables
    var theme = briskjab_syntax_codemirroroption["theme"];
    var line_numbers = ( briskjab_syntax_codemirroroption["line_numbers"] == 'true' );
    var first_line_number = parseInt( briskjab_syntax_codemirroroption["first_line_number"] );
    var tab_size = parseInt( briskjab_syntax_codemirroroption["tab_size"] );
    var dollar_sign = briskjab_syntax_codemirroroption["dollar_sign"];

    // Find textareas on page and replace them with the CodeMirror editor
    $('textarea.bjsh_textarea').each(function(index, element){
        // Switch language mode
        var mime = $( element ).attr( "data-mode" );

        var editor = CodeMirror.fromTextArea(element, {
            lineNumbers: line_numbers,
			styleActiveLine: true,
            firstLineNumber: first_line_number,
            matchBrackets: true,
            indentUnit: tab_size,
            readOnly: true,
            theme: theme,
            mode: mime,
            autoRefresh: true
        });
		/** MUST SEE---some mode like python and cython uses below code in 'mode' option so design script according to it
		mode: {name: "text/x-cython",
               version: 2,
               singleLineStringErrors: false},
		*/
    });

    // Replace line numbers with dollar sign
    if ( dollar_sign == 'true' ) {
        $(".CodeMirror-linenumber").each(function(){
            var number = $(this).text();
            var dollar = number.replace(/[0-9]+/, "$");
            $(this).text(dollar);
        });
    }

});
