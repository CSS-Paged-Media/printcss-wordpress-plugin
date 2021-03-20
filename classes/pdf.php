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
            $sPageOrPost                = get_post_type(reset($aPostIds));
            
            $sHtmlToRender  = magazine_template::_getHTML($sTheme, 'prefix');
            $sHtmlToRender .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getHTML($sTheme, $sPageOrPost));
            $sHtmlToRender .= magazine_template::_getHTML($sTheme, 'postfix');
    
            $sCssToRender   = magazine_template::_getCSS($sTheme, 'style');
            $sCssToRender  .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getCSS($sTheme, $sPageOrPost));
    
            $sJsToRender    = magazine_template::_getJS($sTheme, 'script');
            $sJsToRender   .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getJS($sTheme, $sPageOrPost));
    
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
    
            return [
                'status_code' => $http_status,
                'content'     => $pdfContent
            ];
        }
    }