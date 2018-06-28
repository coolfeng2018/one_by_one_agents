/*              
 * 时间戳转换日期              
 * @param  <int>  unixTime    待时间戳(秒)              
 * @param  <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)              
 * @param  <bool> isCheckTime    true返回2015-07-05  false:2015-7-5           
 * @param  <int>  timeZone   时区
 */
function formatDate(unixTime, isFull=true, timeZone, isCheckTime=true) {
    if (typeof (timeZone) == 'number') {
        unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
    }
    var time = new Date(unixTime * 1000);
    var ymdhis = "";
 
    if (isCheckTime === true) {
        ymdhis += time.getUTCFullYear() + "-";
        ymdhis += formatDateCheckTime((time.getUTCMonth() + 1)) + "-";
        ymdhis += formatDateCheckTime(time.getUTCDate());
        if (isFull === true) {
            ymdhis += " " + formatDateCheckTime(time.getUTCHours()) + ":";
            ymdhis += formatDateCheckTime(time.getUTCMinutes()) + ":";
            ymdhis += formatDateCheckTime(time.getUTCSeconds());
 
        }
    } else {
        ymdhis += time.getUTCFullYear() + "-";
        ymdhis += (time.getUTCMonth() + 1) + "-";
        ymdhis += time.getUTCDate();
        if (isFull === true) {
            ymdhis += " " + time.getUTCHours() + ":";
            ymdhis += time.getUTCMinutes() + ":";
            ymdhis += time.getUTCSeconds();
        }
    }
 
    return ymdhis;
}
 
/*              
 * 时间戳转换日期之检测对象是否小于10    
 */
function formatDateCheckTime(i) {
    if (i < 10)
    { i = "0" + i }
    return i
}