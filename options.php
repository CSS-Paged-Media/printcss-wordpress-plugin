<?php

    if ('magazin_update_options' === $_POST['action']){
        update_option("magazine_rendering_tool", $_POST['magazine_rendering_tool']);
        update_option("magazine_rapidapi_key",   $_POST['magazine_rapidapi_key']);
        update_option("magazine_docraptor_key",   $_POST['magazine_docraptor_key']);
    }

    add_action('admin_menu', function(){
        add_option("magazine_rendering_tool",   "weasyprint");
        add_option("magazine_rapidapi_key",     "");
        add_option("magazine_docraptor_key",    "");

        add_options_page('Magazine', 'Magazine', 9, 'magazine_option_page', function(){
            $magazine_rendering_tool = get_option('magazine_rendering_tool');
            $magazine_rapidapi_key   = get_option('magazine_rapidapi_key');
            $magazine_docraptor_key  = get_option('magazine_docraptor_key');

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

            echo '<div class="wrap wrap-magazine">
                    <h1>
                        Magazine Options <i>powered by <a href="https://printcss.cloud" target="_blank" rel="noopener">PrintCSS Cloud</a></i>
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
                                <th scope="row"><label for=""><a href="https://docraptor.com" target="_blank" rel="noopener">DocRaptor</a> API Key</label></th>
                                <td>
                                    <fieldset>
                                        <legend class="hidden"><a href="https://docraptor.com" target="_blank" rel="noopener">DocRaptor</a> API Key</legend>
                                        <input type="password" name="magazine_docraptor_key" value="'. $magazine_docraptor_key .'" style="width:100%;display:block;" />
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
                                    Check out the Tools Websites for more information about their capabilities: <a href="https://weasyprint.org/" target="_blank" rel="noopener">WeasyPrint</a>, <a href="https://www.pagedjs.org/" target="_blank" rel="noopener">PagedJS</a>, and <a href="https://vivliostyle.org/" target="_blank" rel="noopener">Vivliostyle</a>.
                                </li>
                                <li>
                                    <b>RapidAPI Key:</b><br />
                                    To send the request to the PrintCSS Cloud API, you <a href="https://rapidapi.com/azettl/api/printcss-cloud/pricing" target="_blank" rel="noopener">need to subscribe to a plan on RapidAPI</a>. With this, you get the API key that is required to authenticate with our REST service.
                                </li>
                              </ul>
                              <p>Before you start editing a theme, be sure you selected the correct one.</p>',
            ));
    
            if (count($help_tabs)){
                foreach ($help_tabs as $help_tab){
                    $screen->add_help_tab($help_tab);
                }
            }
        }
    });