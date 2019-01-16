<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/23
 * Time: 9:25
 */
include_once 'includePackage.php';
include_once 'includes/DataSupply.class.php';
//include_once 'functions.php';
//define('REMOTE_IP',"172.19.48.50");
$category=1;

if(isset($_GET['meeting_list'])){
    $query=pdoQuery('meeting_tbl',null,['category'=>$category],'order by meeting_id desc')->fetchAll();
    echo json_encode($query,JSON_UNESCAPED_UNICODE);
    exit;
}

if(isset($_GET['meeting_id'])){
    getMotionInf($_GET['meeting_id']);
    exit;
}

getMotionInf();

exit;


function getMotionInf($meetingID=null){
    $currentMeetingId=pdoQuery('meeting_tbl', ['meeting_id'], ['category' => $GLOBALS['category']], 'order by meeting_id desc limit 1')->fetch()['meeting_id'];
    if($currentMeetingId==$meetingID||!$meetingID){//当前会议
        $currentTime = time();
        $file = file_get_contents('cache/motion_list.json');
        if (!$file) {
            echo "none";
        } else {
            $json = json_decode($file, true);
            $lastUpdateTime = $json['time'];
            if ($currentTime - $lastUpdateTime > 3600 * 24) {
                $json['time'] = $currentTime;
                $json['content']=regroupMotionInf($currentMeetingId);
                file_put_contents('cache/motion_list.json', json_encode($json,JSON_UNESCAPED_UNICODE));
            } else {
            }
            echo json_encode($json['content'],JSON_UNESCAPED_UNICODE);
            exit;
        }
    }else{//历届会议
        $meeting=pdoQuery('meeting_tbl',['meeting_id'],['category'=>$GLOBALS['category'],'meeting_id'=>$meetingID],' limit 1')->fetch();
        if(!$meeting){
            echo 'none';
            exit;
        }
        if(file_exists('cache/motion_list_'.$meetingID.'.json')){
            $file = file_get_contents('cache/motion_list_'.$meetingID.'.json');
            echo $file;
        }else{
            $json=json_encode(regroupMotionInf($meetingID),JSON_UNESCAPED_UNICODE);
            echo $json;
            file_put_contents('cache/motion_list_'.$meetingID.'.json',$json);

        }
        exit;
    }
}


function regroupMotionInf($meetingId)
{

    $motionQuery = pdoQuery('motion_view', ['motion_id','attr_id','motion_attr', 'attr_name', 'step_name', 'meeting', 'category', 'content', 'content_int', 'target', 'value_type', 'attachment', 'step'], ['category' => 1, 'meeting' => $meetingId], null);

    $motionHandler=pdoQuery('motion_handler_inf_view',null,['meeting'=>$meetingId],null);
    $handler=[];
    $motionHandler->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($motionHandler as $row) {
        if($row['attachment'])$row['attachment']='http://183.136.192.58/motion_public/'.$row['attachment'];
        $handler[$row['attr']]=$row;
    }
//    mylog($handler);
    $dutyQuery=pdoQuery('duty_view',['duty_id','user_unit_name','user_group_name'],['meeting'=>$meetingId],null);
    $dutyQuery->setFetchMode(PDO::FETCH_ASSOC);
    $duty=[];
    foreach ($dutyQuery as $row) {
        $duty[$row['duty_id']]=$row;
    }
//    mylog($duty);


    $motionInf = [];
    foreach ($motionQuery as $row) {
        $content=[trim($row['attr_name'],'1')];
        $row['content'] = $row['content'] ? $row['content'] : $row['content_int'];
        if ($row['target']){
            $row['content'] = DataSupply::indexToValue($row['target'], $row['content']);
            $content[]=$row['content'];

            if('领衔人'==$row['attr_name']){
                $duty[$row['content']]=isset($duty[$row['content']])?$duty[$row['content']]:['user_unit_name'=>'','user_group_name'=>''];
//                mylog($row);
                $content[]=$duty[$row['content_int']]['user_unit_name'];
                $content[]=$duty[$row['content_int']]['user_group_name'];
//                mylog($content);
            }
            if('协办单位'==$row['attr_name']){
                $handler[$row['attr_id']]=isset($handler[$row['attr_id']])?$handler[$row['attr_id']]:[];
                $content[]=$handler[$row['attr_id']];
            }

        }else{
            $content[]=$row['content'];
        }

        if (!isset($motionInf[$row['motion_id']])) {
            $motionInf[$row['motion_id']] = [];
        }

        if($row['attachment']){
            $content[]='http://183.136.192.58/motion_public/'.$row['attachment'];
        }
        if(!isset($motionInf[$row['motion_id']][$row['motion_attr']]))$motionInf[$row['motion_id']][$row['motion_attr']]=[];
//        mylog($content);
        $motionInf[$row['motion_id']][$row['motion_attr']][] =$content;

    }
    return $motionInf;

}




