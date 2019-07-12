$(document).ready(function(){	

	try{ $( ".cpf" ).mask( "999.999.999-99" ); } catch(ex){}
	try{ $( ".txt_ddd" ).mask( "99" ); } catch(ex){}
	try{ $( ".date" ).mask( "99/99/9999" ); } catch(ex){}

	$(".cpf_validar").on("blur", function(){
		// alert(validar_cpf($(".cpf_validar").val()));
		if(
			( validar_cpf($(".cpf_validar").val()) == false )
			&& ( $(".cpf_validar").val() != "" )
		){
			$(".cpf_label_alerta").text(" *** Cpf Inválido ***");
			$(".cpf_label").hide(0);
		}else{
			$(".cpf_label_alerta").text("");
			$(".cpf_label").show(0);
		}
	} );
	
	$(".txt_email").on("blur", function(){
		// alert(validar_cpf($(".cpf_validar").val()));
		if(
			( validar_email($(".txt_email").val()) == false )
			&& ( $(".txt_email").val() != "" )
		){
			$(".lbl_email_alerta").text(" *** E-mail Inválido ***");
			$(".lbl_email").hide(0);
		}else{
			$(".lbl_email_alerta").text("");
			$(".lbl_email").show(0);
		}
	} );


	$(".cbo_estados").on( 
		"change",
		function(){
			atualiza_cidades();
		}  
	)

	$(".only_numbers").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

	$(".data-value").each(function(){
		$( this ).val( $(this).data("value") );
	});

	atualiza_cidades();

	$(".btn_cadastrar").unbind('click').bind('click', function(e){
		if( $(".cbo_encontrou").val() == "0" ){
			$(".como_encontrou_label").addClass("field_msg");
			e.preventDefault();
			alert("Selecione a opção de como encontrou o site por favor.");
			return;
		}
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
		if( $(".termos:checked").length == 0 ){
			e.preventDefault();
			alert("Concorde com os termos para prosseguir.");
			return;
		}

	} );

	$(".cbo_encontrou").on("change", function(){
		if( $(".cbo_encontrou").val() != "0" ){
			$(".como_encontrou_label").removeClass("field_msg");
		}
	})

});

cidade_inicio = 1;
function atualiza_cidades(){
	$(".cidades_loading").show(0);
	$(".cbo_cidades").addClass("hidden");
	$(".cbo_cidades").load(
		"/async/?uf=" + $(".cbo_estados").val(),
		function(){
			$(".cidades_loading").hide(0);
			$(".cbo_cidades").removeClass("hidden");
			if(cidade_inicio == 1){
				cidade_inicio = 0;
				if( $(".data-value-cidade").length > 0 ){
					$(".cbo_cidades").val( $(".data-value-cidade").data("value") );
				} else {
					$(".cbo_cidades").val(8832);
				}
			}
		} 
	);
}

function validar_cpf( cpf_a_validar ) {

	// remove pontos e traços
	cpf_a_validar = cpf_a_validar.toString();
	cpf_a_validar = cpf_a_validar.replace(/[^0-9]/g, ''); // remove o que não é dígito com regex
	
	// verificar se possui 11 dígitos, o total padrão de um CPF
	if ( cpf_a_validar.length != 11 ) {
		return false; // já nã será válido
	}	

	// pegando os 9 dígitos 
 	codigo = cpf_a_validar.substr(0, 9);
	
	// fazendo o cálculo para gerar o primeiro dígito
	soma = 0; // será a soma
	numero_calculo = 10; // começa com 10 no primeiro dígito
	for (i=0; i < 9; i++) { 
		soma += ( codigo[i]*numero_calculo-- );	
	}
	$resto = soma%11; // trabalhar com o resto
	if($resto < 2) 
		codigo += "0"; // se for menor que 2 será 0
	else
		codigo += (11-$resto); // caso seja maior que 2 sera subtraído em 11

	// fazendo o cálculo para gerar o segundo dígito
	soma = 0; // zerar a soma
	numero_calculo = 11; // desta vez é 11, para o segundo dígito
	for (i=0; i < 10; i++) { 
		soma += ( codigo[i]*numero_calculo-- );	
	}
	$resto = soma%11; // trabalhar com o resto novamente
	if($resto < 2)  // verifica se é maior que 2
		codigo += "0";
	else
		codigo += (11-$resto);

	// Se forem iguais é porque é válido
	if ( codigo === cpf_a_validar ) {
		return true; // cpd válido!
	} else {
		return false; // cpf inválido!
	}
}

function validar_email(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}