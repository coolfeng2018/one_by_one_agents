@extends('layouts.header')
<link rel="stylesheet" href="/css/button.css">
<script src="/js/clipboard.min.js"></script>
<section data-role="page" id="pgPlayerList">
    <header data-role="header" data-position="fixed">
        <a href="/index/subordinates" data-icon="back" target="_top">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">代理收益明细</b></h1>
    </header>
    <article role="main" class="ui-content">
        <div>
            <div class="article">
              <div class="start">游戏ID：{{$agents->UserId}}</div>
              <div class="center">用户昵称：{{$agents->Nickname}}</div>
            </div>
            <div class="article">
              <div class="start">代理ID：{{$agents->AgentId}}</div>
              <div class="center">分成比例：{{$agents->Ratio}}%</div>
            </div>
            <div class="article">
              <div class="start">累计收益：{{$agents->income}}</div>
              <div class="center"></div>
            </div>
            <div class="article">
              <div class="start">绑定时间：</div>
              <div class="end">{{$agents->CreateAt}}</div>
            </div>
        </div>
    </article>
    <div id="player">
       @if($commissionAgentData)
        @foreach($commissionAgentData as $v)
          <article role="main" class="ui-content data-back">
              <div>
                  <div class="article">
                    <div class="start"><b>时间：</b></div>
                    <div class="end"><b>{{$v->c_time}}</b></div>
                  </div>
                  <div class="article">
                    <div class="start"><b>当日收益：</b></div>
                    <div class="end"><b>{{$v->bonus/1000}}元</b></div>
                  </div>
              </div>
          </article>
        @endforeach
      @endif
    </div>
    <div class="dropload-down player-more"><div class="dropload-noData"><a>加载更多</a></div></div>
    <div class="dropload-down player-no-data" style="display: none"><div class="dropload-noData"><a>暂无数据</a></div></div>
</section>


<style type="text/css">
.data-back{
  margin-top: 5px;
  background-color: rgba(242, 242, 242, 1);
}
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

.dropload-down {
    height: 50px;
}

.dropload-noData {
    height: 50px;
    line-height: 50px;
    text-align: center;
}

</style>

<script src="/jquery_mobile/scripts/jquery.min.js" type="text/javascript"></script>


<!--异步加载-->
<script type="text/javascript">
  var pagePlayer = 2;//代理数据页数
  //代理
  $(".player-more").bind('click',function(){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#editProfile').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        dataType: "json",
        url: '/index/agentsDetail?AgentId={{$agents->AgentId}}&search='+pagePlayer,
        data: params,
        success: function(data) {
            if(data.status){
              console.log(data.result);
              console.log(data.result.queryAgent);
              var phtml = '';
              if(data.result.queryAgent.length!=0){
                jQuery.each(data.result.queryAgent, function(i, val) {  
                    phtml += '<article role="main" class="ui-content data-back"><div><div class="article"><div class="start"><b>时间：</b></div><div class="end"><b>';
                    phtml += val.c_time;
                    phtml += '</b></div></div><div class="article"><div class="start"><b>当日收益：</b></div><div class="end"><b>';
                    phtml += val.bonus/1000;
                    phtml += '元</b></div></div></div></article>';

                  });  
                pagePlayer++;
              }else{
                $(".player-no-data").show();
                $(".player-more").hide();
              }
              $("#player").append(phtml);
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
