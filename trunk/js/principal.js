var posicao = 0;
$(function(){
	$(document).ready(function(){
		$(".boxImagem").corner("3px");
		$(".campos_login").corner("5px");
		$("#publicidade_01, #publicidade_02").corner("5px");
		$("#nav li ul").prepend("<li></li>");
		$("#nav li ul").append("<li></li>");
		$("#nav li ul li:first").css({
			'background-image':'url("img/topo_drop_down.png")'
			,'background-repeat':'no-repeat'
			,'height':'13px'
			,'width':'100px'
		})
		$("#nav li ul li:last").css({
			'background-image':'url("img/baixo_drop_down.png")'
			,'background-position':"1px 0px"
			,'background-repeat':'no-repeat'
			,'height':'16px'
			,'width':'100px'
		});
		$(".boxImagem:first").css({
			'margin-left':'25px'
		});
		$(".boxImagemAnuncio_01:first").css({
			'margin-top':'0px'
		});
		
		jQuery.fn.cycle.updateActivePagerLink = function(pager, currSlideIndex) {
			posicao = currSlideIndex.toString();
			
		};
		$('#destaque_img').cycle({ 
			fx:     'scrollRight', 
			speed:  300, 
			timeout: 3000, 
			pager:  '#destaque_marks'
			,pause:   1
			,cleartype:  true
			,cleartypeNoBg:  true
			,pagerAnchorBuilder: function(idx, slide) {
				return '<a href="javascript:void(0);"><img src="img/mark_img.png" alt="" border="0" /></a>';
			} 
		});
		
		$('#destaque_img_false').hover(
			function () {
				$('#destaque_img').cycle('pause');
			},
			function () {
				$('#destaque_img').cycle('resume', true);
			}
		);
		$('#destaque_img_false').click(function(){
			$('#destaque_img').find("img:eq("+posicao+")").attr('attr');
		});
	});
});