<?php $motion_type=1==$_SESSION['userLogin']['category']?'建议/议案':'提案'?>
<body>
<script>
    var user = eval('(' + '<?php echo json_encode($_SESSION['userLogin'])?>' + ')');
</script>
<script type="text/javascript" src="js/main.js?v=<?php echo rand(10, 1000) ?>"></script>
<script type="text/javascript" src="js/index.js?v=<?php echo rand(10, 1000) ?>"></script>
<script type="text/javascript" src="js/edit_motion.js?v=<?php echo rand(10,1000)?>"></script>
<script type="text/javascript" src="js/zx.js?v=<?php echo rand(10, 1000) ?>"></script>
<script type="text/javascript" src="js/laydate.js"></script>
<script src="js/search.js?v=<?php echo rand(1000, 9999) ?>"></script>

<link rel="stylesheet" type="text/css" media="screen" href="css/main.css?v=<?php echo rand(1000, 9999) ?>">
<header class="header">
    <div class="header-l"><img src="images/p<?php echo $_SESSION['userLogin']['category']?>.jpg" alt="logo"></div>
    <div class="header-r">
        <a href="#">密码修改</a>
        <a href="#" class="sign-out">退出系统</a>
    </div>
</header>
<nav class="home-nav">
    <ul class="clearfix">
        <li id="li1"><a href="#">我要提<?php echo $motion_type?></a></li>
        <li id="li2"><a href="#">个人信息</a></li>
        <li id="li3"><a href="#">历届历次</a></li>
        <?php if(2==$motion_type):?><li id="li4"><a href="#"><?php echo $motion_type?>线索</a></li><?php endif ?>
        <li class="li7"><a href="#">百件<?php echo $motion_type?>汇编</a></li>
        <li class="li8"><a href="#" class="multiple-search">搜索</a></li>
        <li><a href="#">公告</a></li>
    </ul>
    <div class="nav-right">
        <em><?php echo $_SESSION['userLogin']['user_name'] ?></em>，欢迎您登陆！
    </div>
</nav>
<section class="clearfix" id="section">
    <aside id="aside">
        <ul>
            <li class="motion-filter" data-filter="all">我的<?php echo $motion_type?></li>
            <li class="motion-filter" data-filter="coop">我附议的<?php echo $motion_type?></li>
            <li class="motion-filter" data-filter="preCoop" >征集附议<?php echo $motion_type?></li>
            <li class="motion-filter" data-filter="meeting">本届<?php echo $motion_type?></li>
<!--            <li class="motion-filter">--><?php //echo $motion_type?><!--查询</li>-->
        </ul>
    </aside>
    <div class="main-show">
        <span id="span-contract"><i class="icon icon-caret-left"></i></span>
    </div>
    <div class="left section-main" id="main">
        <div class="main-navbar"><h2>当前位置:<em>我的<?php echo $motion_type?></em></h2></div>
        <div class="blueborder_lower">
            <div class="table-box">
                <div class="table">
                    <div class="container">
                        <!--
                                      <div class="fixed" style="position: absolute;z-index: 7;overflow: hidden"></div>
                                      <div class="x-move-container" style="position: absolute;width: calc(100% - 17px);overflow: hidden;z-index: 5">
                                        <div class="x-move" style="position: relative"></div>
                                      </div>
                                      <div class="y-move-container"
                             style="position: absolute;z-index: 6;height: calc(100% - 17px);overflow: hidden;">
                                        <div class="y-move" style="position: relative;overflow:hidden;"></div>
                                      </div>
                        -->
                        <div id="genetable_tableData" style="z-index: 1">
                            <table id="sample2" width="100%" border="0" cellpadding="0" cellspacing="0"
                                   class="sample-table-js source-table">
                                <thead>
                                <tr class="tr0 trr0 list-title">
                                </tr>
                                </thead>
                                <tbody class="list-container">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-page"><span class="first-page"><i class="icon icon-step-backward"></i></span> <span
                    class="span-i prev-page"><i class="icon icon-caret-left"></i></span> <span
                    class="current-page">1</span> <span class="total-page">共43页</span> <span class="span-i next-page"><i
                        class="icon icon-caret-right"></i></span> <span class="last-page"><i
                        class="icon icon-step-forward"></i></span> <span>
        <select style="width: 60px;border: 1px solid #ddd;" class="count-in-page">
            <option value="20">20条</option>
            <option value="30">30条</option>
            <option value="50">50条</option>
        </select>
        </span> <span class="current-num">1-15</span> <span>共<span class="span-num total-num"></span>条</span></div>
        </div>

    </div>
</section>
<footer>
    <p>技术支持 <span>慈溪谷多计算机网络技术有限公司</span></p>
</footer>
<!--弹出层-->
<div class="popup popup1" style="display: none;">
    <div class="mask"></div>
    <div class="motion">
        <h2 class="title-h2">我要提<?php echo $motion_type?><i class="icon icon-close close-popup"></i>
        </h2>

        <div class="mot-h3"><h3><em></em></h3></div>
        <div class="popup-table">
            <h2 class="p-tab-title"><?php echo 1 == $_SESSION['userLogin']['category'] ? '慈溪市人大建议议案办理单' : '慈溪市政协提案办理单' ?></h2>
            <form method="post" class="create-form" enctype="multipart/form-data">
            <table width="" border="1" cellspacing="0" cellpadding="0">

                    <input type="hidden" name="post_method" value="create_motion">
                    <tbody>
                    <tr>
                        <td>会议名称</td>
                        <td class="meeting-name"><?php echo $_SESSION['userLogin']['meeting_name']?></td>
                        <td>登记时间</td>
                        <td class="update-time"><?php echo timeUnixToMysql(time())?></td>
                    </tr>
                    <tr>
                        <td><?php echo 1 == $_SESSION['userLogin']['category'] ? '领衔人' : '提案人' ?></td>
                        <td class="user-name">
                            <?php if(isset($_SESSION['userLogin']['is_admin'])):?>
                                <button class="target-select" data-target="duty" type="button" data-multiple="0">选择</button>
                            <?php else:?>
                            <?php echo $_SESSION['userLogin']['user_name']?>
                            <?php endif?>
                        </td>
                        <td>状态</td>
                        <td class="meeting-status"><?php echo $_SESSION['userLogin']['status'] ?><input type="hidden" name="status" value="<?php echo $_SESSION['userLogin']['status'] ?>"></td>
                    </tr>
                    <?php if(2==$_SESSION['userLogin']['category']):?>
                    <tr>
                        <td>界别</td>
                        <td class="user-group"><?php echo isset($_SESSION['userLogin']['user_group'])?$_SESSION['userLogin']['user_group']:''?></td>
                        <td>性质</td>
                        <td><input type="radio" name="property" value="当年" checked>当年&nbsp;<input type="radio" name="property" value="多年重复" <?php if(1==$_SESSION['userLogin']['category']) echo 'disabled="disabled"' ?>>多年重复</td>
                    </tr>
                    <?php endif?>
                    <tr>
                        <td>附议期限</td>
                        <td><input type="text" id="date-selector" disabled>
                            <button type="button" class="date-btn"></button>
                            <input type="hidden" name="date" class="date-input">
                            <?php if(2==$_SESSION['userLogin']['category']):?>
                            <br><span class="red">其他委员可以在附议期限之前附议此提案</span></td>
                            <?php else:?>
                                <br><span class="red">其他代表可以在附议期限之前附议此建议/议案</span></td>
                            <?php endif ?>
                        <td>希望承办单位</td>
                        <td>
                            <button type="button" class="unit-select target-select" data-target="unit" data-multiple="1">选择
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>案由<br><span class="red">（<?php echo $motion_type?>题目）</span></td>
                        <td colspan="3"><input type="text" name="motion-title" class="input-lon" maxlength="150"></td>
                    </tr>
                    <tr>
                        <td>附件上传</td>
                        <td colspan="3"><input type="file" name="attachment-file"></td>
                    </tr>
                    </tbody>
            </table>
                <input type="hidden" id="need-partner" name="need-partner" value="1">
            </form>
        </div>
        <div class="refer">
            <button type="button" class="close-popup" value="返回">返回</button>
            <button type="button" value="提交" class="submit">立即提交</button>
            <button type="button" class="submit get-partner">征集附议</button>
        </div>
        <div class="mo-footer">
            <?php if(2==$_SESSION['userLogin']['category']):?>
            <p class="red">立即提交： 将您提出的提案提交到提案委</p>

            <p class="red">征集附议： 其他委员将会附议您提出的提案</p>
            <?php else:?>
                <p class="red">立即提交： 将您提出的建议/议案将提交到人大代工委</p>

                <p class="red">征集附议： 其他代表将会附议您提出的建议/议案</p>
            <?php endif?>
        </div>
    </div>
</div>

<div class="popup popup2" style="display: none">
    <div class="mask"></div>
    <div class="motion my-messege">
        <h2 class="title-h2">个人信息<i class="icon icon-close close-popup"></i>
        </h2>

        <div class="mot-h3"><h3>当前位置:<em>代表委员信息</em></h3></div>
        <div class="popup-table">
            <table width="" border="1" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>姓名</td>
                    <td><?php echo $_SESSION['userLogin']['user_name']?></td>
                    <td>身份证号</td>
                    <td></td>
                </tr>
                <tr>
                    <td>工作单位</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td>职位</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td>公众联系Email</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td>邮政编码</td>
                    <td></td>
                    <td>出生年月</td>
                    <td></td>
                </tr>
                <tr>
                    <td>联系电话</td>
                    <td></td>
                    <td>手机</td>
                    <td></td>
                </tr>
                <tr>
                    <td>界别</td>
                    <td></td>
                    <td>政治面貌</td>
                    <td></td>
                </tr>
                <tr>
                    <td>届次</td>
                    <td></td>
                    <td>手机</td>
                    <td></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td colspan="3">&nbsp;</td>
                </tr>

                </tbody>
            </table>
        </div>
        <div class="refer">
            <button type="button" value="返回" class="close-popup" style="padding: 5px 20px;">返回</button>
        </div>
    </div>
</div>
<div class="popup popup3" style="display: none">
    <div class="mask"></div>
    <div class="motion radius">
        <div class="unit">
            <div class="unit-title">
                <h2 class="target-name title-h2">请选择</h2>
                <div class=" back close-unit"> </div>
            </div>
            <div class="unit-table">
                <input type="hidden" class="multiple-type">
                <table width="" border="1" bordercolor="#f08300" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr style="height: 40px;">
                        <td><div class="nav-tab text-center">
                                <input type="text" name="search" id="search-input">
                            </div></td>
                        <td><button type="button" class="u-btn magin0 multiple-search" id="search-button">搜索</button></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><div class="unit-nav selecter-content">
                                <div class="nav-tab">
                                    <h2>按组委<i class="icon icon-chevron-right"></i></h2>
                                    <ul>
                                        <li class="li-1 clearfix">
                                            <button class="btn-1 main-candidate-btn li-btn-all b-fir" type="button">+</button>
                                            <input class="checkbox candidate super li-btn-all" type="checkbox" name="checkbox-lv1"
                                                   value="778">
                                            <button class="btn-2 li-btn-all b-sec" type="button"></button>
                                            <span class="span-1 candidate-name">农业农村组联络委农业农村组联络委农业农村组联络委农业农村组联络委农业农村组联络委</span></li>
                                        <li class="li-2">
                                            <ul>
                                                <li class="li-lv2 main-candidate clearfix">
                                                    <button class="btn-lv2-1 li-btn-all b-thi" type="button"></button>
                                                    <input class="checkbox candidate sub li-btn-all" type="checkbox"
                                                           name="checkbox-lv2"
                                                           value="23">
                                                    <button class="btn-lv2-2 li-btn-all b-sec" type="button"></button>
                                                    <span class="span-1 candidate-name">毛玲洁</span></li>

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div></td>
                        <td><button type="button" class="u-btn target-choose">--></button>
                            <button type="button" class="u-btn chosen-delete"><--</button>
                            <button type="button" class="u-btn chosen-confirm">确认</button></td>
                        <td style="vertical-align: inherit;"><div class="unit-nav">
                                <ul class="target-chosen-ul">
                                </ul>
                            </div></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="popup popup4" style="display: none">
    <div class="mask"></div>
    <div class="motion-container">
        <h2 class="title-h2">提案建议信息<i class="icon icon-close close-popup"></i>

            <div class="popup-table motion-info" style="height: 380px;overflow: auto; margin-top: 5px">

            </div>
        </h2>
    </div>
</div>
<div class="popup popup5" style="display: none">
    <div class="mask"></div>
    <div class="motion-container">
        <h2 class="title-h2">提案线索详情<i class="icon icon-close close-popup"></i>

            <div class="popup-table suggestion-detail" style="height: 380px;overflow: auto; margin-top: 5px">

            </div>
        </h2>
    </div>
</div>


</body>


<script type="text/javascript" src="js/getList.js?v=<?php echo rand(10, 1000) ?>"></script>
<script type="text/javascript" src="js/suggestion.js?v=<?php echo rand(10, 1000) ?>"></script>

</html>
