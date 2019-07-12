$(document).ready(function(){	
	update_value();
	$( "form.pedido" ).submit(function(event){  
		var msg = validate();
		if( msg != "" ) { 
			event.preventDefault(); 
			alert( msg );
		} 
	});
	$( ".update_value" ).change( function(){ update_value(); } );	
});

var valor_total = 0;
function update_value(){
	valor_total = 0;
	$(".update_value").each(function(){
		valor_total += parseFloat( $(this).val() ) * parseFloat( $(this).data("value") );
	});
	$(".label_total").data("gavalue", valor_total );
	if(valor_total == 0){
		$(".label_total").hide(0);
	}else{
		$(".label_total").show(0);
	}
	$(".label_total").text( ("R$ "+valor_total.toFixed(2)).replace( ".","," ) );
}


function validate(){
	if( valor_total == 0 ){
		return ( "Selecione a quantidade de ingresso(s)." );
	}
	return "";
}
