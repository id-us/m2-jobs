define([
	'jquery',
	'Idus_Core/js/idus'
], function ($,idus) {
	'use strict';
	return function(){

		$.fn.hideOption = function() {
			this.each(function() {
				if ( !$(this).parent().is( "span" ) ) {
					$(this).wrap('<span>').hide();
				}else{
					$(this).hide()
				}
			});
		}

		function getLocation(){
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition(onFound, geoError, geoOptions);
			}
		}

		var geoOptions = function() {
			return;
		};

		var geoError = function(position){
			$('.loading .cloc').html($.mage.__('לא ניתן לאתר מיקום'));
		};

		function getDistance(lat,lon,loc){
			var dest = new google.maps.LatLng(lat,lon);
			return (google.maps.geometry.spherical.computeDistanceBetween(loc, dest) / 1000);
		};

		var onFound = function(position){
			var userLat = position.coords.latitude;
			var userLong = position.coords.longitude;
			
			var userLoc = new google.maps.LatLng(userLat,userLong);
			
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({'latLng':userLoc}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if(results[1]){
						locating = true;
					}
					city = results[2].formatted_address;
					$('.loading .cloc').html($.mage.__('מציג משרות לפי מיקום נוכחי')+' : '+city);
				}
			});
			var arr = [];

			$('li.job').each(function(index) {

				var lat = Number($(this).attr("data-latitude"));
				var lng = Number($(this).attr('data-longitude'));
				var dist = Math.round(getDistance(lat,lng,userLoc));
				$(this).attr('data-dist',dist);
				arr[dist] = $(this);
			});

			for(var dist in arr){
				if (String(dist >>> 0) == dist && dist >>> 0 != 0xffffffff) {
					$('ul.jobs').append($('[data-dist='+dist+']'));
				}
			};

			$('.loading .cloc').html($.mage.__('מציג לפי מיקום נוכחי'));
		}

		function initCounter(){
			$('.job_count span').text($('.jobs .job:visible').length)
		}initCounter();
		
		var areas = $('.job_areas select');
		var citis = $('.job_citis select');

		$('#search_job_free_text').keyup(function(){
			areas.val('0');
			citis.val('0');
			$('.jobs .job').removeClass('area_hide city_hide');
			var contains = $(this).val();
			if(contains){
				$('.jobs .job').addClass('free_text_hide');
				$('.jobs .job:contains('+contains+')').removeClass('free_text_hide');
			}else{
				$('.jobs .job').removeClass('free_text_hide');
			}
			initCounter();
		});

		areas.on('change',function(){
			$('#search_job_free_text').val('').trigger('change');
			var area = $(this).val();
			if(area != '0'){
				citis.val('0');
				$('option:not(.first)',citis).hide();
				$('option.'+area,citis).show();
				$('.jobs .job:not(.area_'+area+',.area_0)').addClass('area_hide');
				$('.jobs .job.area_'+area).removeClass('area_hide');
			}else{
				$('option',citis).show();
				$('.jobs .job').removeClass('area_hide');
			}
			initCounter();
		});

		citis.on('change',function(){
			$('#search_job_free_text').val('').trigger('change');
			var city = $(this).val();
			var area = $('option[value='+city+']',citis).attr('class');
			if(city != '0'){
				$('.jobs .job:not(.city_'+city+',.city_0)').addClass('city_hide');
				$('.jobs .job.city_'+city).removeClass('city_hide');
			}else{
				$('option',areas).show();
				$('.jobs .job').removeClass('city_hide');
			}
			initCounter();
		});

		getLocation();
	}
});