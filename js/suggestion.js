/**
 * Created by Administrator on 2017/10/26.
 */
$(document).on('click', '#li4', function () {
    ajaxPost('getSuggestion', {}, handleSuggestionList);
    //$('.popup1').show();
});
$(document).on('click','.content-detail',function(){
    var id=this.id.slice(3);
    ajaxPost('getSuggestionDetail',{id:id},handleSuggestionDetail);
});


function handleSuggestionList(data) {
    var back = backHandle(data);
    //console.log(back);
    if (back) {
        $('.list-title').empty();
        $('.list-container').empty();
        $('.list-title').html('<th>姓名</th><th>电话</th><th>性质类别</th><th>提交时间</th><th>操作</th>');
        $.each(back, function (k, v) {
            var content = '<tr>'
            content += '<td>' + v.name + '</td>' +
            '<td>' + v.tel + '</td>' +
            '<td>' + v.type + '</td>' +
            '<td>' + v.update_time + '</td>' +
            '<td><button class="content-detail" id="con' + v.suggestion_id + '">查看详情</button></td></tr>';
            $('.list-container').append(content);
        });
    }else{
        alert('暂无内容，请稍后再试');
    }
}
function handleSuggestionDetail(data){
    var back=backHandle(data);
    console.log(data);
    if(back){
        $('.suggestion-detail').text(back);
        $('.popup5').show();
        //alert(back);
    }
}
