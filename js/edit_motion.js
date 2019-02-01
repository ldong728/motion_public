
//console.log(staff);
//$(document).ready(function () {
    var antiDouble = false;
    var timeSet = setTime();

    $(document).on('click', '.submit-attr', function () {
        if(confirm('确定要提交反馈吗？')){
            submitAtrrs(1, function (data) {
                var back = backHandle(data);
                $('.popup').hide();
                reflashList(orderby,page,order);
            })
        }
    });

    $(document).on('click', '.save-attr', function () {
        submitAtrrs(0, function (data) {
            //if('meeting'==place){
                $('.close-popup').click();
                reflashList(orderby,page,order);
            //}else{
            //    $('.close-popup').click();
                //window.location.reload(true);
            //}

            //closePopUp($('.m-phpup'));
            //location.href=location.href;
            //window.location.reload(true);
        });
    });
    $(document).on('click','.print-motion',function(){
       $('.table-list').jqprint({debug: true,importCSS:true});
       // $('.table-list').msoPrintArea();
    });
    $(document).on('click','.print-motion-detail',function(){
       alert('功能暂未开放，请点击全文对应链接，下载全文后打印');
    });

    function getTargetList(target, filter, callback) {
        var sTarget = target;
        var sFilter = filter || {};
        ajaxPost('ajaxTargetList', {target: target, filter: sFilter}, callback)
    }

    function getFuyiCount() {
        var count = 0;
        $.each($('.fuyi-count').children('.pre-delete'), function (k, v) {
            count++;
        });
        $('.fuyi').text(count);
    }


    function decodeDate(element) {
        element.each(function (key, subElement) {
            var _ = $(subElement);
            var parent = _.parent();
            //console.log(_.text());
            var data = eval('(' + _.text() + ')');//将数据转化为JS对象
            //console.log(data);
            //return;
            if (data) {
                //console.log(data);
                var attr = data.attr_id || 0;
                var content = '';
                //parent.empty();
                parent.children('span').remove();
                if (data.edit) {//选项可编辑
                    //console.log(data);
                    parent.addClass('update-value');
                    parent.attr('data-attr', attr);
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
                            content += '<textarea class="attr-value" style="width: 80%">' + (data.content || '') + '</textarea>';
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

    function submitAtrrs(step, callback) {

        var sStep = step || 0;
        if (sStep > 0) {
            var verifyData = inputVerify();
            if (verifyData.length > 0) {
                console.log(verifyData);
                alert(verifyData[0].content);
                return;
            }

        }
        var data = {step: sStep, data: []};
        //console.mylog($('.update-value'));
        $('.update-value').each(function (k, v) {
            var f = $(v);
            var s = f.find('.attr-value');
            var multiple = 1 == f.data('multiple');
            var attrType = f.data('type');
            var motionAttr = f.data('motionattr');
            var attrTemplate = f.data('attrtemplate');
            if (multiple) {
                $.each(s, function (sk, sv) {
                    if ($(sv).hasClass('duty-select') || $(sv).hasClass('unit-select'))return;
                    var attrId = sv.id;
                    var value = sv.value;
                    data.data.push({
                        attr_id: attrId,
                        attr_type: attrType,
                        motion_attr: motionAttr,
                        attr_template: attrTemplate,
                        value: value
                    });
                });
            } else {
                var attrId = f.data('attr')
                var value = s.val();
                data.data.push({
                    attr_id: attrId,
                    attr_type: attrType,
                    motion_attr: motionAttr,
                    attr_template: attrTemplate,
                    value: value
                });
            }

        });
        if ($('.handle-value').length > 0) {
            data.step = 0;
            var handlerMotionId = $('.motion_handler_id').attr('id');
            var handlerData = {motion_handler_id: handlerMotionId};
            $('.handle-value').each(function (k, v) {
                handlerData[v.id] = v.value;
            });
            data.handler = handlerData;
            data.confirm=step;
        }
        console.log(data);

        ajaxPost('updateAttr', data, function(data){
            //console.log(data);
            var info=backHandle(data);
            if('unique'==info)alert('案号重复');
            else callback(data);
        });
    }

    function setTime() {
        var sDate = new Date();
        $('.time-display').text(sDate.getFullYear() + '-' + (sDate.getMonth() + 1) + '-' + sDate.getDate());
        //var sTime=setInterval(function(){
        //    var time=new Date();
        //    $('.time-display').text(time.toDateString(sDate.getFullYear()+'-'+(sDate.getMonth()+1)+'-'+sDate.getDate()));
        //},1000);
        //return sTime;
    }

    function closePopUp(element) {
        //element.remove();
        //element.css('display','none');
        element.empty();
        $('.doc-file').remove();
    }

    /**
     * 政协自动变更提案性质类别
     */
    function judgeMotionType() {
        var selecter = $('.judged-value').children('select');
        if ($('.user-type').length > 0 && selecter.length > 0) {
            var _ = $('.user-type');
            var attrValue = _.find('.attr-value');
            //var preDelete= _.find('.pre-delete');
            //console.log(preDelete);
            if (attrValue.length > 1) {
                //console.log('联名');
                selecter.val('联名提案');
                return;
            } else if (0 == attrValue.length) {
                selecter.val('委员');
                $('.conecter').removeClass('verify-value')
                $('.union-conecter').hide();
            } else {
                if (attrValue.val()) {
                    ajaxPost('ajaxUserInf', {id: attrValue.val()}, set);
                } else {
                    ajaxPost('ajaxUserInfFromAttrTbl', {attrId: attrValue.attr('id')}, set);
                }
            }
        }
        function set(data) {
            //console.log(data);
            var back = backHandle(data);
            //console.log(back);
            if ('0' != back.user_unit && '0' != back.user_group) {
                selecter.val('委员');
                $('.conecter').removeClass('verify-value')
                $('.union-conecter').hide();

            } else {
                selecter.val('党派团体');
                $('.union-conecter').show();
                $('.conecter').addClass('verify-value');
            }
        }
    }

    /**
     * 表单校验
     */
    function inputVerify() {
        var errorlist = [];
        var passVerify = $('.pass-verify').find('.attr-value');
        if (passVerify.length > 0 && '立案' != passVerify.val()) {
            return errorlist;
        }

        $.each($('.verify-value'), function (k, v) {
            var _ = $(v);
            var valueInput = _.find('.attr-value');
            var inputButton = _.find('.target-select');
            var value = _.find('.attr-value').val() || '';
            var handleValue = _.find('.handle-value');
            var attrType = _.data('type');
            var valueName = _.prev().text();


            if ('attachment' != attrType) {
                if (inputButton.length > 0) {
                    if (0 == _.find('.pre-delete').length && 0 == _.find('.single-value').length) {
                        errorlist.push({name: valueName, content: valueName + "不能为空"})
                    }

                }
                if (valueInput.length > 0 && 0 == inputButton.length) {
                    if (!value || !Boolean($.trim(value))) {
                        //console.log(value);
                        errorlist.push({name: valueName, content: valueName + "不能为空"})
                    } else {
                        if ('int' == attrType && !value.match(/^-?[1-9]\d*$/)) {
                            errorlist.push({name: valueName, content: valueName + "必须为整数"});
                        }
                    }
                }
            } else {//附件
                //var attachmentFile=_.find('.attachment-file').data('href');
                var attachment = _.find('.attachment-file').data('href');
                var preDelete= _.find('.pre-delete');
                if(preDelete.length>0)return;
                if ('#' == attachment || 'null' == attachment || !attachment) {
                    console.log(attachment)
                    errorlist.push({name: valueName, content: valueName + "未上传"})
                }
            }
            if (handleValue.length > 0) {
                var value = $.trim(handleValue.val());
                var type = handleValue.attr('type');
                if (!value) {
                    errorlist.push({name: valueName, content: valueName + "不能为空"});
                } else {
                    if (!value.match(/^-?[1-9]\d*$/) && 'tel' == type)errorlist.push({
                        name: valueName,
                        content: valueName + "必须为电话号码"
                    });
                }
            }
        });
        var handlerAttachment = $('.upload-handler-file').nextAll('.handle-attachment-file');

        if (handlerAttachment.length > 0 && !handlerAttachment.attr('href')) {
            var handlertAtachmentName = $('.upload-handler-file').parent().prev().text();
            errorlist.push({
                name: handlertAtachmentName,
                content: handlertAtachmentName + "未上传"
            });
        }

        return errorlist;
    }

//});
