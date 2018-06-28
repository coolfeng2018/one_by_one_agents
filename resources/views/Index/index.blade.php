@extends('layouts.header')
<link rel="stylesheet" href="/css/button.css">
<script src="/js/clipboard.min.js"></script>
<section data-role="page" id="pgIndex">
    <header data-role="header" data-position="fixed" data-add-back-btn="false">
        <h1><b style="font-weight:bold;font-size: 1.2rem">代理中心</b></h1>
    </header>
    <article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;">
        <div>
            <div class="article">
              <div class="start">{{$info['AgentName']}}</div>
              <div class="center"></div>
            </div>
            <div class="article">
              <div class="start">游戏ID：{{$info['UserId']}}</div>
              <div class="center"><a href="#pgEditSomeBodyAbout" class="button blue font-color" data-transition="flip">修改个人信息</a></div>
            </div>
            <div class="article">
              <div class="start">代理ID：{{$info['AgentId']}} <a href="#" data-role="button" data-inline="true" data-mini="true" data-clipboard-text="{{$info['AgentId']}}" class="btn" >复制</a></div>
              <div class="end"></div>
            </div>
            <div class="article">
              <div class="start">手机号：</div>
              <div class="end">{{$info['Telephone']}}</div>
            </div>
        </div>
    </article>
    <article class="ui-content">
        <div>
            <div class="article">
                <div class="start left change">
                    <div>今日收益</div>
                    <div style='padding: 10px 0 10px 0;'>{{ $commissionAtToday }}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
                <div class="center right change">
                    <div>累计收益</div>
                    <div style='padding: 10px 0 10px 0;'>{{ $commission }}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
            </div>
            <div class="article">
                <div class="start left change">
                    <div>今日新增会员</div>
                    <div style='padding: 10px 0 10px 0;'>{{$newUserAtToday}}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
                <div class="center right change">
                    <div>总会员数</div>
                    <div style='padding: 10px 0 10px 0;'>{{$userCount}}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
            </div>
            <div class="article">
                <div class="start left change">
                    <div>今日新增代理</div>
                    <div style='padding: 10px 0 10px 0;'>{{$newAgentCountAtToday}}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
                <div class="center right change">
                    <div>总代理数</div>
                    <div style='padding: 10px 0 10px 0;'>{{$agentCount}}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
            </div>
        </div>
    </article>

    <div data-role="main" class="ui-content">
        <ul data-role="listview">
          <li><a href="/index/widthdraw" target="_top">申请提现</a></li>
          <!-- <li><a href="/index/profitdetail" target="_top">收益明细</a></li> -->
          <li><a href="/index/subordinates" target="_top">我的下级</a></li>
          <li><a href="#changPwd">修改密码</a></li>
          <li><a href="#invitationLink">专属邀请链接</a></li>
        </ul>
    </div>


    <a href="/admin/out" data-theme="b" data-role="button" target="_top">退出</a>
</section>

<section data-role="page" id="pgEditSomeBodyAbout">
    <header data-role="header" data-position="fixed" data-add-back-btn="true" data-back-btn-text="返回">
        <a href="#pgIndex" data-icon="back" data-transition="flip">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">修改个人资料</b></h1>
    </header>
        <div data-role="main" class="ui-content">
        <ul data-role="listview">
          <li><a href="#EditPersonalData" data-transition="flip">修改个人资料</a></li>
          <li><a href="#EditBank" data-transition="flip">修改提现信息</a></li>
        </ul>
    </div>
</section>

<section data-role="page" id="EditPersonalData">
    <header data-role="header" data-position="fixed" data-add-back-btn="true" data-back-btn-text="返回">
        <h1><b style="font-weight:bold;font-size: 1.2rem">修改个人资料</b></h1>
    </header>
        <div data-role="main" class="ui-content">
        <form id="editProfile">
          <div class="ui-field-contain">
            <label for="RealName">真实姓名：</label>
            <input type="text" name="RealName" id="RealName" value="{{$info['RealName']}}"> 
            <label for="WechatCode">微信号：</label>
            <input type="text" name="WechatCode" id="WechatCode" value="{{$info['WechatCode']}}">
            <label for="QQCode">QQ号：</label>
            <input type="text" name="QQCode" id="QQCode" value="{{$info['QQCode']}}"> 
            <label for="province">省份：</label>
            <input type="text" name="province" id="province" value="{{$info['province']}}"> 
            <label for="city">城市：</label>
            <input type="text" name="city" id="city" value="{{$info['city']}}"> 
            <label for="area">地区：</label>
            <input type="text" name="area" id="area" value="{{$info['area']}}"> 
            <label for="email">邮箱：</label>
            <input type="email" name="Email" id="Email" value="{{$info['Email']}}">       
          </div>
          <input type="button" data-theme="b" data-role="button"  value="提交" id="editProfileClick">
        </form>
      </div>
</section>

<section data-role="page" id="EditBank">
    <header data-role="header" data-position="fixed">
        <a href="#pgEditSomeBodyAbout" data-icon="back" data-transition="flip">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">修改提现信息</b></h1>
    </header>
        <div data-role="navbar">
          <ul>
            <li><a href="#" class="ui-btn-active ui-state-persist" data-icon="home" data-transition="none">银行卡</a></li>
            <li><a href="#alipay" data-icon="arrow-r" data-transition="none">支付宝</a></li>
            <li><a href="#wechat" data-icon="arrow-r" data-transition="none">微信</a></li>
          </ul>
        </div>
        <div data-role="main" class="ui-content">
        <form id="EditBankForm">
            <label for="CreditCode">银行卡号：</label>
            <input type="text" name="CreditCode" id="CreditCode" value="{{ $info['CreditCode'] }}"> 
            <label for="CreditBank">银行：</label>
            <input type="text" name="CreditBank" id="CreditBank" value="{{ $info['CreditBank'] }}">
            <label for="CreditName">姓名：</label>
            <input type="text" name="CreditName" id="CreditName" value="{{ $info['CreditName'] }}">
        </form>
        </div>
        <a data-theme="b" data-role="button" target="_top" id="EditBankFormClick">保存</a>
</section>

<section data-role="page" id="alipay">
    <header data-role="header" data-position="fixed">
        <a href="#pgEditSomeBodyAbout" data-icon="back" data-transition="flip">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">修改提现信息</b></h1>
    </header>
        <div data-role="navbar">
          <ul>
            <li><a href="#EditBank" data-icon="home" data-transition="none">银行卡</a></li>
            <li><a href="#alipay" class="ui-btn-active ui-state-persist" data-icon="arrow-r" data-transition="none">支付宝</a></li>
            <li><a href="#wechat" data-icon="arrow-r" data-transition="none">微信</a></li>
          </ul>
        </div>
        <div data-role="main" class="ui-content">
        <form id="EditAlipay">
            <label for="AlipayCode">支付宝账号：</label>
            <input type="text" name="AlipayCode" id="AlipayCode" value="{{ $info['AlipayCode'] }}"> 
            <label for="AlipayName">姓名：</label>
            <input type="text" name="AlipayName" id="AlipayName" value="{{ $info['AlipayName'] }}">
        </form>
        </div>
        <a data-theme="b" data-role="button" target="_top" id="EditAlipayClick">保存</a>
</section>

<section data-role="page" id="wechat">
    <header data-role="header" data-position="fixed">
        <a href="#pgEditSomeBodyAbout" data-icon="back" data-transition="flip">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">修改提现信息</b></h1>
    </header>
        <div data-role="navbar">
          <ul>
            <li><a href="#EditBank" data-icon="home" data-transition="none">银行卡</a></li>
            <li><a href="#alipay" data-icon="arrow-r" data-transition="none">支付宝</a></li>
            <li><a href="#wechat" data-icon="arrow-r" data-transition="none" class="ui-btn-active ui-state-persist">微信</a></li>
          </ul>
        </div>
        <div data-role="main" class="ui-content">
        <form id="EditWechat">
            <label for="WithdrawWechatCode">微信账号：</label>
            <input type="text" name="WithdrawWechatCode" id="WithdrawWechatCode" value="{{ $info['WithdrawWechatCode'] }}">
        </form>
        </div>
        <a data-theme="b" data-role="button" target="_top" id="EditWechatClick">保存</a>
</section>


<section data-role="page" id="changPwd">
    <header data-role="header" data-position="fixed" data-add-back-btn="true" data-back-btn-text="返回">
        <h1><b style="font-weight:bold;font-size: 1.2rem">修改密码</b></h1>
    </header>
        <div data-role="navbar">
        </div>
        <div data-role="main" class="ui-content">
            <form id="chang_pwd">
                 <!-- {{ csrf_field() }} -->
                <label for="password">旧密码：</label>
                <input type="password" name="pwdOld" id="pwdOld" placeholder="请输入旧密码">
                <label for="password">新密码：</label>
                <input type="password" name="newPwd" id="newPwd" placeholder="请输入新密码">
                <label for="password">确认新密码：</label>
                <input type="password" name="confirmPwd" id="confirmPwd" placeholder="确认新密码">
                <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
            </form>
        </div>
        <a id="chang_pwd_submit" data-theme="b" data-role="button" target="_top">保存</a>
        <div data-role="popup" class="ui-content" id="error"></div>
</section>


<section data-role="page" id="invitationLink">
    <header data-role="header" data-position="fixed" data-add-back-btn="true" data-back-btn-text="返回">
        <h1><b style="font-weight:bold;font-size: 1.2rem">专属链接</b></h1>
    </header>
        <div data-role="navbar">
        </div>
        <div data-role="main" class="ui-content">
            <div class="article">
              <div style="text-align:center;"> {!! QrCode::size(200)->generate($info['agent_url']); !!} </div>
              <div style="text-align:center;">我的推广码</div>
              <div style="text-align:center;">{{$info['AgentId']}}</div>
              <div style="text-align:center;"><a href="#" data-role="button" data-inline="true" data-mini="true" data-clipboard-text="{{$info['AgentId']}}" class="btn" >复制推广码</a></div>
            </div>
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
    padding-left:30px;
}
.right{
    padding-right:30px;
}
.change{
    /*padding : 0;*/
    text-align: center !important;
    text-decoration: none !important;
    font: !important normal 10px Arial, sans-serif;
    font-size:14px;
}

.ui-btn-x{
    /* 按钮ui */
    background-color: red !important;
    color: #f2f2f2 !important;
}
.ui-btn-g {
    background-color: red !important;
    /*color: #f2f2f2 !important;*/
}
</style>


<script src="/jquery_mobile/scripts/jquery.min.js" type="text/javascript"></script>
<script>
var clipboard = new ClipboardJS('.btn');

clipboard.on('success', function(e) {
    alert('复制成功');

    e.clearSelection();
});

clipboard.on('error', function(e) {
    alert('复制失败,请您手动复制推广码.');
});

//修改密码
$("#chang_pwd_submit").bind("click",function(event){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#chang_pwd').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        url: '/index/change_pwd',
        data: params,
        success: function(data) {
            if(data.status){
                $("#error").text(data.msg);
                $("#error").popup();
                $("#error").popup("open", { "positionTo" : "window", "transition" : "flip" });
                //清空
                $(':input','#chang_pwd') 
                    .not(':button, :submit, :reset, :hidden') 
                    .val('') 
                    .removeAttr('checked') 
                    .removeAttr('selected');
            }else{
                $("#error").text(data.msg);
                $("#error").popup();
                $("#error").popup("open", { "positionTo" : "window", "transition" : "flip" });
            }
        }
    });
});

//修改个人资料
$("#editProfileClick").bind("click",function(event){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#editProfile').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        url: '/index/profile',
        data: params,
        success: function(data) {
            if(data.status){
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

//修改提现信息-银行卡
$("#EditBankFormClick").bind("click",function(event){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#EditBankForm').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        url: '/index/widthdrawUp',
        data: params,
        success: function(data) {
            if(data.status){
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

//修改提现信息-支付宝
$("#EditAlipayClick").bind("click",function(event){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#EditAlipay').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        url: '/index/widthdrawUp',
        data: params,
        success: function(data) {
            if(data.status){
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
//修改提现信息-微信
$("#EditWechatClick").bind("click",function(event){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#EditWechat').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        url: '/index/widthdrawUp',
        data: params,
        success: function(data) {
            if(data.status){
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
