<?php
/**
 * Plugin Name:       Magazine
 * Plugin URI:        https://wordpresstopdf.com/
 * Description:       Create PDFs from your Posts and Pages using the printcss.cloud for PDF generation.
 * Version:           0.1.3
 * Requires at least: 5.7
 * Requires PHP:      7.2
 * Author:            Andreas Zettl
 * Author URI:        https://azettl.net/
 */

require_once __DIR__ . '/classes/template.php';
require_once __DIR__ . '/classes/pdf.php';

require_once __DIR__ . '/activate.php';
require_once __DIR__ . '/bulk_action.php';

if(get_option('magazine_show_button_in_editor') == 1){
	require_once __DIR__ . '/editor_button.php';
}

require_once __DIR__ . '/options.php';
require_once __DIR__ . '/theme.php';
require_once __DIR__ . '/widget.php';
require_once __DIR__ . '/shortcode.php';
require_once __DIR__ . '/rest.php';