<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/17
 * Time: 13:53
 */
function userAuth($user,$psd,$category){
//    mylog('userAuth');
    $userId=$user;
    $password=$psd;
    $isAdmin=false;
    $dutyList=false;
    if(preg_match('/^\d{11}$/',$userId)){
        mylog('match');
        $userInf=pdoQuery('user_tbl',null,array('user_phone'=>$userId),'limit 1')->fetch();
        if($userInf){
            mylog(getArrayInf($userInf));
            if(($userInf['password']&&$userInf['password']==$password)||(!$userInf['password']&&md5($password)==md5(substr($userId,5)))){
                $_SESSION['userLogin']['user_name']=$userInf['user_name'];
//                $_SESSION['userLogin']
                $dutyList=pdoQuery('duty_view',null,array('user'=>$userInf['user_id'],'category'=>$category),'limit 10')->fetchAll();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }else{
        if(1==$category){
            $staffInf=pdoQuery('staff_tbl',array('full_name','user_admin'),array('staff_name'=>$userId,'staff_password'=>$password),' and user_admin <> "{}" limit 1')->fetch();
            if($staffInf){
                $isAdmin=true;
                $_SESSION['userLogin']['is_admin']=true;
                $_SESSION['userLogin']['user_name']=$staffInf['full_name'];
                $dutyList=pdoQuery('duty_view',null,json_decode($staffInf['user_admin'],true),'order by meeting asc');
            }else{
                return false;
            }
        }else{
            $userInf=pdoQuery('user_tbl',null,array('login_name'=>$userId),'limit 1')->fetch();
            if($userInf){
                if(($userInf['password']&&$userInf['password']==$password)||(!$userInf['password']&&md5($password)==md5('123456'))){
                    $_SESSION['userLogin']['user_name']=$userInf['user_name'];
                    $admin_type=pdoQuery('duty_tbl',['admin_type'],array('user'=>$userInf['user_id'],'category'=>$category),'order by meeting desc limit 1')->fetch();
                    if($admin_type['admin_type']){
                        $isAdmin=true;
                        $_SESSION['userLogin']['is_admin']=true;
                        $dutyList=pdoQuery('duty_view',null,json_decode($admin_type['admin_type'],true),'order by meeting asc');
                    }else{
                        $dutyList=pdoQuery('duty_view',null,array('user'=>$userInf['user_id'],'category'=>$category),'limit 10')->fetchAll();
                    }

                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

    }
    if($dutyList){
        $_SESSION['userLogin']['category']=$category;
        $now=time();
        foreach ($dutyList as $row) {
            $_SESSION['userLogin']['duty_list'][$row['duty_id']]=$row['duty_id'];
            if($row['deadline_time']-$now<0){
                if(!$isAdmin){
                    $_SESSION['userLogin']['current_duty']=$row['duty_id'];
                    $_SESSION['userLogin']['user_unit']=$row['user_unit_name'];
                    $_SESSION['userLogin']['user_group']=$row['user_group_name'];
                }
                $_SESSION['userLogin']['status']=time()<$row['end_time']?'大会期间':'闭会期间';
                $_SESSION['userLogin']['meeting']=$row['meeting'];
                $_SESSION['userLogin']['meeting_name']=$row['meeting_name'];


            }else{
//                $_SESSION['userLogin']['current_duty']=0;
            }
        }
//        $_SESSION['user']
    }
    return true;
}
function getIndex(){
    printView('index');
}

function ajaxMyMotionList($data){
        $totalNumber = -1;
//        $staffInf = $_SESSION['userLogin'];
        $count = isset($data['count']) ? $data['count'] : 20;
        $category = $_SESSION['userLogin']['category'];
//        $meeting = isset($data['meeting']) ? $data['meeting'] : $staffInf['meeting'];
        $attrOrderBy = isset($data['attr_order_by']) ? $data['attr_order_by'] : '编号';
        $attrOrder = isset($data['attr_order']) ? $data['attr_order'] : 'desc';
        $page = isset($data['page']) ? $data['page'] : 0;
        $filter = isset($data['filter']) ? $data['filter'] : null;
        $orderStr = 'order by content_int ' . $attrOrder . ',content ' . $attrOrder;
        $sortFilter = array('category' => $category, 'attr_name' => trim($attrOrderBy));
        $keyWord = isset($data['duty_type'])?$data['duty_type']:'提案人';
        $dutyList= isset($data['duty_list'])?$data['duty_list']:0;

        $limitedFilter=array('attr_name'=>$keyWord,'content_int'=>$dutyList);
        $limitTable='motion_view';
        $resultTbl="motion_view";
        $limitStr=null;
        if(isset($filter)){
            if(isset($filter['key_word']))$limitedFilter['attr_name']=$filter['key_word'];
            if(isset($filter['meeting']))$limitedFilter['meeting']=$_SESSION['userLogin']['meeting'];
            if(isset($filter['preCoop'])){
                $limitTable='pre_coop_tbl';
                $limitedFilter=array('category'=>$_SESSION['userLogin']['category']);
                $limitStr=' and end_time>'.time();
                $resultTbl='pre_motion_view';
//                mylog('preCoop');
            }

        }
        if ('当前环节' == $attrOrderBy) {
            $orderStr = 'order by step ' . $attrOrder;
            unset($sortFilter['attr_name']);
        }
        if ('编号' == $attrOrderBy) {
            $orderStr = 'order by zx_motion ' . $attrOrder;
            unset($sortFilter['attr_name']);
        }
        $field = isset($data['field']) ? $data['field'] : array('案号', '领衔人','状态', '提案人', '案别', '案由', '性质类别1', '性质类别2', '原文', '当前环节', '交办单位', '协办单位', '主办单位');
        //$sort用于储存顺序
        $sort = array();
        //$sortList用于储存返回的motion数组
        $sortList = array();
        $motionfilter = array();
        $sortFilter['motion_id']=array();
        $query=pdoQuery($limitTable,array('motion_id'),$limitedFilter,$limitStr);
        foreach ($query as $row) {
            $sortFilter['motion_id'][]=$row['motion_id'];
        }
        $totalNumber=count($sortFilter['motion_id']);


        //搜索
        if (isset($filter['search'])) {
            $attrName = $filter['search']['attr_name'];
            $attrValue = $filter['search']['attr_value'];
            $attrType = $filter['search']['attr_type'];
            if(isset($filter['search']['motion_id_limit']))$originalLimit=$filter['search']['motion_id_limit'];
            $intLimit = array();
            $searchLimit=array();
            $searchQuery=new PDOStatement();
            switch ($attrType) {
                case 'int':
                    $searchQuery = pdoQuery('motion_view', array('motion_id'), array('attr_name' => $attrName, 'content_int' => $attrValue), null);
                    break;
                case 'duty':
                    $searchDuty=pdoQuery('duty_view',array('duty_id'),null,'where user_name like "%'.$attrValue.'%"')->fetchAll();
                    $searchQuery=pdoQuery('motion_view',array('motion_id'),array('attr_name'=>$attrName,'content_int'=>$searchDuty),null);
                    break;
                case 'unit':
                    $searchUnit=pdoQuery('unit_tbl',array('unit_id'),null,'where unit_name like "%'.$attrValue.'%"')->fetchAll();
                    $searchQuery=pdoQuery('motion_view',array('motion_id'),array('attr_name'=>$attrName,'content_int'=>$searchUnit),null);
                    break;
                default:
                    $searchQuery = pdoQuery('motion_view', array('motion_id'), array('attr_name' => $attrName), "and content like \"%$attrValue%\"");
                    break;
            }
            foreach ($searchQuery as $row) {
                $searchLimit[]=$row['motion_id'];
            }
            if(isset($sortFilter['motion_id'])){
                $sortFilter['motion_id']=array_intersect($sortFilter['motion_id'],$searchLimit);
            }else{
                $sortFilter['motion_id']=$searchLimit;
            }
            if(isset($originalLimit)&&$originalLimit){
                mylog(getArrayInf($originalLimit));
                $sortFilter['motion_id']=array_intersect($sortFilter['motion_id'],$originalLimit);
            }
            $totalNumber=count($sortFilter['motion_id']);

        }
    //综合搜索
//    mylog(getArrayInf($filter));
    if(isset($filter['multiple_search'])){
        $searchLimit=null;
//        $multipleSearchFilter=null;
        $multipleSearchTempFilter=array('meeting'=>$_SESSION['userLogin']['meeting']);
        $multipleSearchMotionLimit=null;
        $filterDetail=$filter['multiple_search'];
        if(isset($filterDetail['user_unit'])){
            $multipleSearchTempFilter['user_unit']=$filterDetail['user_unit']['value'];
            unset($filterDetail['user_unit']);
        }
        if(isset($filterDetail['user_group'])){
            $multipleSearchTempFilter['user_group']=$filterDetail['user_group']['value'];
            unset($filterDetail['user_group']);
        }
        if(count($multipleSearchTempFilter)>1){
            $attrName=1==$category?"领衔人":"提案人";
            $multipleDutyList=pdoQuery('duty_tbl',array('duty_id'),$multipleSearchTempFilter,null)->fetchAll();
            $multipleSearchMotionLimit=pdoQuery('motion_view',['motion_id'],['attr_name'=>$attrName,'content_int'=>$multipleDutyList],null);
            foreach ($multipleSearchMotionLimit as $row) {
                $searchLimit[]=$row['motion_id'];
            }
        }
        mylog(getArrayInf($filter['multiple_search']));
        foreach($filterDetail as $k=>$v){
            $sMotionAttr=$v['motionAttr'];
            $sType=$v['type'];
            $sValue=$v['value'];
            $sWhere=['motion_attr'=>$sMotionAttr];
            if(is_array($searchLimit)&&count($searchLimit)>0)$sWhere['motion']=$searchLimit;
            elseif(is_array($searchLimit)&&0==count($searchLimit))break;
            $str=null;
            switch($sType){
                case 'string':
                    $str=' and content like "%'.$sValue.'%"';
                    break;
                case 'option':
                    $sWhere['content']=$sValue;
                    break;
                case 'int':
                    $sWhere['content_int']=$sValue;
                    break;
                default:
                    $sWhere['content_int']=$sValue;
                    break;
            }

            $motionQuery=pdoQuery('attr_tbl',['motion as motion_id'],$sWhere,$str);
            $motions=array();
            foreach ($motionQuery as $row) {
                $motions[]=$row['motion_id'];
            }

            if(is_array($searchLimit)){
                $searchLimit=array_intersect($searchLimit,$motions);
            }else{
                $searchLimit=$motions;
            }
        }




        if(isset($sortFilter['motion_id'])){
            $sortFilter['motion_id']=array_intersect($sortFilter['motion_id'],$searchLimit);
        }else{
            $sortFilter['motion_id']=$searchLimit;
        }
        $totalNumber=count($sortFilter['motion_id']);



    }
        $sortQuery = pdoQuery($resultTbl, array('motion_id'), $sortFilter, 'group by motion_id ' . $orderStr . ' limit ' . $page * $count . ',' . $count);
        foreach ($sortQuery as $row) {
            $sort[] = $row['motion_id'];
            $sortList[$row['motion_id']] = array();
            $motionfilter[] = $row['motion_id'];
        }



        $motionDetail = pdoQuery($resultTbl, null, array('motion_id' => $motionfilter, 'attr_name' => $field), null);
        $singleRow = null;
        foreach ($motionDetail as $row) {
//        if(!$singleRow)$singleRow=$row;
            $content = 'string' == $row['value_type'] ? $row['content'] : $row['content_int'];
            $content = 'attachment' == $row['value_type'] ? $row['attachment'] : $content;
            if ('index' == $row['value_type']) {
//                if ('duty' == $row['target'] && $content) {
//                    $content = $dutyList[$content]['user_name'];
//                } else {
                    $content = DataSupply::indexToValue($row['target'], $content);
//                }
            }


            if (!isset($sortList[$row['motion_id']][$row['attr_name']])) $sortList[$row['motion_id']][$row['attr_name']] = $content;
            else $sortList[$row['motion_id']][$row['attr_name']] .= ',' . $content;
            $sortList[$row['motion_id']]['案由'] = $row['motion_name'];
//        $sortList[$row['motion_id']]['案别']=2==$row['category']?'建议':'提案';
            $sortList[$row['motion_id']]['当前环节'] = $row['step_name'];
            $sortList[$row['motion_id']]['编号'] = $row['zx_motion'];
        }

        if ($singleRow) {

        }
        $motionIdLimit = isset($sortFilter['motion_id']) ? $sortFilter['motion_id'] : null;
        echo ajaxBack(array('list' => $sortList, 'sort' => $sort, 'totalCount' => $totalNumber, 'motionIdLimit' => $motionIdLimit,'field' => $field));
}
/**
 * ajax获取目标表的内容
 * @param $data
 */
function ajaxTargetList($data)
{
//    mylog(getArrayInf($data));
//    mylog(getArrayInf($_SESSION['userLogin']));
    $target = $data['target'];
    $filter = isset($data['filter']) ? $data['filter'] : null;
    $backList = array();
    switch ($target) {
        case 'duty':
            $filter = array('category' => $_SESSION['userLogin']['category'], 'activity' => 1);
            if (isset($_SESSION['userLogin']['duty_list'])) $filter = array_merge($filter, array('duty_id'=>$_SESSION['userLogin']['duty_list']), array('category' => $_SESSION['userLogin']['category'], 'activity' => 1));
            $dutyQuery = pdoQuery('duty_view', null, $filter, null);

//            foreach ($dutyQuery as $row) {
            if (2 == $_SESSION['userLogin']['category']) {
                $backList['unitFilt']['name'] = '按委组';
                $backList['groupFilt']['name'] = '按界别';
                $backList['unitGroup']['name'] = '党派团体';
                foreach ($dutyQuery as $row) {
                    if ($row['user_group'] && $row['user_unit']) {
                        if (!isset($backList['unitFilt']['list'][$row['user_unit']])) {
                            $backList['unitFilt']['list'][$row['user_unit']] = array('name' => $row['user_unit_name'], 'id' => 0);
                        }
                        $backList['unitFilt']['list'][$row['user_unit']]['sub'][] = array('name' => $row['user_name'], 'id' => $row['duty_id']);
                        if (!isset($backList['groupFilt']['list'][$row['user_group']])) {
                            $backList['groupFilt']['list'][$row['user_group']] = array('name' => $row['user_group_name'], 'id' => 0);
                        }
                        $backList['groupFilt']['list'][$row['user_group']]['sub'][] = array('name' => $row['user_name'], 'id' => $row['duty_id']);

                    } else {
                        $backList['unitGroup']['list'][$row['duty_id']] = array('name' => $row['user_name'], 'id' => $row['duty_id']);
                    }
                }

            } else {
                foreach ($dutyQuery as $row) {
                    if (!isset($backList['user_unit'][$row['user_unit']])) {
                        $backList['user_unit'][$row['user_unit']] = array('name' => $row['user_unit_name'], 'id' => '0');
                    }
                    $backList['user_unit'][$row['user_unit']]['sub'][] = array('name' => $row['user_name'], 'id' => $row['duty_id']);
                }

            }
//            }
            echo ajaxBack($backList);
            break;
        case 'unit':
//            $motionInf = pdoQuery('motion_tbl', null, array('motion_id' => $_SESSION['userLogin']['currentMotion']), 'limit 1')->fetch();
//            $step = $motionInf['step'] + 1;
            if ($filter) $str = 'and steps like "%5%"';
            else $str = 'where steps like "%5%"';
            $unitQuery = pdoQuery('unit_tbl', null, $filter, $str);
            foreach ($unitQuery as $row) {
                if (0 != $row['parent_unit']) {
                    $backList[0][$row['parent_unit']]['sub'][] = array('name' => $row['unit_name'], 'id' => $row['unit_id']);
                } else {
                    $backList[0][$row['unit_id']]['name'] = $row['unit_name'];
                    $backList[0][$row['unit_id']]['id'] = $row['member'] ? $row['unit_id'] : 0;
                }

            }
//            mylog(getArrayInf($backList));
            echo ajaxBack($backList);
            break;
        case 'staff':

            $motionInf = pdoQuery('motion_tbl', null, array('motion_id' => $_SESSION['userLogin']['currentMotion']), 'limit 1')->fetch();
            $step = $motionInf['step'] + 1;
            if ($filter) $str = 'and steps like "%' . $step . '%"';
            else $str = 'where steps like "%' . $step . '%"';
            $staffQuery = pdoQuery('staff_admin_view', null, $filter, $str);
            foreach ($staffQuery as $row) {
                if (!isset($backList[0][$row['unit']])) $backList[0][$row['unit']] = array('name' => $row['unit_name'], 'id' => 0);
                $backList[0][$row['unit']]['sub'][] = array('name' => $row['full_name'], 'id' => $row['staff_id']);
            }
            echo ajaxBack($backList);
            break;
        case 'motion':
            if ('all' != $_SESSION['userLogin']['meeting']) $filter['meeting'] = $_SESSION['userLogin']['meeting'];
            $motionList = pdoQuery('motion_tbl', null, $filter, ' and step>0');
            foreach ($motionList as $row) {
                $backList['list'][] = array('name' => $row['motion_name'], 'id' => $row['motion_id']);
            }
            echo ajaxBack($backList);

        default:
            break;


    }
}
function signOut(){
    unset($_SESSION['userLogin']);
    echo ajaxBack('ok');
}
function ajaxCreateNewMotion(){

}
function create_motion(){
    if(isset($_SESSION['userLogin']['create_mark'])&&$_SESSION['userLogin']['create_mark']==$_POST['motion-title'])return;//防止刷新重复提交
    $_SESSION['userLogin']['create_mark']=$_POST['motion-title'];
    include_once 'includes/upload.class.php';
    mylog(getArrayInf($_POST));
//    return;
    if(isset($_FILES['attachment-file'])&&$_FILES['attachment-file']['tmp_name']){
        $uploader=new uploader();
        $uploader->upFile(md5_file($_FILES['attachment-file']['tmp_name']));
        $fileInf=$uploader->getFileInfo();
        file_put_contents($GLOBALS['mypath'].'/original_'.$fileInf['url'], file_get_contents($GLOBALS['mypath'].'/'.$fileInf['url']));
        mylog(getArrayInf($fileInf));

    }else{
        return;
    }
    $category=$_SESSION['userLogin']['category'];
    $motionTemplate=$category;
    if(1==$category){
        $dutyMotionAttr=6;
        $dutyAttrTemp=4;
        $attachmentMotionAttr=31;
        $attachmentAttrTemp=12;
        $propMotionAttr=29;
        $propAttrTemp=9;
        $statusMotionAttr=30;
        $statusAttrTemp=8;
        $titleMotionAttr=61;
        $titleAttrTemp=3;
        $_POST['property']='当年';

    }else{
        $dutyMotionAttr=84;
        $dutyAttrTemp=43;
        $attachmentMotionAttr=21;
        $attachmentAttrTemp=12;
        $propMotionAttr=20;
        $propAttrTemp=9;
        $statusMotionAttr=16;
        $statusAttrTemp=8;
        $titleMotionAttr=36;
        $titleAttrTemp=3;
    }
    pdoTransReady();
    try{
        $motionid=pdoInsert('motion_tbl',array('motion_name'=>addslashes($_POST['motion-title']),'meeting'=>$_SESSION['userLogin']['meeting'],'category'=>$_SESSION['userLogin']['category'],'motion_template'=>$motionTemplate,'document_sha'=>$fileInf['url'],'step'=>$_POST['need-partner'],'duty'=>$_POST['duty']));
        pdoInsert('attr_tbl',array('motion'=>$motionid,'motion_attr'=>$dutyMotionAttr,'attr_template'=>$dutyAttrTemp,'content_int'=>$_POST['duty']));
        pdoInsert('attr_tbl',array('motion'=>$motionid,'motion_attr'=>$attachmentMotionAttr,'attr_template'=>$attachmentAttrTemp,'attachment'=>$fileInf['url'],'content'=>addslashes($fileInf['originalName'])));
        pdoInsert('attr_tbl',array('motion'=>$motionid,'motion_attr'=>$propMotionAttr,'attr_template'=>$propAttrTemp,'content'=>$_POST['property']));
        pdoInsert('attr_tbl',array('motion'=>$motionid,'motion_attr'=>$titleMotionAttr,'attr_template'=>$titleAttrTemp,'content'=>addslashes($_POST['motion-title'])));
        pdoInsert('attr_tbl',array('motion'=>$motionid,'motion_attr'=>$statusMotionAttr,'attr_template'=>$statusAttrTemp,'content'=>$_POST['status']));
        if(0==$_POST['need-partner']&&isset($_POST['date'])){
            mylog('need_coop');
            $preCoopStatus=time()>$_POST['date']?0:1;
            pdoInsert('pre_coop_tbl',array('category'=>$_SESSION['userLogin']['category'],'motion_id'=>$motionid,'status'=>$preCoopStatus,'end_time'=>$_POST['date']));
        }
        if (2 == $_SESSION['userLogin']['category']) {
            pdoInsert('zx_motion_tbl', array('motion' => $motionid));
        }

        pdoCommit();
    }catch(PDOException $e){
        mylog($e->getMessage());
        pdoRollBack();
    }
    unset($_POST);
    unset($_FILES);
//    mylog(getArrayInf($_POST));

}
function getMotion($data){
    global $config;
    $id = $data['motion_id'];
    $_SESSION['userLogin']['currentMotion']=$id;
    $attrFilter = array('motion_id' => $id);
    $meetingInf = pdoQuery('motion_inf_view', null, array('motion_id' => $id), ' limit 1')->fetch();
    $motionQuery = pdoQuery('pre_motion_view', null, $attrFilter, ' order by value_sort desc,motion_attr asc');
    $unitGroupInf = null;
    $userType=1==$_SESSION['userLogin']['category']?'领衔人':'提案人';
    $motion=array();
    $owner=false;
    $cooper=false;
    foreach ($motionQuery as $row) {
        if(0==$row['step']){
            if('附议人'==$row['attr_name']&&in_array($row['content_int'],$_SESSION['userLogin']['duty_list']))$cooper=true;
        }
        $values = $row;
        $optionArray = json_decode($row['option'], true);
        $values['edit'] = false;
        if (6==$row['attr_step']&&6==$row['step']) {
            $values['edit']=true;
        }
        //将attr数据转化为可为用户观看的内容
        $values['content'] = setAttrValue($row);

        if ($values['edit']) {//如操作员流程权限与当前权限吻合，则可修改当前流程选项
            $values['edit'] = true;
            if (count($optionArray) > 0) {//普通选项
                $values['option'] = array();
                foreach ($optionArray as $oRow) {
                    $values['option'][$oRow] = $oRow;
                }
                $values['class'] = 'select';
                if (!$values['content']) $values['content'] = $row['default_value'];
            }
            if ($row['target']) {//数据库内容

//                $values['filter']
            }
            //如果属性支持多值情况的处理
            if (1 == $values['multiple']) {
//                mylog('multiple');
                //如果此属性已包含一个值，且有新值存在，则把值放入multiple_value数组中，存入前先将表内索引值转换为对应的名称
                if (isset($motion[$row['attr_name']]) && $values['content']) {
//                    mylog(getArrayInf($values));
                    $motion[$row['attr_name']]['multiple_value'][$values['attr_id']] = array('content' => $values['content'], 'attachment' => $values['attachment']);

                    //如果新值存在且此属性并未包含值
                } elseif ($values['content']) {
//                    mylog('has content');
                    $motion[$row['attr_name']] = $values;
                    $motion[$row['attr_name']]['multiple_value'][$values['attr_id']] = array('content' => $values['content'], 'attachment' => $values['attachment']);
//                    mylog($row['attr_name'].': '.getArrayInf($motion));
                } else {
                    $motion[$row['attr_name']] = $values;
                }
//                mylog($values['content']);

            } else {
                $motion[$row['attr_name']] = $values;
//                $motion[$row['attr_name']]['multiple_value'][]=array('attr_id'=>$values['attr_id'],'content'=>indexToValue($row['target'],$values['content']));
            }
        } else {
            $values['edit'] = false;
            if (1 == $values['multiple']) {
                if (isset($motion[$row['attr_name']])) {
                    if ($row['attachment']) {
                        $motion[$row['attr_name']]['content'][] = array('content' => $values['content'], 'attachment' => $values['attachment']);
                    } else {
                        $tContent = $motion[$row['attr_name']]['content'] . ',' . $values['content'];
                        $tContent = trim($tContent, ',');
                        $motion[$row['attr_name']]['content'] = $tContent;
                    }
                } else {
                    if ($row['attachment']) {
                        $motion[$row['attr_name']] = $values;
                        $motion[$row['attr_name']]['content'] = array();
                        $motion[$row['attr_name']]['content'][] = array('content' => $values['content'], 'attachment' => $values['attachment']);
                    } else {
                        $motion[$row['attr_name']] = $values;
                    }
                }


            } else {
                $motion[$row['attr_name']] = $values;
            }
        }

        //获取领衔人信息
        if ('领衔人' == $row['attr_name'] || '提案人' == $row['attr_name']) {
            $query = pdoQuery('duty_view', null, array('duty_id' => $row['content_int']), 'limit 1')->fetch();
            $unitGroupInf = array('unit' => $query['user_unit_name'], 'group' => $query['user_group_name']);
        }
    }
    if(in_array($motion[$userType]['content_int'],$_SESSION['userLogin']['duty_list']))$owner=true;
    $currentStep = current($motion)['step'];

    //协办单位列表
    if($currentStep>4){
        $handlerQuery = pdoQuery('motion_handler_view', null, array('motion' => $id, 'status' =>9), null);
        $handlerDisplay = array();
        foreach ($handlerQuery as $row) {
                $handlerDisplay[] = $row;
        }
    }
//    mylog($owner.','.$cooper);
    $canCoop=$currentStep==0&&!$owner&&!$cooper&&!$_SESSION['userLogin']['is_admin'];
    include 'view/motion_inf.html.php';
    return;
}

/**
 * 解析motion_view中获取的数据，将索引或时间戳转换成可显示的值
 * @param  motion_view中的一条数据
 * @return 转换后的内容
 */
function setAttrValue($row)
{
    $content = $row['content'] ? $row['content'] : '';
    if ('int' == $row['value_type']) $content = $row['content_int'];
    if ('time' == $row['value_type'] && $row['content_int'] > 0) $content = date('Y-m-d', $row['content_int']);
    if ($row['target']) {
        $content = DataSupply::indexToValue($row['target'], $row['content_int']);
    }
    return $content;
}

/**ajax填充议案属性
 * @param $data {step:1,data:values}
 */
function updateAttr($data)
{
    $isFoward = $data['step'];
    $motionId = $_SESSION['userLogin']['currentMotion'];
    $motion = pdoQuery('motion_tbl', null, array('motion_id' => $motionId), ' limit 1')->fetch();
    $currentStep = $motion['step'];
    $attrs = isset($data['data']) ? $data['data'] : array();
    $canfoward=true;
    pdoTransReady();
    try {
        foreach ($attrs as $row) {
            $value = array();
            if ((!isset($row['value']) || !$row['value']) && $row['attr_type'] != 'attachment') {//过滤非附件的空值
                continue;
            }
            if ('attachment' == $row['attr_type']) continue;
            if ($row['attr_id']) $value['attr_id'] = $row['attr_id'];
            $value['motion'] = $motionId;
            $value['motion_attr'] = $row['motion_attr'];
            $value['attr_template'] = $row['attr_template'];
            if ('index' == $row['attr_type'] || 'int' == $row['attr_type']) {
                $value['content_int'] = $row['value'];
            } elseif ('time' == $row['attr_type']) {
                $value['content_int'] = time();
            } else {

                $value['content'] = addslashes($row['value']);
                if('不满意'==$value['content'])$canfoward=false;
            }
            pdoInsert('attr_tbl', $value, 'update');
        }
        //点击下一步的操作
        if ($isFoward > 0&&$canfoward) {
            $currentStep++;
            if(1==$currentStep)exeNew('delete from pre_coop_tbl where motion_id='.$motionId);
            pdoUpdate('motion_tbl', array('step' => $currentStep), array('motion_id' => $motionId));

        }

        mylog('ok');
        pdoCommit();
        echo ajaxBack(array('step' => $currentStep, 'id' => $motionId));
    } catch (PDOException $e) {
        mylog($e->getMessage());
        mylog($e->errorInfo);
        pdoRollBack();
        mylog('出错');
        echo ajaxBack($e->errorInfo);
    }


}
/**
 * 返回搜索框
 */
function searchMotionView($data){

    $where=array();
//    mylog(getArrayInf($data));
    $category=isset($data['category'])?$data['category']:1;
    $where['category']=$category;
    $meetingInf['category']=$where['category'];
    if(isset($data['meeting'])){
        $meetingName=pdoQuery('meeting_tbl',array('meeting_name'),array('meeting_id'=>$data['meeting']),'limit 1')->fetch()['meeting_name'];
    }
    $motion=array();
    $query=pdoQuery('motion_attr_view',null,array('motion_template'=>$where['category']),null);
    foreach ($query as $row) {
        if($row['option'])$row['option']=json_decode($row['option'],true);
        $motion[$row['attr_name']]=$row;
        //获取领衔人信息
//        if ('领衔人' == $row['attr_name'] || '提案人' == $row['attr_name']) {
//            $query = pdoQuery('duty_view', null, array('duty_id' => $row['content_int']), 'limit 1')->fetch();
//            $unitGroupInf = array('unit' => $query['user_unit_name'], 'group' => $query['user_group_name']);
//        }
    }
    $userGroup=pdoQuery('user_group_tbl',['user_group_id','user_group_name'],['category'=>$category],null)->fetchAll();
    $userUnit=pdoQuery('user_unit_tbl',['user_unit_id','user_unit_name'],['category'=>$category],null)->fetchAll();

    include '/view/search2.html.php';
    return;




}
function unsetCurrentMotion(){
    unset($_SESSION['userLogin']['currentMotion']);
    echo ajaxBack( 'ok');
}

function ajaxAddCoop($data){

    echo ajaxBack('ok');
}