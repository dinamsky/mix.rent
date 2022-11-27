$( document ).ready(function() {



    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var j = 0;
            var filesAmount = input.files.length;

            console.log(input.files);
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(e,i) {
                    var dataUrl = e.target.result;
                    $(placeToInsertImagePreview).append('<div class="uk-width-1-5 preview_parent uk-position-relative"><img src="'+dataUrl+'" alt=""><span class="delete_preview"><i class="fa fa-close"></i></span><input type="hidden" name="to_upload[]" value="'+input.files[j].name+'"></div>');
                    j++;
                };
                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#foto_upload').on('change', function() {
        $('#foto_list_view').html('').css('margin-top','20px');
        imagesPreview(this, '#foto_list_view');
    });

    $('#foto_list_view').on('click','.delete_preview', function() {
        $(this).parents('.preview_parent').remove();
    });
});