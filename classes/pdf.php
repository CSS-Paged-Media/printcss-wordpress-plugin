<?php

    class magazine_pdf{

        /**
         * This method is used to send a request to the PrintCSS Cloud API and 
         * return the status code and result.
         * 
         * @param array $aPostIds       The post ids for which the PDF should be rendered.
         * @param string $sTheme        The name of the theme which should be used to render.
         * 
         * @return array                The status code and result.
         */
        public static function _renderPDF(array $aPostIds, string $sTheme) : array {
            $magazine_rendering_tool    = get_option('magazine_rendering_tool');
            $magazine_rapidapi_key      = get_option('magazine_rapidapi_key');
            $magazine_docraptor_key     = get_option('magazine_docraptor_key');
            $sPageOrPost                = get_post_type(reset($aPostIds));
            
            $sHtmlToRender  = magazine_template::_getHTML($sTheme, 'prefix');
            $sHtmlToRender .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getHTML($sTheme, $sPageOrPost));
            $sHtmlToRender .= magazine_template::_getHTML($sTheme, 'postfix');
    
            $sCssToRender   = magazine_template::_getCSS($sTheme, 'style');
            $sCssToRender  .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getCSS($sTheme, $sPageOrPost));
    
            $sJsToRender    = magazine_template::_getJS($sTheme, 'script');
            $sJsToRender   .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getJS($sTheme, $sPageOrPost));
            
            switch($magazine_rendering_tool){
                case 'weasyprint':
                case 'pagedjs':
                case 'vivliostyle':
                    $curl = curl_init();
                    if(trim($sJsToRender) === ''){
                        $oSend = [
                            "html" => $sHtmlToRender,
                            "css" => $sCssToRender,
                            "options" => [
                                "renderer" => $magazine_rendering_tool
                            ]
                        ];
                    }else{
                        $oSend = [
                            "html" => $sHtmlToRender,
                            "css" => $sCssToRender,
                            "javascript" => $sJsToRender,
                            "options" => [
                                "renderer" => $magazine_rendering_tool
                            ]
                        ];
                    }
            
                    curl_setopt_array(
                        $curl, 
                        array(
                            CURLOPT_URL => 'https://printcss-cloud.p.rapidapi.com/render',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($oSend),
                            CURLOPT_HTTPHEADER => array(
                                'x-rapidapi-host: printcss-cloud.p.rapidapi.com',
                                'x-rapidapi-key: ' . $magazine_rapidapi_key
                            ),
                        )
                    );
                    $pdfContent = curl_exec($curl);
                    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);
                    break;

                case 'prince14':
                case 'prince13':
                case 'prince12':
                case 'prince11':
                    $sCodeToRender = '';
                    if(strpos($sHtmlToRender, '<html') === false){
                        $sCodeToRender = '<!DOCTYPE html>
                            <html>
                            <head>
                                <style>' . $sCssToRender . '</style>
                            </head>
                            <body>
                                ' . $sHtmlToRender . '
                                <script>' . $sJsToRender . '</script>
                            </body>
                            </html>';
                    }else if(strpos($sHtmlToRender, '</head>') !== false){
                        $sCodeToRender = str_replace('</head>', '<style>' . $sCssToRender . '</style><script>' . $sJsToRender . '</script></head>', $sHtmlToRender);
                    }else{
                        $sCodeToRender = '<style>' . $sCssToRender . '</style>' . $sHtmlToRender . '<script>' . $sJsToRender . '</script>';
                    }

                    $oSend = [
                        'test'             => false,
                        'document_content' => $sCodeToRender,
                        'type'             => 'pdf',
                        'javascript'       => 'true',
                        'pipeline'         => (
                            $magazine_rendering_tool == 'prince14'
                            ?
                            9
                            :
                            (
                                $magazine_rendering_tool == 'prince13'
                                ?
                                8
                                :
                                (
                                    $magazine_rendering_tool == 'prince12'
                                    ?
                                    7
                                    :
                                    6
                                )
                            )
                        )
                    ];

                    $curl = curl_init();
                    curl_setopt_array(
                        $curl, 
                        array(
                            CURLOPT_URL => 'https://' . $magazine_docraptor_key . '@docraptor.com/docs',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($oSend),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type:application/json'
                            ),
                        )
                    );
                    $pdfContent = curl_exec($curl);
                    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);

                    break;
            }
    
            return [
                'status_code' => $http_status,
                'content'     => $pdfContent
            ];
        }
    }