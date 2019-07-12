
$(window).on("scroll", function(){
	if( $(window).scrollTop() > 80 ){
		if(!$("body > header").hasClass("fix")){
			$("body > header").addClass("fix");
			$("body > header .logo-wrapper img").animate( { height: 40 }, 200 );
		}
	}else{
		if($("body > header").hasClass("fix"))
			$("body > header").removeClass("fix");
			$("body > header .logo-wrapper img").animate( { height: 89 }, 100 );
	}
});

