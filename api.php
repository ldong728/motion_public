<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/17
 * Time: 9:25
 */
include_once 'includePackage.php';
//include_once 'functions.php';
define('REMOTE_IP',"172.19.48.50");
global $backData;
$remote=$_SERVER['REMOTE_ADDR'];
//mylog($remote);
$token=isset($_GET['token'])&&$_GET['token']?$_GET['token']:'';
$backData=array('errorCode'=>0,'errorMsg'=>'');

if(REMOTE_IP!=$remote){
    mylog('无权限,from'.$remote);
    $backData['errorCode']=101;
    $backData['无权限'];
    echo json_encode($backData);
    exit;
}

if(isset($_FILES)){
//    mylog(getArrayInf($_POST));
//    mylog(getArrayInf($_FILES));
    if(isset($_POST['file_name'])){
        $fileName=$_POST['file_name'];
    }else{
        setErrorInf('102',"缺少文件名");
    }
    if(isset($_FILES['file'])){
        mylog('file：'.$fileName);
       if(!move_uploaded_file($_FILES['file']['tmp_name'],'files/'.$fileName)){
           setErrorInf('103',"保存出错");
//           mylog('保存出错');
       }
    }
    if(isset($_FILES['original'])){
        if(!move_uploaded_file($_FILES['file']['tmp_name'],'files/'.$fileName)){
            setErrorInf('103',"保存出错");
//           mylog('保存出错');
        }
        if(!move_uploaded_file($_FILES['file']['tmp_name'],'files/'.$fileName)){
            setErrorInf('103',"保存出错");
//           mylog('保存出错');
        }
    }

//    echo 'ok';
}
mylog(getArrayInf($backData));
echo json_encode($backData);
exit;

function setErrorInf($code,$msg){
    global $backData;
    $backData['errorCode']=$code;
    $backData['errorMsg']=$msg;
}




