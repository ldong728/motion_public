<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/13
 * Time: 10:38
 */
include_once 'includePackage.php';
include_once $mypath . '/includes/upload.class.php';
session_start();
//mylog(getArrayInf($_SESSION));
//mylog(getArrayInf($_GET));
if(isset($_SESSION['staffLogin'])&&$_SESSION['staffLogin']['currentMotion']){
    $step=pdoQuery('motion_tbl',array('step'),array('motion_id'=>$_SESSION['staffLogin']['currentMotion']),'limit 1')->fetch()['step'];
    if(isset($_FILES)&&isset($_GET['attachment'])){
        foreach ($_FILES as $k => $v) {
            $uploader=new uploader($k);
            $fileName=md5_file($_FILES[$k]['tmp_name']);
            $uploader->upFile($fileName);
            $inf=$uploader->getFileInfo();
            if('SUCCESS'==$inf['state']){
                    $value=array('motion'=>$_SESSION['staffLogin']['currentMotion'],'motion_attr'=>$_GET['ma'],'attr_template'=>$_GET['at'],'content'=>addslashes($inf['originalName']),'attachment'=>addslashes($inf['url']),'staff'=>$_SESSION['staffLogin']['staffId']);
                    try{
                        if($_GET['a']>0&&!$_GET['mul']){
                            $value['attr_id']=$_GET['a'];
                        }
                        $id=pdoInsert('attr_tbl',$value,'update');
                        $inf['attrId']=$id;
                    }catch(PDOException $e){
                        $inf['state']='fail';
                    }

            }
            $inf['step']=$step;
            echo json_encode($inf);
            if(1==$step){
                file_put_contents($GLOBALS['mypath'].'/original_'.$inf['url'], file_get_contents($GLOBALS['mypath'].'/'.$inf['url']));
                pdoUpdate('motion_tbl',array('document'=>addslashes($inf['originalName']),'document_sha'=>$inf['url']),array('motion_id'=>$_SESSION['staffLogin']['currentMotion']),'limit 1');
            }
        }
    }
}
if(isset($_FILES)&&isset($_GET['handler_attachment'])){
    mylog();
    foreach ($_FILES as $k => $v) {
        $uploader=new uploader($k);
        $fileName=md5_file($_FILES[$k]['tmp_name']);
        $uploader->upFile($fileName);
        $inf=$uploader->getFileInfo();
        $value=array('attachment'=>$inf['url'],'attachment_name'=>addslashes($inf['originalName']));
        try{
            $handlerId=$_GET['handler_attachment'];
            pdoUpdate('motion_handler_tbl',$value,array('motion_handler_id'=>$handlerId,'motion'=>$_SESSION['staffLogin']['currentMotion']),' limit 1');
            echo json_encode($inf);
        }catch(PDOException $e){
            $inf['state']='fail';
            echo json_encode($inf);
        }
    }
}
//if(isset($_FILES)&&isset($_GET['handler_attachment']))

//exit;