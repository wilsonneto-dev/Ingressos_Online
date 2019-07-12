
/* preloading images */
function preload( arr) {
	if (document.images) {

		for ( var i = ( arr.length - 1 ); i >= 0; i--) {
			var img1 = new Image();
			img1.src = arr[ i ];
			// _l("Baixando imagem: " + arr[i]);
		};
	}
}

/* campanha */
// usada para pegar o proximo elemento de um vetor, ou o anterior
function get_elm( arr, ind, acc ){
	// _l("arr: " + arr);
	var ind_current = ind + acc;
	if( ind_current < 0 ){
		ind_current = ( arr.length - 1 );
	}
	if( ind_current >= arr.length ){
		ind_current = 0;
	}
	_content_obj = arr[ ind_current ];
	// _l( "indice atual: " + ind_current + " / valor: " + arr.length );
	return { index : ind_current, content : _content_obj };
};


preload( arr_campaign );
var ind_campaign = 0;

$( document ).ready( function(){

	obj = get_elm( arr_campaign, ind_campaign, 0 );
	$(".billboard").css("background-image","url("+obj.content.img+")");
	$(".billboard").removeClass("loading");
	if( obj.content.link == "" ){
		$(".billboard a").fadeOut(100);
	}else{
		$(".billboard a").text(obj.content.btn);
		$(".billboard a").attr('href',obj.content.link);
		$(".billboard a").fadeIn(100);
	}

	window.setInterval(function(){
		change_background();
	},7000);

	update_banner();

});

function change_background(){
	ind_campaign += 1;
	if( ind_campaign == arr_campaign.length ){
		ind_campaign = 0;
	}
	obj = get_elm( arr_campaign, ind_campaign, 0 );
	$(".billboard").css("background-image","url("+obj.content.img+")");

	if( obj.content.link == "" ){
		$(".billboard a").fadeOut(100);
	}else{
		$(".billboard a").text(obj.content.btn);
		$(".billboard a").attr('href',obj.content.link);
		$(".billboard a").fadeIn(100);
	}

}

$( window ).resize(function() {
   update_banner();
});

function update_banner(  ){
   width = $( window ).width();
   $billboard = $(".billboard");
   if(width < 880){
   		nova_width = width * 0.9;
   		nova_height = ( nova_width/850 ) * 315;
	  $billboard.css("width", nova_width );
	  $billboard.css("height", nova_height );
   }else{
	  $billboard.css("width", 850 );
	  $billboard.css("height", 315 );
	}
}