function getImg(modelo_id,img){
	var url = "";
	$.ajax({
		type:'POST'
		,url:'controller/modelo.controller.php?action=verImagem'
		,async:false
		,data:{
			'modelo_id':modelo_id
			,'img':img
		}
		,success:function(resp){
			resp = resp.toString();
			url = resp;
		}
	});
	return url;
}
function delImg(modelo_id,img){
	var resposta = "";
	$.ajax({
		type:'POST'
		,url:'controller/modelo.controller.php?action=deleteImg'
		,async:false
		,data:{
			'modelo_id':modelo_id
			,'img':img
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
		$('#salvar').click(function(){
			if(verifyObrigatorio()){
				$("#content").mask("Salvando...");
				$("#formSalvar").submit();
			}else{
				newAlert("Preencha os campos em destaque");
			}
		});
		$("#limparCadastro").click(function(){
			e.preventDefault();
			limparCampos($("#formSalvar"));
		});
		$("#limpar").click(function(e){
			e.preventDefault();
			limparCampos($("#formLista"));
			$("#formLista").submit();
		});
		$(".editItem").live('click',function(){
			$("#id").val($(this).attr('rel'));
			$("#formEdicao").submit();
		});
		$("#voltar").click(function(){
			window.location.href = 'modeloLista.php';
		});
		$(".deleteItem").click(function(){
			var id = $(this).attr('rel');
			var obj = $(this);
			if($.trim(id)!=""){
				if(!confirm('Deseja Mesmo Excluir?')){
					return;
				}
				$.ajax({
					type:'POST'
					,url:'controller/modelo.controller.php?action=deleteItem'
					,async:false
					,data:{
						'modelo_id':id
					}
					,success:function(resp){
						resp = resp.toString();
						resp = resp.split("|");
						if($.trim(resp[0])=='CMD_SUCCESS'){
							obj.parent().parent().remove();
						}
						newAlert(resp[1]);
					}
				});
			}
		});
		$(".verImg").click(function(){
			var img = $(this).attr('attr');
			var modelo_id = $("#modelo_id").val();
			var titulo = "";
			switch(img){
				case 'modelo_img_dest_principal':
					titulo = "Destaque Principal";
				break;
				case 'modelo_img_area_modelo':
					titulo = "Img. Área Modelo";
				break;
				case 'modelo_img_dest_semana':
					titulo = "Destaque Semana";
				break;
			}
			
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
									var resposta = delImg(modelo_id,img);
									resposta = resposta.toString();
									resposta = resposta.split("|");
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
			}).html('<img src="'+getImg(modelo_id,img)+'" border="0" alt="" />');
		});
	})
})(jQuery)