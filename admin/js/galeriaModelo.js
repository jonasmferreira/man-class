function getImg(galeria_imagem_id){
	var url = "";
	$.ajax({
		type:'POST'
		,url:'controller/galeriaModelo.controller.php?action=verImagem'
		,async:false
		,data:{
			'galeria_imagem_id':galeria_imagem_id
		}
		,success:function(resp){
			resp = resp.toString();
			url = resp;
		}
	});
	return url;
}
function delImg(galeria_imagem_id){
	var resposta = "";
	$.ajax({
		type:'POST'
		,url:'controller/galeriaModelo.controller.php?action=deleteImg'
		,async:false
		,data:{
			'galeria_imagem_id':galeria_imagem_id
		}
		,success:function(resp){
			resposta = resp.toString();
		}
	});
	return resposta;
}

(function($){
	$.fx.speeds._default = 200;
	$(document).ready(function(){
		$('#tableGaleria thead,tfoot').addClass('ui-dialog-titlebar ui-widget-header');
		$('#tableGaleria tbody tr:even').addClass('even');
		if($('#tabela_lista').length > 0){
			// dataTable
			uTable = $('#tabela_lista').dataTable({
				//"sScrollY": 150,
				"bJQueryUI": true
				,"sPaginationType": "full_numbers"
				,"oLanguage": {
					"sUrl": "js/ptbr.txt"
				}
				,"aoColumnDefs": [
					{ "bSortable": false, "aTargets": [ 0 , -1] }
				]
			});
			$(window).bind('resize', function () {
				uTable.fnAdjustColumnSizing();
			});
		}
		$("#filtrar").click(function(){
			$("#formLista").submit();
		});
		$('.salvar').click(function(){
			if(verifyObrigatorio()){
				$("#content").mask("Salvando...");
				$("#formSalvar").submit();
			}else{
				newAlert("Preencha os campos em destaque");
			}
		});
		$(".limparCadastro").click(function(){
			e.preventDefault();
			limparCampos($("#formSalvar"));
		});
		$(".limpar").click(function(e){
			e.preventDefault();
			limparCampos($("#formLista"));
			$("#formLista").submit();
		});
		$(".editItem").live('click',function(){
			$("#id").val($(this).attr('rel'));
			$("#formEdicao").submit();
		});
		$(".voltar").click(function(){
			window.location.href = 'galeriaModeloLista.php';
		});
		$(".deleteItem").live('click',function(){
			var objeto = $(this);
			var id = objeto.parent().parent().find(".galeria_imagem_id").val();
			var obj = $(this);
			if($.trim(id)!=""){
				if(!confirm('Deseja Mesmo Excluir?')){
					return;
				}
				var resp = delImg(id);
				resp = resp.toString();
				resp = resp.split("|");
				if($.trim(resp[0])=='CMD_SUCCESS'){
					obj.parent().parent().remove();
				}
				newAlert(resp[1]);
			}else{
				obj.parent().parent().remove();
			}
		});
		$(".verImg").live('click',function(){
			var objeto = $(this);
			var galeria_imagem_id = objeto.parent().parent().find(".galeria_imagem_id").val();
			var galeria_imagem_titulo = objeto.parent().parent().find(".galeria_imagem_titulo").val();
			var titulo = galeria_imagem_titulo;
			
			jQuery('<div class="verImagem"></div>').dialog({
				'modal':true,
				'title':titulo,
				'show': "explode",
				'hide': 'explode',
				'width':450,
				'height':450,
				'buttons':{
					'OK':function(){
						$(this).dialog('destroy');
					}
					,'Excluir':function(){
						var dialog1 = $(this);
						$('<div class="newConfirm"></div>').dialog({
							'modal':true,
							'title':'C O N F I R M A Ç Ã O',
							'show': "explode",
							'hide': 'explode',
							'buttons':{
								'Sim':function(){
									var resposta = delImg(galeria_imagem_id);
									resposta = resposta.toString();
									resposta = resposta.split("|");
									if($.trim(resposta[0])=='CMD_SUCCESS'){
										objeto.parent().parent().remove();
									}
									newAlert(resposta[1]);
									dialog1.dialog('destroy');
									$(this).dialog('destroy');
								}
								,
								'Não': function() {
									$(this).dialog('destroy');
								}
							}
						}).html("Deseja mesmo excluir "+titulo+"?");
						
					}
				}
			}).html('<img src="'+getImg(galeria_imagem_id)+'" border="0" alt="" />');
		});
		$("#novaImagem").click(function(){
			$.ajax({
				type:'POST'
				,url:'controller/galeriaModelo.controller.php?action=novaImagem'
				,async:false
				,success:function(resp){
					resp = resp.toString();
					$('#tableGaleria tbody').prepend(resp);
					$('#tableGaleria tbody tr:even').addClass('even');
				}
			});
		});
	})
})(jQuery)