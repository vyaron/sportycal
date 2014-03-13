jQuery(document).ready(function(){
    //$('#teams').ddslick();
//    $('#demoBasic').ddslick({
//        data: ddData,
//        width: 300,
//        imagePosition: "left",
//        selectText: "Select your team",
//        onSelected: function (data) {
//            console.log(data);
//        }
//    });

    jQuery('#teams').bind('change', function(){
        var btnEl = jQuery('#download-calendar');
        if (this.value == 0) {
            btnEl.attr('disabled', 'disabled');
            btnEl.removeAttr('href');
        } else {
            btnEl.removeAttr('disabled');
            btnEl.attr('href', btnEl.attr('data-href') + this.value);
        }
    });
});