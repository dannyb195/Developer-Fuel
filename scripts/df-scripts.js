( function( $ ) {
	$devFuel = $( '#dev-fuel' );
	var lat,
		lon;

	function devFuelgetLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(devFuelshowPosition);
		} else {
			$( '#df-status' ).html( 'Geolocation is not supported by this browser.' );
		}
	} // end getLocation

	function devFuelshowPosition( position ) {
		if ( 'undefined' == typeof position ) {
			console.log( position );
			$( '#df-status' ).html( 'Geolocation is not supported by this browser OR you have blocked your browser for using this feature, please allow your browser to use your location.' );
		}
		if ( 'undefined' != typeof position ) {
			var lat = position.coords.latitude,
				lon = position.coords.longitude;
			// This does the ajax request
			$.ajax( {
				url: ajaxurl,
				data: {
					'action':'dev_fuel_ajax_request',
					'lat' : lat,
					'long' : lon,
					'current_time' : moment().format( 'HHmm' ),
				},
				success:function( data ) {
					// This outputs the result of the ajax request
					url = data;
					$devFuel.attr( 'src', url );
					$devFuel.css( 'opacity', '1' );
				},
				error: function( errorThrown ){
				}
			} );
		}
	} // end showPosition

	devFuelgetLocation();
	devFuelshowPosition();

} )( jQuery );