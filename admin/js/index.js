(function($){
	$(document).ready(function(){
		$("#enviar").click(function(e){
			e.preventDefault();
			if(verifyObrigatorio()){
				$("#frmlogin").submit();
			}else{
				newAlert("Preencha os campos em destaque");
			}
		});
	});
})(jQuery);

