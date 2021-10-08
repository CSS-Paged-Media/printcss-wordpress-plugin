<?php

    if ('magazin_update_options' === $_POST['action']){
        update_option("magazine_rendering_tool",        $_POST['magazine_rendering_tool']);
        update_option("magazine_rapidapi_key",          $_POST['magazine_rapidapi_key']);
        update_option("magazine_docraptor_key",         $_POST['magazine_docraptor_key']);
        update_option("magazine_typeset_token_key",     $_POST['magazine_typeset_token_key']);
        update_option("magazine_typeset_project_key",   $_POST['magazine_typeset_project_key']);
        update_option("magazine_local_command",         $_POST['magazine_local_command']);
    }

    add_action('admin_menu', function(){
        add_option("magazine_rendering_tool",       "weasyprint");
        add_option("magazine_rapidapi_key",         "");
        add_option("magazine_docraptor_key",        "");
        add_option("magazine_typeset_token_key",    "");
        add_option("magazine_typeset_project_key",  "");
        add_option("magazine_local_command",        "");

        add_options_page('Magazine', 'Magazine', 9, 'magazine_option_page', function(){
            $magazine_rendering_tool        = get_option('magazine_rendering_tool');
            $magazine_rapidapi_key          = get_option('magazine_rapidapi_key');
            $magazine_docraptor_key         = get_option('magazine_docraptor_key');
            $magazine_typeset_token_key     = get_option('magazine_typeset_token_key');
            $magazine_typeset_project_key   = get_option('magazine_typeset_project_key');
            $magazine_local_command         = get_option('magazine_local_command');

            $sSelectOptions          = '';
            if(!empty($magazine_rapidapi_key)){
                $sSelectOptions .= '<optgroup label="PrintCSS Cloud">
                                        <option value="weasyprint" '. (($magazine_rendering_tool == 'weasyprint') ? 'selected="selected"' : '') .'>WeasyPrint</option>
                                        <option value="pagedjs" '. (($magazine_rendering_tool == 'pagedjs') ? 'selected="selected"' : '') .'>PagedJS</option>
                                        <option value="vivliostyle" '. (($magazine_rendering_tool == 'vivliostyle') ? 'selected="selected"' : '') .'>Vivliostyle</option>
                                    </optgroup>';
            }
            if(!empty($magazine_docraptor_key)){
                $sSelectOptions .= '<optgroup label="DocRaptor">
                                        <option value="prince14" '. (($magazine_rendering_tool == 'prince14') ? 'selected="selected"' : '') .'>Prince 14</option>
                                        <option value="prince13" '. (($magazine_rendering_tool == 'prince13') ? 'selected="selected"' : '') .'>Prince 13</option>
                                        <option value="prince12" '. (($magazine_rendering_tool == 'prince12') ? 'selected="selected"' : '') .'>Prince 12</option>
                                        <option value="prince11" '. (($magazine_rendering_tool == 'prince11') ? 'selected="selected"' : '') .'>Prince 11</option>
                                    </optgroup>';
            }
            if(!empty($magazine_typeset_token_key) && !empty($magazine_typeset_project_key)){
                $sSelectOptions .= '<optgroup label="typeset.sh">
                                        <option value="typeset0.16.3" '. (($magazine_rendering_tool == 'typeset0.16.3') ? 'selected="selected"' : '') .'>Version 0.16.3</option>
                                        <option value="typeset0.16.0" '. (($magazine_rendering_tool == 'typeset0.16.0') ? 'selected="selected"' : '') .'>Version 0.16.0</option>
                                        <option value="typeset0.15.0" '. (($magazine_rendering_tool == 'typeset0.15.0') ? 'selected="selected"' : '') .'>Version 0.15.0</option>
                                    </optgroup>';
            }
            if(!empty($magazine_local_command)){
                $sSelectOptions .= '<optgroup label="Local">
                                        <option value="local" '. (($magazine_rendering_tool == 'local') ? 'selected="selected"' : '') .'>Local Renderer</option>
                                    </optgroup>';
            }

            echo '<div class="wrap wrap-magazine">
                    <h1>
                        Magazine Options
                    </h1>
                    <form name="magazine_options_form" method="post">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><label for="magazine_rendering_tool">Rendering Tool</label></th>
                                <td>
                                    <fieldset>
                                        <legend class="hidden">Rendering Tool</legend>
                                        <select name="magazine_rendering_tool" style="width:100%;display:block;">
                                            ' . $sSelectOptions . '
                                        </select>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="magazine_rapidapi_key"><a href="https://printcss.cloud" target="_blank" rel="noopener">PrintCSS Cloud</a> RapidAPI Key</label></th>
                                <td>
                                    <fieldset>
                                        <legend class="hidden"><a href="https://printcss.cloud" target="_blank" rel="noopener">PrintCSS Cloud</a> RapidAPI Key</legend>
                                        <input type="password" name="magazine_rapidapi_key" value="'. $magazine_rapidapi_key .'" style="width:100%;display:block;" />
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="magazine_docraptor_key"><a href="https://docraptor.com" target="_blank" rel="noopener">DocRaptor</a> API Key</label></th>
                                <td>
                                    <fieldset>
                                        <legend class="hidden"><a href="https://docraptor.com" target="_blank" rel="noopener">DocRaptor</a> API Key</legend>
                                        <input type="password" name="magazine_docraptor_key" value="'. $magazine_docraptor_key .'" style="width:100%;display:block;" />
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="magazine_typeset_project_key"><a href="https://typeset.sh/" target="_blank" rel="noopener">typeset.sh</a> Project Key</label></th>
                                <td>
                                    <fieldset>
                                        <legend class="hidden"><a href="https://typeset.sh/" target="_blank" rel="noopener">typeset.sh</a> Project Key</legend>
                                        <input type="password" name="magazine_typeset_project_key" value="'. $magazine_typeset_project_key .'" style="width:100%;display:block;" />
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="magazine_typeset_token_key"><a href="https://typeset.sh/" target="_blank" rel="noopener">typeset.sh</a> Project Token</label></th>
                                <td>
                                    <fieldset>
                                        <legend class="hidden"><a href="https://typeset.sh/" target="_blank" rel="noopener">typeset.sh</a> Project Token</legend>
                                        <input type="password" name="magazine_typeset_token_key" value="'. $magazine_typeset_token_key .'" style="width:100%;display:block;" />
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="magazine_local_command">Local Command<br/><i>(using STDIN & STDOUT)</i></label></th>
                                <td>
                                    <fieldset>
                                        <legend class="hidden">Local Command<br/><i>(using STDIN & STDOUT)</i></legend>
                                        <input placeholder="/usr/AHFormatterV71_64/run.sh -x 4 -d @STDIN -o @STDOUT" type="text" name="magazine_local_command" value="'. $magazine_local_command .'" style="width:100%;display:block;" />
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <input type="submit" name="Submit" class="button-primary button-magazine" value="Save Changes" />
                        </p>
                        <input name="action" value="magazin_update_options" type="hidden" />
                    </form>
                </div>
                <style>
                    @import "' . plugin_dir_url( __DIR__ ) . '/magazine/css/options.css";
                </style>';
        });
    });

    add_action('in_admin_header', function(){
        $screen = get_current_screen();
        if ($screen->base == 'settings_page_magazine_option_page') {
            $help_tabs = $screen->get_help_tabs();
            $screen->remove_help_tabs();
    
            $screen->add_help_tab(array(
                'id' => 'magazine_overview_help',
                'title' => 'Overview',
                'content' => '<p>On this screen, you can set the options for your Magazine plugin.</p>
                              <ul>
                                <li>
                                    <b>Rendering Tool:</b><br />
                                    Depending on the filled API Key and Local Command Options you see the supported rendering tools here. You need to use at least one of the API or the local command option for a rendering tool to appear in this selection.
                                </li>
                                <li>
                                    <b>RapidAPI Key:</b><br />
                                    To send the request to the PrintCSS Cloud API, you <a href="https://rapidapi.com/azettl/api/printcss-cloud/pricing" target="_blank" rel="noopener">need to subscribe to a plan on RapidAPI</a>. With this, you get the API key that is required to authenticate with the PrintCSS Cloud REST service. The PrintCSS Cloud supports the rendering tools <a href="https://weasyprint.org/" target="_blank" rel="noopener">WeasyPrint</a>, <a href="https://www.pagedjs.org/" target="_blank" rel="noopener">PagedJS</a>, and <a href="https://vivliostyle.org/" target="_blank" rel="noopener">Vivliostyle</a>.
                                </li>
                                <li>
                                    <b>DocRaptor Key:</b><br />
                                    To send the request to the DocRaptor API, you <a href="https://docraptor.com/signup" target="_blank" rel="noopener">need to subscribe to a plan on docraptor.com</a>. With this, you get the API key that is required to authenticate with the REST service.
                                </li>
                                <li>
                                    <b>typeset.sh Project Key & Project Token:</b><br />
                                    To send the request to the typeset.sh API, you <a href="https://typeset.sh/en/register" target="_blank" rel="noopener">need to subscribe to a plan on typeset.sh</a>. With this, you get the project key and token that is required to authenticate with their REST service.
                                </li>
                                <li>
                                    <b>Local Command:</b><br />
                                    If you have a PDF rendering tool installed locally, you can provide the command here, be aware that the tool needs to support getting the HTML via STDIN and needs to return the PDF via STDOUT.
                                    <ul>
                                        <li>
                                            AH Formatter Example:<br />
                                            <samp>/usr/AHFormatterV71_64/run.sh -x 4 -d @STDIN -o @STDOUT</samp>
                                        </li>
                                        <li>
                                            Prince Example:<br />
                                            <samp>prince --no-warn-css --javascript -</samp>
                                        </li>
                                        <li>
                                            PDFreactor Example:<br />
                                            <samp>cd /path/to/sh/script/ && ./pdfreactor.sh</samp> 
                                            (Get the script <a href="https://gist.github.com/azettl/8546b17bd4880ce44c9b5b2b2ca596a9" target="_blank" rel="noopener">on GitHub</a>)
                                        </li>
                                    </ul>
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

    // Add Links to the plugin page entry   
    add_filter('plugin_action_links_magazine/magazine.php', function(array $aLinks){
        $aLinks[] = '<a href="' . admin_url( 'options-general.php?page=magazine_option_page' ) . '">' . __('Settings') . '</a>';
        
        return array_reverse($aLinks);
    });