<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/3
 * Time: 8:48
 */

class DataSupply {
    private static $data=array();

    public static function indexToValue($targetName,$index){
//        mylog(getArrayInf(DataSupply::$data));
        $content='';
        if(!$index)return $content;
        if(isset(DataSupply::$data[$targetName][$index])){
            $content=DataSupply::$data[$targetName][$index];
        }else{
            switch($targetName){
                case 'unit':
                    $unitQuery=pdoQuery('unit_tbl',array('unit_id as id','unit_name as name'),null,null);
                    foreach ($unitQuery as $row) {
                        DataSupply::$data[$targetName][$row['id']]=$row['name'];
                        if($index==$row['id'])$content=$row['name'];
                    }
                    break;
                case 'duty':
//                    $dutyQuery=pdoQuery('duty_view',array('duty_id as id','user_name as name'),array('activity'=>1),null);
//                    foreach ($dutyQuery as $row) {
//                        DataSupply::$data[$targetName][$row['id']]=$row['name'];
//                        if($index==$row['id'])$content=$row['name'];
//                    }
                    $dutyInf=pdoQuery('duty_view',array('duty_id as id','user_name as name'),array('activity'=>1,'duty_id'=>$index),'limit 1')->fetch();
                    if($dutyInf)$content=$dutyInf['name'];
                    break;
                case 'staff':
                    $staffInf=pdoQuery('staff_tbl',array('staff_id as id','full_name as name'),array('staff_id'=>$index),'limit 1')->fetch();
                    if($staffInf){
                        DataSupply::$data[$targetName][$index]=$staffInf['name'];
                        $content=$staffInf['name'];
                    }
                    break;
                default:
                    $inf=pdoQuery($targetName.'_tbl',array($targetName.'_id as id',$targetName.'_name as name'),array($targetName.'_id'=>$index),'limit 1')->fetch();
                    if($inf)$content=$inf['name'];
                    break;
            }

        }
        return $content;

    }
} 