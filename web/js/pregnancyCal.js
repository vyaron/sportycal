$(function($) {
    $("#datepicker").datepicker();
    $.datepicker.regional['he'] = {
        closeText: 'סגור',
        prevText: '&#x3c;הקודם',
        nextText: 'הבא&#x3e;',
        currentText: 'היום',
        monthNames: ['ינואר', 'פברואר', 'מרץ', 'אפריל', 'מאי', 'יוני',
            'יולי', 'אוגוסט', 'ספטמבר', 'אוקטובר', 'נובמבר', 'דצמבר'
        ],
        monthNamesShort: ['ינו', 'פבר', 'מרץ', 'אפר', 'מאי', 'יוני',
            'יולי', 'אוג', 'ספט', 'אוק', 'נוב', 'דצמ'
        ],
        dayNames: ['ראשון', 'שני', 'שלישי', 'רביעי', 'חמישי', 'שישי', 'שבת'],
        dayNamesShort: ['א\'', 'ב\'', 'ג\'', 'ד\'', 'ה\'', 'ו\'', 'שבת'],
        dayNamesMin: ['א\'', 'ב\'', 'ג\'', 'ד\'', 'ה\'', 'ו\'', 'שבת'],
        weekHeader: 'Wk',
        dateFormat: 'dd/mm/yy',
        firstDay: 0,
        isRTL: true,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['he']);

    $('.btn-cal').on('click', function() {
        $(this).css({
            'background-color': 'rgb(135, 121, 145)',
            'border-color': '#fff'
        })
    });
    
    $('#btnCreateCal').click(function(){
        var lpDate = $("#datepicker").val();
        if (!lpDate) {
            alert('נא הזיני תאריך');
            return;
        }
        //alert(lpDate);
        window.location = 'http://inevermiss.net/nm/addToCalendar/?lp=' + lpDate;
    });
    
});


$('#bg').on('load', function() {

    $('.one').addClass('bounceIn');
    $('.two').addClass('bounceIn').css({
        '-webkit-animation-delay': '2s',
        '-moz-animation-delay': '2s',
        '-ms-animation-delay': '2s',
        '-o-animation-delay': '2s',
        'animation-delay': '2s'
    });
    $('.three').addClass('bounceIn').css({
        '-webkit-animation-delay': '3s',
        '-moz-animation-delay': '3s',
        '-ms-animation-delay': '3s',
        '-o-animation-delay': '3s',
        'animation-delay': '3s'
    });
    $('.four').addClass('bounceIn').css({
        '-webkit-animation-delay': '4s',
        '-moz-animation-delay': '4s',
        '-ms-animation-delay': '4s',
        '-o-animation-delay': '4s',
        'animation-delay': '4s'
    });
    $('.five').addClass('bounceIn').css({
        '-webkit-animation-delay': '5s',
        '-moz-animation-delay': '5s',
        '-ms-animation-delay': '5s',
        '-o-animation-delay': '5s',
        'animation-delay': '5s'
    });
    $('.six').addClass('bounceIn').css({
        '-webkit-animation-delay': '6s',
        '-moz-animation-delay': '6s',
        '-ms-animation-delay': '6s',
        '-o-animation-delay': '6s',
        'animation-delay': '6s'
    });



});


