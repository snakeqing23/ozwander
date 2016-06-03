<?php

/*
	Plugin Name: SCEditor
	Plugin URI: http://www.q2apro.com/plugins/sceditor
	Plugin Description: Provides the SCEditor as WYSIWYG rich text editor for your question2answer forum.
	Licence: Copyright © q2apro.com - All rights reserved
*/


	class qa_html_theme_layer extends qa_html_theme_base {
		
		function head_script(){
		
			// solve sceditor upload bug caused by jquery v1.7.2 which comes by default with q2a v1.7 and lower
			// load latest jquery from CDN
			if (isset($this->content['script'])) {
				foreach ($this->content['script'] as &$scriptline) {
					if(strpos($scriptline,'jquery-1.7.2') !== false) {
						$scriptline = '<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>';
					}
				}
			}
		
			qa_html_theme_base::head_script();
			// check if plugin is enabled, only load js-css-files on ask and question pages
			// and if user is logged-in or if requested by anonymous
			if(qa_opt('q2apro_sceditor_enabled') && ($this->template=='ask' || $this->template=='question'))
			{
				// && (qa_is_logged_in() || qa_get_state())) {
				
				// available: jquery.sceditor.min.js jquery.sceditor.xhtml.min.js jquery.sceditor.bbcode.min.js
				$editorscripting = 'jquery.sceditor.min.js';
				if(qa_opt('q2apro_sceditor_editorplugin')=='xhtml') {
					$editorscripting = 'jquery.sceditor.xhtml.min.js';
				}
				else if(qa_opt('q2apro_sceditor_editorplugin')=='bbcode') {
					$editorscripting = 'jquery.sceditor.bbcode.min.js';
				}
				
				// available themes: default.min.css modern.min.css office.min.css office-toolbar.min.css square.min.css
				$editortheme = qa_opt('q2apro_sceditor_editortheme'); // 'square.min.css'
				
				$this->output('<link rel="stylesheet" type="text/css" href="'.QA_HTML_THEME_LAYER_URLTOROOT.'themes/'.$editortheme.'" />');
				$this->output('<script type="text/javascript" src="'.QA_HTML_THEME_LAYER_URLTOROOT.'minified/'.$editorscripting.'"></script>');
				
				// locale js
				$editorlocale = qa_opt('q2apro_sceditor_editorlocale');
				if($editorlocale!='en') {
					$this->output('<script type="text/javascript" src="'.QA_HTML_THEME_LAYER_URLTOROOT.'languages/'.$editorlocale.'.js"></script>');
				}
				
				$this->output('
				<style type="text/css">
					/* changed to bigger color fields */
					.sceditor-color-option {
						height: 15px;
						width: 15px;
					}
					.sceditor-container {
						width:100%!important;
					}
					.sceditor-container iframe {
						width:95%!important;
					}
					.previewfield {
						width:100%;
						height:auto;
						padding:10px;
						border:1px solid #EEE;
						background:#FFF;
						font-family:sans-serif,Arial;
						font-size:14px;
						line-height:150%;
					}
					.qa-a-form .previewfield, .qa-c-form .previewfield {
						width:88%;
					}
					.previewfield p {
						padding:0;
						margin:0 0 12px 0;
					}
					/* fix for snowflat theme 1.7 */
					.sceditor-button {
						box-sizing:content-box;
					}
				</style>
				');

			} // end q2apro_sceditor_enabled
		}

	} // end qa_html_theme_layer
	

/*
	Omit PHP closing tag to help avoid accidental output
*/