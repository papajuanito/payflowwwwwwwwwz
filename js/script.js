/* Authors:
	Nizar Khalife Iglesias,
	Waldemar Figueroa,
	Alberto Estrada,
	Antonio Rodriguez
*/

GUERREROS = {
	// !Common
	common: {
		map      : '',
		base_url : '',
		site_url : '',
		app_id   : '308942499144483',
		
		init: function() {
			GUERREROS.common.base_url = PHPVARS.base_url;
			GUERREROS.common.site_url = PHPVARS.site_url;
		},
		
		
		//get and set cookie ;from w3
		getCookie: function(c_name)
		{
		var i,x,y,ARRcookies=document.cookie.split(";");
		for (i=0;i<ARRcookies.length;i++)
		{
		  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		  x=x.replace(/^\s+|\s+$/g,"");
		  if (x==c_name)
		    {
		    return unescape(y);
		    }
		  }
		},
		
		avatar_url: function (guerrero_avatar)
		{
			if (!guerrero_avatar)
				return GUERREROS.common.base_url + 'img/avatar_sample_big.jpg';
			if (!isNaN (guerrero_avatar))
				return GUERREROS.common.base_url + 'img/avatars/default_'+ guerrero_avatar +'.jpg';
			
			return GUERREROS.common.base_url + 'uploads/avatars/' + guerrero_avatar;
		},
		
		setCookie: function(c_name,value,exdays)
		{
			var exdate=new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
			document.cookie=c_name + "=" + c_value;
		},
		
		//** Init Custom Google Map **//
		init_googleMap: function(){

		//Green Map Light Styles
			var greeStyle = [
			{
			    featureType: "water",
			    elementType: "geometry",
			    stylers: [
			      { visibility: "on" },
			      { lightness: -100 }
			    ]
			  },{
			    featureType: "landscape",
			    elementType: "geometry",
			    stylers: [
			      { visibility: "on" },
			      { hue: "#22ff00" },
			      { lightness: -85 },
			      { saturation: 61 }
			    ]
			  },{
			    featureType: "administrative",
			    elementType: "geometry",
			    stylers: [
			      { visibility: "on" },
			      { hue: "#00ff4d" },
			      { lightness: 2 }
			    ]
			  },{
			    featureType: "administrative",
			    elementType: "geometry",
			    stylers: [
			      { visibility: "on" },
			      { hue: "#00ff33" },
			      { saturation: 95 },
			      { lightness: -85 }
			    ]
			  },{
			    elementType: "labels",
			    stylers: [
			      { visibility: "on" },
			      { invert_lightness: true },
			      { hue: "#00ff22" },
			      { lightness: -36 }
			    ]
			  },  {
			    featureType: "poi",
			    stylers: [
			      { visibility: "off" }
			    ]
			  }
			];
		
		
		
		  var greenMapType = new google.maps.StyledMapType(greeStyle,
		    {name: "Light Map"});
		
		  // Create a map object, and include the MapTypeId to add
		  // to the map type control.
		  var mapOptions = {
		    zoom: 3,
		    draggableCursor:'pointer',
		    mapTypeControl: false,
		    backgroundColor:"black",
	     	streetViewControl: false,
			overviewMapControl:false,
		    center: new google.maps.LatLng(30.6468, -66.581),
		    mapTypeControlOptions: {
		      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'green_map']
		    }
		  };
		  this.map = new google.maps.Map(document.getElementById('map_canvas'),
		    mapOptions);
		
		  //Associate the styled map with the MapTypeId and set it to display.
		  this.map.mapTypes.set('green_map', greenMapType);
		  this.map.setMapTypeId('green_map');
		  
		  
		}, //***** end init map *****
		
		//** Load all warrior points in Map **//

		loadLightMapPoints : function(path, id, page) {
			//home/get_guerreros_waypoints
			
			id = typeof (id) == 'undefined' ? 'default' : id;
			path = typeof (path) == 'undefined' ? 'wrongpath' : path;
			page = typeof (page) == 'undefined' ? 'wrongpage' : page;

			$.ajax ({
				url 	: GUERREROS.common.site_url + path,
				type	: 'GET',
				success	: function (data, text)
				{
					if (data.response != 'success') {
						// Do something guys
						//alert('error en data');
					}
					
					else
					{
						//******* Load all waypoints *******
						
						//configure info box 
						var boxText = document.createElement("div");
						boxText.style.cssText = "";
						boxText.innerHTML = "";
						var guerrerosOptions = {
						         content: boxText
						        ,disableAutoPan: false
						        ,maxWidth: 0
						        ,pixelOffset: new google.maps.Size(-104, -96)
						        ,zIndex: null
						        ,boxStyle: { 
						          background: "url('"+GUERREROS.common.base_url+"/img/map_bubble.png') no-repeat"
						          ,opacity: 1.0
						          ,width: "202px"
						          ,height:"78px"
						         }
						        ,closeBoxMargin: "-5px 10px 2px 2px"
						        ,closeBoxURL: ""
						        ,infoBoxClearance: new google.maps.Size(0,0)
						        ,isHidden: false
						        ,pane: "floatPane"
						        ,enableEventPropagation: false
						};

						/************* HTML template inside infoBox  *** 
								<div class="infoBox">
									<img class='avatar' src="pathfromdatabase">
									<strong>Real Name (from database )</strong>
									Place from database
								</div>
							*************/
						
						if( id == 'default' ){
							var ib = new InfoBox(guerrerosOptions);
							var geocoder;
							var geocoder2;
							var guerrero=[]; //array containing guerreros waypoints
							//var direccion =[];
							
																
							// set/load Ricky Martin waypoint
							var ricky_data = data.guerreros.shift();
							var r_icon = GUERREROS.common.base_url+'img/ricky_waypoint.png'; 
							var pointerLink = (page == 'home') ? 'cuenta/login' : 'social/perfil/' + ricky_data.guerrero_id;
							
							geocoder = new google.maps.Geocoder();
							var rLatLng = new google.maps.LatLng(ricky_data.guerrero_geo_lat,ricky_data.guerrero_geo_long);
								
								geocoder.geocode({'latLng': rLatLng}, function(results, status) {
		  								
		  								
									var ricky = new google.maps.Marker({
			 							position: rLatLng,
			  							map: GUERREROS.common.map,
			  							icon: r_icon,
			  							animation: google.maps.Animation.DROP, 
			  							html: "<img class='avatar' src='"+ GUERREROS.common.avatar_url (ricky_data.guerrero_avatar) +"'><a href='" + GUERREROS.common.site_url + pointerLink + "' class='gue_name'>"+ricky_data.guerrero_name + "</a><span class='gue_town'>" + ricky_data.guerrero_map_country + "</span><span class='g_friends'>"+ricky_data.friend_total+" Guerreros</span><span class='map_pointer "+ricky_data.legion_style+" "+ ricky_data.rank_style +"'></span>"
									});
																
									google.maps.event.addListener(ricky, 'click', function() {
										ib.pixelOffset_.height= -140;
										ib.pixelOffset_.width = -105;
										ib.setContent(this.html);
										ib.open(GUERREROS.common.map, ricky);
									});

									if(id=='default'){
										GUERREROS.common.map.setCenter(rLatLng);
										ib.pixelOffset_.height= -140;
										ib.pixelOffset_.width = -105;
										ib.setContent(ricky.html);
										ib.open(GUERREROS.common.map, ricky);
									}
								});	 
	
							
							// set/load Other Guerreros waypoints
							var gLatLng;
							var w_icon = GUERREROS.common.base_url+'img/user_waypoint.png';
							for (var i in data.guerreros) {
														
								var g_name, g_country;
								var pointerLink = (page == 'home') ? 'cuenta/login' : 'social/perfil/' + data.guerreros[i].guerrero_id;
								
								/*
if(data.guerreros[i].guerrero_is_name_private == 1)
								{
									
									g_name = data.guerreros[i].guerrero_name;
								}
								else
								{
									g_name = data.guerreros[i].guerrero_real_name;

								}
								*/
								if(data.guerreros[i].guerrero_map_country.length > 14 )
								{ 
									g_country = data.guerreros[i].guerrero_map_country.substring(0, 14) + "…";
								}
								else
								{
									g_country = data.guerreros[i].guerrero_map_country;
								}

								
								
								gLatLng = new google.maps.LatLng(data.guerreros[i].guerrero_geo_lat,data.guerreros[i].guerrero_geo_long);
								
								
								
								guerrero.push(new google.maps.Marker({ 
	 								position: gLatLng,
	      							map: GUERREROS.common.map,
	      							icon: w_icon,
	      							html: "<img class='avatar' src='"+ GUERREROS.common.avatar_url (data.guerreros[i].guerrero_avatar) +"'><a href='" + GUERREROS.common.site_url + pointerLink + "' class='gue_name'>"+ data.guerreros[i].guerrero_name + "</a><span class='gue_town'>"+ g_country+"</span><span class='g_friends'>"+data.guerreros[i].friend_total+" Guerreros</span><span class='map_pointer "+data.guerreros[i].legion_style+" "+ data.guerreros[i].rank_style +"'></span>"
									}));
									
								
	  							google.maps.event.addListener(guerrero[i], 'click', function() {
	  								ib.pixelOffset_.height= -110;
	  								ib.pixelOffset_.width = -103;
	  								ib.setContent(this.html);
									ib.open(GUERREROS.common.map, this);
								}); 
							
			
								
	    
							}
							

							
						}//end allwarriors
						else{ //** this part load only one waypoint depending on the id of the warrior
								var ib = new InfoBox(guerrerosOptions);
								var geocoder;
								var geocoder2;
								var gLatLng;
								var pointerLink = (page == 'home') ? 'cuenta/login' : 'social/perfil/' + data.guerreros.guerrero_id;
								
								if(id==1){
									var _icon = GUERREROS.common.base_url+'img/ricky_waypoint.png'; 
									geocoder = new google.maps.Geocoder();
									var gLatLng = new google.maps.LatLng(data.guerreros.guerrero_geo_lat,data.guerreros.guerrero_geo_long);
									geocoder.geocode({'latLng': gLatLng}, function(results, status) {
										guerrero = new google.maps.Marker({
				 							position: gLatLng,
				  							map: GUERREROS.common.map,
				  							icon: _icon,
				  							animation: google.maps.Animation.DROP, 
				  							html: "<img class='avatar' src='"+ GUERREROS.common.avatar_url (data.guerreros.guerrero_avatar) +"'><a href='" + GUERREROS.common.site_url + pointerLink + "'  class='gue_name'>"+data.guerreros.guerrero_real_name + "</a><span class='gue_town'>" + data.guerreros.guerrero_map_country + "</span><span class='g_friends'>"+data.guerreros.friend_total+" Guerreros</span><span class='map_pointer "+data.guerreros.legion_style+" "+ data.guerreros.rank_style +"'></span>"
										});
																	
										google.maps.event.addListener(guerrero, 'click', function() {
											ib.pixelOffset_.height= -140;
											ib.pixelOffset_.width = -105;
											ib.setContent(this.html);
											ib.open(GUERREROS.common.map, this);
										});
									GUERREROS.common.map.setCenter(gLatLng);
									ib.pixelOffset_.height= -140;
		  							ib.setContent(guerrero.html);
									ib.open(GUERREROS.common.map, guerrero);

									
									});	 
									
								}
								else{
									var g_name, g_country;
									
									if(data.guerreros.guerrero_map_country.length > 14 )
									{ 
										g_country = data.guerreros.guerrero_map_country.substring(0, 14) + "…";
									}
									else
									{
										g_country = data.guerreros.guerrero_map_country;
									}
								
									var _icon = GUERREROS.common.base_url+'img/user_waypoint.png';
									
									gLatLng = new google.maps.LatLng(data.guerreros.guerrero_geo_lat,data.guerreros.guerrero_geo_long);
								
									guerrero = new google.maps.Marker({ 
	 									position: gLatLng,
	      								map: GUERREROS.common.map,
	      								icon: _icon,
	      								html: "<img class='avatar' src='"+ GUERREROS.common.avatar_url (data.guerreros.guerrero_avatar) +"'><a href='" + GUERREROS.common.site_url + pointerLink + "' class='gue_name'>"+data.guerreros.guerrero_name+ "</a><span class='gue_town'>" + g_country + "</span> <span class='g_friends'>"+data.guerreros.friend_total+" Guerreros</span><span class='map_pointer "+data.guerreros.legion_style+" "+ data.guerreros.rank_style +"'></span>"
									});
									
								
	  								google.maps.event.addListener(guerrero, 'click', function() {
	  									ib.pixelOffset_.height= -120;
	  									ib.setContent(this.html);
										ib.open(GUERREROS.common.map, this);
									});
									
									GUERREROS.common.map.setCenter(gLatLng);
									ib.pixelOffset_.height= -120;
		  							ib.setContent(guerrero.html);
									ib.open(GUERREROS.common.map, guerrero);
 
								}
								
												
						}//end else single warrior
						
						// this function close the infoBox bubbles on map click.
						google.maps.event.addListener(GUERREROS.common.map, 'click', function() {
       						 	ib.close();
						});  
					}
				},
				error	: function (obj, text, error) {
					//alert('error');
				}
			}); //end ajax function
		
		},//****** end load map *****
		
		
		//----
		
		
		map_search_setup: function (selectors)
		{
			var geocoder = new google.maps.Geocoder(),
			    w_icon   = GUERREROS.common.base_url+'img/user_waypoint.png',
			    nuevo_guerrero,
			    loadConfigMap,
			    geocodeCoords,
			    drop_pin;
			
			/* Map on Location Settings */
			// Selective map initialize
			
			loadConfigMap = function() {
				GUERREROS.common.init_googleMap();
			  	var lat_val  = $(selectors.lat).val(),
			  	    long_val = $(selectors.long).val(),
			  	    points   = new google.maps.LatLng (lat_val, long_val);
				
			  	//added google map interaction to the form, user now can click and get coordinates
				
				geocodeCoords = function (coords)
				{
					geocoder.geocode({'latLng':coords}, function(result, status) {
						switch(status) {
					    case 'ZERO_RESULTS':
							//alert('Map does not contain details for the given address');
							nuevo_guerrero.setMap(null);
							nuevo_guerrero = null;
							break;
						case 'ERROR':
							//alert('There was a problem in processing. Please try again later.');
							break;					
						case 'OK':
							var resultArr     = result[0].address_components,
							    info          = new Array(),
							    humanLocation = '';
							
							for (var z = 0; z < resultArr.length; ++z)
							{
								if (resultArr[z].types[0] == 'locality') {
									info[z] = resultArr[z].long_name;
								}
								if (resultArr[z].types[0] == 'administrative_area_level_1') {
									info[z] = resultArr[z].long_name;
								}
								if(resultArr[z].types[0] == 'country') {
									info[z] = resultArr[z].long_name;
								}
								
								//	$('#map_search').attr('value',resultArr[z].long_name);
							}
							
							for (var i = 0; i < info.length; ++i)
							{
								if (info[i] != undefined) {
									if (humanLocation == '') {
										humanLocation = info[i];
									}
									else {
										humanLocation += ', '+info[i];
									}
								}
							}
							$(selectors.search).val (humanLocation);
							break;
						}
					});
				};
				
				drop_pin = function (event, kind)
				{
					var latLng,
					    xy,
					    control;
					
					if (kind == "auto") {	
						latLng = event;
					}
					else {
						latLng = event.latLng;
					}
					
					if (nuevo_guerrero) {
			           nuevo_guerrero.setMap (null);
			           nuevo_guerrero = null;
	        		}
	        		
	        		nuevo_guerrero = new google.maps.Marker({ 
						position: latLng,
						map: GUERREROS.common.map,
						icon: w_icon,
						draggable : true,
						animation: google.maps.Animation.DROP
					});
					
					xy = latLng;
					geocodeCoords (xy);
					GUERREROS.common.map.setCenter(xy);
					
					$(selectors.lat).val  (xy.lat());
					$(selectors.long).val (xy.lng());
	
					google.maps.event.addListener (nuevo_guerrero, 'dragend', function() {
						var coords = nuevo_guerrero.getPosition();
						$(selectors.lat).val (coords.lat());
						$(selectors.long).val (coords.lng());
					
						geocodeCoords (coords);
						GUERREROS.common.map.setCenter (coords);
					});
				};
				
				if (lat_val && long_val)
					drop_pin (points, "auto");
				
				google.maps.event.addListener (GUERREROS.common.map, 'click', function (event, point) {
					drop_pin (event);
				});
			};//end loadConfigMap function
			
			loadConfigMap();
			
			$(selectors.search).autocomplete ({
		      //This bit uses the geocoder to fetch address values
		      source: function(request, response) {
					geocoder.geocode ({'address': request.term}, function (results, status) {
						response ($.map (results, function (item) {
							return {
								label     : item.formatted_address,
								value     : item.formatted_address,
								latitude  : item.geometry.location.lat(),
								longitude : item.geometry.location.lng()
							}
						}));
					})
				},
				
				//This bit is executed upon selection of an address
				select: function(event, ui) {
					$(selectors.lat).val (ui.item.latitude);
					$(selectors.long).val (ui.item.longitude);
					var location = new google.maps.LatLng (ui.item.latitude, ui.item.longitude);
					
					if (nuevo_guerrero)
						nuevo_guerrero.setPosition (location);
					else
						nuevo_guerrero = new google.maps.Marker({ 
							position: location,
							map: GUERREROS.common.map,
							icon: w_icon,
							draggable : true,
							animation: google.maps.Animation.DROP
						});
					GUERREROS.common.map.setCenter (location);
				}
			});
		},
		
		embed_dynamic_light : function() {
			var flashvars = false;
			var atributes = { id: 'flash_light' };
			var params = { menu: 'false', quality: 'high', wmode: 'transparent' };
			
			swfobject.embedSWF(GUERREROS.common.base_url + "img/flash_light/Guerreros_Light.swf", "flash_light", "67px", "240px", "10.2.0", GUERREROS.common.base_url + "img/flash_light/expressInstall.swf", flashvars, params, atributes);
		}
	},
	
	// !Home
	home: {
		init: function() {
			// controller-wide code
		},
		
		index: function() {
				
				GUERREROS.common.init_googleMap(); //load custom google map
				GUERREROS.common.loadLightMapPoints('home/get_guerreros_waypoints', undefined, 'home');	// load "guerreros" waypoints including Ricky Martin 
				
				
				//lightbox call, if cookie set dont show if is set, show
				var lightbox = GUERREROS.common.getCookie('video');
				if(!(lightbox))
				{
					$('#over_vaina').show();
					GUERREROS.common.setCookie('video',true,999);
				}
				else
					$('#over_vaina').hide();
				
				
				$('#close_vaina').click(function(){
					GUERREROS.common.setCookie('video',true,999);
					$('#over_vaina').fadeOut('slow', function(){});
				});	
				
				GUERREROS.common.embed_dynamic_light();
		},
		
		light_map: function() {
		
			GUERREROS.common.init_googleMap(); //load custom google map
			GUERREROS.common.loadLightMapPoints('home/get_guerreros_waypoints', undefined, 'lightmap');	// load "guerreros" waypoints including Ricky Martin 
			
			$('#map_canvas').attr("style", "height:100%");	//modify map height to get full browser size
			
			GUERREROS.common.embed_dynamic_light();	
		}
	},
	
	
	// !Cuenta
	cuenta: {
		init: function() {
			// controller-wide code
		},
		
		registro: function() {
			// Change input field with dropdown change.
			$('#guerrero_name_preset').change( function () {
				var name = $(this).val();
				$('#guerrero_name').attr('value', name);
			});

			// Selective map initialize
			if ($('#map_canvas').length) { // Don't load map on registration steps that don't need it
				GUERREROS.common.map_search_setup ({
					search : '#guerrero_map_town',
					lat    : '#guerrero_geo_lat',
					long   : '#guerrero_geo_long'
				});
			}
			
			//----
			
			// Legions and Subscription Types Selected State
			$('#legion_list li, #subscription_type_list li').click (function() {
				var	selected_container	= $(this),
					selected_radio		= null,
					other_selected		= null;
				
				if (selected_container.hasClass ('selected'))
					return false;
				
				selected_radio = selected_container.find ('input:radio');
				other_selected = selected_container.siblings ('.selected');
				
				other_selected.removeClass ('selected');
				selected_container.addClass ('selected');
				selected_radio.prop ('checked', true);
				
				$('.description_' + other_selected.data ('legion')).removeClass ('selected');
				$('.description_' + selected_container.data ('legion')).addClass ('selected');
			});
			
			$('#subscription_type_list li').click (function() {
				var list_item  = $(this),
				    money_elem = list_item.find ('strong');
				
				$('#register p').text ('La inscripción de '+ money_elem.text() +'D te convierte en un Guerrero de Luz');
			});
			
			$('#legion_list.no_highlight li, #subscription_type_list.no_highlight li').unbind ('click');
			
			//----
			
			// Back and Forward submit buttons
			$('#submit_prev').click (function() {
				var form	= $(this).closest ('form'),
					step	= form.find ('#step').val();
				
				form.prop ('action', form.prop ('action') +'/'+ (step - 1));
				form.find ('input:disabled').removeProp ('disabled');
				form.submit();
			});
			
			$('#submit_next').click (function() {
				var form	= $(this).closest ('form'),
					step	= form.find ('#step').val();
				
				form.prop ('action', form.prop ('action') +'/'+ (parseInt (step) + 1));
				form.find ('input:disabled').removeProp ('disabled');
			});
			
			//---
			
			// Birthday Datepicker
			$('#guerrero_birthday_picker').datepicker ({
					dateFormat        : 'd / M / yy',
					showOtherMonths   : true,
					selectOtherMonths : true,
					altField          : $('#guerrero_birthday'),
					altFormat         : 'yy-mm-dd',
					changeYear        : true,
					changeMonth       : true,
					yearRange         : '-100:+0'
			});
			
			//----
			
			// Hide State Dropdown Selectively
			$('#country').change (function() {
				var country         = $(this).val(),
				    state_container = $('#state').closest ('li');
				
				if (country == '' || country == 'CA' || country == 'US' || country == 'UM')
					state_container.fadeIn();
				else
					state_container.fadeOut();
			});
		}
	},
	
	
	// !Social
	social :{
		accept_friend_error : 'Hubo un error aceptando la invitación. Intenta nuevamente.',
		ignore_friend_error : 'Hubo un error ignorando la invitación. Intenta nuevamente.',
		facebook_friends    : null,
		
		init: function() {
			var $overlay,
			    $rank_boxes,
			    rank_count;

			// Birthday Datepicker
			$('#guerrero_birthday_picker').datepicker ({
					dateFormat        : 'd / M / yy',
					showOtherMonths   : true,
					selectOtherMonths : true,
					altField          : $('#guerrero_birthday'),
					altFormat         : 'yy-mm-dd',
					changeYear        : true,
					changeMonth       : true,
					yearRange         : '-100:+0'
			});
		
		
			$('#social').append('<div class="guerreros_lightbox"></div>');
			
			//add reward lightbox to markup
			$('#social').find('div.guerreros_lightbox').after(
				'<div id="recompensa_box">' + 
					'<h3>Haz ganado una nueva recompensa</h3>' + 
					'<span class="super"></span>' + 
					'<h4></h4>' + 
					'<p></p>' + 
					'<a href="'+GUERREROS.common.site_url +'social/recompensas" id="gt_lightbox_cta">Recompensas</a>' + 
				'</div>'
			);
			
			//DESCOMENTAR CUANDO ESTÉ el COPY REQUERIDO	
			// Añadir recompensas al lightbox dinámicamente
			$.ajax ({
				url     : GUERREROS.common.site_url +'social/get_new_trophies',
				type    : 'GET',
				success : function (data, text) {
						
						if (!data.response == 'success') {
						// feedback de error
						}
						else {
							var i = 0;
							
								// funcion recursiva para cambiar contenido del lightbox
								function show_recompensa(i){
									$('#recompensa_box').find('span').removeClass();
									$('#recompensa_box').find('span').addClass(data.new_trophies[i].trophy_style);
									$('#recompensa_box').find('h4').empty();
									$('#recompensa_box').find('h4').html(data.new_trophies[i].trophy_name);								
									$('#social').find('div.guerreros_lightbox').css( { 'display': 'block' } )
									$('#recompensa_box').find('p').empty();								
									$('#recompensa_box').find('p').html(data.description[data.new_trophies[i].trophy_style]);
									
									
									//display the reward and align it on the center
									var boxX = (window.innerWidth * 0.5) - 275;
									var boxY = (window.innerHeight * 0.5) - 215;
									$('#recompensa_box').show();
									
									//add close button
									$('#recompensa_box').append('<a href="javascript:;" id="close_lightbox">Close</a>');
									//show close button
									$('#close_lightbox').show();
								}
								
								if(data.new_trophies != '') 
								{
									show_recompensa(i);
								}
								
								$('#close_lightbox').click(function(e) {
																		
									if( i < data.new_trophies.length-1)
									{
										i++;
										show_recompensa(i);
									}
									else{
										$(this).fadeOut('fast');
										$('#recompensa_box').fadeOut('fast');
										$('.guerreros_lightbox').fadeOut('fast');
									}
								});

						}		
				
				},
				error: function (obj, text, error) {
					// feedback de error 
					//alert(":( problemas con las recompensas");
				}
			});// end ajax call para verificar si hay recompensa
			
			
			//----
			
			
			// Rank lightbox
			$overlay    = $('.guerreros_lightbox');
			$rank_boxes = $('.rank_box');
			rank_count  = $rank_boxes.length;
			
			if (rank_count) {
				$rank_boxes.find ('.close_lightbox, .next_lightbox_cta').click (function() {
					var $button      = $(this),
					    i            = $button.data ('i');
					
					if ((i + 1) >= rank_count) {
						$rank_boxes.fadeOut (400, function() {
							$(this).remove();
						});
						$overlay.fadeOut();
					}
					else {
						$button.closest ('.rank_box').fadeOut (400, function() {
							$(this).remove();
							$rank_boxes.eq (i + 1).fadeIn();
						});
					}
				});
				
				$overlay.fadeIn('fast', function() {
					$rank_boxes.first().fadeIn();
				});
			}
		},
		
		//----
		
		sidebar_recommended : function() {
			// Invite existing guerreros
			$('#recommended_warriors .green_btn').click (function() {
				var $button    = $(this),
				    $container = $button.closest ('li'),
				    $loader;
				
				$button.hide();
				$button.after ('<div class="loading">Procesando…</div>');
				
				$loader = $container.find ('.loading');
				$loader.fadeIn();
				$button.remove();
				
				$.ajax ({
					url     : GUERREROS.common.site_url +'social/ajax_invite_friend/'+ $button.data ('guerrero_id'),
					type    : 'GET',
					success : function (data, text) {
						var message;
						
						if (!data.response == 'success') {
							$loader.remove();
							$button.fadeIn();
						}
						else {
							switch (data.type) {
							case 'self':
							case 'already_friends':
								message = 'Ya son amigos.';
								break;
							case 'accept':
								message = 'Invitación aceptada.';
								break;
							case 'invite':
							case 'invite_pending':
							default:
								message = 'Invitación enviada.';
								break;
							}
							$button.remove();
							$loader.after ('<div class="feedback">'+ message +'</div>');
							$loader.remove();
							$container.find ('.feedback').fadeIn();
						}
					},
					error   : function (obj, text, error) {
						$loader.remove();
						$button.fadeIn();
					}
				});
			});
		},
		
		handle_friend : function (cta, type) {
			if (typeof cta == 'undefined' || typeof type == 'undefined')
				return false;
			
			var accept_cta    = $(cta),
			    cta_container = accept_cta.closest ('.actions'),
			    both_ctas     = cta_container.find ('.profile_btn'),
			    ajax_loader   = cta_container.find ('.ajax_loader'),
			    accept_cta 	  = cta_container.find('.accept'),
			    ignore_cta    = cta_container.find('.ignore'),
			    stop_thinking = function() {
					ajax_loader.hide();
			    },
			    btn_class     = '',
			    error_message = '',
			    success_text  = '',
			    error_feedback = null;
			switch (type) {
			case 'accepted':
				btn_class     = 'accept';
				accept_cta.after ('<div class="loading">Procesando…</div>');
				$loader = accept_cta.next();
				error_message = this.accept_friend_error;
				success_text  = 'Aceptado';
				break;
			case 'ignored':
				btn_class     = 'ignore';
				ignore_cta.after ('<div class="loading">Procesando…</div>');
				$loader = ignore_cta.next();
				error_message = this.ignore_friend_error;
				success_text  = 'Ignorado';
				break;
			}
			
		    error_feedback = function() {
		    	stop_thinking();
		    	both_ctas.show();
		    	alert (error_message);
		    };
			
			both_ctas.hide();
			//both_ctas.after ('<div class="loading">Procesando…</div>');
			
			$loader.fadeIn();

			$.ajax ({
				url     : GUERREROS.common.site_url + 'social/ajax_handle_friend/' + cta_container.data ('guerrero_id') +'/'+ type,
				type    : 'GET',
				success : function (data, text) {
					if (data.response != 'success')
						error_feedback();
					else
					{
						$loader.remove();
						//stop_thinking();
						accept_cta
							.unbind ('click')
							.prop ('disabled', true)
							.removeClass (btn_class)
							.addClass ('success')
							.text (success_text)
							.fadeIn();
					}
				},
				error   : function (obj, text, error) {
					error_feedback();
				}
			});
		},
		
		more_messages: function ($button, guerrero_id) {
			var $loader      = null,
				handle_error = function() {
					$loader.hide();
					$button.fadeIn();
					
					alert ('Hubo un error obteniendo más mensajes. Intenta nuevamente.');
				};
			
			$button.before ('<div class="loading">Procesando…</div>');
			$loader = $button.prev();
			$loader.fadeIn();
			
			$.ajax ({
				url     : GUERREROS.common.site_url +'social/ajax_more_messages/'+ guerrero_id,
				type    : 'POST',
				data    : {
					ref               : $(document.body).data ('action'),
					last_checked_date : $button.data ('last_checked_date')
				},
				success : function (data, text) {
					var $last_message,
						new_markup = '',
					    i;
					
					if (data.response != 'success') {
						handle_error();
					}
					else {
						for (i in data.messages) {
							new_markup += '\
								<div class="single box clearfix new">' + 
									(data.messages[i].send_id == guerrero_id && data.messages[i].send_id != 1 ? '' : '\
										<span class="badge_circle '+ data.messages[i].send_legion +' '+ data.messages[i].send_rank +'"></span>\
										<a href="'+ GUERREROS.common.site_url +'social/perfil/'+ data.messages[i].send_id +'"><img src="'+ GUERREROS.common.avatar_url (data.messages[i].send_avatar) +'" class="user_pic" alt="thumbnail"></a>'
									) +'\
									\
									<div class="message box_content">\
										<span><strong><a href="'+ GUERREROS.common.site_url +'social/perfil/'+ data.messages[i].send_id +'">'+ data.messages[i].send_name +'</a></strong> escribe:</span>\
										<p>'+ data.messages[i].message_text +'</p>\
									</div>\
									<div class="box_btm"> </div>\
								</div>';
						}
						
						$loader.before (new_markup);
						$loader.hide();
						$loader.closest ('.feed').find ('.new').fadeIn ('fast', function() {
							$(this).removeClass ('new');
						});
						
						$last_message = $(data.messages).last();
						$button.data ('last_checked_date', $last_message[0].message_date);
						
						if (data.messages.length < 5)
							$button.fadeOut ('fast', function() {
								$button.remove();
							});
					}
				},
				error   : function (obj, text, error) {
					handle_error();
				}
			});
		},
		
		// Centro de Mando ------------------------------------------------------------
		index: function() {
			var guerrero_id = $('.feed').data ('guerrero_id');
			
			GUERREROS.social.sidebar_recommended();
			
			$('#view_more_notifications').click (function() {
				GUERREROS.social.more_messages ($(this), guerrero_id);
			});
			
			// !Textarea Maxlength 
			$('#open_message').keyup (function() { 
				var textarea = $(this), 
				limit = textarea.prop ('maxlength'), 
				current_text = textarea.val(); 
				counter = limit-current_text.length;
				$('#maxCharacters').html(counter);
				if (current_text.length > limit) { 
				textarea.val (current_text.substr (0, limit)); 
				} 
			});


		},
		
		// Notificaciones ------------------------------------------------------------
		notifications: function() {
			GUERREROS.social.sidebar_recommended();
			
			// Accept Invites
			$('.message .green_btn').live ('click', function() {
				var $loader,
				    $button      = $(this),
				    handle_error = function() {
				    	$loader.remove();
						$button.fadeIn();
				    };
				
				$button.hide();
				$button.after ('<div class="loading">Procesando…</div>');
				$loader = $button.next();
				$loader.fadeIn();
				
				$.ajax ({
					url     : GUERREROS.common.site_url +'social/ajax_handle_friend/'+ $button.data ('guerrero_id') +'/accepted',
					type    : 'GET',
					success : function (data, text) {
						var message;
						
						if (!data.response == 'success') {
							handle_error();
						}
						else {
							$loader.after ('<div class="feedback">Invitación aceptada.</div>');
							$loader.next().fadeIn();
							$loader.remove();
							$button.remove();
						}
					},
					error   : function (obj, text, error) {
						handle_error();
					}
				});
			});
			
			//----
			
			// Get More Notifications
			$('#view_more_notifications').click (function() {
				var $button      = $(this),
				    $loader      = null,
					handle_error = function() {
						$loader.remove();
						$button.fadeIn();
						
						alert ('Hubo un error obteniendo más notificaciones. Intenta nuevamente.');
					};
				
				$button.before ('<div class="loading">Procesando…</div>');
				$loader = $button.prev();
				$loader.fadeIn();
				
				$.ajax ({
					url     : GUERREROS.common.site_url +'social/ajax_more_notifications',
					type    : 'POST',
					data    : {
						last_checked_date      : $button.data ('last_checked_date'),
						last_registration_date : $button.data ('last_registration_date')
					},
					success : function (data, text) {
						var $last_notification,
						    $last_registration,
							new_markup = '',
							notifications_length,
							notification,
							registrations_length,
							registration,
						    i, j;
						
						if (data.response != 'success') {
							handle_error();
						}
						else {
							notifications_length = data.notifications.length;
							
							for (i = 0; i < notifications_length; i++) {
								notification = data.notifications[i];
								
								switch (notification.ticker_type) {
								case '2':  // Notification: Faction Registrations
									registrations_length = notification.guerreros.length;
									
									new_markup += '\
										<div class="single box clearfix">\
											<div class="message box_content">\
												<span class="faction_shield '+ notification.ticker_obj_style +'"></span>\
											  	<p><strong>'+ notification.ticker_obj_string +'</strong> ha reclutado '+ registrations_length +' nuevos miembros en la guerra contra la trata.</p>\
											  	';
									
									for (j = 0; j < registrations_length; j++) {
										registration = notification.guerreros[j];
										new_markup += '\
											<a href="'+ GUERREROS.common.site_url +'social/perfil/'+ registration.guerrero_id +'">\
									  			<img src="'+ GUERREROS.common.avatar_url (registration.guerrero_avatar) +'" class="fb_pic"\
									  				alt="'+ registration.guerrero_name +'"\
									  				title="'+ registration.guerrero_name +'"/>\
									  		</a>';
									}
									
									new_markup += '\
												\
												<span class="not_date">'+ notification.ticker_long_date +'</span>\
											</div>\
											<div class="box_btm">&nbsp;</div>\
										</div>';
									
									$last_registration = $(notification.guerreros).last();
									$button.data ('last_registration_date', $last_registration[0].ticker_date);
									break;
								case '3':  // Notification: Recompensa Won
									new_markup += '\
										<div class="single box clearfix new">\
											<img src="'+ GUERREROS.common.avatar_url (notification.guerrero_avatar) +'" class="user_pic" alt="thumbnail"/>\
											<div class="message box_content">\
												<span class="badge_wheel reward '+ notification.ticker_obj_style +'"></span>\
											  	<p><strong>'+ notification.guerrero_name +'</strong> ha ganado la recompensa <strong>'+ notification.ticker_obj_string +'</strong>.</p>\
											  	\
												<span class="not_date">'+ notification.ticker_long_date +'</span>\
											</div>\
											<div class="box_btm">&nbsp;</div>\
										</div>';
									break;
								case '4':  // Notification: Invitation
									new_markup += '\
										<div class="single box clearfix new">\
											<img src="'+ GUERREROS.common.avatar_url (notification.guerrero_avatar) +'" class="user_pic" alt="thumbnail"/>\
											<div class="message box_content">\
												<a class="green_btn" href="javascript:;" data-guerrero_id="'+ notification.guerrero_id +'">Aceptar</a>\
											  	<p><strong>'+ notification.guerrero_name +'</strong> te ha invitado a unirse a su ejército.</p>\
												\
												<span class="not_date">'+ notification.ticker_long_date +'</span>\
											</div>\
											<div class="box_btm">&nbsp;</div>\
										</div>';
									break;
								case '5':  // Notification: Rank Promotion
									new_markup += '\
										<div class="single box clearfix new">\
											<img src="'+ GUERREROS.common.avatar_url (notification.guerrero_avatar) +'" class="user_pic" alt="thumbnail"/>\
											<div class="message box_content">\
												<span class="badge_wheel honor '+ notification.ticker_obj_style +'"></span>\
											  	<p><strong>'+ notification.guerrero_name +'</strong> ha logrado llegar al rango de <strong>'+ notification.ticker_obj_string +'</strong>.</p>\
											  	\
												<span class="not_date">'+ notification.ticker_long_date +'</span>\
											</div>\
											<div class="box_btm">&nbsp;</div>\
										</div>';
									break;
								default:
									break;
								}
							}
							
							$loader.before (new_markup);
							$loader.remove();
							$button.closest ('#cuartel_notifications').find ('.new').fadeIn ('fast', function() {
								$(this).removeClass ('new');
							});
							
							$last_notification = $(data.notifications).last();
							$button.data ('last_checked_date', $last_notification[0].ticker_date);
							
							if (notifications_length < 6)
								$button.fadeOut ('fast', function() {
									$button.remove();
								});
						}
					},
					error   : function (obj, text, error) {
						handle_error();
					}
				});
			});
		},
		
		/* Recompensas View ------------------------------------------------------------ */
		recompensas: function() {
			var $popup = $('#rewards_popup');
			var positions = [
								{ top: -77, left: -104, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_dedicado.png', id: 0 },
								{ top: -77, left: 87, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_super.png', id: 1 },
								{ top: -77, left: 277, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_fotogenico.png', id: 2 },
								{ top: -77, left: 467, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_mejor_amigo.png', id: 3 },
								
								{ top: 117, left: -104, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_pensador.png', id: 4 },
								{ top: 117, left: 87, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_reclutador.png', id: 5 },
								{ top: 117, left: 277, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_sociable.png', id: 6 },
								{ top: 117, left: 467, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_super.png', id: 7 },
								
								{ top: 310, left: -104, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_dedicado.png', id: 8 },
								{ top: 310, left: 87, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_super.png', id: 9 },
								{ top: 310, left: 277, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_fotogenico.png', id: 10 },
								{ top: 310, left: 467, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_mejor_amigo.png', id: 11 },
								
								{ top: 510, left: -104, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_pensador.png', id: 12 },
								{ top: 510, left: 87, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_reclutador.png', id: 13 },
								{ top: 510, left: 277, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_sociable.png', id: 14 },
								{ top: 510, left: 467, img: GUERREROS.common.base_url + '/img/cuartel/recompensas/reward_super.png', id: 15 }
							];       
			
			var description = {
					
					gue 	:  '¡Eres un guerrero ejemplar! Tu compromiso y esfuerzo con la lucha te hacen ser un Guerrero Dedicado. Continua luchando y erradiquemos la trata.',
					gue_incomplete:'Conviértete en un guerrero dedicado completando tu perfil, ganando un rango, ganando una recompensa y publicando mensajes de luz.',
					reclu	: '¡Tu especialidad es aumentar las filas del ejercito de luz! Continua tu misión expandiendo la lucha como Guerrero Reclutador.',
					reclu_incomplete:'Conviértete en un guerrero reclutador, aumentando las filas de nuestro gran ejercito de luz.',
					pens 	:  'Eres inteligente e introspectivo, y tu prioridad es educar gritar contra la trata, por esto eres un Guerrero Pensador.',
					pens_incomplete:'Conviértete en guerrero pensador educando a tus compañeros guerreros a trávez de los mensajes de luz.',
					amigo   :  'Luchar a tu lado es un honor. Tu valentía y liderazgo de hacen un futuro guerrero de luz, por esto eres un Guerrero Mejor Amigo.',
					amigo_incomplete:'Conviértete en guerrero mejor amigo al compartir tu primer mensaje de luz con tus amigos.',
					super   :  '¡Eres un luchador incansable! Tus ejecuciones inspira el paso de nuestro ejercito en contra de las fuerzas de mal. Te destacas como un Súper Guerrero.',
					super_incomplete:'Conviértete en un super guerrero distribuyendo palabras de luz a más de 25 mensajes.',
					foto  	:  'Tu imagen de luz ilumina y destruye las fuerzas del mal. Continua luchando como Guerrero Fotogénico.',
					foto_incomplete:'Conviértete en un guerrero fotogenico al subir tu primer avatar de luz y compartir tu imagen de guerrero con el mundo.',
					soc 	:  'No pierdes ninguna oportunidad de gritar nuestro mensaje de amor. Sigue la lucha contra las fuerzas del mal como Guerrero Sociable.',
					soc_incomplete:'Conviértete en un guerrero sociable al configurar los accesos a Facebook y/o Twitter.'

			};
			//console.log(description['gue']);
	
			$('#all_rewards li').each(function(i) {
				var $item = $(this).find('img');
				$item.attr('id', i);
				
				$item.hover(function(e) {
						attr = $(this).position();
						$popup.css( { 'top': attr.top-77, 'left': attr.left-104, 'display': 'block' } );
						$popup.find('img').attr('src', $(this).attr("src"));
						$popup.find('h4').html($(this).parent().find("h3").html());
						$popup.find('p').html(description[$(this).attr('alt')]);
				}, function(e) {
					if ($popup.is(':visible') && $popup.is(':hover')) return;
					$popup.css( { 'display': 'none' } );
				});
				
				$popup.mouseleave(function(e) {
					if ($item.is(':hover')) return;
					$popup.css( { 'display': 'none' } );
				});
			});
		},
		
		/* Mis Rangos View ------------------------------------------------------------ */
		mis_rangos: function() {
			var $bar = $('#progress_bar');
			var percent = $bar.data('percent');
			$bar.animate( { width: percent + '%' }, 3000 ); //little animation on the progress bar.
			
			GUERREROS.social.sidebar_recommended();
		},
		
		/* Reclutamiento View ------------------------------------------------------------ */
		reclutamiento: function() {
			
			var $guerreros_de_facebook = $('#guerreros_de_facebook');
			var $guerreros_de_twitter = $('#guerreros_de_twitter');
			var $guerreros_recomendados = $('#guerreros_recomendados');
			var $invitar_email = $('#invitar_via_email');
			
			if($guerreros_de_twitter.data('twitter-on') == 'ON') // this is on if we came back from twitter login
			{
				$('#twitter_btn').addClass('btn_selected');
				if ($guerreros_de_twitter.is(':visible')) return;
				$guerreros_de_facebook.hide();
				$guerreros_de_twitter.hide();
				$invitar_email.hide();
				$guerreros_recomendados.hide();
				$guerreros_de_twitter.fadeIn('fast');
			
			}
			
			
			
			$('#social_btns a').each(function(i) {
				$(this).click(function(e) {
					
					//e.preventDefault();
					$('#social_btns a').removeClass();
					$(this).addClass('btn_selected');
					
					//animate the right part
					switch($(this).attr('id')) {
						case 'fb_btn' :
							if ($guerreros_de_facebook.is(':visible'))
								return;
							if (GUERREROS.social.facebook_friends) {
								$guerreros_de_facebook.hide();
								$guerreros_de_twitter.hide();
								$invitar_email.hide();
								$guerreros_recomendados.hide();
								$guerreros_de_facebook.fadeIn('fast');
							}
								
							
							FB.init({
								appId      : GUERREROS.common.app_id,
								status     : true, // check login status
								cookie     : true, // enable cookies for server access to the session
								xfbml      : true, // parse XFBML
								channelUrl : GUERREROS.common.base_url + 'channel.html', // Custom channel URL
								oauth      : true // enables OAuth 2.0
							});
							if (/chrome/.test(navigator.userAgent.toLowerCase())) { // Chrome hack
								FB.XD._origin = window.location.protocol + '//' + document.domain + '/' + FB.guid();
								FB.XD.Flash.init();
								FB.XD._transport = 'flash';
							}
							
							FB.getLoginStatus (function (response)
							{
								var $facebook_content = $('#facebook_warriors');
								
								if (response.authResponse) {
									FB.api ('/me/friends', function (response) {
										var i,
										    new_markup = '',
										    friends    = response.data,
										    rand       = Math.floor(Math.random()*250),
										    mod        = rand % 3,
										    max        = rand + 33;
										
										for (i = rand; i < max; i++) {
											new_markup += '\
												<li'+ (((i - mod) % 3) == 2 ? ' class="right_side"' : '') +'>\
													<img src="//graph.facebook.com/'+ friends[i].id +'/picture" class="user_pic" alt="thumbnail">\
													<h4>'+ friends[i].name +'</h4>\
													<a data-fb_id="'+ friends[i].id +'" href="javascript:;" class="green_btn">INVITAR GUERRERO</a>\
												</li>';
										}
										
										$('#facebook_warriors').empty().append (new_markup);
										GUERREROS.social.facebook_friends = friends;
										
									});
								}
								else {
									$facebook_content.hide();
									$facebook_content.before ('<a id="fb_connect" href="javascript:;"><img src="'+ GUERREROS.common.base_url +'img/fb_connect_btn.png" alt="Facebook Connect"></a>');
									
									$('#fb_connect').click (function()
									{
										FB.login (function (response)
										{
											if (response.authResponse) {
												FB.api ('/me/friends', function (response) {
													var i,
													    new_markup = '',
													    friends    = response.data,
													    rand       = Math.floor(Math.random()*250),
													    mod        = rand % 3,
													    max        = rand + 33;
													
													for (i = rand; i < max; i++) {
														new_markup += '\
															<li'+ (((i - mod) % 3) == 2 ? ' class="right_side"' : '') +'>\
																<img src="//graph.facebook.com/'+ friends[i].id +'/picture" class="user_pic" alt="thumbnail">\
																<h4>'+ friends[i].name +'</h4>\
																<a data-fb_id="'+ friends[i].id +'" href="javascript:;" class="green_btn">INVITAR GUERRERO</a>\
															</li>';
													}
													
													$('#facebook_warriors').empty().append (new_markup);
													GUERREROS.social.facebook_friends = friends;
													
												});
											}
											else {
												alert ('Debe permitir la aplicación para continuar.');
											}
										});
									});
								}
							});
							
							$guerreros_de_facebook.hide();
							$guerreros_de_twitter.hide();
							$invitar_email.hide();
							$guerreros_recomendados.hide();
							$guerreros_de_facebook.fadeIn('fast');
						break;
						
						case 'twitter_btn' :
							$(this).addClass('btn_selected');
							if ($guerreros_de_twitter.is(':visible')) return;
							$guerreros_de_facebook.hide();
							$guerreros_de_twitter.hide();
							$invitar_email.hide();
							$guerreros_recomendados.hide();
							$guerreros_de_twitter.fadeIn('fast');
							
						break;
						
						case 'contact_btn' :
							if ($invitar_email.is(':visible')) return;
							
							$guerreros_de_facebook.hide();
							$guerreros_de_twitter.hide();
							$invitar_email.hide();
							$guerreros_recomendados.hide();
							$invitar_email.fadeIn('fast');
						break;
					}
				});
			});
			
			//----
			
			// Invite existing guerreros
			$('.gue_invite').click (function() {
				var button    = $(this),
				    container = button.closest ('li'),
				    loader;
				
				button.hide();
				button.after ('<div class="loading">Procesando…</div>');
				
				loader = container.find ('.loading');
				loader.fadeIn();
				
				$.ajax ({
					url     : GUERREROS.common.site_url +'social/ajax_invite_friend/'+ button.data ('guerrero_id'),
					type    : 'GET',
					success : function (data, text) {
						var message;
						
						if (!data.response == 'success') {
							loader.remove();
							button.fadeIn();
						}
						else {
							switch (data.type) {
							case 'self':
							case 'already_friends':
								message = 'Ya son amigos.';
								break;
							case 'accept':
								message = 'Invitación aceptada.';
								break;
							case 'invite':
							case 'invite_pending':
							default:
								message = 'Invitación enviada.';
								break;
							}
							button.remove();
							loader.after ('<div class="feedback">'+ message +'</div>');
							loader.remove();
							container.find ('.feedback').fadeIn();
						}
					},
					error   : function (obj, text, error) {
						loader.remove();
						button.fadeIn();
					}
				});
			});
			
			//----
			
			// Email invites to Facebook friends
			$('#facebook_warriors .green_btn').live ('click', function() {
				var button = $(this);
				
				button.fadeOut();
				FB.ui ({
					method      : 'feed',
					app_id      : GUERREROS.common.app_id,
					to          : button.data ('fb_id'),
					link        : GUERREROS.common.base_url,
					picture		: 'http://guerrerosdeluz.org/development/img/fb_ui_picture.jpeg',
					name        : '¡Únete a mi ejercito de Guerreros de Luz!',
					description : 'Luchemos juntos contra la trata humana, se parte de una comunidad virtual de guerreros en contra de la explotación infantil. Con tu apoyo a esta iniciativa de recaudación de fondos fortaleces laos proyectos comunitarios de la Fundación Ricky Martin.'
				}, 
				function callback(response)
				{
					if(response){
					
						if(response.post_id){
							// Wall Post shared successfull
							alert('El mensaje fue enviado exitosamente.');
							
								$.ajax ({
											url     : GUERREROS.common.site_url + 'social/get_social_trophy',
											type    : 'GET',
											success : function (data, text) {
												if (data.response == 'success')
												{	//tienes el trofeo de social
													
												}
											},
											error   : function (data, text, error) {
												//error;
												//alert("BADTRIP");
												//console.log(data);
											}
										});
	
						} else {
							// Didn't go through or user closed the prompt
							button.fadeIn();
						}
					} 
					else {
							// Didn't go through or user closed the prompt
							button.fadeIn();
					}
				});				
			});
			
			//----
			
			// Email invites to Twitter followers
			
			$('#twitter_warriors .green_btn').live ('click', function() {
				var button = $(this),
				container = button.closest('li'),
				loader;
				
				button.hide();
				button.after ('<div class="loading">Procesando…</div>');
				
				loader = container.find ('.loading');
				
				loader.fadeIn();

				$.ajax ({
					url     : GUERREROS.common.site_url +'social/twitter_send_message/'+ button.data ('follower_id'),
					type    : 'POST',
					success : function (data, text) {
						var message;
						
						if (data.response == 'success') {
						
							button.remove();
							loader.after ('<div class="feedback">Invitación Enviada</div>');
							loader.remove();
							container.find ('.feedback').fadeIn();
							}
						
					},
					error   : function (obj, text, error) {
						loader.remove();
						button.fadeIn();
					}
				});

			});
			
			
			// Add email form
			$('#search_friend').submit (function()
			{
				var $form        = $(this),
				    $email_field = $form.find ('#friend_email'),
				    $email_list  = $('#email_list');
				
				$email_list.append ('<li>'+ $email_field.val() +'<span class="remove_email"></span></li>');
				$('#send_message form').append ('<input type="hidden" name="emails[]" value="'+ $email_field.val() +'">');
				$email_field.val ('');
				$email_list.show();
				
				return false;
			});
			
			$('.remove_email').live('click',function(){
				temp_email = $(this).parent().text();
				$($('#send_message form').find('input[value="'+temp_email+'"]')).remove();
				$(this).parent().remove();
				if(!($('#email_list').html()))
				{
					$('#email_list').hide('slow');
				}
			
			});
		},
		
		/* Perfil View ------------------------------------------------------------ */
		perfil: function() {
			var guerrero_id = $('#map_canvas').data ('guerrero_id');
			
			GUERREROS.common.init_googleMap(); //load custom google map
			GUERREROS.common.loadLightMapPoints('social/get_guerrero_waypoint/'+ guerrero_id, guerrero_id, 'perfil');
			
			$('#view_more_notifications').click (function() {
				GUERREROS.social.more_messages ($(this), guerrero_id);
			});
			
			// !Textarea Maxlength 
			$('#open_message').keyup (function() { 
				var textarea = $(this), 
				limit = textarea.prop ('maxlength'), 
				current_text = textarea.val(); 
				counter = limit-current_text.length;
				$('#maxCharacters').html(counter);
				if (current_text.length > limit) { 
				textarea.val (current_text.substr (0, limit)); 
				} 
			});
			
			//----
			
			// Invite to friends
			$('.add').click (function() {
				var button    = $(this),
				    container = button.closest ('#sub_user_info'),
				    loader;
				
				button.hide();
				button.after ('<div class="loading">Procesando…</div>');
				
				loader = container.find ('.loading');
				loader.fadeIn();
				
				$.ajax ({
					url     : GUERREROS.common.site_url +'social/ajax_invite_friend/'+ button.data ('guerrero_id'),
					type    : 'GET',
					success : function (data, text) {
						var message;
						
						if (!data.response == 'success') {
							loader.remove();
							button.fadeIn();
						}
						else {
							switch (data.type) {
							case 'self':
							case 'already_friends':
								message = 'Ya son amigos.';
								break;
							case 'accept':
								message = 'Invitación aceptada.';
								break;
							case 'invite':
							case 'invite_pending':
							default:
								message = 'Invitación enviada.';
								break;
							}
							button.remove();
							loader.after ('<div class="feedback">'+ message +'</div>');
							loader.remove();
							container.find ('.feedback').fadeIn();
						}
					},
					error   : function (obj, text, error) {
						loader.remove();
						button.fadeIn();
					}
				});
			});
		},
		
		/* Users Search View ------------------------------------------------------------ */
		users_search: function() {
			$('#adv_search_btn').click(function () {
				$('#adv_search_form').submit();
			});
			$('#adv_search_form').submit (function()
			{
				var form             = $(this),
				    search_url       = form.attr ('action'),
				    search_arguments = {
						legion  : form.find ('#search_legion').val(),
						rank    : form.find ('#search_rank').val(),
						country : form.find ('#search_country').val()
					};
				
				for (var name in search_arguments)
					if (search_arguments[name])
						search_url += '/'+ name +'/' + encodeURIComponent (search_arguments[name]);
				
				window.location = search_url;
				return false;
			});
		},
		
		/* Warriors List View ------------------------------------------------------------ */
		my_warriors: function() {
			this.pending_invs();
		},
		
		pending_invs: function() {
			$('.profile_btn.accept').click (function() {
				GUERREROS.social.handle_friend (this, 'accepted');
			});
			
			$('.profile_btn.ignore').click (function() {
				GUERREROS.social.handle_friend (this, 'ignored');
			});
		},
		
		/* Configuracion View ------------------------------------------------------------ */
		configuracion: function() {
			var $datos_div = $('#datos_configuration');
			var $perfil_div = $('#perfil_configuration');
			var $location_div = $('#location_configuration');
			var $avatar_div = $('#avatar_configuration');
			var isMapConfigCalled = false;
			
			$('#configuration_nav a').each(function() {
				$(this).click(function(e) {
					e.preventDefault();
					$('#configuration_nav a').removeClass();
					$(this).addClass('nav_selected');
					
					switch($(this).attr('id')) {
						case 'datos_btn' :
							if($datos_div.is(':visible')) return;
							
							$perfil_div.hide();
							$location_div.hide();
							$avatar_div.hide();
							$perfil_div.hide()
							$datos_div.fadeIn('fast');
							
							// Legions and Subscription Types Selected State
							$('#guerrero_name_preset').change( function () {
								var name = $(this).val();
							$('#guerrero_name').attr('value', name);
							});

							
							$('#legion_list li, #subscription_type_list li').click (function() {
								var	selected_container	= $(this),
									selected_radio		= null,
									other_selected		= null;
								
								if (selected_container.hasClass ('selected'))
									return false;
								
								selected_radio = selected_container.find ('input:radio');
								other_selected = selected_container.siblings ('.selected');
								
								other_selected.removeClass ('selected');
								selected_container.addClass ('selected');
								selected_radio.prop ('checked', true);
								
								$('.description_' + other_selected.data ('legion')).removeClass ('selected');
								$('.description_' + selected_container.data ('legion')).addClass ('selected');
							});
		
							
							$('#legion_list.no_highlight li, #subscription_type_list.no_highlight li').unbind ('click');
						break;
						
						case 'perfil_btn' :
							if ($perfil_div.is(':visible')) return;
							
							$datos_div.hide();
							$location_div.hide();
							$avatar_div.hide();
							$perfil_div.hide();
							$perfil_div.fadeIn('fast');
						break;
						
						case 'location_btn' :
							if ($location_div.is(':visible')) return;
							
							$datos_div.hide();
							$perfil_div.hide();
							$location_div.hide();
							$avatar_div.hide();
							$location_div.fadeIn('fast');
							
							if (!isMapConfigCalled) {
								isMapConfigCalled = true;
								
								GUERREROS.common.map_search_setup ({
									search : '#country_search',
									lat    : '#lat_value',
									long   : '#long_value'
								});
							}
							
						break;
						
						case 'avatar_btn' :
							if ($avatar_div.is(':visible')) return;
							
							$datos_div.hide();
							$location_div.hide();
							$perfil_div.hide();
							$avatar_div.hide();
							$avatar_div.fadeIn('fast');
						break;
					}
				});
			});
			
			//----
			
			//Select preloaded avatars
			$('#sample_avatars img').click(function() {
				var $img = $(this);
				
				$('#sample_avatars .selected').removeClass ('selected');
				$img.addClass ('selected');
				
				$("#pre_loaded_image").attr ('value', $img.data ('avatar_id'));
			});
			
			//----
			
			// Remove avatar CTA
			$('#eliminate_avatar').click (function() {
				var $delete_cta     = $(this),
				    $current_avatar = $('#user_avatar');
				
				$delete_cta.hide();
				$('#avatar_reset').attr ('value', true);
				
				$current_avatar.fadeOut ('fast', function() {
					$current_avatar.attr ('src', GUERREROS.common.base_url + 'img/avatar_sample_big.jpg');
					$delete_cta.after ('<div class="feedback">Haz clic en Actualizar para guardar el cambio.</div>');
					$delete_cta.next().fadeIn();
					$current_avatar.fadeIn();
					$delete_cta.remove();
				});
			});
		}
	},
	
	// !Power
	power : {
		init : function() {
			// controller-wide code
			
		},
		
		index : function() {
			//GUERREROS.common.init_googleMap(); //load custom google map
			//GUERREROS.common.loadLightMapPoints('home/get_guerreros_waypoints');	// load "guerreros" waypoints including Ricky Martin
		},
		
		usuarios: function(){
			
			
		 	$('.check_all').click(function(){
		 		$('.users_table').find(':checkbox').attr('checked', this.checked);
		 	});
		 	
		 	$('select[name="per_page"]').change(function(){
		 	
				$('form[name="page_form"]').submit();
		 	
		 	});
		 	
		 	$('select[name="order_by"]').change(function(){
		 		//console.log($(this).attr('value'));
		 		//if( $(this).attr('value') != 'null'){
		 			$('form[name="order_form"]').submit();
		 		//}
		 	});
		 	
		 	//search part
		 	$('#adv_search_btn').click(function () {
				///console.log("S");
				
				$('#adv_search_form').submit();
			});
			$('#adv_search_form').submit (function()
			{
				var form             = $(this),
				    search_url       = form.attr ('action'),
				    search_arguments = {
						name  : form.find ('#guerrero_name').val(),
						email    : form.find ('#guerrero_email').val(),
					};
				
				for (var name in search_arguments)
					if (search_arguments[name])
						search_url += '/'+ name +'/' + encodeURIComponent (search_arguments[name]);
				//console.log(search_url);
				window.location = search_url;
				return false;
			});

		},
		
		dinero: function(){
	      
	      	var data_array = [];
	    
	    
	    	$.ajax ({
					url     : GUERREROS.common.site_url +'power/money_stats_ajax',
					type    : 'POST',
					success : function (d, text) {
						
						
						if (d.response == 'success') {
							
							
							
							// 30 significa que esta limitado a 30 dia
							for(i=0; i<d.days_stats.length ; i++)
							{
								year = d.days_stats[i].year;
								mes = d.days_stats[i].mes;
								dia = d.days_stats[i].dia;
								
								amount = parseInt(d.days_stats[i].amount);
								data_array.push([new Date(year, mes-1 ,dia),amount]);
								
							}
							drawChart();

					}		
						
					},
					error   : function (obj, text, error) {
						seven_day_stats = null;
					}
			 });

	      // Set a callback to run when the Google Visualization API is loaded.
		      //google.setOnLoadCallback(drawChart);
		
		      // Callback that creates and populates a data table, 
		      // instantiates the pie chart, passes in the data and
		      // draws it.
		      function drawChart() {
			
					var data = new google.visualization.DataTable();
			        data.addColumn('date', 'Fecha');
			        data.addColumn('number', 'Ingreso $');
	/* 		        data.addColumn('number', 'Expenses'); */
					data.addRows(data_array);
										
										var options = {
  								          displayRangeSelector : true,
								          fill: 30,
  								          displayZoomButtons: false,
  								          displayAnnotations: false,
  								          scaleType: 'allfixed',
  								          allowHtml: true
								        };
								
								        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('money_chart'));
								        chart.draw(data, options);

					  			        		
			  }//end drawchart
					$(window).resize(function() { drawChart();});	
		}//end dinero 
		
	}

};


//----	


// !JS Object Loader
UTIL = {
  exec: function( controller, action ) {
    var ns = GUERREROS,
        action = ( action === undefined ) ? "init" : action;

    if ( controller !== "" && ns[controller] && typeof ns[controller][action] == "function" ) {
      ns[controller][action]();
    }
  },

  init: function() {
    var body = document.body,
        controller = body.getAttribute( "data-controller" ),
        action = body.getAttribute( "data-action" );

    UTIL.exec( "common" );
    UTIL.exec( controller );
    UTIL.exec( controller, action );
  }
};

$( document ).ready( UTIL.init );
