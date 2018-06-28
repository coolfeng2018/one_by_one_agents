@extends('layouts.header')
<link rel="stylesheet" href="/css/button.css">
<link rel="stylesheet" href="/dropload/dist/dropload.css">
<script src="/js/clipboard.min.js"></script>
<section data-role="page" id="pgAgentList">
    <header data-role="header" data-position="fixed">
        <a href="/index/subordinates" data-icon="back" target="_top">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">我的下级</b></h1>
    </header>
    <div class="article"></div>
    <div class="article"></div>
    <div class="article"></div>
    <div data-role="main" class="ui-content">
      <form id="createAgent">
          <label for="AgentName">代理昵称：</label>
          <input type="text" name="AgentName" id="AgentName">
          <label for="Telephone">手机号码：</label>
          <input type="text" name="Telephone" id="Telephone">
          <label for="Password">密码：</label>
          <input type="password" name="Password" id="Password">
          <label for="Ratio">分成比例：</label>
          <input type="text" name="Ratio" id="Ratio">
          <input type="hidden" name="UserId" id="UserId" value="{{ $userId }}">
          <a  data-theme="b" data-role="button" id="createAgentClick">保存设置</a>
          <div data-role="popup" class="ui-content" id="error"></div>
      </form>
    </div>

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

.bboder {
  border-bottom:solid 1px #beb7b7 !important;
}
</style>
<script src="/jquery_mobile/scripts/jquery.min.js" type="text/javascript"></script>
<script src="/dropload/dist/dropload.min.js"></script>
<script>
$("#createAgentClick").bind("click",function(event){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#createAgent').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        dataType: "json",
        url: '/index/create_agents',
        data: params,
        success: function(data) {
            if(data.status){
              $(location).attr('href', '/index/subordinates');
              // /index/subordinates
                $("#error").text(data.msg);
                $("#error").popup();
                $("#error").popup("open", { "positionTo" : "window", "transition" : "flip" });
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
