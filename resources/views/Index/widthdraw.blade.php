@extends('layouts.header')
<link rel="stylesheet" href="/css/button.css">
<script src="/js/clipboard.min.js"></script>
<section data-role="page" id="pgAgentList">
    <header data-role="header" data-position="fixed">
        <a href="/index/index" data-icon="back" target="_top">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">提现</b></h1>
    </header>
    <article class="ui-content">
        <div>
            <div class="article">
                <div class="start left change">
                    <div>￥<span class="now_Balance">{{ $info->Balance }}</span></div>
                    <div style='padding: 10px 0 10px 0;font-size:20px;'><b>当前余额</b></div>
                </div>
                <div class="center right change">
                    <div>￥<span class="extractable">{{ $info->extractable }}</span></div>
                    <div style='padding: 10px 0 10px 0;font-size:20px;'><b>可提余额</b></div>
                </div>
            </div>
            <div data-role="main" class="ui-content">
            <form id="widthdraw">
              <label for="widthdrawAmout">提现金额：</label>
              <input type="text" name="widthdrawAmout" id="widthdrawAmout"> 
              <fieldset data-role="fieldcontain">
                <label for="mode">提现方式</label>
                <select name="mode" id="mode" data-native-menu="true">
                 <option value="alipay">支付宝</option>
                 <option value="wechat">微信</option>
                 <option value="bank">银行卡</option>
                </select>
              </fieldset>
              <div class="alipay">
                <label for="AlipayCode">支付宝账号：</label>
                <input type="text" name="AlipayCode" id="AlipayCode" value="{{ $info->AlipayCode }}">
                <label for="AlipayName">用户名：</label>
                <input type="text" name="AlipayName" id="AlipayName" value="{{ $info->AlipayName }}">
              </div>
              <div class="wechat" style="display: none;">
                <label for="wechatAccount">微信账号：</label>
                <input type="text" name="WithdrawWechatCode" id="WithdrawWechatCode" value="{{ $info->WithdrawWechatCode }}">
              </div>
              <div class="bank" style="display: none;">
                <label for="CreditCode">银行卡号：</label>
                <input type="text" name="CreditCode" id="CreditCode" value="{{ $info->CreditCode }}">
                <label for="CreditBank">银行：</label>
                <input type="text" name="CreditBank" id="CreditBank" value="{{ $info->CreditBank }}">
                <label for="CreditName">真实姓名：</label>
                <input type="text" name="CreditName" id="CreditName" value="{{ $info->CreditName }}">
              </div>
              <a data-theme="b" data-role="button" target="_top" id="widthdrawClick">提交</a>
              <div data-role="popup" class="ui-content" id="error"></div>
              <ul data-role="listview" data-inset="true">
                <li><a href="/index/widthdrawdetail" target="_top">提现记录</a></li>
              </ul>
            </form>
            </div>
        </div>
    </article>
</section>

<style type="text/css">
.start{
    float: left;
    /*height: 43px;*/
    /*margin-right:1rem;*/
}
.end{
    float: left;
}
.center{
    float:right;
}
.article{
    overflow: hidden;
    padding-bottom: 0.5rem;
    /*margin-bottom:0.5rem;*/
    font-size: 1.2rem;
    font-weight: 300;
    /*line-height: normal;*/
    /*height: 120px;*/
}
.left{
    padding-left:60px;
}
.right{
    padding-right:60px;
}
.change{
    /*padding : 0;*/
    text-align: center !important;
    text-decoration: none !important;
    font: !important normal 10px Arial, sans-serif;
    font-size:14px;
}
.boder th{
    border-bottom: 1px solid #d6d6d6;
}
.boder tr:nth-child(even) {
    background: #e9e9e9;
}

</style>

<script src="/jquery_mobile/scripts/jquery.min.js" type="text/javascript"></script>
<script>
// $("#div form").bind("submit", function(event) {
//     //回调函数
// });
$("#mode").change(function () {  
  var mode = $(this).children('option:selected').val(); 
  if (mode == "alipay") {  
    $(".alipay").show();
    $(".wechat,.bank").hide();
  } else if (mode == "wechat") {  
    $(".wechat").show();
    $(".bank,.alipay").hide();
  }  else if (mode == "bank") {  
    $(".bank").show();
    $(".wechat,.alipay").hide();
  }  
}); 


$("#widthdrawClick").bind("click",function(event){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#widthdraw').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        dataType: "json",
        url: '/index/widthdraw_agent',
        data: params,
        success: function(data) {
            if(data.status){
                $("#error").text(data.msg);
                $("#error").popup();
                $("#error").popup("open", { "positionTo" : "window", "transition" : "flip" });
                $(".now_Balance").html(data.balance);
                $(".extractable").html(data.rangeMone);
                $("#widthdrawAmout").val('');
            }else{
                $("#error").text(data.msg);
                $("#error").popup();
                $("#error").popup("open", { "positionTo" : "window", "transition" : "flip" });
            }
        }
    });
});
</script>
@extends('layouts.bottom')
