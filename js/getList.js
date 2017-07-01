var dutyType=1==user.category?"领衔人":"提案人";
var dutyList=user.current?user.current_duty:user.duty_list;
var total=0;
var totalPages=0;
var category=user.category;
var orderby=2==user.category?'编号':'案号';
var order=true;
var page=0;
var filter={};
var count=20;
var motionIdLimit=null;
//resizeWindow();
reflashList(orderby,page,order);
//$(window).resize(function(){
//    resizeWindow();
//    mPopup();
//});
$('.count-in-page').change(function(){
    count=parseInt($(this).val());
    reflashList(orderby,page,order);
});
$('.motion-filter').click(function(){
    var filterType=$(this).data('filter');
    switch(filterType){
        case 'coop':
            filter={key_word:'附议人'};
            break;
        case 'preCoop':
            filter={preCoop:1};
            break;
        case 'meeting':
            console.log('meeting');
            filter={meeting:1};
            break;
        default :
            filter={};
    }
    reflashList(orderby,page,order);
});
$('.order-by-attr').click(function(){
    var newOrderby=($(this).text());
    if('性质类别'==newOrderby)newOrderby+=category;
    if(orderby==newOrderby){
        order=!order;
    }else{
        order=true;
        orderby=newOrderby
    }
    var orderText=order?'(升序)':'(降序)';
    reflashList(orderby,page,order);
    $('.order-disply').text(newOrderby+orderText);
});
$(document).on('click','.sign-out',function(){
    signOut(user.category);
});
$(document).on('click','.next-page',function(){
    if(page<totalPages){
        page++;
        reflashList(orderby,page,order);
        console.log(page);
    }

});
$(document).on('click','.prev-page',function(){
    if(page>0){
        page--;
        reflashList(orderby,page,order);
    }

});
$(document).on('click','.first-page',function(){
    page=0;
    reflashList(orderby,page,order);
});
$(document).on('click','.last-page',function(){
    page=totalPages-1;
    reflashList(orderby,page,order)
});

$(document).on('click','.multiple-statistics',function(){
    location.href="?download=multiple_statistics";
});
function pageJump(element,event){
//        console.log(event.keyCode);
    if(13==event.keyCode){
        var sPage= $.trim(element.value);
        if(sPage.match(/^-?[1-9]\d*$/)&&sPage>0&&sPage<totalPages+1){
            page=sPage-1;
            reflashList(orderby,page,order);
        }else{
            alert('页码错误');
        }
    }
}
function resizeWindow(){
    var bHeight = $(document.body).height();
    var wHeight = $(window).height();
    var bWeight = $(document.body).width();
    var weight = bWeight - 239;
    $('.home-r').css('width',weight);
}
function reflashList(sOrderby,sPage,sOrder){
//        console.log('reflash');
    var data={
        duty_type:dutyType,
        duty_list:dutyList,
        category:category,
//        meeting:meetingId,
        attr_order_by:sOrderby||orderby,
        attr_order:sOrder?'asc':'desc',
        page:sPage||page,
        filter:filter,
        count:count
    };

    ajaxPost('ajaxMyMotionList',data,formatNormalData);
}
function reCalculate(totalCount){
    total=totalCount;
    totalPages=Math.ceil(total/count);
    var last=totalCount<((page+1)*count)?totalCount:(page+1)*count;
    $('.current-page').text(page+1);
    $('.total-page').text('共'+totalPages+'页');
    $('.current-num').text((page*count+1)+'-'+last);
    $('.total-num').text(totalCount);
    //$('.page-inf').text(''+(page*count+1)+'-'+last+'共'+totalCount+'条记录，共'+totalPages+'页');
    //console.log(totalPages);

}

function formatNormalData(back){
    //return;
    var type=1==user.category?'领衔人':'提案人';
    var myCount=1+(page*count);
    var title=' <th>&nbsp;&nbsp;</th><th>案号</th><th>'+type+'</th><th>状态</th><th>环节</th><th>案由</th><th>主办单位</th><th>协办单位</th>';
    $('.list-title').html(title);
    $('.list-container').empty();
    var value=backHandle(back);
    var c=value.list;
    $.each(value.sort,function(k,v){
        if(v>0){
            var content='<tr class="tr1 trr1 table-element">';
            content+='<td>'+(myCount++)+'</td>';
            content+='<td>'+(c[v]['案号']||'')+'</td>';
            content+='<td>'+(c[v][type]||'')+'</td>';
            content+='<td>'+(c[v]['状态']||'')+'</td>';
            content+='<td>'+(c[v]['当前环节']||'')+'</td>';
            content+='<td style="cursor: pointer" class="motion-content" id="mot'+v+'">'+c[v]['案由']||''+'</td>';
            content+='<td>'+(c[v]['主办单位']||'')+'</td>';
            content+='<td>'+(c[v]['协办单位']||'')+'</td>';
            content+='</td></tr>';
            $('.list-container').append(content);

        }
    });
    reCalculate(value.totalCount);
    motionIdLimit=value.motionIdLimit;

}
function formatBack(back){
    var value=backHandle(back);
    $('.list-content').remove();
    var myCount=1+(page*count);
    var c=value.list;
//                console.log(c);
    if(1==data.category) {
        $.each(value.sort, function (k, v) {
            if(v>0){
                var unitName='';
                var delButton=staff.steps.indexOf('3')>-1?'<td><button class="delete-motion" id="del'+v+'">X</button></td>':'';
                if(['审核','登记','反馈'].indexOf(c[v]['当前环节'])>-1)unitName='市人大代工委';
                if('交办'==c[v]['当前环节'])unitName=c[v]['交办单位']||'市政府督查室';
                if('办理'==c[v]['当前环节'])unitName=c[v]['主办单位']||'';
                var listContent = '<tr class="list-content">' +
                    '<td>' + (myCount++) +
                    '<td><input type="checkbox" class="check" value='+v+'></td>' +
                    '<td>' + (c[v]['案号']||'')+ '</td>' +
                    '<td>' + (c[v]['领衔人']||'') + '</td>' +
                    '<td>' + (c[v]['案别']||'') + '</td>' +
                    '<td class="motion-select" id="' + v + '"><a href="#">' + (c[v]['案由']||'') + '</a></td>' +
                    '<td>' + (c[v]['性质类别' + category]||'')+ '</td>' +
                    '<td><a href="' + (c[v]['原文'] || '#') + '">附件</a></td>' +
                    '<td>' + (c[v]['当前环节']||'') + '</td>' +
                    '<td>' + unitName + '</td>' +
                    '<td style="white-space: nowrap;text-overflow: clip; overflow: hidden">' + (c[v]['协办单位']||'') + '</td>' +
                    delButton+
                    '</tr>';
                $('.list-table').append(listContent);
            }


        });
    }else{
        $.each(value.sort, function (k, v) {
            if(v>0){
                var unitName='';
                var delButton=staff.steps.indexOf('3')>-1?'<td><button class="delete-motion" id="del'+v+'">X</button></td>':'';
                if(['审核','登记','反馈'].indexOf(c[v]['当前环节'])>-1)unitName='市政协办提案委';
                if('交办'==c[v]['当前环节'])unitName=c[v]['交办单位']||'市政府督查室';
                if('办理'==c[v]['当前环节'])unitName=c[v]['主办单位']||'';
                var listContent = '<tr class="list-content">' +
                    '<td>' + (myCount++) +
                    '<td><input type="checkbox" class="check" value='+v+'></td>' +
                    '<td>' + c[v]['编号'] + '</td>' +
                    '<td>' + (c[v]['案号']||'') + '</td>' +
                    '<td>' + (c[v]['提案人']||'') + '</td>' +
//                            '<td>' + c[v]['案别'] + '</td>' +
                    '<td class="motion-select" id="' + v + '"><a href="#">' + c[v]['案由'] + '</a></td>' +
                    '<td>' + (c[v]['性质类别' + category]||'') + '</td>' +
                    '<td><a href="' + (c[v]['原文'] || '#') + '">附件</a></td>' +
                    '<td>' + (c[v]['当前环节']||'') + '</td>' +
                    '<td>' + unitName + '</td>' +
                    '<td style="white-space: nowrap;text-overflow: clip; overflow: hidden">' + (c[v]['协办单位']||'') + '</td>' +
                    delButton+
                    '</tr>';
                $('.list-table').append(listContent);
            }


        });
    }
    reCalculate(value.totalCount);
    motionIdLimit=value.motionIdLimit;
    console.log(motionIdLimit);


}
    var searchAttrName;
    var searchAttrType;
    //搜索脚本
    $('.search').click(function(){
        searchAttrName=$(this).data('filter');
        searchAttrType=$(this).data('type');
        $('.search-input').attr('placeholder',$(this).text()+'搜索');
        $('.search-input').val('');
        $('.search-container').show();
//        alert(attrName);
        });
    $('.search-button').click(function(){
        var input=$('.search-input');
        if($.trim(input.val())){
        filter.search={attr_name:searchAttrName,attr_value:input.val(),attr_type:searchAttrType};
        if($(this).hasClass('inner'))filter.search.motion_id_limit=motionIdLimit;
        reflashList(orderby,page,order);
        }
        $('.search-mask').click();

        });

    $('.search-mask').click(function(){
//        delete filter.search;
        $('.search-container').hide();
        })
