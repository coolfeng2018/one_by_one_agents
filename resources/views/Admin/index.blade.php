<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>管理平台</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="管理平台 " name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/hsgm/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/hsgm/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/hsgm/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/hsgm/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/hsgm/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="/hsgm/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="/hsgm/pages/css/login-4.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <link rel="shortcut icon" href="favicon.ico" /> </head>
<!-- END HEAD -->

<body class=" login">
<div class="logo">
</div>
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" action="#" method="post">

        <h3 class="form-title">管理平台</h3>
        {{ csrf_field() }}
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" id="username" /> </div>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" id="password" /> </div>
        </div>

        <div class="form-actions">
            <button type="button" id="login" class="btn green pull-right"> Login </button>
        </div>
    </form>
    <!-- END LOGIN FORM -->
</div>
<!-- END LOGIN -->
</body>


<script src="/hsgm/global/plugins/respond.min.js"></script>
<script src="/hsgm/global/plugins/excanvas.min.js"></script>
<script src="/hsgm/global/plugins/ie8.fix.min.js"></script>

<script src="/hsgm/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/hsgm/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/hsgm/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="/hsgm/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/hsgm/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/hsgm/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>

{{--<script src="/hsgm/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>--}}
{{--<script src="/hsgm/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>--}}
{{--<script src="/hsgm/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>--}}
<script src="/hsgm/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script src="/hsgm/global/scripts/app.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){
    $.backstretch([
            "/hsgm/pages/media/bg/1.jpg",
            "/hsgm/pages/media/bg/2.jpg",
            "/hsgm/pages/media/bg/3.jpg",
            "/hsgm/pages/media/bg/4.jpg",
            "/hsgm/pages/media/bg/5.jpg",
            "/hsgm/pages/media/bg/6.jpg"
        ], {
            fade: 1000,
            duration: 8000
        }
    );

    $(document).keydown(function(event){
        if(event.keyCode==13){
            $("#login").click();
        }
    });

    $('#login').click(function () {

        var user = $('#username').val();
        var pwds = $('#password').val();

        if(user.length<2 || user.length>18){
            alert('帐号错误，请重新输入');
            return false;
        }

        if(pwds.length<4 || pwds.length>18){
            alert('密码错误，请重新输入');
            return false;
        }
        $.ajax( {
            type : "post",
            url : "/admin/check",
            dataType : 'json',
            data : {'_token':'{{csrf_token()}}',user:user,pwds:pwds},
            success : function(data) {
                if(data.status){
                    window.location.href=data.url;
                }else{
                    alert(data.msg);
                }
            }
        });
    });
})
</script>

</html>