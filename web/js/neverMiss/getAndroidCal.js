function handleMailForm(){
    var mailForm = jQuery('#mail-form');
    mailForm.validate();
    mailForm.submit(function(e){
        e.preventDefault();

        mailForm.find('[type=submit]').addClass('loading');

        if (mailForm.valid()){
            jQuery.ajax({
                url : '/cal/sendAndroidMail',
                type : 'POST',
                dataType : 'json',
                data : mailForm.serialize()
            }).done(function(res){
                mailForm.find('[type=submit]').removeClass('loading');

                //setGlobalAlert(res);

                if (res.success) mailForm.hide();
                else if (res && res.errors) mailForm.data('validator').showErrors(res.errors);
            });
        }
    });
}

jQuery(document).ready(function(){
    handleMailForm();
});