$( document ).ready(function() {

    tinymce.init({
        selector: '.visual_editor',
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu template textcolor paste textcolor colorpicker textpattern autoresize"
        ],
        toolbar1: "code | removeformat | bold italic underline | alignleft aligncenter alignright alignjustify | formatselect | bullist numlist | link unlink image",
        menubar: false,
        fontsize_formats: '10px 14px 16px 24px 32px',
        file_browser_callback: RoxyFileBrowser,
        forced_root_block: false,
        force_p_newlines: false,
        remove_linebreaks: false,
        force_br_newlines: true,
        remove_trailing_nbsp: false,
        statusbar: false,
        verify_html: false,
        autoresize_bottom_margin: 16,
        convert_urls: false,
        relative_urls: false,
        remove_script_host: false,
        language: 'ru'
    });

});

function RoxyFileBrowser(field_name, url, type, win) {
    var roxyFileman = '/assets/tinymce/plugins/fileman/index.html';
    if (roxyFileman.indexOf("?") < 0) {
        roxyFileman += "?type=" + type;
    }
    else {
        roxyFileman += "&type=" + type;
    }
    roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
    if(tinyMCE.activeEditor.settings.language){
        roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
    }
    tinyMCE.activeEditor.windowManager.open({
        file: roxyFileman,
        title: 'Файловый менеджер',
        width: document.documentElement.scrollWidth-50,
        height: document.documentElement.clientHeight-50,
        resizable: "yes",
        plugins: "media",
        inline: "yes",
        close_previous: "no"
    }, {     window: win,     input: field_name    });
    return false;
}

function close_files(){
    $('.files_panel').hide();
}
