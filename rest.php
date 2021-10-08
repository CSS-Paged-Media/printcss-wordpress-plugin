<?php

add_action( 'rest_api_init', function () {
  register_rest_route( 'magazine/v1', '/pdf', array(
    'methods' => 'POST',
    'callback' => 'renderPDFForRest',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ) );
} );

function renderPDFForRest( WP_REST_Request $request ) {
    $aParameters = $request->get_params();

    if(!isset($aParameters['theme']) || trim($aParameters['theme']) == ''){
        return new WP_Error( 'no_theme', 'Invalid Theme', array( 'status' => 404 ) );
    }

    if(!isset($aParameters['ids']) || !is_array($aParameters['ids'])){
        return new WP_Error( 'no_ids', 'Invalid Post IDs', array( 'status' => 404 ) );
    }
   
    $aRenderResult = magazine_pdf::_renderPDF($aParameters['ids'], $aParameters['theme']);
    $http_status   = $aRenderResult['status_code'];
    $pdfContent    = $aRenderResult['content'];

    if($http_status == 200){
        header('Content-Type: application/pdf');
        echo $pdfContent;
        exit;
    }else{
        return new WP_REST_Response(json_decode($pdfContent), $http_status);
    }
}