( function( $ ) {
	$devFuel = $( '#dev-fuel' );
	var lat,
		lon,
		geoError = 'Geolocation is not supported by this browser OR you have blocked your browser for using this feature, please allow your browser to use your location.';

	function devFuelgetLocation() {
		if ( ! navigator.geolocation ) {
			$( '#df-status' ).html( geoError );
		} else {
			navigator.geolocation.getCurrentPosition( function( position ) {
				if ( 'object' === typeof position ) {
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
					} ); // end ajax
				} // end if object
			} ); // navigator.geolocation.getCurrentPosition
		} // end else
	} // end getLocation

	devFuelgetLocation();

} )( jQuery );