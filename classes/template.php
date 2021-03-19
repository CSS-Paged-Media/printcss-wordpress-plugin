<?php

    class magazine_template{
        
        /**
         * This method is used to get the content of a HTML template file.
         * 
         * @param string $sTheme        The name of the Magazine Template
         * @param string $sType         The name of the file without file extension
         * 
         * @return string               The content of the file if available otherwise a empty string.
         */
        public static function _getHTML(string $sTheme, string $sType) : string {
            $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sTheme . '/';
            $sHtml              = '';

            if(is_dir($sMagazineThemePath)){
                if(is_file($sMagazineThemePath . $sType . '.html')){
                    $sHtml = file_get_contents($sMagazineThemePath . $sType . '.html');
                }
            }

            return $sHtml;
        }
        
        /**
         * This method is used to get the content of a CSS template file.
         * 
         * @param string $sTheme        The name of the Magazine Template
         * @param string $sType         The name of the file without file extension
         * 
         * @return string               The content of the file if available otherwise a empty string.
         */
        public static function _getCSS(string $sTheme, string $sType) : string {
            $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sTheme . '/css/';
            $sCSS               = '';

            if(is_dir($sMagazineThemePath)){
                if(is_file($sMagazineThemePath . $sType . '.css')){
                    $sCSS = file_get_contents($sMagazineThemePath . $sType . '.css');
                }
            }

            return $sCSS;
        }
        
        /**
         * This method is used to get the content of a JavaScript template file.
         * 
         * @param string $sTheme        The name of the Magazine Template
         * @param string $sType         The name of the file without file extension
         * 
         * @return string               The content of the file if available otherwise a empty string.
         */
        public static function _getJS(string $sTheme, string $sType) : string {
            $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sTheme . '/js/';
            $sJS                = '';

            if(is_dir($sMagazineThemePath)){
                if(is_file($sMagazineThemePath . $sType . '.js')){
                    $sJS = file_get_contents($sMagazineThemePath . $sType . '.js');
                }
            }

            return $sJS;
        }

        /**
         * This method is used to replace the {{NAME}} placeholders in the passed content.
         * The placeholders available are {{slug}}, {{title}}, {{feature_image}}, {{content}},
         * {{author}}, {{date}}, {{date_gmt}}, {{excerpt}}, {{status}}, {{year}}, {{month}},
         * {{day}}, {{hour}}, {{minute}}.
         * 
         * Additionally there is support for the advanced custom fields (ACF), these placeholders
         * have a ACF_ prefix {{ACF_fieldname}}.
         * 
         * @param array $aPostIds   The ids of the posts which need to be used for replacement.
         * @param string $sContent  The content in which the placeholders need to be replaced.
         * 
         * @return string           The content with replaced placeholders.
         */
        public static function _replacePlaceholders(array $aPostIds, string $sContent) : string {
            $sContentFinal = '';
    
            foreach ($aPostIds as $post_id) {
                $sContentTemp = '';
                $sContentTemp .= str_replace(
                    [
                        '{{title}}',
                        '{{content}}',
                        '{{feature_image}}',
                        '{{slug}}',
                        '{{author}}',
                        '{{date}}',
                        '{{date_gmt}}',
                        '{{excerpt}}',
                        '{{status}}',
                        '{{year}}',
                        '{{month}}',
                        '{{day}}',
                        '{{hour}}',
                        '{{minute}}'
                    ],
                    [
                        get_the_title($post_id),
                        apply_filters('the_content', get_post_field('post_content', $post_id)),
                        (has_post_thumbnail($post_id) ? get_the_post_thumbnail($post_id, 'full') : ''),
                        get_post_field('post_name', $post_id),
                        get_the_author_meta('display_name', get_post_field('post_author', $post_id)),
                        get_post_field('post_date', $post_id),
                        get_post_field('post_date_gmt', $post_id),
                        get_post_field('post_excerpt', $post_id),
                        get_post_field('post_status', $post_id),
                        date('Y', strtotime(get_post_field('post_date', $post_id))),
                        date('m', strtotime(get_post_field('post_date', $post_id))),
                        date('d', strtotime(get_post_field('post_date', $post_id))),
                        date('H', strtotime(get_post_field('post_date', $post_id))),
                        date('i', strtotime(get_post_field('post_date', $post_id))),
                    ],
                    $sContent
                );
    
                /* Add ACF Support Start */
                    if(function_exists('get_field_objects')){
                        $fields = get_field_objects($post_id);
    
                        foreach($fields as $fieldname => $fieldArray){
                            $sContentTemp = str_replace(
                                '{{ACF_' . $fieldname . '}}',
                                (
                                    ($fieldArray['default_value'] != '' && $fieldArray['value'] == '')
                                    ?
                                    $fieldArray['default_value']
                                    :
                                    (
                                        (is_array($fieldArray['value']) && $fieldArray['type'] == 'image')
                                        ?
                                        $fieldArray['value']['url']
                                        :
                                        $fieldArray['value']
                                    )
                                ),
                                $sContentTemp
                            );
                        }
                    }
                /* ADD ACF Support END */
    
                $sContentFinal .= $sContentTemp;
            }
    
            return $sContentFinal;
        }
    }