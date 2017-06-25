// JavaScript Document
    var cols = 5;
    $(document).ready(function () {
//        $('#sample2').children('thead').find('th').each(function(k,v){
//            console.log('before clone id:'+k+',value:'+$(v).outerWidth());
//        });
//        initTable($('#sample2'));
    });

    $('#genetable_tableData').scroll(function (event) {
        var left = this.scrollLeft;
        var top = this.scrollTop;
//        console.log(left);
        $('.x-move').css('left', -left);
        $('.y-move').css('top', -top);
    });
    $('body').mousemove(function () {
//        $('#genetable_tableData').scroll();

    });
    function initTable(tableElement) {
        var containerLength=0;
        var totalLength=tableElement.width();
        var thHeight=0;
		var thWidthList=[];
        console.log(totalLength);

        tableElement.children('thead').find('th').each(function (k, v) {
            if (k < cols) {
                containerLength+=$(v).outerWidth();
                thHeight=$(v).outerHeight();

            }
            thWidthList.push($(v).width());
            $(v).width($(v).outerWidth());
            $(v).css('minWidth',$(v).outerWidth());
            $(v).addClass('moveAble'+k);
        });
        $('th').css('white-space','normal');
        tableElement.css('table-layout','fixed');

//        return;
//        console.log(thWidthList);
        var xMove = tableElement.clone();
        var fixedHead = tableElement.clone();
        var yMove = tableElement.clone();
        $('.x-move-container').height(thHeight);
        $('.x-move').append(xMove);
        fixedHead.find('th').each(function (k, v) {
            if (k > cols - 1) {
                $(v).addClass('pre-remove');
            } else {
                $(v).css('width', $(v).width());
            }
        });
        fixedHead.find('.pre-remove').remove();
//        fixedHead.css('width', cloneTableWidth);
        $('.fixed').height(thHeight);
        $('.fixed').width(containerLength);
        $('.fixed').append(fixedHead);

        $('.y-move-container').css('width', containerLength+1);
        $('.y-move').width(containerLength+1);
        $('.y-move').append(yMove);

    }