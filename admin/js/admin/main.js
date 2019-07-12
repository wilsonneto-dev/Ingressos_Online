
function message(_str, _type){
	/*$.noticeAdd({
		text : _str,
		type : _type,
		stayTime: 5000
	});*/
	alert(_str);
}

// collapsible
function open_collapsible(elm){
	try{
		$elm = $( elm );
		$parent = $( $elm.parent() );
		$collapsible = $( $parent.find(".collapsible")[0] );
		$collapsible.toggle(300);
	}catch(ex){ alert(ex); }
}

function new_window( _url ){
	$("<div></div>").load( _url , function(){ triggers_update( this ); } ).dialog({width: 800, height: 400 });
}

$(document).ready( function(){ 
	try{ 
		initialize(); 
	} catch( ex ){} 
});

/*
function triggers_update( _elm ) {
	
	//alert("ok");

	try{

		// $( _elm ).find("a").on("click",function(){ new_window( $(this).attr("href") ); return false; });

		$( _elm ).find(".button_open_collapsible").click(function(){
			open_collapsible(this);
		});
		$( _elm ).find("#TabelaSort").columnFilters({excludeColumns:[0]});
		$( _elm ).find("#TabelaSort").tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0: { sorter: false } }}) ;
		$( _elm ).find( ".data" ).datepicker({changeYear: true, yearRange: '2000:2020'});
		$( _elm ).find( ".data" ).keypress(function(){ return false; });
	
		$( _elm ).find("a.iframe").fancybox({
			'width'				: '75%',
			'height'			: '75%',
			'autoScale'			: false,
			'type'				: 'iframe'
		});
		
	}
	catch(ex){}

}

// jquery onload
$(document).ready(function(){
	$(".fancy_image").fancybox();
});

*/
