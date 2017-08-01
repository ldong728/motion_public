<div class="mask"></div>
<div class="suggest cont">
<div class="sug-head clearfix">
    <div class="sug-head-l"><h1><?php echo 1 == $meetingInf['category'] ? '人大建议议案查询' : '政协提案查询' ?></h1></div>
    <div class="sug-head-r"><p>当前环节：<?php echo current($motion)['step_name'] ?></p></div>
</div>
<div class="sug-main">
<div class="sug-main-nav clearfix">
    <a href="#" class="close-popup">返回</a>
    <a href="#" class="start-multiple-search">搜索</a>
</div>
<div class="sug-main-content edit-area">
<div class="content-title">
    <p style="height: 40px;"><?php echo 1 == $meetingInf['category'] ? '慈溪市人大建议议案查询' : '慈溪市政协提案查询' ?></p>
</div>
<div class="table-list">
<link rel="stylesheet" type="text/css" media="print" href="stylesheet/print.css?v=<?php echo rand(1000, 9999) ?>">
<table width="100%" height="296" border="0" bordercolor="#f08300" cellpadding="0" cellspacing="0" class="motion-table">
<tbody style="font-size: 14px">
<tr>
    <th><p> 会议名称</p></th>
    <td colspan="2" width="165">
        <p><?php echo $meetingName ?></p>
    </td>
    <th>登记时间</th>
    <td colspan="2" width="165"><?php echo $motion['登记时间']['attr_name'] ?>
    </td>
    <th>状态</th>
    <td colspan="2" width="165"><span
            class="encoded-search-data"><?php echo json_encode($motion['状态'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>
<tr>
    <th>案别</th>
    <td colspan="2">
        <?php if(2==$meetingInf['category']):?><span>提案</span>
        <?php else:?>
            <span
                class="encoded-search-data"><?php echo json_encode($motion['案别'], JSON_UNESCAPED_UNICODE) ?></span>
        <?php endif?>
    </td>
    <th>案号</th>
    <td colspan="2" class="verify-value"><span
            class="encoded-search-data"><?php echo json_encode($motion['案号'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <th>是否公开</th>
    <td colspan="2"><span
            class="encoded-search-data"><?php echo json_encode($motion['是否公开'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>
<tr>
    <th>性质类别</th>
    <td colspan="2" class="verify-value"><span
            class="encoded-search-data"><?php echo json_encode($motion['性质类别' . $meetingInf['category']], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <th>性质</th>
    <td colspan="2"><span
            class="encoded-search-data" ><?php echo json_encode($motion['性质'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <th><?php echo 1 == $meetingInf['category'] ? '代表团' : '属性' ?></th>
    <td colspan="2" class="search-key" data-motionattr="user_group">
        <select class="search-value">
            <option value="0">请选择</option>
            <?php foreach($userGroup as $row):?>
                <option value="<?php echo $row['user_group_id']?>"><?php echo $row['user_group_name']?></option>
            <?php endforeach ?>
        </select>
    </td>
</tr>
<?php if (2 == $meetingInf['category']): ?>
    <tr>
        <th>委组</th>
        <td colspan="2" class="search-key" data-motionattr="user_unit">
            <select class="search-value">
                <option value="0">请选择</option>
                <?php foreach($userUnit as $row):?>
                <option value="<?php echo $row['user_unit_id']?>"><?php echo $row['user_unit_name']?></option>
                <?php endforeach ?>
            </select></td>
        <th>提案分类</th>
        <td colspan="2" class="judged-value"><span
                class="encoded-search-data"><?php echo json_encode($motion['提案分类'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
        <th>附议人数</th>
        <td colspan="2"  class="fuyi">&nbsp;</td>
    </tr>
<?php endif ?>
<?php if(1==$meetingInf['category']):?>
    <tr>
        <th>领衔人</th>
        <td colspan="5" class="verify-value name-auto" style="text-align: left;padding-left: 10px;"><span
                class="encoded-search-data"><?php echo json_encode($motion['领衔人'], JSON_UNESCAPED_UNICODE) ?></span></td>
        <th>所属中心组</th>
        <td class="search-key" data-motionattr="user_unit">
            <select class="search-value">
                <option value="0">请选择</option>
                <?php foreach($userUnit as $row):?>
                    <option value="<?php echo $row['user_unit_id']?>"><?php echo $row['user_unit_name']?></option>
                <?php endforeach ?>
            </select></td>
    </tr>

<?php endif ?>
<?php if(2==$meetingInf['category']):?>
    <tr>
        <th>提案人</th>
        <td colspan="7" class="user-type verify-value" style="text-align: left;padding-left: 10px;"><span
                class="encoded-search-data"><?php echo json_encode($motion['提案人'], JSON_UNESCAPED_UNICODE) ?></span></td>

    </tr>
<?php endif ?>
<tr>
    <th >附议人</th>
    <td colspan="7" class="fuyi-count colspan7" colspan="7" style="text-align: left;padding-left: 10px;"><span
            class="encoded-search-data"><?php echo json_encode($motion['附议人'], JSON_UNESCAPED_UNICODE) ?></span></td>

</tr>
<tr>
    <th>案由</th>
    <td colspan="7" class="colspan7 motion-name-area verify-value" style="text-align: left; padding-left: 10px;"><span
            class="encoded-search-data"><?php echo json_encode($motion['案由'], JSON_UNESCAPED_UNICODE) ?></span></td>
</tr>
<?php if(1==$meetingInf['category']):?>
<tr>
    <th>审核</th>
    <td colspan="7" class="colspan7" style="text-align: left;padding-left: 10px;"><span
            class="encoded-search-data"><?php echo json_encode($motion['审核'.$meetingInf['category']], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>
<?php else: ?>
    <tr>
        <th>初审</th>
        <td colspan="3" class="colspan3" style="text-align: left;padding-left: 10px;"><span
                class="encoded-search-data"><?php echo json_encode($motion['初审'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
    </tr>
    <tr>
        <th>审核</th>
        <td colspan="3" class="colspan3" style="text-align: left;padding-left: 10px;"><span
                class="encoded-search-data"><?php echo json_encode($motion['审核'.$meetingInf['category']], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
    </tr>
<?php endif ?>
<tr>
    <th>审核意见</th>
    <td colspan="7" class="colspan7" style="text-align: left;padding-left: 10px;"><span
            class="encoded-search-data"><?php echo json_encode($motion['审核意见'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>

<tr>
    <th>主办单位</th>
    <td colspan="7" class="colspan7 verify-value" style="text-align: left;padding-left: 10px;"><span
            class="encoded-search-data"><?php echo json_encode($motion['主办单位'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>
<th>协办单位</th>
<td colspan="7" class="colspan7" style="text-align: left;padding-left: 10px;"><span
        class="encoded-search-data"><?php echo json_encode($motion['协办单位'], JSON_UNESCAPED_UNICODE) ?></span>
</td>
</tr>
<!--<tr>-->
<!--    <th>主办答复时间</th>-->
<!--    <td width="105px"><span-->
<!--            class="encoded-search-data">--><?php //echo json_encode($motion['主办答复时间'], JSON_UNESCAPED_UNICODE) ?><!--</span>-->
<!--    </td>-->
<!--    <th>文号</th>-->
<!--    <td width="95px" class="verify-value"><span-->
<!--            class="encoded-search-data verify-value">--><?php //echo json_encode($motion['文号'], JSON_UNESCAPED_UNICODE) ?><!--</span>-->
<!--    </td>-->
<!--    <th>类别标记</th>-->
<!--    <td width="100px"><span-->
<!--            class="encoded-search-data">--><?php //echo json_encode($motion['类别标记'], JSON_UNESCAPED_UNICODE) ?><!--</span>-->
<!--    </td>-->
<!--    <th>签发人</th>-->
<!--    <td class="verify-value"><span-->
<!--            class="encoded-search-data verify-value">--><?php //echo json_encode($motion['主办签发人'], JSON_UNESCAPED_UNICODE) ?><!--</span>-->
<!--    </td>-->
<!--</tr>-->
<tr>
    <th rowspan="2" style="border-right: 1px solid #f08300">反馈意见</th>
    <th>办理工作</th>
    <td><span class="encoded-search-data"><?php echo json_encode($motion['办理工作'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <th>办理结果</th>
    <td><span class="encoded-search-data"><?php echo json_encode($motion['办理结果'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <th colspan="2">办理面商形式</th>
    <td><span class="encoded-search-data"><?php echo json_encode($motion['面商形式'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>
<tr><th>面商人</th>
    <td><span class="encoded-search-data"><?php echo json_encode($motion['面商人'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <th>采纳情况</th>
    <td><span class="encoded-search-data"><?php echo json_encode($motion['采纳情况'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <th colspan="2">落实情况</th>
    <td><span class="encoded-search-data"><?php echo json_encode($motion['落实情况'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>



</tbody>
</table>
</div>
</div>
</div>
</div>
<table class="table-list">

</table>
<div class="mask1"></div>
<div class="unit" style="display: none;z-index: 999;position: fixed">
    <div class="unit-title">
        <h2 class="target-name">请选择</h2>
        <div class="back close-unit"></div>
    </div>
    <div class="unit-table">
        <input type="hidden" class="multiple-type">
        <table width="700" border="1" bordercolor="#f08300" cellspacing="0" cellpadding="0">
            <tbody>
            <tr style="height: 40px;">
                <td><div class="nav-tab"><input type="text" name="search" style="width: 280px;height: 20px; margin: 0 10px;" id="search-input"></div></td>
                <td><button type="button" class="u-btn " id="search-button">搜索</button></td>
                <td></td>
            </tr>
            <tr>
                <td rowspan="4">
                    <div class="unit-nav selecter-content">
                        <div class="nav-tab">
                            <h2><i class="icon icon-chevron-right"></i></h2>
                            <ul>
                                <li class="li-1 clearfix">
                                    <button class="btn-1 main-candidate-btn" type="button"></button>
                                    <input class="checkbox candidate super" type="checkbox" name="checkbox-lv1"
                                           value="778">
                                    <button class="btn-2" type="button"></button>
                                    <span class="span-1 candidate-name">农业农村组联络委</span></li>
                                <li class="li-2">
                                    <ul>
                                        <li class="li-lv2 main-candidate clearfix">
                                            <button class="btn-lv2-1" type="button"></button>
                                            <input class="checkbox candidate sub" type="checkbox" name="checkbox-lv2"
                                                   value="23">
                                            <button class="btn-lv2-2" type="button"></button>
                                            <span class="span-1 candidate-name">毛玲洁</span></li>

                                    </ul>
                                </li>
                            </ul>

                        </div>
                    </div>
                </td>
                <td width="100" rowspan="2">
                    <button type="button" class="u-btn target-choose">--></button>
                    <button type="button" class="u-btn chosen-delete"><--</button>
                    <button type="button" class="u-btn chosen-confirm">确认</button>
                </td>
                <td width="300px" height="28">
                    <div class="unit-nav">
                        <ul class="target-chosen-ul">

                        </ul>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    decodeSearchDate( $('.encoded-search-data'));
</script>


