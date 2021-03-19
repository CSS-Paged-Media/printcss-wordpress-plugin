<?php 

    add_filter( 'bulk_actions-edit-post', 'magazine_render_pdf_bulk_actions');
    add_filter( 'bulk_actions-edit-page', 'magazine_render_pdf_bulk_actions');
    
    function magazine_render_pdf_bulk_actions($bulk_actions) {
        $bulk_actions['magazine_render_pdf_bulk_action'] = __( 'Render PDF', 'magazine_render_pdf');

        return $bulk_actions;
    }

    add_filter('handle_bulk_actions-edit-post', 'magazine_render_pdf_bulk_handler', 10, 3);
    add_filter('handle_bulk_actions-edit-page', 'magazine_render_pdf_bulk_handler', 10, 3);
    
    function magazine_render_pdf_bulk_handler($redirect_to, $doaction, $post_ids) {
        if ($doaction !== 'magazine_render_pdf_bulk_action') {
            
            return $redirect_to;
        }

        $aRenderResult = magazine_pdf::_renderPDF($post_ids);
        $http_status   = $aRenderResult['status_code'];
        $pdfContent    = $aRenderResult['content'];

        if($http_status == 200){
            $upload_dir = wp_upload_dir();
            $filename   = 'magazin.' . implode('.', $post_ids) . '-' . date('Y-m-d-H-i-s') . '.pdf';

            if ( wp_mkdir_p( $upload_dir['path'] . '/magazine' ) ) {
                $file = $upload_dir['path'] . '/magazine/'. $filename;
            }
            else {
                $file = $upload_dir['basedir'] . '/magazine/'. $filename;
            }

            file_put_contents($file, $pdfContent);

            $wp_filetype = wp_check_filetype($filename, null);

            $attachment = array(
                'post_mime_type'    => $wp_filetype['type'],
                'post_title'        => sanitize_file_name($filename),
                'post_content'      => '',
                'post_status'       => 'inherit'
            );

            $attach_id = wp_insert_attachment($attachment, $file);
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            $redirect_to = add_query_arg( 'magazine_pdf_content_attachment', $attach_id, $redirect_to);
        }else{
            $oError      = json_decode($pdfContent);
            $redirect_to = add_query_arg( 'magazine_pdf_content_error', $oError->message, $redirect_to);
        }

        return $redirect_to;
    }

    add_action('admin_notices', function(){
        if (!empty($_REQUEST['magazine_pdf_content_attachment'])) {
            print( '<div id="message" class="updated fade">PDF generation done, 
            <a download href="' . wp_get_attachment_url($_REQUEST['magazine_pdf_content_attachment']) 
            . '">download the PDF here</a>
            </div>');
        }else if(!empty($_REQUEST['magazine_pdf_content_error'])){
            print( '<div id="message" class="error fade">Error "' 
                . $_REQUEST['magazine_pdf_content_error'] .'" generating PDF file.</div>');
        }
    });