<?php global $isUpdated,$typeList?>
<body>
<script>
</script>
<script type="text/javascript" src="js/main.js?v=<?php echo rand(10, 1000) ?>"></script>
<script type="text/javascript" src="js/index.js?v=<?php echo rand(10, 1000) ?>"></script>
<script type="text/javascript" src="js/laydate.js"></script>
<script type="text/javascript" src="js/zx.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="css/main.css?v=<?php echo rand(1000, 9999) ?>">
<style>
    .container {
        background-color: #ffffff;
        border: none;
    }
    .input-container {

        background-color:yellow;
        width: 500px;;
    }
    .input-container input {
        float: left;
    }
    .input-container div {
        float: left;
    }
    .clue{position: absolute;left: 50%;top: 50%; width: 960px;margin-left: -480px;margin-top: -480px;padding: 40px 60px; background-color: #fff;box-sizing: border-box;}
    .clue label{display: block;padding: 10px 0;vertical-align: top}
    .clue label span.title{display: inline-block; width: 15%;font-size: 14px;color: #333; text-align: right;margin-right:20px;letter-spacing: 1px;}
    .clue label input[type=text],.clue label input[type=tel],.clue label select{width: 276px;height: 25px; font-size: 14px;border: 1px solid #aaa;box-sizing: border-box;letter-spacing: 1px;}
    .clue label select{appearance:none;-moz-appearance:none;-webkit-appearance:none;background: url("images/arrow.jpg") no-repeat scroll right center transparent;select::-ms-expand { display: none; }}
    .clue .textarea-title{float: left;}
    .clue label textarea{width: 75%;font-size: 14px;line-height: 1.5;letter-spacing: 1px;resize: none;
    }
    .clue input, .clue textarea, .clue select{color: #333;padding: 0 10px;}
    .clue .submit{padding: 3px 10px; background-color: #f4f8ff;color: #174d7b;border: 1px solid #aedaf7;}
    .clue .first-option{color: #999;}
</style>
<header class="header">
    <div class="header-l"><img src="images/p2.jpg" alt="logo"></div>
</header>
<nav class="home-nav">
    <ul class="clearfix">
        <li>提案线索征集</li>

    </ul>
    <div class="nav-right">
    </div>
</nav>
<section class="clearfix" id="section">
    <aside id="aside">
        <ul>
            <li class="motion-filter" data-filter="all"></li>

        </ul>
    </aside>
    <div class="main-show">
<!--        <span id="span-contract"><i class="icon icon-caret-left"></i></span>-->

    </div>
    <div class="left section-main" id="main">


<!--        <div class="blueborder_lower">-->
<!--            <div class="table-box">-->
<!--                <div class="table">-->
<!--                    <div class="container">-->
<!--                        <form action="?suggestion=2" method="post">-->
<!--                            <input type="hidden" name="suggestion_update" value="1">-->
<!---->
<!---->
<!--                        <div class="input-container">-->
<!--                            <div class="input-title">姓名</div><input type="text" name="name">-->
<!---->
<!--                        </div>-->
<!--                            <div class="input-container">-->
<!--                                <div class="input-title">联系手机</div><input type="text" name="tel">-->
<!---->
<!--                            </div>-->
<!--                            <div class="input-container">-->
<!--                                <div class="input-title">性质类别</div><select name="type"><option>hahahahah</option></select>-->
<!---->
<!--                            </div>-->
<!--                            <div class="input-container">-->
<!--                                <div class="input-title">姓名</div><textarea name="content"></textarea>-->
<!--                            </div>-->
<!--                            <input type="submit" value="确认提交">-->
<!--                        </form>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
    </div>
</section>
<footer>
    <p>技术支持 <span>慈溪谷多计算机网络技术有限公司</span></p>
</footer>
<!--弹出层-->

<div class="clue">
    <form method="post" action="?suggestion=2">
        <input type="hidden" name="suggestion_update" value="1">
        <label>
            <span class="title">姓名：</span>
            <input type="text" name="name" pattern="[\u4e00-\u9fa5]{2,6}" title="2到6个中文字">
        </label>
        <label>
            <span class="title">电话：</span>
            <input type="tel" name="tel" pattern="[0-9]{11}" title="11位电话号码">
        </label>
        <label>
            <span class="title">性质类别：</span>
            <select name="type">
                <option value="" disabled selected class="">请选择</option>
                <?php foreach($typeList as $value):?>
                    <option value="<?php echo $value ?>"class=""><?php echo $value ?></option>
                <?php endforeach ?>
            </select>
        </label>
        <label>
            <span class="title textarea-title">内容：</span>
            <textarea name="content" rows="7" maxlength="2000"></textarea>
        </label>
        <label>
            <span class="title"></span>
            <input class="submit" type="submit" value="确认提交" >
        </label>
    </form>
</div>



</body>
<script>
    var isUpdate=<?php echo $isUpdated?>;
    var mainHeight=$('.clue').height();
    $('.clue').css('margin-top',(-mainHeight/2)+'px');
    if(isUpdate)alert('提交成功，请关闭页面');



</script>


<!--<script type="text/javascript" src="js/getList.js?v=--><?php //echo rand(10, 1000) ?><!--"></script>-->

</html>
