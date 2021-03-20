<?php
    session_start();

    if ('magazine_select_theme' === $_POST['action']){
        $_SESSION['magazine_theme_to_edit'] = $_POST['magazine_theme_selection'];
    }

    if ('magazine_edit_theme' === $_POST['action']){
        $sSelectedTheme = $_SESSION['magazine_theme_to_edit'];

        if(isset($sSelectedTheme)){
            magazine_template::_setHTML($sSelectedTheme, 'prefix', $_POST['magazine_print_html_prefix']);
            magazine_template::_setHTML($sSelectedTheme, 'post', $_POST['magazine_print_html_post']);
            magazine_template::_setHTML($sSelectedTheme, 'page', $_POST['magazine_print_html_page']);
            magazine_template::_setHTML($sSelectedTheme, 'postfix', $_POST['magazine_print_html_postfix']);

            magazine_template::_setCSS($sSelectedTheme, 'style', $_POST['magazine_print_css_main']);
            magazine_template::_setCSS($sSelectedTheme, 'post', $_POST['magazine_print_css_post']);
            magazine_template::_setCSS($sSelectedTheme, 'page', $_POST['magazine_print_css_page']);

            magazine_template::_setJS($sSelectedTheme, 'script', $_POST['magazine_print_js_main']);
            magazine_template::_setJS($sSelectedTheme, 'post', $_POST['magazine_print_js_post']);
            magazine_template::_setJS($sSelectedTheme, 'page', $_POST['magazine_print_js_page']);
        }
    }

    if('magazine_duplicate_theme' === $_POST['action']){
        $sSelectedTheme = $_SESSION['magazine_theme_to_edit'];

        if(isset($sSelectedTheme)){
            magazine_template::_duplicateTemplate($sSelectedTheme, $_POST['magazine_theme_duplicate_name']);
        }
    }

    add_action('admin_menu', function(){
        add_theme_page('Magazine', 'Magazine', 9, 'magazine_theme_page', function(){
            $aThemes        = magazine_template::_getTemplateNames();
            
            if(is_array($aThemes) && count($aThemes) == 0){ // Create Demo Template if there is none
                magazine_template::_createDemoTemplate();
                $aThemes = magazine_template::_getTemplateNames();
            }

            $sThemeOptions  = '';

            if(!isset($_SESSION['magazine_theme_to_edit'])){
                $_SESSION['magazine_theme_to_edit'] = reset($aThemes);
            }

            $sSelectedTheme                = $_SESSION['magazine_theme_to_edit'];

            foreach($aThemes as $sThemeName){
                $sThemeOptions .= '<option value="' 
                    . $sThemeName . '"' 
                    . ($sThemeName == $sSelectedTheme ? 'selected' : '') 
                    . '>' . $sThemeName . '</option>';
            }

            $magazine_print_html_prefix    = magazine_template::_getHTML($sSelectedTheme, 'prefix');
            $magazine_print_html_post      = magazine_template::_getHTML($sSelectedTheme, 'post');
            $magazine_print_html_page      = magazine_template::_getHTML($sSelectedTheme, 'page');
            $magazine_print_html_postfix   = magazine_template::_getHTML($sSelectedTheme, 'postfix');

            $magazine_print_css_main       = magazine_template::_getCSS($sSelectedTheme, 'style');
            $magazine_print_css_post       = magazine_template::_getCSS($sSelectedTheme, 'post');
            $magazine_print_css_page       = magazine_template::_getCSS($sSelectedTheme, 'page');

            $magazine_print_js_main        = magazine_template::_getJS($sSelectedTheme, 'script');
            $magazine_print_js_post        = magazine_template::_getJS($sSelectedTheme, 'post');
            $magazine_print_js_page        = magazine_template::_getJS($sSelectedTheme, 'page');

            echo '<div class="wrap">
                <h1>Magazine Theme Editor <i>powered by <a href="https://printcss.cloud" target="_blank" rel="noopener">PrintCSS Cloud</a></i></h1>
                <form name="magazine_theme_selection_form" method="post">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Select Theme to Edit</th>
                            <td>
                                <fieldset>
                                    <legend class="hidden">Select Theme to Edit</legend>
                                    <select onchange="this.form.submit()" name="magazine_theme_selection" style="width:100%;display:block;">
                                        ' . $sThemeOptions . '    
                                    </select>
                                    <label for="magazine_theme_selection">
                                        Themes are placed in the "wp-content/magazine_themes/" folder.
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    <input name="action" value="magazine_select_theme" type="hidden" />
                </form>
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit ' . $sSelectedTheme . '
                </h1>
                <form name="magazine_theme_edit_form" method="post">
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">
                            HTML
                        </th>
                        <td>
                            <details>
                                <summary>Prefix <i>prefix.html</i><span>(Comes first, <b><u>NO</u> Placeholder Support</b>)</span></summary>
                                <textarea style="display:none;" name="magazine_print_html_prefix" />'. htmlentities($magazine_print_html_prefix) .'</textarea>
                                <div id="magazine_print_html_prefix">'. htmlentities($magazine_print_html_prefix) .'</div>
                            </details>
                            <details>
                                <summary>Post <i>post.html</i><span>(Once per selected Post, <b>Placeholder Support</b>)</span></summary>
                                <textarea style="display:none;" name="magazine_print_html_post" />'. htmlentities($magazine_print_html_post) .'</textarea>
                                <div id="magazine_print_html_post">'. htmlentities($magazine_print_html_post) .'</div>
                            </details>
                            <details>
                                <summary>Page <i>page.html</i><span>(Once per selected Page, <b>Placeholder Support</b>)</span></summary>
                                <textarea style="display:none;" name="magazine_print_html_page" />'. htmlentities($magazine_print_html_page) .'</textarea>
                                <div id="magazine_print_html_page">'. htmlentities($magazine_print_html_page) .'</div>
                            </details>
                            <details>
                                <summary>Postfix <i>postfix.html</i><span>(Comes last, <b><u>NO</u> Placeholder Support</b>)</span></summary>
                                <textarea style="display:none;" name="magazine_print_html_postfix" />'. htmlentities($magazine_print_html_postfix) .'</textarea>
                                <div id="magazine_print_html_postfix">'. htmlentities($magazine_print_html_postfix) .'</div>
                            </details>
                        </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">CSS</th>
                            <td>
                                <details>
                                    <summary>Main <i>style.css</i><span>(Comes first, <b><u>NO</u> Placeholder Support</b>)</span></summary>
                                    <textarea style="display:none;" name="magazine_print_css_main" />'. $magazine_print_css_main .'</textarea>
                                    <div id="magazine_print_css_main">'. $magazine_print_css_main .'</div>
                                </details>
                                <details>
                                    <summary>Post <i>post.css</i><span>(Once per selected Post, <b>Placeholder Support</b>)</span></summary>
                                    <textarea style="display:none;" name="magazine_print_css_post" />'. $magazine_print_css_post .'</textarea>
                                    <div id="magazine_print_css_post">'. $magazine_print_css_post .'</div>
                                </details>
                                <details>
                                    <summary>Page <i>page.css</i><span>(Once per selected Page, <b>Placeholder Support</b>)</span></summary>
                                    <textarea style="display:none;" name="magazine_print_css_page" />'. $magazine_print_css_page .'</textarea>
                                    <div id="magazine_print_css_page">'. $magazine_print_css_page .'</div>
                                </details>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">JavaScript</th>
                            <td>
                                <details>
                                    <summary>Main <i>script.js</i><span>(Comes first, <b><u>NO</u> Placeholder Support</b>)</span></summary>
                                    <textarea style="display:none;" name="magazine_print_js_main" />'. $magazine_print_js_main .'</textarea>
                                    <div id="magazine_print_js_main">'. $magazine_print_js_main .'</div>
                                </details>
                                <details>
                                    <summary>Post <i>post.js</i><span>(Once per selected Post, <b>Placeholder Support</b>)</span></summary>
                                    <textarea style="display:none;" name="magazine_print_js_post" />'. $magazine_print_js_post .'</textarea>
                                    <div id="magazine_print_js_post">'. $magazine_print_js_post .'</div>
                                </details>
                                <details>
                                    <summary>Page <i>page.js</i><span>(Once per selected Page, <b>Placeholder Support</b>)</span></summary>
                                    <textarea style="display:none;" name="magazine_print_js_page" />'. $magazine_print_js_page .'</textarea>
                                    <div id="magazine_print_js_page">'. $magazine_print_js_page .'</div>
                                </details>
                            </td>
                        </tr>
                    </table>
                    <p>
                        The placeholder <i>{{slug}}</i>, <i>{{title}}</i>, <i>{{feature_image}}</i> and <i>{{content}}</i> are for the post/page title, feature image and content. Please be aware that images need to be available via a public URL for the API to use them. Additionally you can use the placeholders <i>{{author}}</i>,
                        <i>{{date}}</i>,
                        <i>{{date_gmt}}</i>,
                        <i>{{excerpt}}</i>,
                        <i>{{status}}</i>. If you need to show the date of the post/page in a different format you can use the placeholders 
                        <i>{{year}}</i>,
                        <i>{{month}}</i>,
                        <i>{{day}}</i>,
                        <i>{{hour}}</i>,
                        <i>{{minute}}</i>.
                    </p>
                    <p>
                        ACF is also supported just add {{YOUR_FIELD_NAME}} (<b>Important:</b> Use the name not the label!) and if your fields value appears in the PDF.
                    </p>
                    <p>
                        Be aware that JavaScript is only supported by PagedJS and Vivliostyle.
                    </p>
                    <p class="submit">
                        <input type="submit" name="Submit" class="button-primary" value="Save Theme Changes" />
                    </p>
                    <input name="action" value="magazine_edit_theme" type="hidden" />
                </form>
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    Duplicate ' . $sSelectedTheme . '
                </h1>
                <form name="magazine_theme_duplicate_form" method="post">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Name of Duplicate</th>
                            <td>
                                <fieldset>
                                    <legend class="hidden">Name of Duplicate</legend>
                                    <input name="magazine_theme_duplicate_name" value="Copy of ' . $sSelectedTheme . '" style="width:100%;display:block;" />
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" name="Submit" class="button-primary" value="Duplicate Theme ' . $sSelectedTheme . '" />
                    </p>
                    <input name="action" value="magazine_duplicate_theme" type="hidden" />
                </form>
            </div>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/jquery.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/ace.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/emmet.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/ace-ext-emmet.js"></script>
            <script>
                $(document).ready(function() {
                    var htmlPrefixEditor = ace.edit("magazine_print_html_prefix");
                    htmlPrefixEditor.session.setMode("ace/mode/html");
                    htmlPrefixEditor.setOption("enableEmmet", true);
                    htmlPrefixEditor.session.setTabSize(2);
                    htmlPrefixEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_html_prefix"]\').val(htmlPrefixEditor.session.getValue());
                    });

                    var htmlPostEditor = ace.edit("magazine_print_html_post");
                    htmlPostEditor.session.setMode("ace/mode/html");
                    htmlPostEditor.setOption("enableEmmet", true);
                    htmlPostEditor.session.setTabSize(2);
                    htmlPostEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_html_post"]\').val(htmlPostEditor.session.getValue());
                    });

                    var htmlPageEditor = ace.edit("magazine_print_html_page");
                    htmlPageEditor.session.setMode("ace/mode/html");
                    htmlPageEditor.setOption("enableEmmet", true);
                    htmlPageEditor.session.setTabSize(2);
                    htmlPageEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_html_page"]\').val(htmlPageEditor.session.getValue());
                    });

                    var htmlPostFixEditor = ace.edit("magazine_print_html_postfix");
                    htmlPostFixEditor.session.setMode("ace/mode/html");
                    htmlPostFixEditor.setOption("enableEmmet", true);
                    htmlPostFixEditor.session.setTabSize(2);
                    htmlPostFixEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_html_postfix"]\').val(htmlPostFixEditor.session.getValue());
                    });

                    var cssMainEditor = ace.edit("magazine_print_css_main");
                    cssMainEditor.session.setMode("ace/mode/css");
                    cssMainEditor.session.setTabSize(2);
                    cssMainEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_css_main"]\').val(cssMainEditor.session.getValue());
                    });

                    var cssPostEditor = ace.edit("magazine_print_css_post");
                    cssPostEditor.session.setMode("ace/mode/css");
                    cssPostEditor.session.setTabSize(2);
                    cssPostEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_css_post"]\').val(cssPostEditor.session.getValue());
                    });

                    var cssPageEditor = ace.edit("magazine_print_css_page");
                    cssPageEditor.session.setMode("ace/mode/css");
                    cssPageEditor.session.setTabSize(2);
                    cssPageEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_css_page"]\').val(cssPageEditor.session.getValue());
                    });

                    var jsMainEditor = ace.edit("magazine_print_js_main");
                    jsMainEditor.session.setMode("ace/mode/javascript");
                    jsMainEditor.session.setTabSize(2);
                    jsMainEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_js_main"]\').val(jsMainEditor.session.getValue());
                    });

                    var jsPostEditor = ace.edit("magazine_print_js_post");
                    jsPostEditor.session.setMode("ace/mode/javascript");
                    jsPostEditor.session.setTabSize(2);
                    jsPostEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_js_post"]\').val(jsPostEditor.session.getValue());
                    });

                    var jsPageEditor = ace.edit("magazine_print_js_page");
                    jsPageEditor.session.setMode("ace/mode/javascript");
                    jsPageEditor.session.setTabSize(2);
                    jsPageEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_js_page"]\').val(jsPageEditor.session.getValue());
                    });
                });
            </script>
            <style>
                #magazine_print_html_prefix,
                #magazine_print_html_post,
                #magazine_print_html_page,
                #magazine_print_html_postfix, 
                #magazine_print_css_main, 
                #magazine_print_css_post, 
                #magazine_print_css_page, 
                #magazine_print_js_main, 
                #magazine_print_js_post, 
                #magazine_print_js_page{
                    height: 400px;
                    width: 100%;
                    font-size: 14px;
                    border-bottom: 2px solid lightgray;
                    border-left: 2px solid lightgray;
                    border-right: 2px solid lightgray;
                    box-sizing: border-box;
                }

                h1 svg{
                    height: 18px;
                }

                details{
                    margin-bottom:12px;
                }

                summary{
                    cursor:pointer;
                    height: 32px;
                    line-height: 32px;
                    clear: both;
                    border-bottom: 2px solid lightgray;
                }

                summary:focus{
                    outline-color:lightseagreen;
                }

                summary::marker{
                    color:lightseagreen;
                }

                summary i{
                    color:lightgray;
                    font-weight:bold;
                }

                summary span{
                    float:right;
                }
            </style>';
        });
    });