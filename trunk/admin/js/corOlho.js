
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
			window.location.href = 'corOlhoLista.php';
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
					,url:'controller/corOlho.controller.php?action=deleteItem'
					,async:false
					,data:{
						'usuario_id':id
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
	})
})(jQuery)