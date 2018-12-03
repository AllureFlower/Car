/* 
 * drag 1.0
 * create by tony@jentian.com
 * date 2015-08-18
 * 拖动滑块
 */
(function($){

	
	$(".handler handler_bg").on('click',function(ev)
    {
       var oEvent=ev||event;
       console.log("x坐标是:"+oEvent.clientX+",y坐标是:"+oEvent.clientY);
    })

    $.fn.drag = function(options){
        var x, drag = this, isMove = false, defaults = {
        };
        var options = $.extend(defaults, options);
        //添加背景，文字，滑块
        var html = '<div class="drag_bg"></div>'+
                    '<div class="drag_text" onselectstart="return false;" unselectable="on">拖动图片验证</div>'+
                    '<div class="handler handler_bg"></div>';
        this.append(html);
        
        var handler = drag.find('.handler');
        var drag_bg = drag.find('.drag_bg');
        var text = drag.find('.drag_text');
        var maxWidth = drag.width() - handler.width();  //能滑动的最大间距
        
        //鼠标按下时候的x轴的位置
        handler.mousedown(function(e){
            $xy_img.show();
            isMove = true;
            x = e.pageX - parseInt(handler.css('left'), 10);
        });

        var $xy_img = $("#xy_img");

        //鼠标指针在上下文移动时，移动距离大于0小于最大间距，滑块x轴位置等于鼠标移动距离
        $(document).mousemove(function(e){
            var _x = e.pageX - x;
            if(isMove){
                $xy_img.css({'left': _x});
                if(_x > 0 && _x <= maxWidth){
                    handler.css({'left': _x});
                    drag_bg.css({'width': _x});
                }else if(_x > maxWidth){  //鼠标指针移动距离达到最大时清空事件
                  //  dragOk();
                }
            }
        }).mouseup(function(e){
            isMove = false;
            var _x = e.pageX - x;

            $.ajax({ //校验
                type:"POST",
                url:"/car/admin.php/Public/validation", 
                dataType:"JSON",
                async: false,
                data:{point:_x+3},
                success:function(result){
                    if(result['state'] == 0 ) {
                        for(var i=1; 4>=i; i++){
                            $xy_img.animate({left:_x-(40-10*i)},50);
                            $xy_img.animate({left:_x+2*(40-10*i)},50,function(){
                                $xy_img.css({'left':result['data']});
                            });
                        }
                        handler.css({'left': maxWidth});
                        drag_bg.css({'width': maxWidth});
                        $xy_img.removeClass('xy_img_bord');
                        dragOk();
                    } else {
                        $xy_img.css({'left': 0});
                        handler.css({'left': 0});
                        drag_bg.css({'width': 0});
                        //if(_x < maxWidth){ //鼠标松开时，如果没有达到最大距离位置，滑块就返回初始位置
                        //    handler.css({'left': 0});
                        //    drag_bg.css({'width': 0});
                        //}
                    }
                },
                beforeSend:function(){
                    //$(".text-c").children('td').html('<i style="color: lightseagreen">加载中...</i>');
                }
            });

        });
        
        //清空事件
        function dragOk(){
            handler.removeClass('handler_bg').addClass('handler_ok_bg');
            text.text('验证通过');
            drag.css({'color': '#fff'});
            handler.unbind('mousedown');
            $(document).unbind('mousemove');
            $(document).unbind('mouseup');
        }
    };
})(jQuery);


