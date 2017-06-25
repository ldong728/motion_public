$(document).on('hover','.sample-table-js tr',function(){
//console.log('hover');
//var id = $(this).children(":first").text();
$(this).toggleClass('hover');
});