
		var marker = "";
		var marker_cep = "";
		var pos = "";
		var pos_cep = "";
		var add_cep = "";
		var map = "";
		
		 // O estilo do novo mapa
		var styles = [
			{
			  stylers: [
				{ hue: "#00ffe6" },
				{ saturation: -20 }
			  ]
			},{
			  featureType: "road",
			  elementType: "geometry",
			  stylers: [
				{ lightness: 100 },
				{ visibility: "simplified" }
			  ]
			},{
			  featureType: "road",
			  elementType: "labels",
			  stylers: [
				{ visibility: "off" }
			  ]
			}
		];
		
		// registrar o estilo
		var styledMap = new google.maps.StyledMapType(
			styles,
			{
				name: "Customizado"
			}
		);
		
		function initialize() {
			var myOptions = {
				center: new google.maps.LatLng( 
					( $("#coord_x").val() != "" ) ? $("#coord_x").val() : $("#coord_x").data()["value"], 
					( $("#coord_y").val() != "" ) ? $("#coord_y").val() : $("#coord_y").data()["value"] 
				),
				zoom: 12,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				mapTypeControlOptions: {
					mapTypeIds: [
						google.maps.MapTypeId.ROADMAP, 'map_style'
					]
				}			
			};
			
			map = new google.maps.Map(document.getElementById("mapa"), myOptions);
			
			// registrando no mapa os novo tipo
			map.mapTypes.set('map_style', styledMap);
			//map.setMapTypeId('map_style');
			
			google.maps.event.addListener(map, 'click', function(event) {
				pos = event.latLng;
				$("#coord_x").val( pos.lat() );
				$("#coord_y").val( pos.lng() );
				if(marker != "") marker.setMap(null);
				marker = new google.maps.Marker({
					position: pos,
					map: map,
					title: ''+pos+'',
					icon : "/adm/imgs/logo-location.png"
				});
			});

			if($("#coord_x").val() != ""){
				pos = new google.maps.LatLng( 
					( $("#coord_x").val() != "" ) ? $("#coord_x").val() : $("#coord_x").data()["value"], 
					( $("#coord_y").val() != "" ) ? $("#coord_y").val() : $("#coord_y").data()["value"] 
				);
				$("#coord_x").val( pos.lat() );
				$("#coord_y").val( pos.lng() );
				if(marker != "") marker.setMap(null);
				marker = new google.maps.Marker({
					position: pos,
					map: map,
					title: ''+pos+'',
					icon : "/adm/imgs/logo-location.png"
				});
			}
					  
		}
		
		
 