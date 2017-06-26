<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/17
 * Time: 13:53
 */
function userAuth($user,$psd,$category){
    mylog('userAuth');
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
        $staffInf=pdoQuery('staff_tbl',array('full_name','user_admin'),array('staff_name'=>$userId,'staff_password'=>$password),' and user_admin <> "{}" limit 1')->fetch();
        if($staffInf){
            $isAdmin=true;
            $_SESSION['userLogin']['isAdmin']=true;
            $dutyList=pdoQuery('duty_view',null,json_decode($staffInf['user_admin'],true),null);
        }else{
            return false;
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
//        $dutyList = array();
//        $countFilter = array('category' => $category);


        //获取代表委员数据，用以替换数据中的索引值
//        $dutyQuery = pdoQuery('duty_view', array('duty_id', 'user_name', 'user_unit_name', 'user_unit', 'user_group', 'user_group_name'), array('meeting' => $meeting), null);
//        foreach ($dutyQuery as $row) {
//            $dutyList[$row['duty_id']] = $row;
//        }
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
        $query=pdoQuery('motion_view',array('motion_id'),array('attr_name'=>$keyWord,'content_int'=>$dutyList),null);
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


//        if (-1 == $totalNumber) {
//            $totalNumber = pdoQuery('motion_tbl', array('count(*) as count'), array('meeting' => $meeting), 'and step>0')->fetch()['count'];
//        }
        $sortQuery = pdoQuery('motion_view', array('motion_id'), $sortFilter, 'group by motion_id ' . $orderStr . ' limit ' . $page * $count . ',' . $count);
        foreach ($sortQuery as $row) {
            $sort[] = $row['motion_id'];
            $sortList[$row['motion_id']] = array();
            $motionfilter[] = $row['motion_id'];
        }


        $motionDetail = pdoQuery('motion_view', null, array('motion_id' => $motionfilter, 'attr_name' => $field), null);
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
//    include_once 'upload.php';
mylog(getArrayInf($_POST));
    $category=$_SESSION['userLogin']['category'];



}