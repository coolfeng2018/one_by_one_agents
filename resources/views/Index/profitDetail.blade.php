@extends('layouts.header')
<link rel="stylesheet" href="/css/button.css">
<script src="/js/clipboard.min.js"></script>
<section data-role="page" id="pgAgentList">
    <header data-role="header" data-position="fixed">
        <a href="/index/index" data-icon="back" target="_top">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">收益明细-代理</b></h1>
    </header>
    <div class="article"></div>
    <div class="article"></div>
    <div class="article"></div>
    <a href="#pgSearchList" data-theme="b" data-role="button">搜索</a>
    <div data-role="main" class="ui-content">
      <table data-role="table"  data-mode="ui-responsive" class="ui-responsive ui-shadow boder" id="myTable">
        <thead>
          <tr>
            <th>今日收益</th>
            <th>下级代理</th>
            <th>会员</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $commissionAtToday }}</td>
            <td>{{ $commissionAgentsAtToday }}</td>
            <td>{{ $commissionPlayerAtToday }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div data-role="navbar">
      <ul>
        <li><a href="#" data-icon="grid" data-transition="none" class="ui-btn-active ui-state-persist">下级代理收益</a></li>
        <li><a href="#pgPlayerList" data-icon="user" data-transition="none">会员收益</a></li>
      </ul>
    </div>
    <div id="agent">
      @if($commissionAgentData)
        @foreach($commissionAgentData as $v)
          <article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;">
              <div>
                  <div class="article">
                    <div class="start">代理ID：{{$v->agent_id}}</div>
                  </div>
                  <div class="article">
                    <div class="start">游戏ID：{{$v->uid}}</div>
                  </div>
                  <div class="article">
                    <div class="start">昵称：{{$v->Nickname}}</div>
                    <div class="center"></div>
                  </div>
                  <div class="article">
                    <div class="start">税收时间：</div>
                    <div class="end"><?php echo date('Y-m-d H:i:s', $v->c_time);?></div>
                  </div>
                  <div class="article">
                    <div class="start">游戏场次：</div>
                    <div class="end">{{$v->table_name}}</div>
                  </div>
                  <div class="article">
                    <div class="start">游戏流水：</div>
                    <div class="end">{{$v->money/1000}}</div>
                  </div>
                  <div class="article">
                    <div class="start">收益：</div>
                    <div class="end">{{$v->bonus/1000}}元</div>
                  </div>
              </div>
          </article>
        @endforeach
      @endif
    </div>
    <div class="dropload-down agent-more"><div class="dropload-noData"><a>加载更多</a></div></div>
    <div class="dropload-down agent-no-data" style="display: none"><div class="dropload-noData"><a>暂无数据</a></div></div>
</section>

<section data-role="page" id="pgPlayerList">
    <header data-role="header" data-position="fixed">
        <a href="/index/index" data-icon="back" target="_top">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">收益明细-会员</b></h1>
    </header>
    <div class="article"></div>
    <div class="article"></div>
    <div class="article"></div>
    <a href="#pgSearchList" data-theme="b" data-role="button" target="_top">搜索</a>
    <div data-role="main" class="ui-content">
      <table data-role="table"  data-mode="ui-responsive" class="ui-responsive ui-shadow boder" id="myTable">
        <thead>
          <tr>
            <th>今日收益</th>
            <th>下级代理</th>
            <th>会员</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $commissionAtToday }}</td>
            <td>{{ $commissionAgentsAtToday }}</td>
            <td>{{ $commissionPlayerAtToday }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div data-role="navbar">
      <ul>
        <li><a href="#pgAgentList" data-icon="grid" data-transition="none">下级代理收益</a></li>
        <li><a href="#" data-icon="user" data-transition="none" class="ui-btn-active ui-state-persist">会员收益</a></li>
      </ul>
    </div>
    <div id="player">
      @if($commissionPlayerData)
        @foreach($commissionPlayerData as $v)
          <article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;">
              <div>
                  <div class="article">
                    <div class="start">游戏ID：{{$v->uid}}</div>
                  </div>
                  <div class="article">
                    <div class="start">昵称：{{$v->Nickname}}</div>
                    <div class="center"></div>
                  </div>
                  <div class="article">
                    <div class="start">税收时间：</div>
                    <div class="end"><?php echo date('Y-m-d H:i:s', $v->c_time);?></div>
                  </div>
                  <div class="article">
                    <div class="start">游戏场次：</div>
                    <div class="end">{{$v->table_name}}</div>
                  </div>
                  <div class="article">
                    <div class="start">游戏流水：</div>
                    <div class="end">{{$v->money/1000}}</div>
                  </div>
                  <div class="article">
                    <div class="start">收益：</div>
                    <div class="end">{{$v->bonus/1000}}元</div>
                  </div>
              </div>
          </article>
        @endforeach
      @endif
    </div>
    <div class="dropload-down player-more"><div class="dropload-noData"><a>加载更多</a></div></div>
    <div class="dropload-down player-no-data" style="display: none"><div class="dropload-noData"><a>暂无数据</a></div></div>
</section>

<section data-role="page" id="pgSearchList">
    <header data-role="header" data-position="fixed" data-add-back-btn="true" data-back-btn-text="返回">
        <h1><b style="font-weight:bold;font-size: 1.2rem">收益明细</b></h1>
    </header>
    <!--搜索面板start-->
    <div id="searchPanel"> 
        <div data-role="main" class="ui-content">
        <form id="editProfile">
          <div class="ui-field-contain">
            <label for="UserId">玩家ID：</label>
            <input type="text" name="UserId" id="UserId" value=""> 
            <label for="startTime">开始时间：</label>
            <input type="text" name="startTime" id="startTime" value=""> 
            <label for="endTime">结束时间：</label>
            <input type="text" name="endTime" id="endTime" value="">     
          </div>
          <a id="searchPanelClick" href="#pgAgentList" data-theme="b" data-role="button">搜索</a>
          <div data-role="popup" class="ui-content" id="error"></div>
        </form>
      </div>
    </div>
    <!--搜索面板end-->
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

<!-- Mobiscroll JS and CSS Includes -->
    <script src="/jquery_mobile/mobiscroll/js/mobiscroll.core.js"></script>
    <script src="/jquery_mobile/mobiscroll/js/mobiscroll.frame.js"></script>
    <script src="/jquery_mobile/mobiscroll/js/mobiscroll.scroller.js"></script>
    <script src="/jquery_mobile/mobiscroll/js/mobiscroll.util.datetime.js"></script>
    <script src="/jquery_mobile/mobiscroll/js/mobiscroll.datetimebase.js"></script>
    <script src="/jquery_mobile/mobiscroll/js/mobiscroll.datetime.js"></script>
    <script src="/jquery_mobile/mobiscroll/js/mobiscroll.android-holo-light.js"></script>
    <script src="/jquery_mobile/mobiscroll/js/i18n/mobiscroll.i18n.zh.js"></script>

    <link href="/jquery_mobile/mobiscroll/css/mobiscroll.frame.css" rel="stylesheet" type="text/css" />
    <link href="/jquery_mobile/mobiscroll/css/mobiscroll.frame.android-holo.css" rel="stylesheet" type="text/css" />
    <link href="/jquery_mobile/mobiscroll/css/mobiscroll.scroller.css" rel="stylesheet" type="text/css" />
    <link href="/jquery_mobile/mobiscroll/css/mobiscroll.scroller.android-holo.css" rel="stylesheet" type="text/css" />
    <link href="/jquery_mobile/mobiscroll/css/mobiscroll.android-holo-light.css" rel="stylesheet" type="text/css" />
    <script>
         $('#startTime,#endTime').mobiscroll().date({
              theme: 'android-holo-light',     // Specify theme like: theme: 'ios' or omit setting to use default 
              mode: 'mixed',       // Specify scroller mode like: mode: 'mixed' or omit setting to use default 
              display: 'modal', // Specify display mode like: display: 'bottom' or omit setting to use default 
              dateFormat: 'yyyy-mm-dd',
              lang: 'zh'        // Specify language like: lang: 'pl' or omit setting to use default 
          }); 
    </script>
<!-- Mobiscroll JS and CSS Includes -->
<script type="text/javascript"> 
$("#searchPanelClick").bind("click",function(){
  page = 2;
  pagePlayer = 2;
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#editProfile').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        dataType: "json",
        url: '/index/profitdetail?search=1',
        data: params,
        success: function(data) {
            if(data.status){
              console.log(data.result);
              console.log(data.result.queryAgent);
              var ahtml = '';
              if(data.result.queryAgent.length!=0){
                  jQuery.each(data.result.queryAgent, function(i, val) {  
                    ahtml += '<article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;">';
                    ahtml += '<div><div class="article"><div class="start">';
                    ahtml += '代理ID：'+val.agent_id;
                    ahtml += '</div></div><div class="article"><div class="start">';
                    ahtml += '游戏ID：'+val.uid;
                    ahtml += '</div></div><div class="article"><div class="start">';
                    ahtml += '昵称：'+val.Nickname;
                    ahtml += '</div><div class="center"></div></div><div class="article"><div class="start">';
                    ahtml += '税收时间：';
                    ahtml += '</div><div class="end">';
                    ahtml += val.c_time;
                    ahtml += '</div></div><div class="article"><div class="start">游戏场次：</div><div class="end">';
                    ahtml += val.table_name;
                    ahtml += '</div></div><div class="article"><div class="start">游戏流水：</div><div class="end">';
                    ahtml += val.money/1000;
                    ahtml += '</div></div><div class="article"><div class="start">收益：</div><div class="end">';
                    ahtml += val.bonus/1000;
                    ahtml += '元</div></div></div></article>';
                    console.log(val);
                  });  
              }
              var phtml = '';
              if(data.result.queryPlayer.length!=0){
                jQuery.each(data.result.queryPlayer, function(i, val) {  
                    phtml += '<article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;"><div><div class="article"><div class="start">游戏ID：';
                    phtml += val.uid;
                    phtml += '</div></div><div class="article"><div class="start">昵称：'+val.Nickname;
                    phtml += '</div><div class="center"></div></div><div class="article"><div class="start">税收时间：</div><div class="end">';
                    phtml += val.c_time;
                    phtml += '</div></div><div class="article"><div class="start">游戏场次：</div><div class="end">';
                    phtml += val.table_name;
                    phtml += '</div></div><div class="article"><div class="start">游戏流水：</div><div class="end">';
                    phtml += val.money/1000;
                    phtml += '</div></div><div class="article"><div class="start">收益：</div><div class="end">';
                    phtml += val.bonus/1000;
                    phtml += '元</div></div></div></article>';
                  });  
              }
                $("#player").html(phtml);
                $("#agent").html(ahtml);

                $(".agent-no-data").hide();
                $(".agent-more").show();

                $(".player-no-data").hide();
                $(".player-more").show();
            }else{
                $("#error").text(data.msg);
                $("#error").popup();
                $("#error").popup("open", { "positionTo" : "window", "transition" : "flip" });
            }
        }
    });
});


</script>


<!--异步加载-->
<script type="text/javascript">
  var page = 2;//代理数据页数
  var pagePlayer = 2;//会员数据页数
  $(".agent-more").bind('click',function(){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#editProfile').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        dataType: "json",
        url: '/index/profitdetail?search='+page,
        data: params,
        success: function(data) {
            if(data.status){
              console.log(data.result);
              console.log(data.result.queryAgent);
              var ahtml = '';
              if(data.result.queryAgent.length!=0){
                  jQuery.each(data.result.queryAgent, function(i, val) {  
                    ahtml += '<article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;">';
                    ahtml += '<div><div class="article"><div class="start">';
                    ahtml += '代理ID：'+val.agent_id;
                    ahtml += '</div></div><div class="article"><div class="start">';
                    ahtml += '游戏ID：'+val.uid;
                    ahtml += '</div></div><div class="article"><div class="start">';
                    ahtml += '昵称：'+val.Nickname;
                    ahtml += '</div><div class="center"></div></div><div class="article"><div class="start">';
                    ahtml += '税收时间：';
                    ahtml += '</div><div class="end">';
                    ahtml += val.c_time;
                    ahtml += '</div></div><div class="article"><div class="start">游戏场次：</div><div class="end">';
                    ahtml += val.table_name;
                    ahtml += '</div></div><div class="article"><div class="start">游戏流水：</div><div class="end">';
                    ahtml += val.money/1000;
                    ahtml += '</div></div><div class="article"><div class="start">收益：</div><div class="end">';
                    ahtml += val.bonus/1000;
                    ahtml += '元</div></div></div></article>';
                    console.log(val);
                  });  
                page++;
              }else{
                $(".agent-no-data").show();
                $(".agent-more").hide();
              }
              $("#agent").append(ahtml);
            }else{
              $("#error").text(data.msg);
              $("#error").popup();
              $("#error").popup("open", { "positionTo" : "window", "transition" : "flip" });
            }
        }
    });
  });


  //会员
  $(".player-more").bind('click',function(){
    var params = $.param({'_token': '{{csrf_token()}}'}) + '&' + $('#editProfile').serialize();
    console.log(params);
    $.ajax({
        type: 'post',
        dataType: "json",
        url: '/index/profitdetail?search='+pagePlayer,
        data: params,
        success: function(data) {
            if(data.status){
              console.log(data.result);
              console.log(data.result.queryPlayer);
              var phtml = '';
              if(data.result.queryPlayer.length!=0){
                jQuery.each(data.result.queryPlayer, function(i, val) {  
                    phtml += '<article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;"><div><div class="article"><div class="start">游戏ID：';
                    phtml += val.uid;
                    phtml += '</div></div><div class="article"><div class="start">昵称：'+val.Nickname;
                    phtml += '</div><div class="center"></div></div><div class="article"><div class="start">税收时间：</div><div class="end">';
                    phtml += val.c_time;
                    phtml += '</div></div><div class="article"><div class="start">游戏场次：</div><div class="end">';
                    phtml += val.table_name;
                    phtml += '</div></div><div class="article"><div class="start">游戏流水：</div><div class="end">';
                    phtml += val.money/1000;
                    phtml += '</div></div><div class="article"><div class="start">收益：</div><div class="end">';
                    phtml += val.bonus/1000;
                    phtml += '元</div></div></div></article>';
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
