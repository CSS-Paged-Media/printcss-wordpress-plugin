<?php

add_action('admin_print_footer_scripts', function(){
    global $post;
    
	$screen 		 = get_current_screen();
    $supported_types = get_post_types();
    
	if(
		strpos($screen->parent_file, 'edit.php') !== FALSE 
		&& 
		in_array($screen->id, $supported_types) 
		&& 
		in_array($screen->post_type, $supported_types) 
		&& 
		$screen->action != 'add'
	){
		
		$aThemes = magazine_template::_getTemplateNames();
            
        if(is_array($aThemes) && count($aThemes) == 0){ // Create Demo Template if there is none
            magazine_template::_createDemoTemplate();
            $aThemes = magazine_template::_getTemplateNames();
        }

		$sTemplateOptions = '';
        foreach($aThemes as $sTheme){
            $sTemplateOptions .= '<option value=\'' . $sTheme . '\'>' . __( 'PDF Theme: ' . $sTheme, 'magazine_render_pdf') . '</option>';
        }
		
		$sLink = '$(".wrap .page-title-action").after("<select id=\'magazine_template_selection\'>' . $sTemplateOptions . '</select><a style=\'color: white;background: lightseagreen;border: lightseagreen;\' href=\'#\' onclick=\'magazine_post_request([' . $post->ID . '], document.getElementById(\"magazine_template_selection\").value);\' class=\'prev-post page-title-action\'>Render PDF</a>");';
		if($screen->is_block_editor){
			$sLink = '$(".edit-post-header__settings").prepend("<select id=\'magazine_template_selection\'>' . $sTemplateOptions . '</select><a style=\'margin-right:10px;background: lightseagreen !important;border-color: lightseagreen !important;\' href=\'#\' onclick=\'magazine_post_request([' . $post->ID . '], document.getElementById(\"magazine_template_selection\").value);\' class=\'prev-post components-button is-button is-primary is-large\'>Render PDF</a>");';
		}
		echo '
			<script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/restrequest.js"></script>
			<script>
				if(window.jQuery) {
					jQuery(document).ready(function($) {
						$(window).load(function() {
							setTimeout(function(){
								' . $sLink . '
							}, 2000);
						});
					});
				}
            </script>';
    }
});