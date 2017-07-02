<link rel="stylesheet" type="text/css" media="screen" href="css/login.css?v=<?php echo rand(1000, 9999) ?>">

<body>
<div class="header">
    <div class="container">
        <div class="header-l"><p>欢迎来到慈溪市政协提案办理系统</p></div>
        <div class="header-r"><a href="#">加入收藏</a><span class="sep">|</span><a href="#">设为主页</a></div>
        <div class="header-m"><p class="date"></p></div>
    </div>
</div>
<div class="main">
    <div class="container">
        <div class="badge"><img src="images/p2.jpg"></div>
        <div class="bg-img"><img src="images/pc2.jpg"></div>
        <div class="login-box">
            <form action="?" method="post">
                <h2 class="title">请登录</h2>

                <p>账号</p>
                <input type="text" name="user" class="user">

                <p>密码</p>
                <input type="password" name="password" class="pwd">
                <input type="hidden" name="category" value="2">
                <button class="btn" onclick="form.submit()">登&nbsp;&nbsp;&nbsp;&nbsp;录</button>
            </form>
        </div>
    </div>
</div>
<div class="footer">
    <div class="footer-t"></div>
    <div class="container">
        <div class="footer-tech"><p>技术支持：慈溪市谷多计算机网络技术有限公司</p></div>
    </div>
</div>
<script type="text/javascript">
    var error='<?php echo isset($_GET['error'])?$_GET['error']:''?>';
    init();

    $(document).ready(function(){
        resizeView();
        $(window).resize(function(){
            resizeView()
        })

    });
    function resizeView(){
        var bHeight = $(document.body).height();
        var wHeight = $(window).height();
        if (bHeight > wHeight){
            $('.footer').css('position','static');
        }else{
            return;
        }
    }
    function init(){
        var sDate=new Date();
        $('.date').text(sDate.getFullYear()+'年'+(sDate.getMonth()+1)+'月'+sDate.getDate()+'日');
        console.log(sDate);
        if('password'==error)alert('用户名或密码错误');

    }

</script>
</body>
</html>
