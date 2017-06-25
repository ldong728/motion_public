$(document).on('click','.multiple-search',function(){
    var maskHeight = $(document.body).height();
    ajaxPost('searchMotionView',{category:category,meeting:meetingId},function(data){
        $('.m-popup').html(data);
        $('.m-popup').show();
        $('.mask').show();
        $('.mask').css('height',maskHeight);
        mPopup();
    });
});

function decodeSearchDate(element) {
    element.each(function (key, subElement) {
        var _ = $(subElement);
        var parent = _.parent();
        //console.log(_.text());
        var data = eval('(' + _.text() + ')');//将数据转化为JS对象
        if (data) {
            var content = '';
            parent.children('span').remove();
            if (data.edit) {//选项可编辑
                //console.log(data);
                parent.addClass('search-value');
                parent.attr('data-type', data.value_type);
                parent.attr('data-motionattr', data.motion_attr);
                parent.attr('data-attrtemplate', data.attr_template);
                parent.attr('data-multiple', data.multiple);
                if (data.option) {
                    //同一属性有多值的情况
                    if (1 == data.multiple) {
                        $.each(data.option, function (k, v) {
                            var checked = '';
                            var attrId = '';
                            var defalultValue='';
                            if (data.multiple_value && $(data.multiple_value).length > 0) {
                                $.each(data.multiple_value, function (id, cnt) {
                                    //console.log(cnt);
                                    //console.log(id);
                                    if (v == cnt.content) {
                                        if(!id)defalultValue='attr-value';
                                        checked = 'checked="checked"';
                                        attrId = 'id="' + id + '"';
                                    }
                                })
                            } else {
                            }
                            content += '<label ><input class="mutiple-input '+defalultValue+'" style="width: 20px" type="checkbox" value="' + k + '" ' + checked + ' ' + attrId + '>' + v + '</label></br>'
                        });
                    } else {
                        var isValue = data.target ? '' : 'attr-value';
                        content += '<select class="' + (data['class']) + ' ' + isValue + '">';
                        $.each(data.option, function (k, v) {
                            var selected = v == data.content ? 'selected="selected"' : '';
                            content += '<option value="' + k + '" ' + selected + '>' + v + '</option>';
                        });
                        content += '</select>';
                    }
                } else if (data.target) {
                    if (1 == data.multiple) {
                        if ($(data.multiple_value).length > 0) {
                            $.each(data.multiple_value, function (id, value) {
                                content += '<span class="pre-delete attr-value" id="' + id + '">' + value.content + '</span>'
                            });
                        }
                        content += '<button class="target-select" data-target="' + data.target + '">添加</button>'
                    } else {
                        if (data.content)content += '<input type="hidden" class="attr-value" value="' + data.content_int + '"><span class="single-value">' + data.content + '</span>';
                        content += '<button class="target-select" data-target="' + data.target + '">选择</button>'
                    }
                }
                else if (data.has_attachment > 0) {
                    content +=
                        '<button class="button choose-file">选择附件</button>' +
                        '<input type="file" class="doc-file" id="file' + data.motion_attr + '" name="file' + data.motion_attr + '" style="display:none"">';
                    if(1==data.multiple){
                        console.log(data);
                        if ($(data.multiple_value).length > 0) {
                            console.log(data);
                            $.each(data.multiple_value, function (id, value) {
                                content += '<span class="multiple-attachment-content"><a href="'+value.attachment+'">'+value.content+'</a><a href="#"><span class="pre-delete pre-btn" id="' + id + '">X</span></a></span>'
                            });
                        }
                    }else{
                        var attachmentName = data.attachment ? data.content : '';
                        content += '<a class="attachment-file" href="#" data-href="' + data.attachment + '">' + attachmentName + '</a>'
                    }

                } else if ('time' == data.value_type) {
                    content += '<input type="hidden" class="attr-value" value="1"><span class="time-display"></span>';
                } else {
                    if ('string' == data.value_type) {
                        content += '<textarea class="attr-value">' + (data.content || '') + '</textarea>';
                    } else {
                        content += '<input type="text" class="attr-value" value="' + (data.content || '') + '" width="20px">';
                    }

                }
            } else {//选项不可编辑
                if (data.attachment) {
                    if(1==data.multiple){
                        //console.log(data);
                        $.each(data.content,function(attaKey,attaData){
                            content += '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' + attaData.attachment + '">' + (attaData.content || '') + '</a>'
                        });

                    }else{
                        //console.log('no multiple');
                        content += '<a href="' + data.attachment + '">' + (data.content || '') + '</a>'
                    }

                } else {
                    //console.log(data);
                    content += data.content || '';
                }
            }
            parent.append(content);
        }

    });
}