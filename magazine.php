<?php
/**
 * Plugin Name:       Magazine
 * Plugin URI:        https://gumroad.com/l/wp-magazine-printcss-cloud
 * Description:       Create PDFs from your Posts and Pages using the printcss.cloud for PDF generation.
 * Version:           0.0.5
 * Requires at least: 5.7
 * Requires PHP:      7.2
 * Author:            Andreas Zettl
 * Author URI:        https://azettl.net/
 */

require_once __DIR__ . '/classes/template.php';
require_once __DIR__ . '/classes/pdf.php';

require_once __DIR__ . '/activate.php';
require_once __DIR__ . '/bulk_action.php';
require_once __DIR__ . '/options.php';
require_once __DIR__ . '/widget.php';


###############################################################################################################

/**
 * Theme Page Start
 */

    add_action('admin_menu', function(){
        add_theme_page('Magazine', 'Magazine', 9, 'magazin_theme_page', function(){
            echo '<div class="wrap">
                <div id="icon-options-general" class="icon32"><br /></div>
                <h2>Magazine Theme Editor <i>powered by <a href="https://printcss.cloud" target="_blank" rel="noopener">PrintCSS Cloud</a></i></h2>
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
                            <br />
                            <br />
                            ACF is also supported just add {{YOUR_FIELD_NAME}} (<b>Important:</b> Use the name not the label!) and if your fields value appears in the PDF.
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
        });
    });

/**
 * Theme Page END
 */