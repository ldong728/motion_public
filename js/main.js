function showToast(str){
    $('.toast').empty();
    $('.toast').append(str)
    $('.toast').fadeIn('fast')
    var t = setTimeout('$(".toast").fadeOut("slow")', 800);
}
function loading(){
    $('.loading').show();
}
function stopLoading(){
    $('.loading').hide();
}
function backHandle(data){
    var re=eval('('+data+')');
    if(0==re.errcode){
        var state= null==re.data?0:re.data;
        //console.mylog(state);
        return state;
    }else{
        console.log('error: '+re.errmsg);
        return false;
    }
}
function ajaxPost(method,ajaxData,callback){
    loading();
    $.post('index.php',{ajax:method,ajax_data:ajaxData},function(data){
        stopLoading();
        callback(data);
    });
}
function mylog(data){
    console.log(data);
}
function signOut(category){
    ajaxPost('signOut',{},function(data){
        var value=backHandle(data);
        if('ok'==value)location.href='index.php?c='+category;
        else console.log(value);
    });
}
function prepareElement(){
    var returnData={};
    var classList=[];
    $.each(arguments,function(k,v){
        if($(v.toString()).length>0){
            returnData[v]=$(v).clone();
        }
        classList.push(v);
    });

    $.each(classList,function(k,v){
        $(v).remove();
    });
    return returnData;
}
function loading(){

}

function stopLoading(){

}