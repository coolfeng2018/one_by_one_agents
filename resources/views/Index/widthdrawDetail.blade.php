@extends('layouts.header')
<link rel="stylesheet" href="/css/button.css">
<link rel="stylesheet" href="/dropload/dist/dropload.css">
<script src="/js/clipboard.min.js"></script>
<section data-role="page" id="pgAgentList">
    <header data-role="header" data-position="fixed">
        <a href="/index/widthdraw" data-icon="back" target="_top">返回</a>
        <h1><b style="font-weight:bold;font-size: 1.2rem">提现记录</b></h1>
    </header>
    <div class="article"></div>
    <div class="article"></div>
    <div class="article"></div>
    @foreach ($result as $v)
      <article role="main" class="ui-content bboder">
          <div>
              <div class="article">
                <div class="start">提现金额：￥{{ $v->Amount }}</div>
              </div>
              <div class="article">
                <div class="start">提现渠道：@if($v->WithdrawChannel==0) 银行 @elseif($v->WithdrawChannel==1) 支付宝 @else 微信 @endif</div>
                <div class="center">
                    @if($v->Status==0) 
                    <a href="#pgEditSomeBodyAbout" class="button blue font-color" data-transition="flip">
                      审核中 
                    </a>
                    @elseif($v->Status==1) 
                    <a href="#pgEditSomeBodyAbout" class="button green font-color" data-transition="flip">
                      已提现 
                    </a>
                    @else 
                    <a href="#pgEditSomeBodyAbout" class="button red font-color" data-transition="flip">
                      已拒绝 
                    </a>
                    @endif
                </div>
              </div>
              <div class="article">
                <div class="start">提现时间：</div>
                <div class="end">{{ $v->CreateAt }}</div>
              </div>
          </div>
      </article>
    @endforeach
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
// $('#pgAgentList').dropload({
//   scrollArea : window,
//   loadDownFn : function(me){
//       $.ajax({
//           type: 'GET',
//           url: '/index/widthdrawdetail?page=2&paging=1',
//           dataType: 'json',
//           success: function(data){
//               alert(data);
//               console.log(data);

//               var redBagLeftList = '<article role="main" class="ui-content bboder"><div>123321</div></article>';  
//               $("#pgAgentList").append(redBagLeftList); 

//               // 每次数据加载完，必须重置
//               // me.resetload();
//           },
//           error: function(xhr, type){
//               alert('Ajax error!');
//               // 即使加载出错，也得重置
//               // me.resetload();
//           }
//       });
//   }
// });
</script>
@extends('layouts.bottom')
