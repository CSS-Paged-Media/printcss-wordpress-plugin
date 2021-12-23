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
            $magazine_rendering_tool        = get_option('magazine_rendering_tool');
            $magazine_rapidapi_key          = get_option('magazine_rapidapi_key');
            $magazine_docraptor_key         = get_option('magazine_docraptor_key');
            $magazine_typeset_token_key     = get_option('magazine_typeset_token_key');
            $magazine_typeset_project_key   = get_option('magazine_typeset_project_key');
            $magazine_local_command         = get_option('magazine_local_command');
            $sPageOrPost                    = get_post_type(reset($aPostIds));
            
            $sHtmlToRender  = magazine_template::_replacePrefixPlaceholders($aPostIds, magazine_template::_getHTML($sTheme, 'prefix'));
            $sHtmlToRender .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getHTML($sTheme, $sPageOrPost));
            $sHtmlToRender .= magazine_template::_replacePrefixPlaceholders($aPostIds, magazine_template::_getHTML($sTheme, 'postfix'));
    
            $sCssToRender   = magazine_template::_getCSS($sTheme, 'style');
            $sCssToRender  .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getCSS($sTheme, $sPageOrPost));
            $sCssToRender  .= '.hide_render_link_in_pdf{ display:none !important; }';
    
            $sJsToRender    = magazine_template::_getJS($sTheme, 'script');
            $sJsToRender   .= magazine_template::_replacePlaceholders($aPostIds, magazine_template::_getJS($sTheme, $sPageOrPost));
            
            /**
             * The function magazineModifyHtml can be defined in the functions.php of your 
             * WordPress installation, you will get passed an string of the HTML code which you can modify before the PDF gets rendered.
             */
            if(function_exists('magazineModifyHtml')){
                $sHtmlToRender = magazineModifyHtml($sHtmlToRender);
            }

            /**
             * The function magazineModifyCss can be defined in the functions.php of your 
             * WordPress installation, you will get passed an string of the CSS code which you can modify before the PDF gets rendered.
             */
            if(function_exists('magazineModifyCss')){
                $sCssToRender = magazineModifyCss($sCssToRender);
            }

            /**
             * The function magazineModifyJavascript can be defined in the functions.php of your 
             * WordPress installation, you will get passed an string of the Javascript code which you can modify before the PDF gets rendered.
             */
            if(function_exists('magazineModifyJavascript')){
                $sJsToRender = magazineModifyJavascript($sJsToRender);
            }
            
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
                    $sCodeToRender = self::_getCompleteCodeToRender(
                        $sHtmlToRender,
                        $sCssToRender,
                        $sJsToRender
                    );

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

                case 'typeset0.16.3':
                case 'typeset0.16.0':
                case 'typeset0.15.0':
                    $sVersionNumber = str_replace('typeset', '', $magazine_rendering_tool);
                    $sCodeToRender = self::_getCompleteCodeToRender(
                        $sHtmlToRender,
                        $sCssToRender,
                        $sJsToRender
                    );

                    $curl = curl_init();
                    curl_setopt_array(
                        $curl, 
                        array(
                            CURLOPT_URL => 'https://api.typeset.sh/?version=' . $sVersionNumber,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => $sCodeToRender,
                            CURLOPT_HTTPHEADER => array(
                                'project: ' . $magazine_typeset_project_key,
                                'token: ' . $magazine_typeset_token_key
                            ),
                        )
                    );
                    $pdfContent = curl_exec($curl);
                    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);

                    break;
                case 'local':
                    $sCodeToRender = self::_getCompleteCodeToRender(
                        $sHtmlToRender,
                        $sCssToRender,
                        $sJsToRender
                    );
                    $descriptorspec = array(
                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                        2 => array("pipe", "w")   // stderr is a file to write to
                    );
                    $process = proc_open($magazine_local_command, $descriptorspec, $pipes);
                    $pdfContent = null;
                    if (is_resource($process)) {
                        // $pipes now looks like this:
                        // 0 => writeable handle connected to child stdin
                        // 1 => readable handle connected to child stdout
                        // Any error output will be appended to /tmp/error-output.txt
                    
                        fwrite($pipes[0], $sCodeToRender);
                        fclose($pipes[0]);
                    
                        $pdfContent = stream_get_contents($pipes[1]);
                        if($pdfContent == '' || $pdfContent === null){
                            $pdfContent  = 'Problems with local renderer command';
                            $http_status = 400;
                        }else{
                            $http_status = 200;
                        }

                        fclose($pipes[1]);
                        $sErrors = stream_get_contents($pipes[2]);
                        if($sErrors != ''){
                            $pdfContent  = $sErrors;
                            $http_status = 400;
                        }
                        fclose($pipes[2]);
        
                        // It is important that you close any pipes before calling
                        // proc_close in order to avoid a deadlock

                        $return_value = proc_close($process);
                    }else{
                        $pdfContent  = 'Problems with local renderer command';
                        $http_status = 400;
                    }

                    break;
            }
    
            return [
                'status_code' => $http_status,
                'content'     => $pdfContent
            ];
        }

        /**
         * This Method is used to build the code to render. 
         * 
         * @param string $sHtmlToRender         The HTML code for the PDF
         * @param string $sCssToRender          The CSS code for the PDF
         * @param string $sJsToRender           The JS code for the PDF 
         * 
         * @return string
         */
        public static function _getCompleteCodeToRender(
            string $sHtmlToRender,
            string $sCssToRender,
            string $sJsToRender
        ) : string {
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

            # Remove ftp:// and file:// links
            $sCodeToRender = preg_replace('/\b(ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $sCodeToRender);
            
            return $sCodeToRender;
        }
    }
