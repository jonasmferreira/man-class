
(function($){
	$.fx.speeds._default = 200;
	$(document).ready(function(){
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
	})
})(jQuery)