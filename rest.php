<?php

add_action( 'rest_api_init', function () {
  register_rest_route( 'magazine/v1', '/pdf', array(
    'methods' => 'POST',
    'callback' => 'renderPDFForRest',
    'permission_callback' => function () {
      return is_user_logged_in();
    },
    'args' => array(
        'ids' => array(
          'required' => true,
          'validate_callback' => function($param, $request, $key) {
            return isset($param) && is_array($param);
          },
          'type' => 'array',
          'description' => 'An array of Post IDs.'
        ),
        'theme' => array(
          'required' => true,
          'validate_callback' => function($param, $request, $key) {
            return isset($param) && trim($param) != '';
          },
          'type' => 'string',
          'description' => 'The Theme name as string.'
        ),
    )
  ) );
} );

function renderPDFForRest( WP_REST_Request $request ) {
    $aParameters = $request->get_params();
   
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