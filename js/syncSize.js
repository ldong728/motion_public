var mouseDown=false;
var elementActive=false;
var currentElement=null;
//alert('hahah');
$(document).ready(function(){
 
		
	
	$(document).on('click','.home-nav-ul-js li a',function(k,v){
		var text=$(this).parent().text();
		$('.li-list').removeClass('li-list');
		$(this).parent().addClass('li-list');
		$.each($('.lv1-2-js').find('li'),function(k,v){
//			alert($(v));
//			alert(text);
			$(v).removeClass('lv2-cur')
			if (text == $(v).text()){
				$(v).addClass('lv2-cur');
			}else{

			}
		});
	});
	
	$(document).on('click','.icon-close-js',function(event){
		

			
		var text=$(this).parent().text();
		if($(this).parent().attr('class') == 'li-list'){
			
			if ($(this).parent().next().length > 0){
								
				
//				alert(text);
				$.each($('.lv1-2-js').find('li'),function(k,v){
					if (text == $(v).text()){
						$(v).removeClass('lv2-cur');

					}else{
						
					}
				});
				$(this).parent().next().addClass('li-list');
				var text=$(this).parent().next().text();
//				var thishref=$(this).prev().attr("href");
				var newhref=$(this).parent().next().find('a').attr("href");
//				alert($('#iframe').attr('src'));
				$('#iframe').attr('src',newhref);
//				alert(newhref);
				$.each($('.lv1-2-js').find('li'),function(k,v){
					if (text == $(v).text()){
						$(v).addClass('lv2-cur');

					}else{
						
					}
				});
				$(this).parent().remove();
			}else{
				$.each($('.lv1-2-js').find('li'),function(k,v){
					if (text == $(v).text()){
						$(v).removeClass('lv2-cur');

					}else{
						
					}
				});
				$(this).parent().prev().addClass('li-list');
				var text=$(this).parent().prev().text();
				var newhref=$(this).parent().prev().find('a').attr("href");
//				alert($('#iframe').attr('src'));
				$('#iframe').attr('src',newhref);
				$.each($('.lv1-2-js').find('li'),function(k,v){
					if (text == $(v).text()){
						$(v).addClass('lv2-cur');
					}else{
						
					}
				});
				$(this).parent().remove();	
			}
			
		}else{
			
			$(this).parent().remove();
		}
		event.stopPropagation();
		
	});
	

    $('.more-filter-js').click(function(){
        $(window.parent.document).find('.more-filter-block-js').show();
    });	
	
    $('.close-popup-js').click(function(){
       $(this).parents('.popup').hide();
    });
    $('.filter-wrap-js td').click(function(){
        var _=$(this);
        var name=_.text();
        if(!_.hasClass('light')){
            _.addClass('light');
            var table=$('.home-search-table');
            table.append('<div class="li clearfix"><div class="li-l">'+name+'：</div><div class="li-r"><input type="text" name="name"></div></div>');
        }else{
            var td=$('.home-search-table').find('.li-l');
            td.each(function(k,v){
               if((name+"：")==$(v).text()){
                   $(v).parent().remove();
               }
            });
            _.removeClass('light');

        }
    });

	 $('#span6').click(function(){
        $(window.parent.document).find('#popup-teacher').show();
    });	
	
	 $('#span1').click(function(){
        $(window.parent.document).find('#popup-college').show();
    });	
	
	 $('#span2').click(function(){
        $(window.parent.document).find('#popup-pro').show();
    });	
	
	
	
	$('#span-contract').click(function(){
       $('.home-l').toggle();
	
        if('none'==$('.home-l').css('display')){
			$('.home-r').css('width','100%');
        }else{
			$('.home-r').css('width','89%');
        };
    });
	
	$('#span-contract').click(function(){
        $(this).children('i').toggleClass('icon-caret-right');
   	});


});



$(document).on('change','.multiple-selecter-js',function(){
    var checked=$(this).prop('checked');
    var tr=$(this).parents('tr');
    var id=$(this).parent().prev().text();
    console.log(id);
    if(checked){

        $('.trr'+id).addClass('highlight');
    }else{

        $('.trr'+id).removeClass('highlight');
    }
});
$(document).on('click','.teacher-add',function(){
   //console.log('click outer');
    $('.select-table-js').slideUp('fast');
});
$(document).on('click','.category-selector',function(event){
    event.stopPropagation();
   var _=$(this);
    _.next().slideToggle('fast');
});
$(document).on('click','.select-table-js td input',function(){
   var checked=$(this).prop('checked');
    var name=$(this).parent().next().text();
    if(checked){
        var content='<span>'+name+'</span>';
        $('.category-selector').append(content);
    }else{
        $.each($('.category-selector span'),function(k,v){
           if(name==$(v).text())$(v).remove();
        });
    }
});

$(document).on('click','.select-table-js',function(event){
    event.stopPropagation();
    //console.log('click');
    //console.log(event);
    return true;
});
$(document).on('mousemove','body',moveHandler);
$(document).on('mouseup','body',function(event){
   currentElement=null;
});
$(document).on('mousedown','th',function(event){
//	console.log('down')
    currentElement=getElement(this,event);
    var edge=edgeJudge($(this),event);
	if(edge>2)edge=2;
    elementActive = edge;
//	console.log(edge);
});
$(document).on('mouseup','th',function(event){
    elementActive=false;
});
$(document).on('mousemove', 'th', function (event) {
    var edge = edgeJudge($(this), event);
    if (2 == edge) {
        this.style.cursor = 'e-resize';
    } else {
        if ('e-resize' == this.style.cursor)this.style.cursor = 'auto';
    }
});
$(document).on('mousemove','.popup-title',function(event){
   var edge=edgeJudge($(this),event);
    //console.log(edge);
    switch(edge){
        case 2:
            this.style.cursor='e-resize';
            break;
        case 4:
            this.style.cursor='s-resize';
            break;
        case 6:
            this.style.cursor='se-resize';
            break;
        default :
            this.style.cursor='auto';
            break;
    }
});
$(document).on('mousedown','.popup-title',function(event){
    currentElement=getElement(this,event);
    var edge=edgeJudge($(this),event);
//    if(edge>2)edge=2;
    elementActive = edge;
    console.log(elementActive);
});
//$(document).on('resize','.popup-title',function(){
//   console.log('resize');
//});
//$(document).on('click','.home-nav-ul-js li',function(){
//    $('.li-list').removeClass('li-list');
//    $(this).addClass('li-list');
//});


$(document).on('resize','.college-add',function(){
	var width = $(this).width();
	var height = $(this).height();
   console.log('resize');
	$('.add-table-h').height(height-85);
});
$(document).on('resize','.pro-content',function(){
	var width = $(this).width();
	var height = $(this).height();
   console.log('resize');
	$('.add-table-h2').height(height-85);
});


function moveHandler(event){
    if(!currentElement)return;
    var x=event.pageX;
    var y=event.pageY;
    var _=currentElement.element;
    //console.log(currentElement);
    //console.log(x-currentElement.left+currentElement.width-currentElement.mOffsetX);

        if(2==elementActive){
            //console.log(currentElement);
            $(_).width(x-currentElement.left+currentElement.width+-currentElement.mOffsetX);
            if(currentElement.syncClass){
                $('.'+currentElement.syncClass).width(x-currentElement.left+currentElement.width+-currentElement.mOffsetX);
                if(currentElement.fixedLayer){
                    $('.fixed').width(x-currentElement.left+currentElement.fixedLayer+-currentElement.mOffsetX);
                    $('.y-move-container').width(x-currentElement.left+currentElement.fixedLayer+-currentElement.mOffsetX);
                    $('.y-move').width(x-currentElement.left+currentElement.fixedLayer+-currentElement.mOffsetX)

                }
            }

            //console.log(currentElement.width+' and '+ _.width);



            $(_).resize();
            //console.log(x-currentElement.left+currentElement.width-currentElement.mOffsetX);
        }else
        if(4==elementActive){
            //console.log('verc');
            $(_).height(y-currentElement.top+currentElement.height-currentElement.mOffsetY);
            $(_).resize();
        }else
        if(6==elementActive){
            //console.log('horizon and verc');
            $(_).width(x-currentElement.left+currentElement.width-currentElement.mOffsetX);
            $(_).height(y-currentElement.top+currentElement.height-currentElement.mOffsetY);
            $(_).resize();
        }


}
function getElement(element,event){
    var _=$(element);
    var className= _.attr('class');
    var fixedLayer=false;
    if(!className.match(/moveAble/)){
        className=false;
    }else{
        var id=className.slice(8);
        if(id<cols){
            fixedLayer=$('.y-move-container').outerWidth();
        }
    }
    //console.log(className);
    var x=event.pageX;
    var y=event.pageY;
    var handleElement={
        element:element,
        top:_.offset().top,
        left: _.offset().left,
        width: _.outerWidth(),
        height:_.outerHeight(),
        mOffsetX:x-_.offset().left,
        mOffsetY:y-_.offset().top,
        syncClass:className,
        fixedLayer:fixedLayer

    };
    return handleElement;
}
function edgeJudge(jqElement,event){
    var edge=0;
    var mx=event.pageX;
    var my=event.pageY;
    var eleLeft=jqElement.offset().left;
    var eleTop=jqElement.offset().top;
    var eleRight=eleLeft+jqElement.width();
    var eleBottom=eleTop+jqElement.height();
    if(eleBottom-my<10)edge+=4;
    else if(my-eleTop<10)edge+=1;
    if(eleRight-mx<15)edge+=2;
    else if(mx-eleLeft<10)edge+=8;
    return edge;
}

$(document).on('mouseenter','.li-over',function(){
	$(this).children('ul').slideDown(100);
});

$(document).on('mouseleave','.li-over',function(){
	$(this).children('ul').slideUp(100);
});



