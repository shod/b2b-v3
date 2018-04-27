tinyMCE.init({
    // General options
    mode : "exact",
	elements : 'description',
    theme : "simple",
    language : "ru",
    document_base_url : '/',
    relative_urls : false,
    convert_urls : true,
    remove_script_host : false,
    remove_linebreaks : true,
    extended_valid_elements : "div[*],p[*]",
	paste_preprocess : function(pl, o) {
	  o.content = strip_tags( o.content,'' );
	},
    forced_root_block:'p',
    force_p_newlines: false,
	nline_styles :true,
	cleanup : true,
	plugins : "paste",
	paste_remove_spans: true,
	paste_remove_styles: true, 

	style_formats : [
        {title : "Bold text", inline : "b"},
        {title : "Red text", inline : "span", styles : {color : "#ff0000"}},
        {title : "Red header", block : "h1", styles : {color : "#ff0000"}},
        {title : "Example 1", inline : "span", classes : "example1"},
        {title : "Example 2", inline : "span", classes : "example2"},
        {title : "Table styles"},
        {title : "Table row 1", selector : "tr", classes : "tablerow1"}
    ],
	
	//plugins : "",

    // Theme options
    theme_advanced_resizing : true,


    // Skin options
    skin : "default",
    skin_variant : "silver",

    /*content_css : "css/styles.css",*/

});

function strip_tags (str, allowed_tags)
{

    var key = '', allowed = false;
    var matches = [];    var allowed_array = [];
    var allowed_tag = '';
    var i = 0;
    var k = '';
    var html = ''; 
    var replacer = function (search, replace, str) {
        return str.split(search).join(replace);
    };
    // Build allowes tags associative array
    if (allowed_tags) {
        allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
    }
    str += '';

    // Match tags
    matches = str.match(/(<\/?[\S][^>]*>)/gi);
    // Go through all HTML tags
    for (key in matches) {
        if (isNaN(key)) {
                // IE7 Hack
            continue;
        }

        // Save HTML tag
        html = matches[key].toString();
        // Is tag not in allowed list? Remove from str!
        allowed = false;

        // Go through all allowed tags
        for (k in allowed_array) {            // Init
            allowed_tag = allowed_array[k];
            i = -1;

            if (i != 0) { i = html.toLowerCase().indexOf('<'+allowed_tag+'>');}
            if (i != 0) { i = html.toLowerCase().indexOf('<'+allowed_tag+' ');}
            if (i != 0) { i = html.toLowerCase().indexOf('</'+allowed_tag)   ;}

            // Determine
            if (i == 0) {                allowed = true;
                break;
            }
        }
        if (!allowed) {
            str = replacer(html, "", str); // Custom replace. No regexing
        }
    }
    return str;
}
