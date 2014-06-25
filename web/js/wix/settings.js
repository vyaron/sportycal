var gSettingAjax = null;

function refresh(){
    window.location.reload();
}

function createAccount(e){
    if (e) e.preventDefault();
    var popup = window.open(BASE_URL + '/nm/register/?isPopup=1', '', 'width=650,height=600,location=0,menubar=0,status=0,titlebar=0,toolbar=0');
    popup.focus();
}

function connect(e){
    if (e) e.preventDefault();
    var popup = window.open(BASE_URL + '/partner/login/?isPopup=1', '', 'width=650,height=600,location=0,menubar=0,status=0,titlebar=0,toolbar=0');
    popup.focus();
}

function disconnect(e){
    if (e) e.preventDefault();

    $.ajax({
        url : '/main/logout'
    }).always(function(){
        refresh();
    });
}

function upgrade(e){
    if (e) e.preventDefault();
    if (Wix) Wix.Settings.openBillingPage();
}

function createCalendar(e){
    if (e) e.preventDefault();
    var popup = window.open(BASE_URL + '/nm/calCreate/?isPopup=1', 'Create', 'width=980,height=870,scrollbars=1');
    popup.focus();
}

function editCalendar(e){
    if (e) e.preventDefault();

    var calId = $('input[name="cal_id"], select[name="cal_id"]').val();
    var popup = window.open(BASE_URL + '/nm/calEdit/id/' + calId + '/?isPopup=1', 'Edit', 'width=980,height=870,scrollbars=1');
    popup.focus();
}

function toggleEditCalendar(){
    var calId = Wix.UI.get('cal_id') ?  Wix.UI.get('cal_id').value : '';
    var editCalendar = $('#edit-calendar');

    if (calId) editCalendar.show();
    else editCalendar.hide();
}


function setValue(name, data){
//    console.log(name, data);
    if (name == "text_color" || name == "line_color"){
        if (data.cssColor) $('input[name="' + name + '"]').val(data.cssColor);
    } else if (name == "bg_color"){
        if (data.color) $('input[name="' + name + '"]').val(data.color.value);
        $('input[name="bg_opacity"]').val(data.opacity);
        $('#bg_opacity_value').text(Math.floor(data.opacity * 100) + '%');
    } else if (name == "cal_id"){
        if (data.value) $('input[name="' + name + '"]').val(data.value);
        toggleEditCalendar();
    } else {
        $('input[name="' + name + '"]').val(data);
    }

    $('#settings-form').submit();
}

function refreshWidget(){
    var compId = Wix.Utils.getOrigCompId();
    Wix.Settings.refreshAppByCompIds(compId);
}

$(document).ready(function () {
    if (Wix && Wix.Settings) Wix.Settings.refreshApp();

    Wix.UI.initialize({
        cal_id : null
    });

    $('#create-account').click(createAccount);
    $('.connect').click(connect);
    $('#disconnect').click(disconnect);
    $('.upgrade').click(upgrade);

    $('#create-calendar').click(createCalendar);
    $('#edit-calendar').click(editCalendar);


    //form
    var settingsForm = $('#settings-form');
    settingsForm.submit(function(e){
        e.preventDefault();

        if (gSettingAjax) gSettingAjax.abort();

        gSettingAjax = $.ajax({
            url : BASE_URL + '/wix/update/?origCompId=' + COMP_ID,
            type : 'PUT',
            dataType : 'json',
            data : settingsForm.serialize()
        }).always(refreshWidget);
    });

    //Toggle edit calendar button based on selected calendar
    toggleEditCalendar();
//    var selectCalendar = $('select[name="cal_id"]');
//    if (selectCalendar.length){
//        selectCalendar.change(function(){
//            toggleEditCalendar();
//            settingsForm.submit();
//        });
//        toggleEditCalendar();
//    }


    //subscribe to changes in all keys
    Wix.UI.onChange('*', function(value, key){
        //do some awesome stuff with the value
        setValue(key, value);
    });

//    //ui
//    $('.accordion').Accordion();
//
//    $('.color-selector').each(function(i, el){
//        var jEl = $(el);
//        if (jEl.ColorPicker) {
//            jEl.ColorPicker({startWithColor : jEl.attr('data-color')});
//        }
//    });
//
//    $('.slider').each(function(i, el){
//        var jEl = $(el);
//        jEl.Slider({
//            type: "Value",
//            value: jEl.attr('data-opacity')
//        });
//    });
//
//    $('.checkbox').each(function(i, el){
//        var jEl = $(el);
//        jEl.Checkbox({ checked: jEl.attr('data-checked') });
//    });
//
//    //form
//    var settingsForm = $('#settings-form');
//    settingsForm.submit(function(e){
//        e.preventDefault();
//
//        if (gSettingAjax) gSettingAjax.abort();
//
//        gSettingAjax = $.ajax({
//            url : BASE_URL + '/wix/update/?origCompId=' + COMP_ID,
//            type : 'PUT',
//            dataType : 'json',
//            data : settingsForm.serialize()
//        }).always(refreshWidget);
//    });
//
//    //update values
//    $(document).on("colorChanged", function(e, data){
//        setValue(data.type, data.selected_color);
//    });
//
//    $(document).on("slider.change", function(e, data){
//        setValue(data.type, data.value);
//    });
//
//    $(document).on("checkbox.change", function (event, data) {
//        setValue(data.type, data.checked);
//    });
//
//    $('#create-account').click(createAccount);
//    $('.connect').click(connect);
//    $('#disconnect').click(disconnect);
//    $('.upgrade').click(upgrade);
//
//    $('#create-calendar').click(createCalendar);
//    $('#edit-calendar').click(editCalendar);
//
//    //Toggle edit calendar button based on selected calendar
//    var selectCalendar = $('select[name="cal_id"]');
//    if (selectCalendar.length){
//        selectCalendar.change(function(){
//            toggleEditCalendar();
//            settingsForm.submit();
//        });
//        toggleEditCalendar();
//    }
});