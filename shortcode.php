<?php
    add_shortcode('magazine', function($attributes){
        if(!isset($attributes['theme'])){
            return 'Magazine Shortcode: Theme attribute is missing.';
        }
        $aThemes = magazine_template::_getTemplateNames();

        if(!in_array($attributes['theme'], $aThemes)){
            return 'Magazine Shortcode: Can not find the provided theme name.';
        }
        
        global $wp;
        $text = $attributes['text'] ? esc_attr($attributes['text']) : 'Render PDF';

        $sCurrentURL = home_url($wp->request);
        return '<a class="hide_render_link_in_pdf" href="' . $sCurrentURL . '?renderPDF=true&theme=' . $attributes['theme'] . '">' 
            . $text . '</a>';
    });