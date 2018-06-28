<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, user-scalable=no, width=device-width">
        <link rel="stylesheet" href="jquery_mobile/css/jquery.mobile-1.4.5.min.css" />
        <link rel="stylesheet" href="jquery_mobile/css/index.css">
        <title>代理中心-登录</title>
    </head>
    <body>
        <section data-role="page" id="pgLogIn">
            <header data-role="header" data-position="fixed" data-add-back-btn="true">
                <h1>代理登录</h1>
            </header>
            <article role="main" class="ui-content">
                <h2>登陆</h2>
                <div>
                    <form id="formLogIn">
                        {{ csrf_field() }}
                        <label for="inPhoneLogIn">手机号码: </label><input type="text" required placeholder="请输入11位手机号码" name="inPhoneLogIn" id="inPhoneLogIn" />
                        <label for="inPasswordLogIn">密码: </label><input type="password" required placeholder="请输入您的密码" name="inPasswordLogIn" id="inPasswordLogIn" />
                        <input type="submit" value="登陆" id="btnLogIn" />
                    </form>
                </div>

                <div data-role="popup" class="ui-content" id="popLoginNonexistant">账号不存在!</div>
                <div data-role="popup" class="ui-content" id="popLoginIncorrect">账号或密码错误!</div>
                <div data-role="popup" class="ui-content" id="error">账号或密码错误!</div>
            </article>
        </section>

        <script src="jquery_mobile/scripts/jquery-1.8.0.min.js"></script>
        <script src="jquery_mobile/scripts/jquery.mobile-1.4.5.min.js"></script>
        <script src="jquery_mobile/scripts/index.js"></script>
    </body>
</html>
