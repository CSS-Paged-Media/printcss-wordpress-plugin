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
         * This method is used to store updated HTML content to the file.
         * 
         * @param string $sTheme        The name of the Magazine Template
         * @param string $sType         The name of the file without file extension
         * @param string $sNewContent   The new file content
         * 
         * @return void
         */
        public static function _setHTML(string $sTheme, string $sType, string $sNewContent) : void {
            $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sTheme . '/';

            if(is_dir($sMagazineThemePath)){
                file_put_contents($sMagazineThemePath . $sType . '.html', $sNewContent);
            }else{
                if(mkdir($sMagazineThemePath)){
                    file_put_contents($sMagazineThemePath . $sType . '.html', $sNewContent);
                }
            }
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
         * This method is used to store updated CSS content to the file.
         * 
         * @param string $sTheme        The name of the Magazine Template
         * @param string $sType         The name of the file without file extension
         * @param string $sNewContent   The new file content
         * 
         * @return void
         */
        public static function _setCSS(string $sTheme, string $sType, string $sNewContent) : void {
            $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sTheme . '/css/';

            if(is_dir($sMagazineThemePath)){
                file_put_contents($sMagazineThemePath . $sType . '.css', $sNewContent);
            }else{
                if(mkdir($sMagazineThemePath)){
                    file_put_contents($sMagazineThemePath . $sType . '.css', $sNewContent);
                }
            }
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
         * This method is used to store updated JavaScript content to the file.
         * 
         * @param string $sTheme        The name of the Magazine Template
         * @param string $sType         The name of the file without file extension
         * @param string $sNewContent   The new file content
         * 
         * @return void
         */
        public static function _setJS(string $sTheme, string $sType, string $sNewContent) : void {
            $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sTheme . '/js/';

            if(is_dir($sMagazineThemePath)){
                file_put_contents($sMagazineThemePath . $sType . '.js', $sNewContent);
            }else{
                if(mkdir($sMagazineThemePath)){
                    file_put_contents($sMagazineThemePath . $sType . '.js', $sNewContent);
                }
            }
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
                        if(is_array($fields)){
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
                    }
                /* ADD ACF Support END */
    
                $sContentFinal .= $sContentTemp;
            }
    
            return $sContentFinal;
        }

        /**
         * This Method returns all folders (themes) within the magazine_themes folder as array.
         * 
         * @return array        Array containing the theme names.
         */
        public static function _getTemplateNames() : array {
            $aDirectories = [];
            foreach(glob(WP_CONTENT_DIR . '/magazine_themes/*', GLOB_ONLYDIR) as $sDirectory) {
                $aDirectories[] = basename($sDirectory);
            }

            return $aDirectories;
        }

        /**
         * This method is used to duplicate a theme, the duplication first checks if the new folder already
         * exists, if so then nothing is done.
         * 
         * @param string $sOriginalTheme    The name of the theme which should get duplicated
         * @param string $sDuplicateName    The name of the new duplicate
         * 
         * @return void
         */
        public static function _duplicateTemplate(string $sOriginalTheme, string $sDuplicateName) : void {
            $sMagazineOriginalThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sOriginalTheme . '/';
            $sMagazineDuplicateThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sDuplicateName . '/';
            
            if(!is_dir($sMagazineDuplicateThemePath)){
                self::_recursiveCopy($sMagazineOriginalThemePath, $sMagazineDuplicateThemePath);
            }
        }

        /**
         * This Method is used to create a demo template when the plugin is activated 
         * or when no template is available but the theme editor is opened.
         * 
         * @return void
         */
        public static function _createDemoTemplate() : void {
            $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/';
            if(!is_dir($sMagazineThemePath)){
                mkdir($sMagazineThemePath);
            }

            $sMagazineDemoThemePath = $sMagazineThemePath . 'Demo/';
            if(mkdir($sMagazineDemoThemePath)){ # create demo theme
                
                // HTML for POSTs with placeholder support post.html
                    file_put_contents(
                        $sMagazineDemoThemePath . 'post.html', 
                        "<div class=\"new-page-per-post\"><!-- This div and class is used to always start a post/page on a new page in the PDF -->\n\t<div class=\"logo-area\"><!-- This div creates a sample logo on each page -->\n\t\t<div class=\"circle\"></div>\n\t\t<h1>Company Logo</h1>\n\t</div>\n\t<div class=\"copyright\"><!-- This div is used to show the copyright message in the footer of each page -->\n\t\t<p>&copy; Copyright {{year}}</p>\n\t\t<p>All contents of this news are protected by copyright. Company Ltd. owns the copyright.</p>\n\t</div>\n\t<div class=\"content\"><!-- The main content of the page/post -->\n\t\t<h3>News</h3>\n\t\t<h1>{{title}}</h1>\n\t\t<p>{{content}}</p>\n\t</div>\n</div>"
                    );

                // HTML for Pages with placeholder support page.html
                    file_put_contents(
                        $sMagazineDemoThemePath . 'page.html', 
                        "<div class=\"new-page-per-post\"><!-- This div and class is used to always start a post/page on a new page in the PDF -->\n\t<div class=\"logo-area\"><!-- This div creates a sample logo on each page -->\n\t\t<div class=\"circle\"></div>\n\t\t<h1>Company Logo</h1>\n\t</div>\n\t<div class=\"copyright\"><!-- This div is used to show the copyright message in the footer of each page -->\n\t\t<p>&copy; Copyright {{year}}</p>\n\t\t<p>All contents of this news are protected by copyright. Company Ltd. owns the copyright.</p>\n\t</div>\n\t<div class=\"content\"><!-- The main content of the page/post -->\n\t\t<h3>News</h3>\n\t\t<h1>{{title}}</h1>\n\t\t<p>{{content}}</p>\n\t</div>\n</div>"
                    );

                // HTML for before everything no placeholder support prefix.html
                    file_put_contents(
                        $sMagazineDemoThemePath . 'prefix.html', 
                        'Cover Page'
                    );

                // HTML for after everything no placeholder support postfix.html
                    file_put_contents(
                        $sMagazineDemoThemePath . 'postfix.html', 
                        'Back Cover Page'
                    );

                // CSS Folder and Files:
                    $sMagazineDemoThemeCSSPath = $sMagazineDemoThemePath . 'css/';
                    if(mkdir($sMagazineDemoThemeCSSPath)){ # create demo theme css folder

                        // CSS general, always loaded no placeholder support style.css
                            file_put_contents(
                                $sMagazineDemoThemeCSSPath . 'style.css', 
                                "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap');\n\nbody{\n\tfont-family: 'Poppins', sans-serif;\n}\n\n@page{\n\tsize:210mm 297mm; /* We use A4 as page format */\n\tmargin:4cm 3cm 3cm 1cm; /* The margins top: 4cm, right: 3cm, bottom: 3cm, left: 1cm */\n\t\n\t@top-right{\n\t\tcontent:element(coverLogo); /* Running elements are used to show the logo on each page */\n\t}\n\t\n\t@top-right-corner{ /* The dark strip on the right side of each page is generated with these @-rules */\n\t\tcontent:\"\";\n\t\tbackground:#000222;\n\t}\n\t\n\t@right-top{\n\t\tcontent:\"\";\n\t\tbackground:#000222;\n\t}\n\t\n\t@right-middle{\n\t\tcontent:\"\";\n\t\tbackground:#000222;\n\t}\n\t\n\t@right-bottom{\n\t\tcontent:\"\";\n\t\tbackground:#000222;\n\t}\n\t\n\t@bottom-right-corner{ /* The green strip on the bottom of each page is generated with these @-rules */\n\t\tcontent:\"\";\n\t\tbackground:#44b75b;\n\t}\n\t\n\t@bottom-right{\n\t\tcontent:\"\";\n\t\tbackground:#44b75b;\n\t}\n\t\n\t@bottom-center{\n\t\tcontent:\"\";\n\t\tbackground:#44b75b;\n\t}\n\t\n\t@bottom-left{ /* The copyright message is also a running element so it shows up on each page */\n\t\tcontent:element(copyright);\n\t\twidth:210mm;\n\t\tbackground:#44b75b;\n\t}\n\t\n\t@bottom-left-corner{\n\t\tcontent:\"\";\n\t\tbackground:#44b75b;\n\t}\n}\n\n.logo-area{ /* setting the logo area as running element so it moves from the normal page flow into the margin boxes */\n\tposition:running(coverLogo);\n}\n\n.copyright{ /* setting the copyright as running element so it moves from the normal page flow into the margin boxes */\n\tposition:running(copyright);\n\tfont-size:8pt;\n}\n\n.new-page-per-post{ /* using always as break-before parameter we ensure a new page for each new post, page-break-before is set for support of older renderers. */\n\tbreak-before:always;\n\tpage-break-before:always;\n}\n\n.circle{ /* the green circle of the sample logo */\n\twidth:1cm;\n\theight:1cm;\n\tborder:.4cm solid #44b75b;\n\tborder-radius:100%;\n\tdisplay:inline-block;\n}\n\n.logo-area h1{ /* the text of the sample logo */\n\tdisplay:inline-block;\n\ttext-transform:uppercase;\n\tcolor:#000222;\n\twidth:5cm;\n\tline-height:1;\n\tmargin-left:0.25cm;\n\tposition:relative;\n\ttop:-.25cm; \n}\n\n.content h1{ /* Only the h1 is styled in the content area */\n\tcolor:#000222;\n\tmargin-top:0;\n\ttext-transform: capitalize;\n}"
                            );
    
                        // CSS for Pages with placeholder support page.css
                            file_put_contents(
                                $sMagazineDemoThemeCSSPath . 'page.css', 
                                ''
                            );
    
                        // CSS for Posts with placeholder support post.css
                            file_put_contents(
                                $sMagazineDemoThemeCSSPath . 'post.css', 
                                ''
                            );
                    }

                // JS Folder and Files:
                    $sMagazineDemoThemeJSPath = $sMagazineDemoThemePath . 'js/';
                    if(mkdir($sMagazineDemoThemeJSPath)){ # create demo theme css folder

                        // JS general, always loaded no placeholder support style.js
                            file_put_contents(
                                $sMagazineDemoThemeJSPath . 'script.js', 
                                ''
                            );
    
                        // JS for Pages with placeholder support page.js
                            file_put_contents(
                                $sMagazineDemoThemeJSPath . 'page.js', 
                                ''
                            );
    
                        // JS for Posts with placeholder support post.js
                            file_put_contents(
                                $sMagazineDemoThemeJSPath . 'post.js', 
                                ''
                            );
                    }
            }
        }

        /**
         * This Method is used to recursively copy folders and files.
         * 
         * @param string $sSourceFolder     The path of the source folder
         * @param string $sTargetFolder     The path of the new target folder
         * 
         * @return void
         */
        public static function _recursiveCopy(string $sSourceFolder, string $sTargetFolder) : void {
            if (is_dir($sSourceFolder)) {
                mkdir($sTargetFolder);
                $files = scandir($sSourceFolder);

                foreach ($files as $file){
                    if ($file != "." && $file != ".."){
                        self::_recursiveCopy("$sSourceFolder/$file", "$sTargetFolder/$file");
                    }
                }
            }elseif(file_exists($sSourceFolder)) {
                copy($sSourceFolder, $sTargetFolder);
            }
        }

        /**
         * This Method is used to download one theme as a ZIP file.
         * 
         * @param string $sTheme            The name of the theme which needs to be downloaded
         * 
         * @return void
         */
        public static function _download(string $sTheme) : void {
            $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/' . $sTheme . '/';

            if(is_dir($sMagazineThemePath)){
                $oZip = new ZipArchive();
                $sFile = tempnam("tmp", "zip");

                if ($oZip->open($sFile, ZipArchive::OVERWRITE)!==TRUE) {
                    add_action('admin_notices', function(){
                        print('<div id="message" class="error fade"><p>Theme Download not possible</p></div>');
                    });
                    return;
                }

                // Create zip
                self::_createZip($oZip, $sMagazineThemePath);

                $oZip->close();

                header('Content-Type: application/zip');
                header('Content-Length: ' . filesize($sFile));
                header('Content-Disposition: attachment; filename="magazine.theme.' . $sTheme . '.zip"');
                readfile($sFile);
                unlink($sFile);
                exit;
            }
        }

        /**
         * This Method is used to create the actual ZIP for the download method.
         * 
         * @param object $oZip              The ZipArchive object
         * @param string $sDirectory        The current folder to be zipped.
         * 
         * @return void
         */
        public static function _createZip(ZipArchive $oZip, string $sDirectory) : void {
            $sRelativeFolder = str_replace(
                ABSPATH . 'wp-content/magazine_themes/',
                '',
                $sDirectory
            );
            
            if (is_dir($sDirectory)){
                if ($dh = opendir($sDirectory)){
                    while (($file = readdir($dh)) !== false){
                        if (is_file($sDirectory.$file)) {
                            if($file != '' && $file != '.' && $file != '..'){
                                $oZip->addFile($sDirectory.$file, $sRelativeFolder.$file);
                            }
                        }else{
                            if(is_dir($sDirectory.$file) ){
                                if($file != '' && $file != '.' && $file != '..'){
                                    $oZip->addEmptyDir($sRelativeFolder.$file);
                                    $folder = $sDirectory.$file.'/';
                                    self::_createZip($oZip,$folder);
                                }
                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }

        /**
         * This Method is used to upload a new theme and unzip it.
         * 
         * @param $aFiles       The $_FILES array of the submitted form
         * 
         * @return void
         */
        public static function _upload(array $aFiles) : void{
            $file_name       = $aFiles["file"]["name"];
            $file_name_arr   = explode('.', $file_name);
            $extension       = array_pop($file_name_arr);

            if ('zip' !== $extension) {
                add_action('admin_notices', function(){
                    print('<div id="message" class="error fade"><p>Theme File does not seem to be a ZIP file.</p></div>');
                });
                return;
            }

            $sUploadedFile = tempnam("tmp", "zipuploaded");

            if (!function_exists('WP_Filesystem')) {
                include_once ABSPATH . 'wp-admin/includes/file.php';
            }
        
            WP_Filesystem();

            if (move_uploaded_file($aFiles['file']['tmp_name'], $sUploadedFile)) {
                unzip_file($sUploadedFile, ABSPATH . 'wp-content/magazine_themes/');
            }else{
                add_action('admin_notices', function(){
                    print('<div id="message" class="error fade"><p>Theme Upload not possible</p></div>');
                });
                return;
            }

            unlink($sUploadedFile);

            add_action('admin_notices', function(){
                print('<div id="message" class="updated fade"><p>Theme Upload Successful</p></div>');
            });
        }
    }