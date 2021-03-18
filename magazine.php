<?php
/**
 * Plugin Name:       Magazine
 * Plugin URI:        https://gumroad.com/l/wp-magazine-printcss-cloud
 * Description:       Create PDFs from your Posts and Pages using the printcss.cloud for PDF generation.
 * Version:           0.0.3
 * Requires at least: 5.7
 * Requires PHP:      7.2
 * Author:            Andreas Zettl
 * Author URI:        https://azettl.net/
 */

/**
 * Plugin Options START
 */

    function magazine_option_page() {
        $magazine_rendering_tool = get_option('magazine_rendering_tool');
        $magazine_rapidapi_key   = get_option('magazine_rapidapi_key');
        $magazine_print_css      = get_option('magazine_print_css');
        $magazine_print_html     = get_option('magazine_print_html');
        $magazine_print_js       = get_option('magazine_print_js');

        echo '<div class="wrap">
                <div id="icon-options-general" class="icon32"><br /></div>
                <h2>Magazine <i>powered by <a href="https://printcss.cloud" target="_blank" rel="noopener">PrintCSS Cloud</a></i></h2>
                <form name="magazine_options_form" method="post">
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row">Rendering Tool</th>
                    <td>
                        <fieldset>
                        <legend class="hidden">Rendering Tool</legend>
                        <select name="magazine_rendering_tool" style="width:100%;display:block;">
                            <option value="weasyprint" '. (($magazine_rendering_tool == 'weasyprint') ? 'selected="selected"' : '') .'>WeasyPrint</option>
                            <option value="pagedjs" '. (($magazine_rendering_tool == 'pagedjs') ? 'selected="selected"' : '') .'>PagedJS</option>
                            <option value="vivliostyle" '. (($magazine_rendering_tool == 'vivliostyle') ? 'selected="selected"' : '') .'>Vivliostyle</option>
                        </select>
                        <label for="magazine_rendering_tool">
                            Check out the Tools Websites for more information about their capabilities: <a href="https://weasyprint.org/" target="_blank" rel="noopener">WeasyPrint</a>, <a class="hover:text-gray-900" href="https://www.pagedjs.org/" target="_blank" rel="noopener">PagedJS</a>, and <a class="hover:text-gray-900" href="https://vivliostyle.org/" target="_blank" rel="noopener">Vivliostyle</a>.
                        </label>
                        </fieldset>
                    </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row">RapidAPI Key</th>
                    <td>
                        <fieldset>
                        <legend class="hidden">RapidAPI Key</legend>
                        <input type="password" name="magazine_rapidapi_key" value="'. $magazine_rapidapi_key .'" style="width:100%;display:block;" />
                        <label for="magazine_rapidapi_key">
                            <b>To send the request to the PrintCSS Cloud API, you <a href="https://rapidapi.com/azettl/api/printcss-cloud/pricing">need to subscribe to a plan on RapidAPI</a>. With this, you get the API key that is required to authenticate with our REST service.</b>
                        </label>
                        </fieldset>
                    </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row">Print HTML Template</th>
                    <td>
                        <fieldset>
                        <legend class="hidden">Print HTML Template</legend>
                        <textarea style="display:none;" name="magazine_print_html" />'. htmlentities($magazine_print_html) .'</textarea>
                        <div id="magazine_print_html">'. htmlentities($magazine_print_html) .'</div>
                        <label for="magazine_print_html">
                            <b>The HTML gets rendered once per selected post/page, so if you do a bulk operation on five posts, the HTML code will be rendered foreach post.</b>
                            <br/>
                            The placeholder <i>{{title}}</i>, <i>{{feature_image}}</i> and <i>{{content}}</i> are for the post/page title, feature image and content. Please be aware that images need to be available via a public URL for the API to use them. Additionally you can use the placeholders <i>{{author}}</i>,
                            <i>{{date}}</i>,
                            <i>{{date_gmt}}</i>,
                            <i>{{excerpt}}</i>,
                            <i>{{status}}</i>. If you need to show the date of the post/page in a different format you can use the placeholders 
                            <i>{{year}}</i>,
                            <i>{{month}}</i>,
                            <i>{{day}}</i>,
                            <i>{{hour}}</i>,
                            <i>{{minute}}</i>.
                        </label>
                        </fieldset>
                    </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row">Print CSS</th>
                    <td>
                        <fieldset>
                        <legend class="hidden">Print CSS</legend>
                        <textarea style="display:none;" name="magazine_print_css" />'. $magazine_print_css .'</textarea>
                        <div id="magazine_print_css">'. $magazine_print_css .'</div>
                        <label for="magazine_print_css">
                            Add your Print CSS Code here.
                        </label>
                        </fieldset>
                    </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row">Additional JavaScript</th>
                    <td>
                        <fieldset>
                        <legend class="hidden">Additional JavaScript</legend>
                        <textarea style="display:none;" name="magazine_print_js" />'. $magazine_print_js .'</textarea>
                        <div id="magazine_print_js">'. $magazine_print_js .'</div>
                        <label for="magazine_print_js">
                            Add your additional JavaScript Code here, be aware that only PagedJS and Vivliostyle support JavaScript.
                        </label>
                        </fieldset>
                    </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
                </p>
                <input name="action" value="magazin_update_options" type="hidden" />
                </form>
            </div>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/jquery.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/ace.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/emmet.js"></script>
            <script src="' . plugin_dir_url( __DIR__ ). '/magazine/javascript/ace/ace-ext-emmet.js"></script>
            <script>
                $(document).ready(function() {
                    var htmlEditor = ace.edit("magazine_print_html");
                    htmlEditor.session.setMode("ace/mode/html");
                    htmlEditor.setOption("enableEmmet", true);
                    htmlEditor.session.setTabSize(2);
                    htmlEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_html"]\').val(htmlEditor.session.getValue());
                    });

                    var cssEditor = ace.edit("magazine_print_css");
                    cssEditor.session.setMode("ace/mode/css");
                    cssEditor.session.setTabSize(2);
                    cssEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_css"]\').val(cssEditor.session.getValue());
                    });

                    var jsEditor = ace.edit("magazine_print_js");
                    jsEditor.session.setMode("ace/mode/javascript");
                    jsEditor.session.setTabSize(2);
                    jsEditor.session.on("change", function(){
                        $(\'textarea[name="magazine_print_js"]\').val(jsEditor.session.getValue());
                    });
                });
            </script>
            <style>
                #magazine_print_html, #magazine_print_css, #magazine_print_js{
                    height: 400px;
                    width: 100%;
                    font-size: 14px;
                }
            </style>';
    }

    function magazine_add_menu() {
        add_option("magazine_rendering_tool",   "weasyprint");
        add_option("magazine_rapidapi_key",     "");
        add_option("magazine_print_html",       "<div class=\"new-page-per-post\"><!-- This div and class is used to always start a post/page on a new page in the PDF -->\n\t<div class=\"logo-area\"><!-- This div creates a sample logo on each page -->\n\t\t<div class=\"circle\"></div>\n\t\t<h1>Company Logo</h1>\n\t</div>\n\t<div class=\"copyright\"><!-- This div is used to show the copyright message in the footer of each page -->\n\t\t<p>&copy; Copyright {{year}}</p>\n\t\t<p>All contents of this news are protected by copyright. Company Ltd. owns the copyright.</p>\n\t</div>\n\t<div class=\"content\"><!-- The main content of the page/post -->\n\t\t<h3>News</h3>\n\t\t<h1>{{title}}</h1>\n\t\t<p>{{content}}</p>\n\t</div>\n</div>");
        add_option("magazine_print_css",        "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap');\n\nbody{\n\tfont-family: 'Poppins', sans-serif;\n}\n\n@page{\n\tsize:210mm 297mm; /* We use A4 as page format */\n\tmargin:4cm 3cm 3cm 1cm; /* The margins top: 4cm, right: 3cm, bottom: 3cm, left: 1cm */\n\t\n\t@top-right{\n\t\tcontent:element(coverLogo); /* Running elements are used to show the logo on each page */\n\t}\n\t\n\t@top-right-corner{ /* The dark strip on the right side of each page is generated with these @-rules */\n\t\tcontent:\"\";\n\t\tbackground:#000222;\n\t}\n\t\n\t@right-top{\n\t\tcontent:\"\";\n\t\tbackground:#000222;\n\t}\n\t\n\t@right-middle{\n\t\tcontent:\"\";\n\t\tbackground:#000222;\n\t}\n\t\n\t@right-bottom{\n\t\tcontent:\"\";\n\t\tbackground:#000222;\n\t}\n\t\n\t@bottom-right-corner{ /* The green strip on the bottom of each page is generated with these @-rules */\n\t\tcontent:\"\";\n\t\tbackground:#44b75b;\n\t}\n\t\n\t@bottom-right{\n\t\tcontent:\"\";\n\t\tbackground:#44b75b;\n\t}\n\t\n\t@bottom-center{\n\t\tcontent:\"\";\n\t\tbackground:#44b75b;\n\t}\n\t\n\t@bottom-left{ /* The copyright message is also a running element so it shows up on each page */\n\t\tcontent:element(copyright);\n\t\twidth:210mm;\n\t\tbackground:#44b75b;\n\t}\n\t\n\t@bottom-left-corner{\n\t\tcontent:\"\";\n\t\tbackground:#44b75b;\n\t}\n}\n\n.logo-area{ /* setting the logo area as running element so it moves from the normal page flow into the margin boxes */\n\tposition:running(coverLogo);\n}\n\n.copyright{ /* setting the copyright as running element so it moves from the normal page flow into the margin boxes */\n\tposition:running(copyright);\n\tfont-size:8pt;\n}\n\n.new-page-per-post{ /* using always as break-before parameter we ensure a new page for each new post, page-break-before is set for support of older renderers. */\n\tbreak-before:always;\n\tpage-break-before:always;\n}\n\n.circle{ /* the green circle of the sample logo */\n\twidth:1cm;\n\theight:1cm;\n\tborder:.4cm solid #44b75b;\n\tborder-radius:100%;\n\tdisplay:inline-block;\n}\n\n.logo-area h1{ /* the text of the sample logo */\n\tdisplay:inline-block;\n\ttext-transform:uppercase;\n\tcolor:#000222;\n\twidth:5cm;\n\tline-height:1;\n\tmargin-left:0.25cm;\n\tposition:relative;\n\ttop:-.25cm; \n}\n\n.content h1{ /* Only the h1 is styled in the content area */\n\tcolor:#000222;\n\tmargin-top:0;\n\ttext-transform: capitalize;\n}");
        add_option("magazine_print_js",         "");

        add_options_page('Magazine', 'Magazine', 9, __FILE__, 'magazine_option_page');
    }

    if ('magazin_update_options' === $_POST['action']){
        update_option("magazine_rendering_tool", $_POST['magazine_rendering_tool']);
        update_option("magazine_rapidapi_key",   $_POST['magazine_rapidapi_key']);
        update_option("magazine_print_html",     $_POST['magazine_print_html']);
        update_option("magazine_print_css",      $_POST['magazine_print_css']);
        update_option("magazine_print_js",       $_POST['magazine_print_js']);
    }

    add_action('admin_menu', 'magazine_add_menu');

/**
 * Plugin Options END
 */

###############################################################################################################

/**
 * Post Bulk Action Start
 */

    add_filter( 'bulk_actions-edit-post', 'magazine_render_pdf_bulk_actions');
    add_filter( 'bulk_actions-edit-page', 'magazine_render_pdf_bulk_actions');
    
    function magazine_render_pdf_bulk_actions($bulk_actions) {
        $bulk_actions['magazine_render_pdf_bulk_action'] = __( 'Render PDF', 'magazine_render_pdf');

        return $bulk_actions;
    }

    add_filter('handle_bulk_actions-edit-post', 'magazine_render_pdf_bulk_handler', 10, 3);
    add_filter('handle_bulk_actions-edit-page', 'magazine_render_pdf_bulk_handler', 10, 3);
    
    function magazine_render_pdf_bulk_handler($redirect_to, $doaction, $post_ids) {
        if ($doaction !== 'magazine_render_pdf_bulk_action') {
            
            return $redirect_to;
        }

        $magazine_rendering_tool    = get_option('magazine_rendering_tool');
        $magazine_rapidapi_key      = get_option('magazine_rapidapi_key');
        $magazine_print_css         = get_option('magazine_print_css');
        $magazine_print_html        = get_option('magazine_print_html');
        $magazine_print_js          = get_option('magazine_print_js');
        $magazine_print_html_final  = '';


        foreach ( $post_ids as $post_id ) {
            $magazine_print_html_tmp  = '';
            $magazine_print_html_tmp .= str_replace(
                [
                    '{{title}}',
                    '{{content}}',
                    '{{feature_image}}',
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
                $magazine_print_html
            );

            /* Add ACF Support Start */
                if(function_exists('get_field_objects')){
                    $fields = get_field_objects($post_id);

                    foreach($fields as $fieldname => $fieldArray){
                        $magazine_print_html_tmp = str_replace(
                            '{{' . $fieldname . '}}',
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
                            $magazine_print_html_tmp
                        );
                    }
                }
            /* ADD ACF Support END */

            $magazine_print_html_final .= $magazine_print_html_tmp;
        }

        $curl = curl_init();
        if(trim($magazine_print_js) === ''){
            $oSend = [
                "html" => $magazine_print_html_tmp,
                "css" => $magazine_print_css,
                "options" => [
                    "renderer" => $magazine_rendering_tool
                ]
            ];
        }else{
            $oSend = [
                "html" => $magazine_print_html_tmp,
                "css" => $magazine_print_css,
                "javascript" => $magazine_print_js,
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

        if($http_status == 200){
            $upload_dir = wp_upload_dir();
            $filename   = 'magazin.' . implode('.', $post_ids) . '-' . date('Y-m-d-H-i-s') . '.pdf';

            if ( wp_mkdir_p( $upload_dir['path'] . '/magazine' ) ) {
                $file = $upload_dir['path'] . '/magazine/'. $filename;
            }
            else {
                $file = $upload_dir['basedir'] . '/magazine/'. $filename;
            }

            file_put_contents($file, $pdfContent);

            $wp_filetype = wp_check_filetype($filename, null);

            $attachment = array(
                'post_mime_type'    => $wp_filetype['type'],
                'post_title'        => sanitize_file_name($filename),
                'post_content'      => '',
                'post_status'       => 'inherit'
            );

            $attach_id = wp_insert_attachment($attachment, $file);
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            $redirect_to = add_query_arg( 'magazine_pdf_content_attachment', $attach_id, $redirect_to);
        }else{
            $oError      = json_decode($pdfContent);
            $redirect_to = add_query_arg( 'magazine_pdf_content_error', $oError->message, $redirect_to);
        }

        return $redirect_to;
    }

    add_action('admin_notices', 'magazine_render_pdf_action_admin_notice');
    
    function magazine_render_pdf_action_admin_notice() {
        if (!empty($_REQUEST['magazine_pdf_content_attachment'])) {
            print( '<div id="message" class="updated fade">PDF generation done, 
            <a download href="' . wp_get_attachment_url($_REQUEST['magazine_pdf_content_attachment']) 
            . '">download the PDF here</a>
            </div>');
        }else if(!empty($_REQUEST['magazine_pdf_content_error'])){
            print( '<div id="message" class="error fade">Error "' 
                . $_REQUEST['magazine_pdf_content_error'] .'" generating PDF file.</div>');
        }
    }

/**
 * Post Bulk Action End
 */

###############################################################################################################
 
/**
 * Frontend Render PDF Start
 */

/**
 * Frontend Render PDF END
 */
