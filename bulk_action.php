<?php 

    add_filter('bulk_actions-edit-post', 'magazine_render_pdf_bulk_actions');
    add_filter('bulk_actions-edit-page', 'magazine_render_pdf_bulk_actions');
    
    function magazine_render_pdf_bulk_actions($bulk_actions) {
        $aThemes = magazine_template::_getTemplateNames();
            
        if(is_array($aThemes) && count($aThemes) == 0){ // Create Demo Template if there is none
            magazine_template::_createDemoTemplate();
            $aThemes = magazine_template::_getTemplateNames();
        }

        foreach($aThemes as $sTheme){
            $bulk_actions['magazine_render_pdf_bulk_action_'. $sTheme] = __( 'Render PDF with ' . $sTheme . ' Theme', 'magazine_render_pdf');
        }

        return $bulk_actions;
    }

    add_filter('handle_bulk_actions-edit-post', 'magazine_render_pdf_bulk_handler', 10, 3);
    add_filter('handle_bulk_actions-edit-page', 'magazine_render_pdf_bulk_handler', 10, 3);
    
    function magazine_render_pdf_bulk_handler($redirect_to, $doaction, $post_ids) {
        if (strpos($doaction, 'magazine_render_pdf_bulk_action_') !== 0) {
            
            return $redirect_to;
        }

        $sTheme = str_replace('magazine_render_pdf_bulk_action_', '', $doaction);

        $aRenderResult = magazine_pdf::_renderPDF($post_ids, $sTheme);
		
		$redirect_to = add_query_arg( 'magazine_pdf_theme', $sTheme, $redirect_to);
		$redirect_to = add_query_arg( 'magazine_pdf_post_ids', json_encode($post_ids), $redirect_to);

        return $redirect_to;
    }
	
	add_action('in_admin_footer', function(){
		
		wp_localize_script(
			'wp-api', 
			'wpApiSettings', 
			array(
				'root' => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' )
			)
		);
		wp_enqueue_script('wp-api');
		
		if (!empty($_REQUEST['magazine_pdf_theme']) && !empty($_REQUEST['magazine_pdf_post_ids'])) {
			
			echo '
				<script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/restrequest.js"></script>
				<script>
					if(window.jQuery) {
						jQuery(document).ready(function($) {
							$(window).load(function() {
								magazine_post_request(' . $_REQUEST['magazine_pdf_post_ids'] . ', "' . $_REQUEST['magazine_pdf_theme'] . '");
							});
						});
					}
				</script>
			';
		}
		
	}, 1);

    // If option to show bulk action on custom post types is on add the filters (add_filter)
    if(get_option('magazine_show_action_on_custom_post_types') == 1){
        add_action('wp_loaded', function(){
            $args = array(
                'public'   => true,
                '_builtin' => false,
             );
            
             $post_types = get_post_types( $args ); 
             foreach ( $post_types  as $post_type ) {
                add_filter('bulk_actions-edit-' . $post_type, 'magazine_render_pdf_bulk_actions');
                add_filter('handle_bulk_actions-edit-' . $post_type, 'magazine_render_pdf_bulk_handler', 10, 3);
             }
        });
    }