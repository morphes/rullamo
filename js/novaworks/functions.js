(function(){
	
	// USE STRICT
	"use strict";
	/////////////////////////////////////////////
	// HEADER
	/////////////////////////////////////////////
	
	var navSearch = jQuery('#nav-search').find('input'),
		navSearchLink = jQuery('.nav-search-link'),		
		miniHeader = jQuery('#mini-header'),
		miniHeaderSearch = jQuery('#mini-search').find('input'),
		miniHeaderSearchLink = jQuery('.mini-search-link');
		
	var header = {
		init: function() {
			
			if (jQuery('body').hasClass('header-overlay')) {
				header.headerOverlaySet();
				$window.smartresize(function(){  
					header.headerOverlaySet();
				});
			}
			
			
			header.miniHeaderInit();
			
			navSearchLink.on('click', function(e) {
				if (jQuery('.wrapper').width() > 767 || jQuery('body').hasClass('responsive-fixed')) {
					e.preventDefault();
					navSearch.animate({
						opacity: 1,
						width: 140
					}, 200);
					navSearch.focus();
				}
			});
			
			navSearch.focus(function() {
				if (jQuery('.wrapper').width() > 767 || jQuery('body').hasClass('responsive-fixed')) {
					navSearch.css('display', 'inline-block').animate({
						opacity: 1,
						width: 140
					}, 200);
				}
			});
					
			navSearch.blur(function() {
				if (jQuery('.wrapper').width() > 767 || jQuery('body').hasClass('responsive-fixed')) {
					jQuery(this).animate({
						opacity: 0,
						width: 1
					}, 200);
					setTimeout(function() {
						navSearch.css('display', 'none');
					}, 300);
				}
			});
						
			miniHeaderSearchLink.on('click', function(e) {
				e.preventDefault();
				miniHeaderSearch.animate({
					opacity: 1,
					width: 140
				}, 200);
				miniHeaderSearch.focus();
			});
			
			miniHeaderSearch.focus(function() {
				jQuery(this).animate({
					opacity: 1,
					width: 140
				}, 200);
			});
					
			miniHeaderSearch.blur(function() {
				jQuery(this).animate({
					opacity: 0,
					width: 1
				}, 200);
			});
			
			jQuery(window).scroll(function() { 
				if ((jQuery(this).scrollTop() > 300) && !jQuery('body').hasClass('has-mini-header')) {
					header.miniHeaderShow();
				} else if ((jQuery(this).scrollTop() < 250) && jQuery('body').hasClass('has-mini-header')) {
					header.miniHeaderHide();
				}
			});
			
			jQuery('.mobile-search-link').on('click', function() {
				
				var isVisible = jQuery('.mobile-search-form').is(":visible");
				
				jQuery('.mobile-search-form').slideToggle();
				
				if (isVisible) {
					jQuery('.mobile-search-link').removeClass('active');
				} else {
					jQuery('.mobile-search-form input').focus();
					jQuery('.mobile-search-link').addClass('active');
				}
			});
							
		},
		miniHeaderInit: function() {
			miniHeader.find('a[title="home"]').html('<i class="fa-home"></i>');
		},
		miniHeaderShow: function() {
			jQuery('body').addClass('has-mini-header');
			miniHeader.css('display', 'block');
			miniHeader.animate({
				"top": "0"
			}, 400);
		},
		miniHeaderHide: function() {
			jQuery('body').removeClass('has-mini-header');
			miniHeader.animate({
				"top": "-80"
			}, 400);
			setTimeout(function() {
				miniHeader.css('display', 'none');
			}, 600);
		},
		headerOverlaySet: function() {
			var headerWrapHeight = jQuery('.header-wrap').height();
							
			if (jQuery('#main-container').find('#swift-slider').length === 0 && jQuery('#main-container').find('.home-slider-wrap').length === 0 && jQuery('#page-wrap').find('.page-heading').length === 0) {
				jQuery('.inner-page-wrap').animate({
					'padding-top': headerWrapHeight + 20
				}, 300);
			} else {
				jQuery('.page-heading').animate({
					'padding-top': headerWrapHeight + 25
				}, 300);
			}
		}
	};
	/////////////////////////////////////////////
	// NAVIGATION
	/////////////////////////////////////////////
	
	var nav = {
		init: function() {
			
			var lastAjaxSearchValue = "",
				searchTimer = false;
		
			// Add parent class to items with sub-menus
			jQuery("ul.sub-menu").parent().addClass('parent');
			
			// Menu parent click function
			jQuery('.menu li.parent > a').on('click', function(e) {
			
				if (jQuery('.wrapper').width() < 768 || jQuery('body').hasClass('standard-browser')) {
					return e;
				}
				
				var directDropdown = jQuery(this).parent().find('ul.sub-menu').first();
				if (directDropdown.css('opacity') == 1) {
					return e;
				} else {
					e.preventDefault();
				}
			});
			
			var menuTop = 35;
			var menuTopReset = 80;
			
			// Enable hover dropdowns for window size above tablet width
			jQuery("nav").find(".menu li.parent").not(".no-hover").hoverIntent({
				over: function() {
					if (jQuery('.wrapper').width() > 767 || jQuery('body').hasClass('responsive-fixed')) {
						
						// Setup menuLeft variable, with main menu value
						var closestSubMenu = jQuery(this).find('ul.sub-menu').first();
						var subMenuWidth = closestSubMenu.outerWidth(true);
						var mainMenuItemWidth = jQuery(this).outerWidth(true);
						var menuLeft = '-' + (Math.round(subMenuWidth / 2) - Math.round(mainMenuItemWidth / 2)) + 'px';
						var menuContainer = jQuery(this).closest('nav');
						
						// Check if this is the top bar menu							
						if (menuContainer.hasClass("top-menu")) {
							if (menuContainer.parent().parent().parent().hasClass("top-bar-menu-right")) {
							menuLeft = "";
							} else {
							menuLeft = "-1px";
							}
							menuTop = 35;
							menuTopReset = 51;
						} else if (menuContainer.hasClass("header-menu")) {
							menuLeft = "-1px";
							menuTop = 40;
							menuTopReset = 52;
						} else if (menuContainer.hasClass("mini-menu") || menuContainer.parent().hasClass("mini-menu")) {
							menuTop = 35;
							menuTopReset = 53;
						} else {
							menuTop = 35;
							menuTopReset = 54;
						}
						
						// Check if second level dropdown
						if (closestSubMenu.parent().parent().hasClass("sub-menu")) {
							menuLeft = closestSubMenu.parent().parent().outerWidth(true) - 2;
						}
											
						closestSubMenu.addClass('show-dropdown').css('top', menuTop);
						closestSubMenu.css('z-index', parseInt(closestSubMenu.css('z-index')) + 1);
					}
				},
				out:function() {
					if (jQuery('.wrapper').width() > 767 || jQuery('body').hasClass('responsive-fixed')) {
						jQuery(this).find('ul.sub-menu').first().removeClass('show-dropdown').css('top', menuTopReset);
					}
				}
			});
			
			jQuery(document).on("mouseenter",".shopping-bag-item", function() {
				
				var subMenuTop = 50;
				
				if (jQuery(this).parent().parent().hasClass("mini-menu")) {
					subMenuTop = 50;
				}
				
				jQuery(this).find('ul.sub-menu').first().addClass('show-dropdown').css('top', subMenuTop);
			}).on("mouseleave",".shopping-bag-item", function() {
				if (jQuery('.wrapper').width() > 767 || jQuery('body').hasClass('responsive-fixed')) {
					jQuery(this).find('ul.sub-menu').first().removeClass('show-dropdown').css('top', 64);
				}
			});
			
			
			
			
		
			// Toggle Mobile Nav show/hide			
			jQuery('a.show-main-nav').on('click', function(e) {
				e.preventDefault();
				if (jQuery('#main-navigation').is(':visible')) {
				jQuery('.header-overlay .header-wrap').css('position', '');
				} else {
				jQuery('.header-overlay .header-wrap').css('position', 'relative');
				}
				jQuery('#main-navigation').toggle();
			});
			
			jQuery(window).smartresize(function(){  
				if (jQuery('.wrapper').width() > 767 || jQuery('body').hasClass('responsive-fixed')) {
					var menus = jQuery('nav').find('ul.menu');
					menus.each(function() {
						jQuery(this).css("display", "");
					});
				}
			});
			
			// Set current language to top bar item
			var currentLanguage = jQuery('li.aux-languages').find('.current-language span').text();
			if (currentLanguage !== "") {
				jQuery('li.aux-languages > a').text(currentLanguage);
			}
			
			
			// AJAX SEARCH
			jQuery('li.menu-search a').on('click', function(e) {
				e.preventDefault();
				
				var subSearchMenu = jQuery(this).parent().find('.sub-menu'),
					menuContainer = jQuery(this).closest('nav'),
					menuTop = 50,
					menuTopReset = 64;
				
				if (menuContainer.hasClass("mini-menu") || menuContainer.parent().hasClass("mini-menu")) {
					menuTop = 50;
					menuTopReset = 58;
				}
				
				if (!subSearchMenu.hasClass('show-dropdown')) {
					subSearchMenu.addClass('show-dropdown').css('top', menuTop);
					subSearchMenu.css('z-index', parseInt(subSearchMenu.css('z-index')) + 1);
				} else {
					if (jQuery('.wrapper').width() > 767 || jQuery('body').hasClass('responsive-fixed')) {
						subSearchMenu.removeClass('show-dropdown').css('top', menuTopReset);
					}
				}
				
			});
			

			
		},
		hideNav: function(subnav) {
			setTimeout(function() {
				if (subnav.css("opacity") === "0") {
					subnav.css("display", "none");
				}
			}, 300);
		},
		ajaxSearch: function(e) {			
			var searchInput = jQuery(e.currentTarget),
				searchValues = searchInput.parents('form').serialize() + '&action=sf_ajaxsearch',
				results = jQuery('.ajax-search-results'),
				loadingIndicator = jQuery('.ajax-search-wrap .ajax-loading');

			jQuery.ajax({
				url: ajaxurl,
				type: "POST",
				data: searchValues,
				beforeSend: function() {
					loadingIndicator.fadeIn(50);
				},
				success: function(response) {
				    if (response == 0) {
				    	response = "";
			        } else {
			        	results.html(response);
					}
				},
				complete: function() {
				    loadingIndicator.fadeOut(200);
				    results.slideDown(400);
				}
			});
		}
	};
	/////////////////////////////////////////////
	// MAP FUNCTIONS
	/////////////////////////////////////////////
	
	var map = {
		init:function() {
			var maps = jQuery('.map-canvas');
			maps.each(function(index, element) {
				var mapContainer = element,
					mapAddress = mapContainer.getAttribute('data-address'),
					mapZoom = mapContainer.getAttribute('data-zoom'),
					mapType = mapContainer.getAttribute('data-maptype'),
					mapColor = mapContainer.getAttribute('data-mapcolor'),
					mapSaturation = mapContainer.getAttribute('data-mapsaturation'),
					pinLogoURL = mapContainer.getAttribute('data-pinimage');
				
				map.getCoordinates(mapAddress, mapContainer, mapZoom, mapType, mapColor, mapSaturation, pinLogoURL);
			
			});
			map.fullscreenMap();
			jQuery(window).smartresize( function() {
			
				map.fullscreenMap();
			});
		},
		getCoordinates: function(address, mapContainer, mapZoom, mapType, mapColor, mapSaturation, pinLogoURL) {
			var geocoder;
			geocoder = new google.maps.Geocoder();			
			geocoder.geocode({
				'address': address
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (mapSaturation == "mono") {
						mapSaturation = -100;
					} else {
						mapSaturation = -20;
					}

					var styles = [
						{
							stylers: [
								{hue: mapColor},
								{saturation: mapSaturation}
							]
						}
					];
					
					var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});  
					var mapTypeIdentifier = "",
						companyPos = "",
						isDraggable = true,
						mapCoordinates = results[0].geometry.location,
						latitude = results[0].geometry.location.lat(),
						longitude = results[0].geometry.location.lng();			
					//if (isMobileAlt) {
					//isDraggable = false;
					//}
					
					if (mapType === "satellite") {
					mapTypeIdentifier = google.maps.MapTypeId.SATELLITE;
					} else if (mapType === "terrain") {
					mapTypeIdentifier = google.maps.MapTypeId.TERRAIN;
					} else if (mapType === "hybrid") {
					mapTypeIdentifier = google.maps.MapTypeId.HYBRID;
					} else {
					mapTypeIdentifier = google.maps.MapTypeId.ROADMAP;
					}
							
					var latlng = new google.maps.LatLng(latitude, longitude);
					var settings = {
						zoom: parseInt(mapZoom, 10),
						scrollwheel: false,
						center: latlng,
						draggable: isDraggable,
						mapTypeControl: true,
						mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
						navigationControl: true,
						navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
						mapTypeId: mapTypeIdentifier
					};	
					var mapInstance = new google.maps.Map(mapContainer, settings);
					var companyMarker = "";
					
					// ADD MARKER AFTER 1 SECOND
					jQuery(mapContainer).ready(function() {
						setTimeout(function() {
							if (pinLogoURL) {
								var companyLogo = new google.maps.MarkerImage(pinLogoURL,
									new google.maps.Size(150,75),
									new google.maps.Point(0,0),
									new google.maps.Point(75,75)
								);
								companyPos = new google.maps.LatLng(latitude, longitude);
								companyMarker = new google.maps.Marker({
									position: mapCoordinates,
									map: mapInstance,
									icon: companyLogo,
									animation: google.maps.Animation.DROP
								});
							} else { 
								companyPos = new google.maps.LatLng(latitude, longitude);
								companyMarker = new google.maps.Marker({
									position: mapCoordinates,
									map: mapInstance,
									animation: google.maps.Animation.DROP
								});
							}
							
							google.maps.event.addListener(companyMarker, 'click', function() {
								window.location.href = 'http://maps.google.com/maps?q='+companyPos;
							});
							
							google.maps.event.addDomListener(window, 'resize', function() {
								mapInstance.setCenter(companyPos);
							});
						}, 1000);
					});
							
					// MAP COLOURIZE
					if (mapColor !== "") {
					mapInstance.mapTypes.set('map_style', styledMap);
					mapInstance.setMapTypeId('map_style');
					}
					
				} else {
					console.log(status);
				}
			});			
		},
		fullscreenMap: function() {
			var fullscreenMap = jQuery('.fullscreen-map'),
				container = jQuery('#page-wrap'),
				mapOffset = container.offset().left,
				windowWidth = jQuery(window).width();

			if (windowWidth > 768) {
				mapOffset = mapOffset;
			} else {
				mapOffset = 20;
			}
			
			if (jQuery('.wrapper').hasClass('boxed-layout')) {
				windowWidth = jQuery('.wrapper').width();
				
				if (windowWidth > 1024) {
					mapOffset = 45;
				} else if (windowWidth > 768) {
					mapOffset = 30;
				} else if (windowWidth > 480) {
					mapOffset = 24;
				} else {
					mapOffset = 20;
				}
			}
						
			fullscreenMap.find('.map-canvas').css('width', windowWidth);
			fullscreenMap.css('margin-left', '-' + mapOffset + 'px');
			
		}
	};
	var product = {
		init: function () {
			if (jQuery.fn.imagesLoaded) {	
				product.productResize();
				jQuery(window).smartresize(function(){  
					product.productResize();
				});
			}
		},
		productResize: function() {
			
			jQuery('.nova-widget-style-').imagesLoaded(function() {
				
				var productImageHeight = jQuery(this).find('.item-product-image-box img').height();
				console.log(productImageHeight);
				jQuery(this).find('.owl-prev').css('height', productImageHeight  + 'px');
				jQuery(this).find('.owl-next').css('height', productImageHeight  + 'px');
			});
			
			
		}
	};
	var onReady = {
		init: function(){
			header.init();
			nav.init();
			product.init();
		}
	};
	var onLoad = {
		init: function(){
			if (jQuery("#novaworks-active").hasClass('has-map')) {
				map.init();
			}
		}
	};	
jQuery(window).load(onLoad.init);	
jQuery(document).ready(onReady.init);
})(jQuery);
