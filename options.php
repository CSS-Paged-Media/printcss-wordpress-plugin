<?php

    if ('magazin_update_options' === $_POST['action']){
        update_option("magazine_rendering_tool", $_POST['magazine_rendering_tool']);
        update_option("magazine_rapidapi_key",   $_POST['magazine_rapidapi_key']);
    }

    add_action('admin_menu', function(){
        add_option("magazine_rendering_tool",   "weasyprint");
        add_option("magazine_rapidapi_key",     "");

        add_options_page('Magazine', 'Magazine', 9, 'magazine_option_page', function(){
            $magazine_rendering_tool = get_option('magazine_rendering_tool');
            $magazine_rapidapi_key   = get_option('magazine_rapidapi_key');

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
                        </table>
                        <p class="submit">
                            <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
                        </p>
                        <input name="action" value="magazin_update_options" type="hidden" />
                    </form>
                </div>';
        });
    });
