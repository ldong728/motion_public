// JavaScript Document
$(document).ready(function(){
	reSize();
	$(window).resize(function(){
		reSize();
	})
	
	function reSize(){
		var secWidth =  document.body.offsetWidth;
		var bHeight = $(window).height();
		var topHeight = 170;
		var asiWidth = 190;
		
		var secHeight = bHeight-topHeight;
		var maiWidth = secWidth - asiWidth;
		$('#main').css('width',maiWidth);
		$('#section').css('height',secHeight);
		$('.table-box').css('height',secHeight-75);
	}
	
	$(document).on('click','.home-nav li',function(){
		$(this).siblings().removeClass('li-cur');
		$(this).addClass('li-cur');
	});
	
	$(document).on('click','#li1',function(){
        console.log(user);

		$('.popup1').show();
	});
	$(document).on('click','#li2',function(){
		$('.popup2').show();
	});
	$(document).on('click','.mask',function(){
        $('.target-value-selecter').removeClass('target-value-selecter');
		$('.popup').hide();
	});
    $(document).on('click','.close-popup',function(){
        $('.target-value-selecter').removeClass('target-value-selecter');
        $('.popup').hide();
    });
    $(document).on('click','.submit',function(){
        var error=false;
        $('.create-form').find('input').each(function(k,v){
            if('motion-title'==$(v).attr('name')&&!$.trim($(v).val()))error='案由不能为空';
            if('attachment-file'==$(v).attr('name')&&!$(v).val())error="附件未上传";
        });
        if(error){
            alert(error);
        }else{
            if($(this).hasClass('get-partner')){
                $('#need-partner').val(0);
                var setDate=$('#date-selector').val();
                if(setDate){
                    var dateTimeStamp=Date.parse(new Date(setDate));
                    dateTimeStamp=(dateTimeStamp+'').slice(0,10);
                    $('.date-input').val(dateTimeStamp);
                }else{
                    alert('截止日期未设置');
                    return;
                }

                console.log(dateTimeStamp);
            }
            $('.create-form').submit();
        }
    });
    //$(document).on('click','#date-selector',function(){
    $('.date-btn').click(function () {
        laydate({
            elem: '#date-selector',
            min: laydate.now(+2),
            max: laydate.now(+30)
        });
    });
    $(document).on('click','.motion-content',function(){
        var id=$(this).attr('id').slice(3);
        ajaxPost('getMotion',{motion_id:id},function(data){
            //console.log(data);
            $('.motion-info').html(data)
            $('.popup4').show();

        });
    })

	
});

$(document).on('click', '.target-select', function () {
    console.log()
    if ($('.target-value-selecter').length > 0)return;
    var _ = $(this);
    var f = _.parent();
    var target = _.data('target');
    var multiple = _.data('multiple');
    var selecterName = f.prev('th').text();
    var existValue = [];
    f.addClass('target-value-selecter');
    //$.each(f.find('.pre-delete'), function (k, v) {
    //    var id = $(v).prev('.added-value').val();
    //    var attrId = $(v).attr('id');
    //    existValue.push({id: id, name: $(v).text(), attrId: attrId});
    //});

    getTargetList(target, null, function (back) {
        var listData = backHandle(back);
        //console.log($(listData).length);
        //console.log(listData);
        $('.selecter-content').empty();
        var listContent = '';
        var chosenContent = '';
        //填充待选项
        $.each(listData, function (k1, v1) {

            var value1 = v1.list || v1;
            var hidden = v1.list ? 'style="display:none"' : '';
            listContent += v1.list ? '<div class="nav-tab"><h2 class="main-category"><i class="icon icon-chevron-right"></i>' + v1.name + '</h2>' : '<div class="nav-tab">';
            $.each(value1, function (k2, v2) {
                var icon=v2.sub?'-':'.';
                listContent += '<ul ' + hidden + '><li class="li-1 clearfix">' +
                '<button class="btn-1 main-candidate-btn li-btn-all b-fir" type="button">'+icon+'</button>' +
                '<input class="checkbox candidate super" type="checkbox" name="checkbox-lv1" value="' + v2.id + '">' +
                '<button class="btn-2 li-btn-all b-sec" type="button"></button>' +
                '<span class="span-1 candidate-name">' + v2.name + '</span>' +
                '</li>';
                if (v2.sub) {
                    listContent += '<li class="li-2"><ul>';
                    $.each(v2.sub, function (k3, v3) {
                        listContent += '<li class="li-lv2 main-candidate clearfix">' +
                        '<button class="btn-lv2-1 li-btn-all b-thi" type="button"></button>' +
                        '<input class="checkbox candidate sub" type="checkbox" name="checkbox-lv2" value="' + v3.id + '">' +
                        '<button class="btn-lv2-2 li-btn-all b-sec" type="button"></button>' +
                        '<span class="span-1 candidate-name">' + v3.name + '</span>' +
                        '</li>'
                    });
                    listContent += '</li></ul>'
                }

                listContent += '</ul>';
            });
            listContent += v1.list ? '</div>' : '';

        });
        //listContent+='';
        //console.log(listContent);
        $('.selecter-content').append(listContent);
        //填充已选项
        $('.target-chosen-ul').empty();
        //$.each(existValue, function (k, v) {
        //    var attrIdContent = '';
        //    if (v.attrId)attrIdContent = '<input type="hidden" class="exist-attr-id" value="' + v.attrId + '">';
        //    chosenContent += '<li class="clearfix">' + attrIdContent +
        //    '<input class="checkbox exist" type="checkbox" name="checkbox-lv1" value="' + v.id + '">' +
        //    '<span class="span-1 exist-name">' + v.name + '</span>' +
        //    '</li>'
        //});
        //$('.target-chosen-ul').append(chosenContent);
        $('.multiple-type').val(multiple);
        $('.target-name').text('请选择' + selecterName);
        $('.popup3').show();
    });
});
$(document).on('click', '.main-category', function () {
    var _ = $(this);
    _.children('i').toggleClass('icon-chevron-down');
    var uls = _.nextAll('ul');
    uls.slideToggle('fast');
});

$(document).on('click', '.candidate', function () {
    //console.log('candidate');
    var _ = $(this);
    var chosen = _.prop('checked');
    //console.log(Boolean(0==_.val()));
    //console.log(_.hasClass('super'));
    if (0 == _.val() && _.hasClass('super')) {
        console.log('sub');
        var fUl = _.parents('ul');
        fUl.find('.sub').prop('checked', chosen);
    }
});
//点击选择框中的选择按钮
$(document).on('click', '.target-choose', function () {
    var multiple = $('.multiple-type').val();
    var chosenList = {};
    var existContent = '';
    $.each($('input.exist'), function (k, v) {
        var _ = $(v);
        var attrInf = _.prev('input.exist-attr-id')
        var name = _.nextAll('.exist-name').text();
        chosenList[name] = {id: _.val(), name: name};
        if (attrInf.length > 0)chosenList[name].attrId = attrInf.val();
    });
    //多值情况
    if (1 == multiple) {
        $.each($('input.candidate'), function (k, v) {
            var _ = $(v);
            var name = _.nextAll('.candidate-name').text();
            if (_.prop('checked') && 0 != _.val()) {
                if (!chosenList[name])chosenList[name] = {id: _.val(), name: name};
            }
        });

    } else {//单值情况
        var valueCount = 0;
        $.each($('input.candidate'), function (k, v) {
            if (valueCount > 0)return false;
            var _ = $(v);
            var name = _.nextAll('.candidate-name').text();
            if (_.prop('checked') && 0 != _.val()) {
                chosenList = {};
                chosenList[name] = {id: _.val(), name: name};
                valueCount++;
            }
            console.log('reached');

        });
    }
    $.each(chosenList, function (k, v) {
        var attrIdContent = '';
        if (v.attrId)attrIdContent = '<input type="hidden" class="exist-attr-id" value="' + v.attrId + '">';
        existContent += '<li class="clearfix">' + attrIdContent +
        '<input class="checkbox exist" type="checkbox" name="checkbox-lv1" value="' + v.id + '">' +
        '<span class="span-1 exist-name">' + v.name + '</span>' +
        '</li>'
    });
    //console.log(chosenList);
    $('.target-chosen-ul').empty();
    $('.target-chosen-ul').append(existContent);


});
//点击选择框中的删除按钮
$(document).on('click', '.chosen-delete', function () {
    $.each($('input.exist'), function (k, v) {
        var _ = $(v);
        var name = _.nextAll('.exist-name').text();
        if (_.prop('checked') && _.val()) {
            //已写入数据库的
            if (_.prev('input.exist-attr-id').length > 0) {
                ajaxPost('ajaxDeleteAttr', {id: _.prev('input.exist-attr-id').val()}, function (data) {

                });
            }
            _.parent().remove();
        }
    });
});
//点击加号按钮
$(document).on('click', '.main-candidate-btn', function () {
    var _ = $(this);
    var fUl = _.parents('ul');
    _.toggleClass('btn-change-bg');
    fUl.find('.main-candidate').slideToggle('fast');
});

$(document).on('click', '.close-unit', function () {
    $('.target-value-selecter').removeClass('target-value-selecter');
    $('.unit').hide();
    judgeMotionType();
});
$(document).on('click', '.chosen-confirm', function () {
    var content = '';
    var valuePlace = $('.target-value-selecter');
    var multiple = $('.multiple-type').val();
    var selectButton=valuePlace.children('.target-select');
    var target=selectButton.data('target');
    target=$('input.exist').length>1?target+'[]':target;
    selectButton.prevAll().remove();
        $.each($('input.exist'), function (k, v) {
            var _ = $(v);
            var key = _.val();
            var name = _.nextAll('.exist-name').text();
                content += '<input type="hidden" name="'+target+'" value="' + key + '"><span class="pre-delete">' + name + '</span>';
        });

       selectButton.before(content);



    $('.target-value-selecter').removeClass('target-value-selecter');
    $('.popup3').hide();

});
$(document).on('click','#search-button',function(){
    var word= $.trim($('#search-input').val());
    if(word){
        $('li.li-lv2').css('display','none');
        $.each($('.candidate-name'),function(k,v){
            var name=$(v).text();
            if(name.match(word)){
                $(v).parent().css('display','list-item');
            }
        })
    }else{

    }
});
function getTargetList(target, filter, callback) {
    var sTarget = target;
    var sFilter = filter || {};
    //alert('getTarget');
    ajaxPost('ajaxTargetList', {target: target, filter: sFilter}, callback)
}




