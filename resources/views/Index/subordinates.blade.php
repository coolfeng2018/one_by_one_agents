@extends('layouts.header')
<link rel="stylesheet" href="/css/button.css">
<script src="/js/clipboard.min.js"></script>
<section data-role="page" id="pgAgentList">
    <header data-role="header" data-position="fixed">
        <a href="/index/index" data-icon="back" target="_top">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">代理列表</b></h1>
    </header>
    <article class="ui-content">
        <div>
            <div class="article">
                <div class="start left change">
                    <div>下级代理人数</div>
                    <div style='padding: 10px 0 10px 0;'>{{ $agentsCount }}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
                <div class="center right change">
                    <div>下级会员人数</div>
                    <div style='padding: 10px 0 10px 0;'>{{ $playerCount }}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
            </div>
            <div class="article">
                <div class="left change">
                    <div>我的分成比例</div>
                    <div style='padding: 10px 0 10px 0;'>{{ $info['Ratio'] }}%</div>
                    <div style='width: 100%;border-bottom:solid 1px #beb7b7;'></div>
                </div>
            </div>
            <div data-role="navbar">
              <ul>
                <li><a href="#" data-icon="user" data-transition="none" class="ui-btn-active ui-state-persist">代理列表</a></li>
                <li><a href="#pgPlayerList" data-icon="user" data-transition="none">会员列表</a></li>
              </ul>
            </div>
            <div data-role="main" class="ui-content">
            @if($agents)
                @foreach ($agents as $v)
                      <article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;">
                          <p>游戏ID：{{ $v->UserId }}</p>
                          <p>代理ID：{{ $v->AgentId }}</p>
                          <p>绑定时间：{{ $v->CreateAt }}</p>
                          <p>昵称：{{ $v->Nickname }}</p>
                          <p>分成比例：{{ $v->Ratio }}%<div class="center">
                                <a href="/index/agentsDetail?AgentId={{ $v->AgentId }}" target="_top" class="button blue font-color" data-transition="flip" style="height: 30px;line-height: 30px;">收益明细</a>
                            </div></p>
                          <p>
                            累计收益：{{ $v->income }}
                          </p>
                      </article>
                @endforeach
            @endif
            </div>
        </div>
    </article>
</section>

<section data-role="page" id="pgPlayerList">
    <header data-role="header" data-position="fixed">
        <a href="/index/index" data-icon="back" target="_top">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">会员列表</b></h1>
    </header>
    <article class="ui-content">
        <div>
            <div class="article">
                <div class="start left change">
                    <div>下级代理人数</div>
                    <div style='padding: 10px 0 10px 0;'>{{ $agentsCount }}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
                <div class="center right change">
                    <div>下级会员人数</div>
                    <div style='padding: 10px 0 10px 0;'>{{ $playerCount }}</div>
                    <div style='width: 100px;border-bottom:solid 1px #beb7b7;'></div>
                </div>
            </div>
            <div class="article">
                <div class="left change">
                    <div>我的分成比例</div>
                    <div style='padding: 10px 0 10px 0;'>{{ $info['Ratio'] }}%</div>
                    <div style='width: 100%;border-bottom:solid 1px #beb7b7;'></div>
                </div>
            </div>
            <div data-role="navbar">
              <ul>
                <li><a href="#pgAgentList" data-icon="user" data-transition="none">代理列表</a></li>
                <li><a href="#" data-icon="user" data-transition="none" class="ui-btn-active ui-state-persist">会员列表</a></li>
              </ul>
            </div>
            <div data-role="main" class="ui-content">
              @foreach ($players as $v)
                <article role="main" class="ui-content" style=" border-bottom:solid 1px #beb7b7;">
                    <p>游戏ID：{{ $v->UserId }}</p>
                    <!-- <p>代理ID：{{ $v->AgentId }}</p> -->
                    <p>绑定时间：{{ $v->CreateTime }}</p>
                    <p>昵称： {{ $v->Nickname }} @if($v->isAgent==true) (<b style="color:red;">代理id：{{$v->agents}}</b>) @endif<div class="center">
                        <a href="/index/playerDetail?userId={{ $v->UserId }}" target="_top" class="button blue font-color" data-transition="flip" style="height: 30px;line-height: 30px;">收益明细</a>
                        @if($v->isAgent==false)
                            <a href="/index/add_agent?userId={{ $v->UserId }}" target="_top" class="button blue font-color" data-transition="flip" style="height: 30px;line-height: 30px;">升级代理</a>
                        @endif
                    </div>
                </p>
                    <p>累计收益：{{ $v->income }}</p>
                </article>
              @endforeach
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
.boder th{
    border-bottom: 1px solid #d6d6d6;
}
.boder tr:nth-child(even) {
    background: #e9e9e9;
}

</style>


<script>
var clipboard = new ClipboardJS('.btn');

clipboard.on('success', function(e) {
    alert('复制推广码ok');

    e.clearSelection();
});

clipboard.on('error', function(e) {
    alert('复制失败,请您手动复制推广码.');
});
</script>
@extends('layouts.bottom')
