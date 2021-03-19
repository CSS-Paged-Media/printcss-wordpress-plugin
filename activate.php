<?php

    register_activation_hook( __FILE__, function(){
        $sMagazineThemePath = WP_CONTENT_DIR . '/magazine_themes/';
        if(!is_dir($sMagazineThemePath)){
            if(mkdir($sMagazineThemePath)){ # create main theme directory

                $sMagazineDemoThemePath = $sMagazineThemePath . 'demo/';
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
        }
    });