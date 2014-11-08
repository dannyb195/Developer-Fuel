( function( $ ) {

	$devFuel = $( '#dev-fuel' );
	var lat,
		lon;

	function getLocation() {

		if (navigator.geolocation) {

			navigator.geolocation.getCurrentPosition(showPosition);

			console.log( navigator );

		} else {

			$devFuel.innerHTML = "Geolocation is not supported by this browser.";

		}

	}

	function showPosition( position ) {

		if ( position ) {

			var lat = position.coords.latitude,

			lon = position.coords.longitude;

			// url = 'https://www.google.com/maps/embed/v1/search?key=AIzaSyCqJh1N8nSE05RUzpVYrr_1VoOeO2-yMDc&zoom=13&center=' + lat + ',' + lon + '&q=earth';

			// $devFuel.attr( 'src', url );

			// $devFuel.css( 'opacity', '1' );

			// $devFuel.contentWindow.location.reload();

			// We'll pass this variable to the PHP function example_ajax_request
			var fruit = 'Banana';
			console.log( fruit );
			// This does the ajax request
			$.ajax( {
				url: ajaxurl,
				data: {
					'action':'example_ajax_request',
					'lat' : lat,
					'long' : lon,
					'current_time' : moment().format( 'HHmm' ),
				},
				success:function(data) {
					// This outputs the result of the ajax request
					console.log(data + ' this is data');

					url = data;

					$devFuel.attr( 'src', url );



					// $devFuel.contentWindow.location.reload();

					$devFuel.css( 'opacity', '1' );




				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});


		}

	}

	alert( moment().format( 'HH:mm a' ) );

	getLocation();
	showPosition();


} )( jQuery );