<?php
    session_start();

    if ('magazine_select_theme' === $_POST['action']){
        $_SESSION['magazine_theme_to_edit'] = $_POST['magazine_theme_selection'];
    }

    if('magazine_download_theme' === $_POST['action']){
        $sSelectedTheme = $_SESSION['magazine_theme_to_edit'];

        if(isset($sSelectedTheme)){
            magazine_template::_download($sSelectedTheme);
        }
    }

    if('magazine_upload_theme' === $_POST['action']){
        magazine_template::_upload($_FILES);
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

            if(
                !isset($_SESSION['magazine_theme_to_edit'])
                ||
                !in_array($_SESSION['magazine_theme_to_edit'], $aThemes)
            ){
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

            echo '<div class="wrap wrap-magazine">
                <h1>
                    Magazine Theme Editor
                </h1>
                <form name="magazine_theme_selection_form" method="post">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><label for="magazine_theme_selection">Select Theme to Edit</label></th>
                            <td>
                                <fieldset>
                                    <legend class="hidden">Select Theme to Edit</legend>
                                    <select onchange="this.form.submit()" name="magazine_theme_selection" style="width:100%;display:block;">
                                        ' . $sThemeOptions . '    
                                    </select>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    <input name="action" value="magazine_select_theme" type="hidden" />
                </form>
                <form name="magazine_theme_download_form" method="post">
                    <input name="action" value="magazine_download_theme" type="hidden" />
                    <p class="submit">
                        <input type="submit" name="Submit" class="button-primary button-magazine" value="Download Theme ' . $sSelectedTheme . '" />
                    </p>
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
                                <summary>Prefix <i>prefix.html</i></summary>
                                <div class="placeholder-button-wrapper">
                                    <textarea style="display:none;" name="magazine_print_html_prefix" />'. htmlentities($magazine_print_html_prefix) .'</textarea>
                                    <div id="magazine_print_html_prefix">'. htmlentities($magazine_print_html_prefix) .'</div>
                                    <div class="placeholder-buttons">
                                        <h4>Placeholders:</h4>
                                        <ul>
                                            <li onclick="htmlPrefixEditor.insert(\'{{toc_list}}\');">Table of Contents</li>
                                        </ul>
                                        <h4>ACF Conditional Placeholders:</h4>
                                        <ul>
                                            <li onclick="htmlPrefixEditor.insert(\'{{post.ACF_fieldName=fieldValue.slug}}\');">Slug</li>
                                            <li onclick="htmlPrefixEditor.insert(\'{{post.ACF_fieldName=fieldValue.title}}\');">Title</li>
                                            <li onclick="htmlPrefixEditor.insert(\'{{post.ACF_fieldName=fieldValue.excerpt}}\');">Excerpt</li>
                                            <li onclick="htmlPrefixEditor.insert(\'{{post.ACF_fieldName=fieldValue.feature_image}}\');">Feature Image</li>
                                            <li onclick="htmlPrefixEditor.insert(\'{{post.ACF_fieldName=fieldValue.content}}\');">Content</li>
                                            <li onclick="htmlPrefixEditor.insert(\'{{post.ACF_fieldName=fieldValue.author}}\');">Author</li>
                                            <li onclick="htmlPrefixEditor.insert(\'{{post.ACF_fieldName=fieldValue.date}}\');">Date</li>
                                            <li onclick="htmlPrefixEditor.insert(\'{{post.ACF_fieldName=fieldValue.date_gmt}}\');">Date GMT</li>
                                        </ul>
                                    </div>
                                </div>
                            </details>
                            <details>
                                <summary>Post (&amp; Custom Post Types) <i>post.html</i></summary>
                                <div class="placeholder-button-wrapper">
                                    <textarea style="display:none;" name="magazine_print_html_post" />'. htmlentities($magazine_print_html_post) .'</textarea>
                                    <div id="magazine_print_html_post">'. htmlentities($magazine_print_html_post) .'</div>
                                    <div class="placeholder-buttons">
                                        <h4>Placeholders:</h4>
                                        <ul>
                                            <li onclick="htmlPostEditor.insert(\'{{slug}}\');">Slug</li>
                                            <li onclick="htmlPostEditor.insert(\'{{title}}\');">Title</li>
                                            <li onclick="htmlPostEditor.insert(\'{{excerpt}}\');">Excerpt</li>
                                            <li onclick="htmlPostEditor.insert(\'{{feature_image}}\');">Feature Image</li>
                                            <li onclick="htmlPostEditor.insert(\'{{content}}\');">Content</li>
                                            <li onclick="htmlPostEditor.insert(\'{{author}}\');">Author</li>
                                            <li onclick="htmlPostEditor.insert(\'{{status}}\');">Status</li>
                                            <li onclick="htmlPostEditor.insert(\'{{categories}}\');">Categories</li>
                                            <li onclick="htmlPostEditor.insert(\'{{category_slugs}}\');">Category Slugs</li>
                                            <li onclick="htmlPostEditor.insert(\'{{date}}\');">Date</li>
                                            <li onclick="htmlPostEditor.insert(\'{{date_gmt}}\');">Date GMT</li>
                                            <li onclick="htmlPostEditor.insert(\'{{year}}\');">Year</li>
                                            <li onclick="htmlPostEditor.insert(\'{{month}}\');">Month</li>
                                            <li onclick="htmlPostEditor.insert(\'{{day}}\');">Day</li>
                                            <li onclick="htmlPostEditor.insert(\'{{hour}}\');">Hour</li>
                                            <li onclick="htmlPostEditor.insert(\'{{minute}}\');">Minute</li>
                                            <li onclick="htmlPostEditor.insert(\'{{ACF_fieldName}}\');">ACF Field</li>
                                        </ul>
                                    </div>
                                </div>
                            </details>
                            <details>
                                <summary>Page <i>page.html</i></summary>
                                <div class="placeholder-button-wrapper">
                                    <textarea style="display:none;" name="magazine_print_html_page" />'. htmlentities($magazine_print_html_page) .'</textarea>
                                    <div id="magazine_print_html_page">'. htmlentities($magazine_print_html_page) .'</div>
                                    <div class="placeholder-buttons">
                                        <h4>Placeholders:</h4>
                                        <ul>
                                            <li onclick="htmlPageEditor.insert(\'{{slug}}\');">Slug</li>
                                            <li onclick="htmlPageEditor.insert(\'{{title}}\');">Title</li>
                                            <li onclick="htmlPageEditor.insert(\'{{excerpt}}\');">Excerpt</li>
                                            <li onclick="htmlPageEditor.insert(\'{{feature_image}}\');">Feature Image</li>
                                            <li onclick="htmlPageEditor.insert(\'{{content}}\');">Content</li>
                                            <li onclick="htmlPageEditor.insert(\'{{author}}\');">Author</li>
                                            <li onclick="htmlPageEditor.insert(\'{{status}}\');">Status</li>
                                            <li onclick="htmlPageEditor.insert(\'{{categories}}\');">Categories</li>
                                            <li onclick="htmlPageEditor.insert(\'{{category_slugs}}\');">Category Slugs</li>
                                            <li onclick="htmlPageEditor.insert(\'{{date}}\');">Date</li>
                                            <li onclick="htmlPageEditor.insert(\'{{date_gmt}}\');">Date GMT</li>
                                            <li onclick="htmlPageEditor.insert(\'{{year}}\');">Year</li>
                                            <li onclick="htmlPageEditor.insert(\'{{month}}\');">Month</li>
                                            <li onclick="htmlPageEditor.insert(\'{{day}}\');">Day</li>
                                            <li onclick="htmlPageEditor.insert(\'{{hour}}\');">Hour</li>
                                            <li onclick="htmlPageEditor.insert(\'{{minute}}\');">Minute</li>
                                            <li onclick="htmlPageEditor.insert(\'{{ACF_put_the_field_name_here}}\');">ACF Field</li>
                                        </ul>
                                    </div>
                                </div>
                            </details>
                            <details>
                                <summary>Postfix <i>postfix.html</i></summary>
                                <div class="placeholder-button-wrapper">
                                    <textarea style="display:none;" name="magazine_print_html_postfix" />'. htmlentities($magazine_print_html_postfix) .'</textarea>
                                    <div id="magazine_print_html_postfix">'. htmlentities($magazine_print_html_postfix) .'</div>
                                    <div class="placeholder-buttons">
                                        <h4>Placeholders:</h4>
                                        <ul>
                                            <li onclick="htmlPostFixEditor.insert(\'{{toc_list}}\');">Table of Contents</li>
                                        </ul>
                                        <h4>ACF Conditional Placeholders:</h4>
                                        <ul>
                                            <li onclick="htmlPostFixEditor.insert(\'{{post.ACF_fieldName=fieldValue.slug}}\');">Slug</li>
                                            <li onclick="htmlPostFixEditor.insert(\'{{post.ACF_fieldName=fieldValue.title}}\');">Title</li>
                                            <li onclick="htmlPostFixEditor.insert(\'{{post.ACF_fieldName=fieldValue.excerpt}}\');">Excerpt</li>
                                            <li onclick="htmlPostFixEditor.insert(\'{{post.ACF_fieldName=fieldValue.feature_image}}\');">Feature Image</li>
                                            <li onclick="htmlPostFixEditor.insert(\'{{post.ACF_fieldName=fieldValue.content}}\');">Content</li>
                                            <li onclick="htmlPostFixEditor.insert(\'{{post.ACF_fieldName=fieldValue.author}}\');">Author</li>
                                            <li onclick="htmlPostFixEditor.insert(\'{{post.ACF_fieldName=fieldValue.date}}\');">Date</li>
                                            <li onclick="htmlPostFixEditor.insert(\'{{post.ACF_fieldName=fieldValue.date_gmt}}\');">Date GMT</li>
                                        </ul>
                                    </div>
                                </div>
                            </details>
                        </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">CSS</th>
                            <td>
                                <details>
                                    <summary>Main <i>style.css</i><span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg> Placeholders</span></summary>
                                    <textarea style="display:none;" name="magazine_print_css_main" />'. $magazine_print_css_main .'</textarea>
                                    <div id="magazine_print_css_main">'. $magazine_print_css_main .'</div>
                                </details>
                                <details>
                                    <summary>Post (&amp; Custom Post Types) <i>post.css</i></summary>
                                    <div class="placeholder-button-wrapper">
                                        <textarea style="display:none;" name="magazine_print_css_post" />'. $magazine_print_css_post .'</textarea>
                                        <div id="magazine_print_css_post">'. $magazine_print_css_post .'</div>
                                        <div class="placeholder-buttons">
                                            <h4>Placeholders:</h4>
                                            <ul>
                                                <li onclick="cssPostEditor.insert(\'{{slug}}\');">Slug</li>
                                                <li onclick="cssPostEditor.insert(\'{{category_slugs}}\');">Category Slugs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </details>
                                <details>
                                    <summary>Page <i>page.css</i></summary>
                                    <div class="placeholder-button-wrapper">
                                        <textarea style="display:none;" name="magazine_print_css_page" />'. $magazine_print_css_page .'</textarea>
                                        <div id="magazine_print_css_page">'. $magazine_print_css_page .'</div>
                                        <div class="placeholder-buttons">
                                            <h4>Placeholders:</h4>
                                            <ul>
                                                <li onclick="cssPageEditor.insert(\'{{slug}}\');">Slug</li>
                                                <li onclick="cssPageEditor.insert(\'{{category_slugs}}\');">Category Slugs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </details>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">JavaScript</th>
                            <td>
                                <details>
                                    <summary>Main <i>script.js</i><span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg> Placeholders</span></summary>
                                    <textarea style="display:none;" name="magazine_print_js_main" />'. $magazine_print_js_main .'</textarea>
                                    <div id="magazine_print_js_main">'. $magazine_print_js_main .'</div>
                                </details>
                                <details>
                                    <summary>Post (&amp; Custom Post Types) <i>post.js</i></summary>
                                    <div class="placeholder-button-wrapper">
                                        <textarea style="display:none;" name="magazine_print_js_post" />'. $magazine_print_js_post .'</textarea>
                                        <div id="magazine_print_js_post">'. $magazine_print_js_post .'</div>
                                        <div class="placeholder-buttons">
                                            <h4>Placeholders:</h4>
                                            <ul>
                                                <li onclick="jsPostEditor.insert(\'{{slug}}\');">Slug</li>
                                                <li onclick="jsPostEditor.insert(\'{{category_slugs}}\');">Category Slugs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </details>
                                <details>
                                    <summary>Page <i>page.js</i></summary>
                                    <div class="placeholder-button-wrapper">
                                        <textarea style="display:none;" name="magazine_print_js_page" />'. $magazine_print_js_page .'</textarea>
                                        <div id="magazine_print_js_page">'. $magazine_print_js_page .'</div>
                                        <div class="placeholder-buttons">
                                            <h4>Placeholders:</h4>
                                            <ul>
                                                <li onclick="jsPageEditor.insert(\'{{slug}}\');">Slug</li>
                                                <li onclick="jsPageEditor.insert(\'{{category_slugs}}\');">Category Slugs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </details>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" name="Submit" class="button-primary button-magazine" value="Save Theme Changes" />
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
                        <input type="submit" name="Submit" class="button-primary button-magazine" value="Duplicate Theme ' . $sSelectedTheme . '" />
                    </p>
                    <input name="action" value="magazine_duplicate_theme" type="hidden" />
                </form>
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload Theme
                </h1>
                <form name="magazine_theme_upload_form" method="post" enctype="multipart/form-data">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Theme ZIP File</th>
                            <td>
                                <fieldset>
                                    <legend class="hidden">Theme ZIP File</legend>
                                    <input type="file" accept="application/zip" name="file" style="width:100%;display:block;" />
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    <input name="action" value="magazine_upload_theme" type="hidden" />
                    <p class="submit">
                        <input type="submit" name="Submit" class="button-primary button-magazine" value="Upload New Theme" />
                    </p>
                </form>
            </div>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/jquery.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/ace.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/emmet.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/ace-ext-emmet.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/theme.js"></script>
            <style>
                @import "' . plugin_dir_url( __DIR__ ) . '/magazine/css/theme.css";
            </style>';
        });
    });

    add_action('in_admin_header', function(){
        $screen = get_current_screen();
        if ($screen->base == 'appearance_page_magazine_theme_page') {
            $help_tabs = $screen->get_help_tabs();
            $screen->remove_help_tabs();
    
            $screen->add_help_tab(array(
                'id' => 'magazine_overview_help',
                'title' => 'Overview',
                'content' => '<p>On this screen, you can manage your Magazine themes. The themes are placed in the <i>"wp-content/magazine_themes/"</i> folder on your filesystem.</p>
                              <p>From this screen, you can:</p>
                              <ul>
                                <li>View Themes</li>
                                <li>Edit Themes</li>
                                <li>Duplicate Themes</li>
                                <li>Upload Themes</li>
                              </ul>
                              <p>Before you start editing a theme, be sure you selected the correct one.</p>',
            ));

            $screen->add_help_tab(array(
                'id' => 'magazine_html_help',
                'title' => 'HTML',
                'content' => '<p>In the HTML section of this screen, you can edit all HTML files of your Magazine theme.</p>
                              <ul>
                                <li>
                                    <b>Prefix (prefix.html)</b><br/>
                                    The prefix HTML you can use for front covers or intros, basically anything which should be added only once at the beginning of the PDF. In the prefix, you can only use certain placeholders.
                                </li>
                                <li>
                                    <b>Post (post.html)</b><br/>
                                    The post HTML will be loaded whenever you want to render a Blogpost PDF; this file gets added once per selected Blogpost. So if you choose 5 Blogposts, this HTML gets repeated five times. Within the post HTML, you can use any placeholders, for example, the <i>{{title}}</i> to always show the current Blogpost title. This file also gets loaded if you render a PDF for any custom post type.
                                </li>
                                <li>
                                    <b>Page (page.html)</b><br/>
                                    The page HTML will be loaded whenever you want to render a PDF from a page. This file gets added once per selected page. So if you choose five pages, this HTML gets repeated five times. Within the page HTML, you can use any placeholders, for example, the <i>{{title}}</i> to always show the current page title.
                                </li>
                                <li>
                                    <b>Postfix (postfix.html)</b><br/>
                                    The postfix HTML you can use for back covers or indexes, basically anything which should be added only once at the end of the PDF. In the postfix, you can only use certain placeholders.
                                </li>
                              </ul>
                              <p>You can leave any of these HTML files empty if you do not need them. For example, if you do not need a cover, do not put content into the prefix HTML.</p>',
            ));
    
            $screen->add_help_tab(array(
                'id' => 'magazine_css_help',
                'title' => 'CSS',
                'content' => '<p>In the CSS section of this screen, you can edit all CSS files of your Magazine theme.</p>
                            <ul>
                            <li>
                                <b>Main (style.css)</b><br/>
                                The main CSS is loaded for any rendering you do. In the main CSS, you can not use any placeholders.
                            </li>
                            <li>
                                <b>Post (post.css)</b><br/>
                                The post CSS will be loaded whenever you want to render a Blogpost PDF; this file gets added once per selected Blogpost. So if you choose 5 Blogposts, this CSS gets repeated five times. Within the post CSS, you can use any placeholders, for example, the <i>{{slug}}</i> to get one class per Blogpost slug. This file also gets loaded if you render a PDF for any custom post type.
                            </li>
                            <li>
                                <b>Page (page.css)</b><br/>
                                The page CSS will be loaded whenever you want to render a page PDF; this file gets added once per selected page. So if you choose 5 pages, this CSS gets repeated five times. Within the post CSS, you can use any placeholders, for example, the <i>{{slug}}</i> to get one class per page slug.
                            </li>
                            </ul>
                            <p>You can leave any of these CSS files empty if you do not need them. For example, if you do not need a Blogpost specific CSS, do not put content into the post CSS file.</p>',
            ));
    
            $screen->add_help_tab(array(
                'id' => 'magazine_js_help',
                'title' => 'JavaScript',
                'content' => '<p>In the JavaScript section of this screen, you can edit all JavaScript files of your Magazine theme.</p>
                            <p><b>Be aware that JavaScript is not supported by all PDF rendering engines, check first before you add JavaScript to your theme.</b></p>
                            <ul>
                            <li>
                                <b>Main (script.js)</b><br/>
                                The main JavaScript is loaded for any rendering you do. In the main JavaScript, you can not use any placeholders.
                            </li>
                            <li>
                                <b>Post (post.js)</b><br/>
                                The post JavaScript will be loaded whenever you want to render a Blogpost PDF; this file gets added once per selected Blogpost. So if you choose 5 Blogposts, this JavaScript gets repeated five times. Within the post JavaScript, you can use any placeholders, for example, the <i>{{slug}}</i> to get one class per Blogpost slug. This file also gets loaded if you render a PDF for any custom post type.
                            </li>
                            <li>
                                <b>Page (page.js)</b><br/>
                                The page JavaScript will be loaded whenever you want to render a page PDF; this file gets added once per selected page. So if you choose 5 pages, this JavaScript gets repeated five times. Within the post JavaScript, you can use any placeholders, for example, the <i>{{slug}}</i> to get one class per page slug.
                            </li>
                            </ul>
                            <p>You can leave any of these JavaScript files empty if you do not need them. For example, if you do not need a Blogpost specific JavaScript, do not put content into the post JavaScript file.</p>',
            ));
    
            $screen->add_help_tab(array(
                'id' => 'magazine_placeholder_help',
                'title' => 'Placeholder',
                'content' => '  <p><b>Placeholders for Post and Page Files:</b></p>
                                <p>The placeholders <i>{{slug}}</i>, <i>{{title}}</i>, <i>{{feature_image}}</i> and <i>{{content}}</i> are for the post/page slug, title, feature image and content. Additionally you can use the placeholders <i>{{author}}</i>, <i>{{date}}</i>, <i>{{date_gmt}}</i>, <i>{{excerpt}}</i>, <i>{{status}}</i>, <i>{{category_slugs}}</i>, and <i>{{categories}}</i>. If you need to show the date of the post/page in a different format you can use the placeholders <i>{{year}}</i>, <i>{{month}}</i>, <i>{{day}}</i>, <i>{{hour}}</i>, <i>{{minute}}</i>.</p>

                                <p>Please be aware that images need to be available via a public URL for the API to use them.</p>
                                
                                <p>ACF is also supported. Just add <i>{{ACF_YOUR_FIELD_NAME}}</i>. Important: use the name, not the label!</p>
                                
                                <p><b>Placeholders for Prefix and Postfix Files:</b></p>
                                <p>To generate a table of contents you can use the placeholder <i>{{toc_list}}</i> and to access fields from pages/posts which where selected to be rendered you can use placeholders like <i>{{post.ACF_acf_fieldname=value.feature_image}}</i>
                                this way you get the content of the feature image field for the first post/page which has "value" in the ACF field "acf_fieldname".</p>
                                ',
            ));
    
            $screen->add_help_tab(array(
                'id' => 'magazine_links_help',
                'title' => 'Helpful PrintCSS Resources',
                'content' => '<ul>
                                <li>
                                    <b>Introduction to PrintCSS and CSS Paged Media</b><br />
                                    <a href="https://print-css.rocks/" target="_blank" rel="noopener">https://print-css.rocks/</a>
                                </li>
                                <li>
                                    <b>Introduction to CSS for Paged Media</b><br />
                                    <a href="https://www.antennahouse.com/css" target="_blank" rel="noopener">https://www.antennahouse.com/css</a>
                                </li>
                                <li>
                                    <b>Designing For Print With CSS</b><br />
                                    <a href="https://www.smashingmagazine.com/2015/01/designing-for-print-with-css/" target="_blank" rel="noopener">https://www.smashingmagazine.com/2015/01/designing-for-print-with-css/</a>
                                </li>
                                <li>
                                    <b>Building Books with CSS3</b><br />
                                    <a href="https://alistapart.com/article/building-books-with-css3/" target="_blank" rel="noopener">https://alistapart.com/article/building-books-with-css3/</a>
                                </li>
                                <li>
                                    <b>CSS Paged Media Module Level 3</b><br />
                                    <a href="https://www.w3.org/TR/css-page-3/" target="_blank" rel="noopener">https://www.w3.org/TR/css-page-3/</a>
                                </li>
                                <li>        
                                    <b>CSS Generated Content for Paged Media Module</b><br />
                                    <a href="https://www.w3.org/TR/css-gcpm-3/" target="_blank" rel="noopener">https://www.w3.org/TR/css-gcpm-3/</a>
                                </li>
                                <li>
                                    <b>A comparison of different html2pdf tools</b><br />
                                    <a href="https://www.html2pdf.guru/" target="_blank" rel="noopener">https://www.html2pdf.guru/</a>
                                </li>
                                <li>
                                    <b>PrintCSS Articles</b><br />
                                    <a href="http://printcss.blog/" target="_blank" rel="noopener">http://printcss.blog/</a>
                                </li>
                                <li>
                                    <b>PrintCSS Videos</b><br />
                                    <a href="https://printcss.tube/" target="_blank" rel="noopener">https://printcss.tube/</a>
                                </li>
                                <li>
                                    <b>PrintCSS Directory</b><br />
                                    <a href="https://printcss.directory/" target="_blank" rel="noopener">https://printcss.directory/</a>
                                </li>
                                <li>
                                    <b>PrintCSS Cards</b><br />
                                    <a href="https://printcss.cards/" target="_blank" rel="noopener">https://printcss.cards/</a>
                                </li>
                            </ul>',
            ));
    
            if (count($help_tabs)){
                foreach ($help_tabs as $help_tab){
                    $screen->add_help_tab($help_tab);
                }
            }
        }
    });