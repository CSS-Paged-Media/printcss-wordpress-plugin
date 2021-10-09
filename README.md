# Magazine

* Download Link: https://wordpresstopdf.com/
* Tags: PDF, HTML to PDF, PrintCSS, WeasyPrint, PagedJS, Vivliostyle, PrintCSS Cloud, RapidAPI
* Requires at least: 5.7
* Tested up to: 5.7
* Version: 0.1.2

Create PDFs from your Posts and Pages using the printcss.cloud for PDF generation.

## Installation and Setup

1. Upload the complete directory into your wp-content/plugins folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add your PrintCSS Cloud Key to the Settings Page under 'Settings' > 'Magazine.'
4. Modify the sample code on the Settings Page
5. Go to your Pages or Posts, select some of them and use the Bulk Action 'Render PDF.'
6. The result PDF gets stored in the Media Library.

## Changelog

- 0.1.2
    - Add Rest Endpoint (POST /wp-json/magazine/v1/pdf, Body {"ids": [265, 123], "theme": "Events"})
    - Add Support for Custom Post Types
- 0.1.1
    - Add new Placeholders for Posts and Pages ({{categories}}, {{category_slugs}})
    - Add Placeholders for Prefix and Postfix HTML ({{toc_list}})
    - Add ACF Conditional Placeholders for Prefix and Postfix HTML (example: {{post.ACF_fieldName=fieldValue.title}})
    - Update Help Text with new Placeholders 
    - Add Placeholder Buttons next to the Theme Editors
    - add magazineSortAndFilterPosts for functions.php (The function magazineSortAndFilterPosts can be defined in the functions.php of your WordPress installation. You will get passed an array with the post ids, which you can filter or sort. You will need to return an array with the post ids in your method.)
- 0.1.0
    - Add Shortcode
    - Fix broken Placeholder Help Text
    - Add PDFreactor sample to Help Text
    - Change Layout of Options and Theme Page
    - Update Demo Theme with Front and Back Cover (only updates if you have no Theme available)
- 0.0.9
    - Add DocRaptor and Typeset.sh APIs and allow local command to render.
- 0.0.8
    - Add Download Theme as ZIP Option
    - Add Upload Theme ZIP File Option
    - Add Help Tabs for Options and Themes Page
- 0.0.7
    - Update Theme and Options Page Layout
    - Fix Activation Hook to create Demo Theme
- 0.0.6
    - Added Themes and Theme Editor
- 0.0.5 
    - Add Widget to show render link on the website
    - Placeholder replacement in the CSS and JS files
    - Added ACF_ as a prefix for the ACF Placeholders
    - Added Placeholder for the page/post slug
- 0.0.4 
    - Added Feature Image and ACF Field Placeholders
- 0.0.3 
    - First Release