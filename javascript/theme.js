$(document).ready(function() {
    var htmlPrefixEditor = ace.edit("magazine_print_html_prefix");
    htmlPrefixEditor.session.setMode("ace/mode/html");
    htmlPrefixEditor.setOption("enableEmmet", true);
    htmlPrefixEditor.session.setTabSize(2);
    htmlPrefixEditor.session.on("change", function(){
        $('textarea[name="magazine_print_html_prefix"]').val(htmlPrefixEditor.session.getValue());
    });

    var htmlPostEditor = ace.edit("magazine_print_html_post");
    htmlPostEditor.session.setMode("ace/mode/html");
    htmlPostEditor.setOption("enableEmmet", true);
    htmlPostEditor.session.setTabSize(2);
    htmlPostEditor.session.on("change", function(){
        $('textarea[name="magazine_print_html_post"]').val(htmlPostEditor.session.getValue());
    });

    var htmlPageEditor = ace.edit("magazine_print_html_page");
    htmlPageEditor.session.setMode("ace/mode/html");
    htmlPageEditor.setOption("enableEmmet", true);
    htmlPageEditor.session.setTabSize(2);
    htmlPageEditor.session.on("change", function(){
        $('textarea[name="magazine_print_html_page"]').val(htmlPageEditor.session.getValue());
    });

    var htmlPostFixEditor = ace.edit("magazine_print_html_postfix");
    htmlPostFixEditor.session.setMode("ace/mode/html");
    htmlPostFixEditor.setOption("enableEmmet", true);
    htmlPostFixEditor.session.setTabSize(2);
    htmlPostFixEditor.session.on("change", function(){
        $('textarea[name="magazine_print_html_postfix"]').val(htmlPostFixEditor.session.getValue());
    });

    var cssMainEditor = ace.edit("magazine_print_css_main");
    cssMainEditor.session.setMode("ace/mode/css");
    cssMainEditor.session.setTabSize(2);
    cssMainEditor.session.on("change", function(){
        $('textarea[name="magazine_print_css_main"]').val(cssMainEditor.session.getValue());
    });

    var cssPostEditor = ace.edit("magazine_print_css_post");
    cssPostEditor.session.setMode("ace/mode/css");
    cssPostEditor.session.setTabSize(2);
    cssPostEditor.session.on("change", function(){
        $('textarea[name="magazine_print_css_post"]').val(cssPostEditor.session.getValue());
    });

    var cssPageEditor = ace.edit("magazine_print_css_page");
    cssPageEditor.session.setMode("ace/mode/css");
    cssPageEditor.session.setTabSize(2);
    cssPageEditor.session.on("change", function(){
        $('textarea[name="magazine_print_css_page"]').val(cssPageEditor.session.getValue());
    });

    var jsMainEditor = ace.edit("magazine_print_js_main");
    jsMainEditor.session.setMode("ace/mode/javascript");
    jsMainEditor.session.setTabSize(2);
    jsMainEditor.session.on("change", function(){
        $('textarea[name="magazine_print_js_main"]').val(jsMainEditor.session.getValue());
    });

    var jsPostEditor = ace.edit("magazine_print_js_post");
    jsPostEditor.session.setMode("ace/mode/javascript");
    jsPostEditor.session.setTabSize(2);
    jsPostEditor.session.on("change", function(){
        $('textarea[name="magazine_print_js_post"]').val(jsPostEditor.session.getValue());
    });

    var jsPageEditor = ace.edit("magazine_print_js_page");
    jsPageEditor.session.setMode("ace/mode/javascript");
    jsPageEditor.session.setTabSize(2);
    jsPageEditor.session.on("change", function(){
        $('textarea[name="magazine_print_js_page"]').val(jsPageEditor.session.getValue());
    });
});