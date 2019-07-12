$(document).ready(function(){	
	$(".btn_cadastrar").unbind('click').bind('click', function(e){

		if( $(".senha_confirmar").val() != $(".senha").val() ){
			$(".confirme_senha_label").addClass("field_msg");
			e.preventDefault();
			alert("As senhas informadas não são iguais.");
			return;
		}

		if( $(".senha_confirmar").val().length < 6 ){
			e.preventDefault();
			alert("A senha deve conter pelo menos 6 caracteres.");
			return;
		}

	} );

});
