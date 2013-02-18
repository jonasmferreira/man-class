(function($){
	var icons = {
		header: "ui-icon-circle-arrow-e",
		headerSelected: "ui-icon-circle-arrow-s"
	};
	$.datepicker.setDefaults($.datepicker.regional['pt-BR']);
	$(document).ready(function(){
		if($.browser.msie){
			$("#main_content #content").css({
				'display':'inline'
				,'width':'80%'
			});
		}
		
		if($('textarea.editor').length > 0){
			$('textarea.editor').ckeditor();
		
			var editor = $('textarea.editor').ckeditorGet();
			CKFinder.setupCKEditor(editor, '../lib/ckfinder/') ;
		}
		
		$(".telefone").setMask('phone');
		$(".inteiro").setMask('integer');
		$(".dec").setMask('signed-decimal');
		$(".peso").setMask('weight');
		$(".altura").setMask('height');
		$(".idade").setMask('age');
		$(".dec").setMask('signed-decimal');
		$(".celular").setMask('cellphonebr');
		$(".dt").setMask('date');
		$(".hr").setMask('time');
		$(".datepicker").datepicker({
			showButtonPanel: true
		});
		$.fx.speeds._default = 1000;
		/* Ativar Endereço de link */
		var dmPaginaAtivo = window.location+"";
		dmPaginaAtivo = dmPaginaAtivo.split("/").pop();
		if(dmPaginaAtivo != ""){
			var pagina = $('#accordion a[href$="'+dmPaginaAtivo+'"]');
			pagina.addClass('activeLink');
			/*var menu_pai = pagina.parent().parent().parent().prev().text()
			$("#pagina").text(menu_pai+" >> "+pagina.text());
			pagina.parent().parent().parent().prev().text()*/
		}
		
		$( "#accordion" ).accordion({
			autoHeight: false,
			navigation: true,
			collapsible: true,
			icons: icons
		});
		//$("#tema").themeswitcher({buttonPreText:'Temas: '});
		$('.botoes *').button();
		$( "#teste" ).buttonset();
		$('.form-main table').attr({
			'cellpadding':'2',
			'cellspacing':'1',
			'width':'100%',
			'border':'0'
		});
		$('.form-main .legend').addClass('ui-dialog-titlebar ui-widget-header');
		$("#formLista tbody tr:even").addClass('even');
		/*$('.form-main table thead,tfoot').addClass('ui-dialog-titlebar ui-widget-header');

		$('.form-main table:not(:.noZebra) tbody tr:odd').addClass('even');

		$('.form-main table tbody tr').live('mouseover',function(){
				$(this).addClass('ui-state-hover');
		});
		$('.form-main table tbody tr').live('mouseout',function(){
				$(this).removeClass('ui-state-hover');
		});*/
		
		
		$("#firstPag").click(function(){
			var pagIni = jQuery("#paginaAtual").html();
			var pagMax = jQuery("#paginaTotal").html();
			pagIni = 1;
			jQuery("#paginaAtual").html(pagIni);
			jQuery(".todas").css({
				'display':'none'
			});
			if (jQuery.browser.msie ) {
				jQuery(".num_"+pagIni).css({
					'display':'block'
				});
			}else{
				jQuery(".num_"+pagIni).css({
					'display':'table-row'
				});
			}
			jQuery("#paginaAtual").html(pagIni);
		});
		$("#prevPag").click(function(){
			var pagIni = jQuery("#paginaAtual").html();
			var pagMax = jQuery("#paginaTotal").html();
			pagIni = parseInt(pagIni)-1;
			if(pagIni<1){
				pagIni = 1;
			}
			jQuery("#paginaAtual").html(pagIni);
			jQuery(".todas").css({
				'display':'none'
			});
			if (jQuery.browser.msie ) {
				jQuery(".num_"+pagIni).css({
					'display':'block'
				});
			}else{
				jQuery(".num_"+pagIni).css({
					'display':'table-row'
				});
			}
			jQuery("#paginaAtual").html(pagIni);
		});
		
		$("#nextPag").click(function(){
			var pagIni = jQuery("#paginaAtual").html();
			var pagMax = jQuery("#paginaTotal").html();

			pagIni = parseInt(pagIni)+1;
			if(pagIni>pagMax){
				pagIni = pagMax;
			}
			jQuery("#paginaAtual").html(pagIni);
			jQuery(".todas").css({
				'display':'none'
			});
			if (jQuery.browser.msie ) {
				jQuery(".num_"+pagIni).css({
					'display':'block'
				});
			}else{
				jQuery(".num_"+pagIni).css({
					'display':'table-row'
				});
			}
			jQuery("#paginaAtual").html(pagIni);
		});
		$("#lastPag").click(function(){
			var pagIni = jQuery("#paginaAtual").html();
			var pagMax = jQuery("#paginaTotal").html();
			pagIni = pagMax;
			jQuery("#paginaAtual").html(pagIni);
			jQuery(".todas").css({
				'display':'none'
			});
			if (jQuery.browser.msie ) {
				jQuery(".num_"+pagIni).css({
					'display':'block'
				});
			}else{
				jQuery(".num_"+pagIni).css({
					'display':'table-row'
				});
			}
			jQuery("#paginaAtual").html(pagIni);
		});
	});
})(jQuery);

$(".logoff").live('click',function(){
	$.fx.speeds._default = 400;
	$('<div class="newAlert2"></div>').dialog({
		'modal':true,
		'title':'A L E R T A',
		'show': "explode",
		'hide': 'explode',
		'buttons':{
			'Sim':function(){
				window.location.href = "logoff.php";
			}
			,
			'Não': function() {
				$(this).dialog('close');
			}
		}
	}).html("Deseja realmente sair do sistema?");

});

var newAlert = function(mensagem,tempo,url){
	tempo = (tempo == undefined) ? 400 : tempo;
	url = (url == undefined) ? false : url;
	$.fx.speeds._default = tempo;
	jQuery('<div class="newAlert"></div>').dialog({
		'modal':true,
		'title':'A L E R T A',
		'show': "explode",
		'hide': 'explode',
		'buttons':{
			'OK':function(){
				$(this).dialog('destroy');
				window.setTimeout(function(){
					$(".newAlert").remove();
					if(url!=false){
						window.location.href=url;
					}
				},300);
			}
		}
	}).html(mensagem);
}
var newAlertSubmit = function(id_form,mensagem){
	var tempo = 400;
	$.fx.speeds._default = tempo;
	jQuery('<div class="newAlert"></div>').dialog({
		'modal':true,
		'title':'A L E R T A',
		'show': "explode",
		'hide': 'explode',
		'buttons':{
			'OK':function(){
				$(this).dialog('destroy');
				window.setTimeout(function(){
					$(".newAlert").remove();
					id_form.submit();
				},300);
			}
		}
	}).html(mensagem);
}


var popup = function(mensagem,titulo,tempo){
	tempo = (tempo == undefined) ? 400 : tempo;
	$.fx.speeds._default = tempo;
	jQuery('<div class="popup"></div>').dialog({
		'modal':true,
		'title':titulo,
		'show': "explode",
		'hide': 'explode',
		'width':450,
		'height':450,
		'buttons':{
			'OK':function(){
				$(this).dialog('destroy');
				window.setTimeout(function(){
					$(".newAlert").remove();
				},300);
			}
		}
	}).html('<div style="text-align:center">'+mensagem+'</div>');
}

function soNumeros(v){
	v = v.toString();
	return v.replace(/\D/g,"")
}

function verifyObrigatorio(){
	if(jQuery(".obrigatorio").length > 0){
		jQuery(".obrigatorio").removeClass('error');
		var obj;
		var qtd = 0;
		jQuery(".obrigatorio").each(function(){
			obj = jQuery(this);
			if(jQuery.trim(obj.val())==''){
				obj.addClass("error");
				qtd++;
			}
		});
		return (qtd > 0)?false:true;
	}else{
		return true;
	}
}
function limparCampos(obj){
	obj.find('select').find('option:first').attr('selected','selected');
	obj.find('input[type=text]').val('');
	obj.find('input[type=file]').val('');
	obj.find('textarea').val('');
}