$( document ).ready(function() {



});

function edit_card_validate(){
    var general_type = $('#generalTypeId').find('option:selected').val();
    //var header = $('#edit_card_form').find('input[name="header"]').val();
    var model = $('#markModelId').find('option:selected').val()-0;
    var city = $('form select[name="cityId"]').find('option:selected').val();
    var noMark = $('input[name="noMark"]').prop('checked');
    var ownMark = $('input[name="ownMark"]').val();
    var subfields = [];

    var message = [];

    // if($('#foto_upload').val() === ''){
    //     message.push('\nФотографии');
    // }

    if (!general_type) message.push('\nVehicle type');
    //if (!header) message.push('\nЗаголовок');
    if (!model || model === 0) message.push('\nModel');
    if (!city || city === '0') message.push('\nCity');

    // $('.sub_field_field').each(function(){
    //     var field_id = $(this).data('id');
    //     var label = $('.subfield_label[data-id="'+field_id+'"]').html();
    //     if ($(this).hasClass('is_last')) {
    //         var val;
    //         if ($(this).hasClass('subFieldSelect')) {
    //             val = $(this).find('option:selected').val();
    //             if (!val || val === '0') message.push('\n'+label);
    //         } else {
    //             val = $(this).val();
    //             if (!val) message.push('\n'+label);
    //         }
    //     } else {
    //         message.push('\n'+label);
    //     }
    //     subfields.push(field_id);
    // });
    //
    // if (subfields.length === 0) message.push('Дополнительные поля транспорта\n');

    if (message.length > 0){
        alert('Please fill:\n'+message);
        return false;
    }
}