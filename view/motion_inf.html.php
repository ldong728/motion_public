<h2 class="p-tab-title"><?php echo 1 == $_SESSION['userLogin']['category'] ? '慈溪市人大建议议案办理单' : '慈溪市政协提案办理单' ?></h2>
<table width="" border="1" cellspacing="0" cellpadding="0">
<tbody style="font-size: 14px">
<tr>
    <td><p> 会议名称</p></td>
    <td>
        <p><?php echo substr($meetingInf['meeting_name'], 0) ?></p>
    </td>
    <td>登记时间</td>
    <td><span
            class="encoded-data"><?php echo json_encode($motion['登记时间'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <td>状态</td>
    <td><span
            class="encoded-data"><?php echo json_encode($motion['状态'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>
<tr>
    <td>案别</td>
    <td >
        <?php if(2==$meetingInf['category']):?><span>提案</span>
        <?php else:?>
            <span
                class="encoded-data"><?php echo json_encode($motion['案别'], JSON_UNESCAPED_UNICODE) ?></span>
        <?php endif?>
    </td>
    <td>案号</td>
    <td  class="verify-value"><span
            class="encoded-data"><?php echo json_encode($motion['案号'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <td>是否公开</td>
    <td ><span
            class="encoded-data"><?php echo json_encode($motion['是否公开'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
</tr>
<tr>
    <td>性质类别</td>
    <td  class="verify-value"><span
            class="encoded-data"><?php echo json_encode($motion['性质类别' . $meetingInf['category']], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <td>性质</td>
    <td ><span
            class="encoded-data" ><?php echo json_encode($motion['性质'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    <td><?php echo 1 == $meetingInf['category'] ? '代表团' : '属性' ?></td>
    <td ><?php if($unitGroupInf):?><?php echo $unitGroupInf['group']?><?php endif?></td>
</tr>
<?php if (2 == $meetingInf['category']): ?>
    <tr>
        <td>委组</td>
        <td colspan="2"><?php if($unitGroupInf):?><?php echo $unitGroupInf['unit']?><?php endif?></td>
        <td>提案分类</td>
        <td colspan="2" class="judged-value"><span
                class="encoded-data"><?php echo json_encode($motion['提案分类'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>

    </tr>
<?php endif ?>
<?php if(1==$meetingInf['category']):?>
    <tr>
        <td>领衔人</td>
        <td colspan="5" class="verify-value name-auto" style="text-align: left;padding-left: 10px;"><span
                class="encoded-data"><?php echo json_encode($motion['领衔人'], JSON_UNESCAPED_UNICODE) ?></span></td>
    </tr>
<?php endif ?>
<?php if(2==$meetingInf['category']):?>
    <tr>
        <td>提案人</td>
        <td colspan="5" class="user-type verify-value" style="text-align: left;padding-left: 10px;"><span
                class="encoded-data"><?php echo json_encode($motion['提案人'], JSON_UNESCAPED_UNICODE) ?></span></td>

    </tr>
    <tr class="union-conecter" <?php if(!$motion['提案联系人']['content'])echo 'style="display: none"'?>>
        <td>联系人</td>
        <td colspan="5" class="conecter" style="text-align: left;padding-left: 10px;" ><span
                class="encoded-data"><?php echo json_encode($motion['提案联系人'], JSON_UNESCAPED_UNICODE) ?></span></td>
    </tr>
<?php endif ?>
<tr>
    <td>附议人</td>
    <td colspan="5" class="fuyi-count" style="text-align: left;padding-left: 10px;"><span
         class="encoded-data"><?php echo json_encode($motion['附议人'], JSON_UNESCAPED_UNICODE) ?></span></td>

</tr>
<?php if($canCoop):?>
    <tr>
        <td></td>
        <td><button class="add-coop">我要附议</button></td>
        </tr>
<?php endif ?>
<tr>
    <td>案由</td>
    <td colspan="5" class="colspan7 motion-name-area verify-value" style="text-align: left; padding-left: 10px;"><span
            class="encoded-data"><?php echo json_encode($motion['案由'], JSON_UNESCAPED_UNICODE) ?></span></td>
</tr>
<tr>
    <td>全文</td>
    <td colspan="5" class="colspan7 verify-value" style="text-align: left;padding-left: 10px;"><span
            class="encoded-data"><?php echo json_encode($motion['原文'], JSON_UNESCAPED_UNICODE) ?></span></td>
</tr>
<tr>
    <td>摘要</td>
    <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span
            class="encoded-data"><?php echo json_encode($motion['摘要'], JSON_UNESCAPED_UNICODE) ?></span></td>
</tr>
<?php if(2==$meetingInf['category']):?>

    <tr>
        <td>初审</td>
        <td  style="text-align: left;padding-left: 10px;"><span
                class="encoded-data"><?php echo json_encode($motion['初审'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
        <td>初审意见</td>
        <td colspan="3" style="text-align: left;padding-left: 10px;"><span
                class="encoded-data"><?php echo json_encode($motion['初审意见'], JSON_UNESCAPED_UNICODE) ?></span></td>
    </tr>
<?php endif?>
<?php if ($meetingInf['step'] > 2): ?>
    <tr>
        <td>审核</td>
        <td colspan="5" class="colspan7 pass-verify" style="text-align: left;padding-left: 10px;"><span
                class="encoded-data"><?php echo json_encode($motion['审核' . $meetingInf['category']], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
    </tr>
    <tr>
        <td>审核意见</td>
        <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span
                class="encoded-data"><?php echo json_encode($motion['审核意见'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
    </tr>
    <?php if (2 == $meetingInf['category']): ?>
        <tr>
            <td>交办单位</td>
            <td colspan="5" class="colspan7 verify-value" style="text-align: left;padding-left: 10px;"><span
                    class="encoded-data"><?php echo json_encode($motion['交办单位'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
    <?php endif ?>
    <?php if(1==$meetingInf['category']&&$motion['交办意见']['content']):?>
        <?php if(3==$meetingInf['step']):?>
            <tr>
                <td>交办意见</td>
                <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span
                        class="encoded-data"><?php echo json_encode($motion['交办意见'], JSON_UNESCAPED_UNICODE) ?></span>
                </td>
            </tr>
        <?php endif ?>
        <tr>
            <td>交办单位</td>
            <td colspan="5" class="colspan7 verify-value" style="text-align: left;padding-left: 10px;"><span
                    class="encoded-data"><?php echo json_encode($motion['交办单位'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
    <?php endif?>
<?php endif ?>
<?php if ($meetingInf['step'] > 3): ?>
    <?php if(1==$meetingInf['category']):?>
        <tr>
            <td>交办意见</td>
            <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span
                    class="encoded-data"><?php echo json_encode($motion['交办意见'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
    <?php endif ?>
    <tr>
        <td>主办单位</td>
        <td colspan="5" class="colspan7 verify-value" style="text-align: left;padding-left: 10px;"><span
                class="encoded-data"><?php echo json_encode($motion['主办单位'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
    </tr>
    <td>协办单位</td>
    <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span
            class="encoded-data"><?php echo json_encode($motion['协办单位'], JSON_UNESCAPED_UNICODE) ?></span>
    </td>
    </tr>
<?php endif ?>
<?php if ($meetingInf['step'] > 4): ?>
        <tr>
            <td>主办答复时间</td>
            <td colspan="2"><span
                    class="encoded-data"><?php echo json_encode($motion['主办答复时间'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
            <td>文号</td>
            <td class="verify-value" colspan="2"><span
                    class="encoded-data verify-value"><?php echo json_encode($motion['文号'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
            </tr><tr>
            <td>类别标记</td>
            <td colspan="2"><span
                    class="encoded-data"><?php echo json_encode($motion['类别标记'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
            <td>签发人</td>
            <td class="verify-value" colspan="2"><span
                    class="encoded-data verify-value"><?php echo json_encode($motion['主办签发人'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
        <tr>
            <td>主办意见全文</td>
            <td colspan="5" class="colspan7 verify-value" style="text-align: left;padding-left: 10px;"><span
                    class="encoded-data"><?php echo json_encode($motion['主办答复全文'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
        <tr>
            <td>已落实事项</td>
            <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span
                    class="encoded-data"><?php echo json_encode($motion['已落实事项'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
        <tr>
            <td>计划落实事项</td>
            <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span
                    class="encoded-data"><?php echo json_encode($motion['计划落实事项'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
    <?php foreach ($handlerDisplay as $row): ?>
        <tr>
            <td>协办单位名称</td>
            <td  style="text-align: left;padding-left: 10px"><?php echo $row['unit_name']?></td>
            <td colspan="2">协办办理状态</td>
            <td colspan="2">
                <?php echo 9==$row['status']?'已完成':'未完成'?>
            </td>

        </tr>
        <tr>
            <td>联系人</td>
            <td ><?php echo $row['contact_name'] ?></td>
            <td>联系电话</td>
            <td ><?php echo $row['contact_phone'] ?></td>
            <td>回复时间</td>
            <td ><?php echo $row['reply_time']?date('Y-m-d',$row['reply_time']):'' ?></td>
        </tr>
        <tr>
            <td>协办意见全文</td>
            <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;">
                <a <?php echo $row['attachment'] ? 'href="' . $row['attachment'] . '"' : '' ?>><?php echo $row['attachment_name'] ?></a>
            </td>
        </tr>
    <?php endforeach ?>
   <?php endif ?>
<?php if ($meetingInf['step'] > 5||(isset($motion['办理工作']['content'])&&$motion['办理工作']['content'])): ?>
<?php if(2==$meetingInf['category']):?>
    <tr>
        <td>办理工作</td>
        <td><span class="encoded-data"><?php echo json_encode($motion['办理工作'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
        <td>办理结果</td>
        <td><span class="encoded-data"><?php echo json_encode($motion['办理结果'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
        <td>采纳情况</td>
        <td class="verify-value"><span class="encoded-data"><?php echo json_encode($motion['采纳情况'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
    </tr>
    <tr>
        <td>面商形式</td>
        <td class="verify-value"><span class="encoded-data"><?php echo json_encode($motion['面商形式'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
        <td>面商人</td>
        <td><span class="encoded-data"><?php echo json_encode($motion['面商人'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
        <td>落实情况</td>
        <td><span class="encoded-data"><?php echo json_encode($motion['落实情况'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
    </tr>
    <tr>
        <td>反馈意见全文</td>
        <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span class="encoded-data"><?php echo json_encode($motion['反馈意见全文'], JSON_UNESCAPED_UNICODE) ?></span>
        </td>
    </tr>
    <?php else: ?>
        <tr>
            <td>面商形式</td>
            <td colspan="2" class="verify-value"><span class="encoded-data"><?php echo json_encode($motion['面商形式'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
            <td>采纳情况</td>
            <td colspan="2" class="verify-value"><span class="encoded-data"><?php echo json_encode($motion['采纳情况'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
        <tr>
            <td>办理工作</td>
            <td colspan="2"><span class="encoded-data"><?php echo json_encode($motion['办理工作'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
            <td>办理结果</td>
            <td colspan="2"><span class="encoded-data"><?php echo json_encode($motion['办理结果'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
        <tr>
            <td>反馈意见全文</td>
            <td colspan="5" class="colspan7" style="text-align: left;padding-left: 10px;"><span class="encoded-data"><?php echo json_encode($motion['反馈意见全文'], JSON_UNESCAPED_UNICODE) ?></span>
            </td>
        </tr>
    <?php endif ?>
    <?php if(6==$currentStep):?>
        <tr>
            <td>操作</td>
            <td colspan="5"><button class="save-attr">保存（暂不提交）</button><button class="submit-attr">提交反馈</button></td>
        </tr>

        <?php endif ?>
<?php endif ?>
<?php if(0==$currentStep&&$owner):?>
    <tr>
        <td>操作</td>
        <td colspan="5"><button class="submit-attr">提交</button></td>
    </tr>
<?php endif?>
</tbody>
</table>
<script>
    decodeDate($('.encoded-data'));
</script>
