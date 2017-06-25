<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/11/3
 * Time: 23:20
 */


function printView($viewName,$title='提案议案处理系统'){
    include 'template/header.html.php';
    include 'view/'.$viewName.'.html.php';
}

function getUserList(){
    if($_SESSION['staffLogin']['meeting']=='all')return null;
    if(isset($_SESSION['staffLogin']['userList'])){
        foreach ($_SESSION['staffLogin']['userList'] as $k => $v) {
            $filter[$k]=$v;
        }
        mylog(getArrayInf($filter));
        mylog(getArrayInf($_SESSION['staffLogin']['userList']));
        $query=pdoQuery('duty_view',array('duty_id as id','user_name as name'),$filter,null);
        $userList=$_SESSION['staffLogin']['category']==1?array('0'=>'选择人大代表'):array('0'=>'选择政协委员');
        foreach ($query as $row) {
//            mylog(getArrayInf($row));
            $userList[$row['id']]=$row['name'];
        }
        return array('class'=> 'attr-value user-selectr','list'=>$userList);
    }else{
        return 1==$_SESSION['staffLogin']['category']?array('class'=>'duty-group','list'=>array('0'=>'选择人大代表','unit'=>'按所属单位','group'=>'按代表团')):array('class'=>'duty-group','list'=>array('0'=>'选择政协委员','unit'=>'按委组','group'=>'按界别'));
    }
}
function getUnitList($parentId='all',$step=''){
    $id='all'==$parentId?0:$parentId;
    $class='all'==$parentId?'unit-super':'attr-value unit-sub';
        $query=pdoQuery('unit_tbl',array('unit_id as id','unit_name as name'),array('parent_unit'=>$id),' and steps like "%'.$step.'%"');
        $list=array('0'=>'选择单位');
        foreach ($query as $row) {
            $list[$row['id']]=$row['name'];
        }
        return array('class'=>$class,'list'=>$list);
}
function indexToValue($target,$index){
//    global $duty,$unit;
    if(isset($index)&&$index!=null){
        switch($target){
            case 'duty':
                return pdoQuery('duty_view',array('user_name'),array('duty_id'=>$index),' limit 1')->fetch()['user_name'];
                break;
            case 'unit':
                return pdoQuery($target.'_tbl',array($target.'_name'),array($target.'_id'=>$index),' limit 1')->fetch()[$target.'_name'];
                break;
        }
    }else{
        return null;
    }

}
//function post
