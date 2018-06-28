$(function() {
    $('#demo').daterangepicker({
        "autoUpdateInput": true,
        // "showDropdowns": true,
        "timePicker": true,
        "timePicker24Hour": true,
        "timePickerSeconds": true,
        "dateLimit": {
            "days": 175
        },
        "ranges" : {  
        // '最近1小时': [moment().subtract('hours',1), moment()],  
        '今日': [moment().startOf('day'), moment()],  
        '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],  
        '最近3日': [moment().subtract('days', 2).startOf('day'), moment()],  
        '最近7日': [moment().subtract('days', 6).startOf('day'), moment()],  
        '最近15日': [moment().subtract('days', 14).startOf('day'), moment()],  
        '最近30日': [moment().subtract('days', 29).startOf('day'), moment()],  
        '本月': [moment().startOf("month"),moment().endOf("month")],  
        '上个月': [moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")]  
        }, 
        "locale": {
            "direction": "ltr",
            "format": "YYYY-MM-DD HH:mm:ss",
            "separator": " - ",
            "applyLabel": "确定",
            "cancelLabel": "取消",
            "fromLabel": "From",
            "toLabel": "To",
            // "customRangeLabel": "Custom",
            "customRangeLabel" : '自定义',  
            "daysOfWeek": [ '日', '一', '二', '三', '四', '五', '六' ],
            "monthNames": [ '一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            "firstDay": 1
        },
        "alwaysShowCalendars": true,
        "opens": "right",
        // "startDate": "04/03/2018",
        // "endDate": "04/09/2018"
    }, function(start, end, label) {
       var s = start.format('YYYY-MM-DD HH:mm:ss');
       var e = end.format('YYYY-MM-DD HH:mm:ss');
       $('#stime').val(s);
       $('#etime').val(e);
       console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
    });
});
    