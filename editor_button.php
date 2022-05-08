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
		
		$sLink = '$(".wrap .page-title-action").after("<select id=\'magazine_template_selection\'>' . $sTemplateOptions . '</select><a style=\'color: white;background: lightseagreen;border: lightseagreen;\' href=\'#\' onclick=\'magazine_post_request();\' class=\'prev-post page-title-action\'>Render PDF</a>");';
		if($screen->is_block_editor){
			$sLink = '$(".edit-post-header__settings").prepend("<select id=\'magazine_template_selection\'>' . $sTemplateOptions . '</select><a style=\'margin-right:10px;background: lightseagreen !important;border-color: lightseagreen !important;\' href=\'#\' onclick=\'magazine_post_request();\' class=\'prev-post components-button is-button is-primary is-large\'>Render PDF</a>");';
		}
		echo '<script>
				if(window.jQuery) {
					jQuery(document).ready(function($) {
						$(window).load(function() {
							setTimeout(function(){
								' . $sLink . '
							}, 2000);
						});
					});
						
					const b64toBlob = (b64Data, contentType="", sliceSize=512) => {
					  const byteCharacters = atob(b64Data);
					  const byteArrays = [];

					  for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
						const slice = byteCharacters.slice(offset, offset + sliceSize);

						const byteNumbers = new Array(slice.length);
						for (let i = 0; i < slice.length; i++) {
						  byteNumbers[i] = slice.charCodeAt(i);
						}

						const byteArray = new Uint8Array(byteNumbers);
						byteArrays.push(byteArray);
					  }

					  const blob = new Blob(byteArrays, {type: contentType});
					  return blob;
					}
						
					function magazine_post_request(){
					  
						jQuery.ajax( {
						   url: wpApiSettings.root + "magazine/v1/pdf",
						   method: "POST",
						   beforeSend: function ( xhr ) {
							   xhr.setRequestHeader( "X-WP-Nonce", wpApiSettings.nonce );
						   },
						   data:{
								"ids": [' . $post->ID . '],
								"theme": document.getElementById("magazine_template_selection").value,
								"base64": true
							},
							success: function (data){
								console.log(data);
								var blob = b64toBlob(data, "application/pdf");
								var link=document.createElement("a");
								var url=window.URL.createObjectURL(blob);
								link.href=url;
								link.download="magazine.pdf";
								link.click();
								setTimeout(() => URL.revokeObjectURL(url), 2000);
							},
							error: function (data){
								alert("PDF generation, please try again.");        
							}
						});
					  
						
					}
				}
            </script>';
    }
});