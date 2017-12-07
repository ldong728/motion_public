<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/27
 * Time: 9:58
 */
include_once 'includePackage.php';
include_once 'function.php';
include_once 'includes/DataSupply.class.php';
global $userInf;
session_start();
if($config['server_status']>0){
    echo '网站维护中，请稍后重试,状态码：'.$config['server_status'];
    exit;
}
if(isset($_GET['suggestion'])){
    $typeList=["工业经济","农林水利","财贸金融","道路交通","城建管理","环境保护","医药卫生","科技教育","文化体育","劳动人事","政法统战","其他"];
    $isUpdated=0;
    if(isset($_POST['suggestion_update'])){
        $content=[['name'=>addslashes($_POST['name']),'tel'=>addslashes($_POST['tel']),'type'=>addslashes($_POST['type']),'content'=>addslashes($_POST['content']),'from_ip'=>$_SERVER['REMOTE_ADDR'],'status'=>0]];
        pdoBatchInsert('suggestion_tbl',$content);
        $isUpdated=1;
        unset($_POST);
        printView('suggestion','提案线索征集页');
        exit;
    }

    printView('suggestion','提案线索征集页');
    exit;
}
if(isset($_SESSION['userLogin'])){
//    mylog(getArrayInf($_POST));
    $userInf=$_SESSION['userLogin'];
    if(isset($_POST['ajax'])){
        $ajaxData=isset($_POST['ajax_data'])?$_POST['ajax_data']:null;
        $_POST['ajax']($ajaxData);
        exit;
    }
    if(isset($_POST['post_method'])){

        $_POST['post_method']();
        getIndex();
        exit;
    }

   getIndex();
}else{
    if(isset($_POST['user'])&&isset($_POST['password'])&&isset($_POST['category'])){
        $category=$_POST['category'];
        if(userAuth($_POST['user'],$_POST['password'],$category)){
//            echo getArrayInf($_SESSION);
            getIndex();
            exit;
        }
        unset($_POST['password']);
        header('Location:index.php?c='.$category.'&error=password');
        exit;
    }else{
        global $category;
        $category =isset($_GET['c'])?$_GET['c']:2;
        if(isset($_GET['user'])&&isset($_GET['password'])){
            userAuth($_GET['user'],$_GET['password'],$category);
            header('Location:index.php?c='.$category.'&error=password');
            exit;
        }
        $title='';
        switch($category){
            case 1:
                $title='人大登入-慈溪市人大代表议案建议系统';
                break;
            case 2:
               $title='政协登入-慈溪市政协提案办理系统';
                break;

        }
        printView('login'.$category,$title);

    }
}