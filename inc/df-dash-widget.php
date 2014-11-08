<?php

/**
* Super important class that shows us where to gget coffe or beer while traveling
*
* @package Developer Fuel
* @author Dan Beil
**/

class DF_Dash_Widget {

	private $options;

	public static $df_time_key = array( '00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30', '24:00', '24:30', );

	public function df_time() {
		foreach ( self::$df_time_key as $time ) {
			$key = str_replace( ':', '', $time );
			$df_time_key[ $key ] = $time;
		}
		return $df_time_key;
	}

	public function __construct() {
		add_action( 'admin_init', array( $this, 'df_register_settings' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'df_dash_widget' ) );
		add_action( 'admin_menu', array( $this, 'df_menu' ) );
		add_action( 'wp_ajax_dev_fuel_ajax_request', array( $this, 'dev_fuel_ajax_request' ) );
	}

	public function df_register_settings() {
		register_setting(
			'dev_fuel_option_group', // Option group
			'dev_fuel_option_name', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'dev_fuel_setting_section_id', // ID
			'Developer Fuel Settings', // Title
			array( $this, 'print_section_info' ), // Callback
			'dev-fuel-setting-admin' // Page
		);

		add_settings_field(
			'key_number',
			'Google Maps Key <small>(link to google for key -> http://bit.ly/1tpOA3x)</small>',
			array( $this, 'key_number_callback' ),
			'dev-fuel-setting-admin',
			'dev_fuel_setting_section_id'

		);

		add_settings_field(
			'time_start',
			'Start Time for coffee',
			array( $this, 'time_start_callback' ),
			'dev-fuel-setting-admin',
			'dev_fuel_setting_section_id'
		);

		add_settings_field(
			'end_time_start',
			'End Time for coffee (i.e. Start time for BEER)',
			array( $this, 'time_end_callback' ),
			'dev-fuel-setting-admin',
			'dev_fuel_setting_section_id'
		);
	}

	public function df_menu() {
		// using options.php here make a hidden options page
		add_submenu_page( 'options.php', 'Developer Fuel', 'Developer Fuel', 'delete_plugins', 'df-settings', array( $this, 'df_settings' ) );
	}

	public function df_settings() { ?>
		<div class="wrap">
			<form method="post" action="options.php">
				<?php
				$this->options = get_option( 'dev_fuel_option_name' );
				// This prints out all hidden setting fields
				settings_fields( 'dev_fuel_option_group' );
				do_settings_sections( 'dev-fuel-setting-admin' );
				submit_button();
				?>
			</form>
		</div>
	<?php }

	public function sanitize( $input ) {
		$new_input = array();
		if( isset( $input['key_number'] ) ) {
			$new_input['key_number'] = urlencode( $input['key_number'] );
		}
		if( isset( $input['time_start'] ) ) {
			$new_input['time_start'] = intval( $input['time_start'] );
		}
		if( isset( $input['time_end'] ) ) {
			$new_input['time_end'] = intval( $input['time_end'] );
		}
		return $new_input;
	}

	public function print_section_info() {
		echo 'Enter your settings below:</br><small>Google Maps Key must be entered to find coffee and or beer</small>';
	}

	public function key_number_callback() {
		echo sprintf(
			'<input type="text" id="key_number" name="dev_fuel_option_name[key_number]" value="%s" />',
			isset( $this->options['key_number'] ) ? esc_attr( $this->options['key_number']) : ''
		);
	}

	public function time_start_callback() {
		echo '<select name="dev_fuel_option_name[time_start]">';
			foreach ( self::df_time() as $key => $time ) {
				$selected = isset( $this->options['time_start'] ) ? $this->options['time_start'] : '';
				$selected = $key == $selected ? 'selected' : '';
				echo '<option value="' . $key . '"' . $selected . '>' . $time . '</option>';
			}
		echo '</select>';
	}

	public function time_end_callback() {
		echo '<select name="dev_fuel_option_name[time_end]">';
			foreach ( self::df_time() as $key => $time ) {
				$selected = isset( $this->options['time_end'] ) ? $this->options['time_end'] : '';
				$selected = $key == $selected ? 'selected' : '';
				echo '<option value="' . $key . '"' . $selected . '>' . $time . '</option>';
			}
		echo '</select>';
	}

	public function df_dash_widget() {
		wp_add_dashboard_widget( 'dev_fuel_dashboard_widget', 'Developer Fuel Dashboard Widget', array( $this, 'df_dash_output' ) );
	}

	public function df_dash_output() {
		// User entered data is escaped / sanatize on save
		self::df_settings();
		$options = get_option( 'dev_fuel_option_name' );
		$key = $options['key_number'];
		echo '<span id="df-status"></span>';
		echo '<iframe id="dev-fuel" width="500" height="400" frameborder="0" style="border:0; opacity: 0;" src="https://www.google.com/maps/embed/v1/search?key=' . $key . '&zoom=1&q=coffee"></iframe>';
	}

	public function dev_fuel_ajax_request() {
		// User entered data is escaped / sanatize on save
		$options = get_option( 'dev_fuel_option_name' );
		// The $_GET contains all the data sent via ajax
		if ( ! empty( $options['key_number'] ) && isset( $_GET ) ) {

			$lat = $_GET['lat'];
			$long = $_GET['long'];
			$current_time = $_GET['current_time'];
			$key = $options['key_number'];
			$start = $options['time_start'];
			$end = $options['time_end'];

			if ( $start < $current_time && $current_time < $end ) {
				$test = "It's" . $current_time_string . " and coffee time </br>";
				$drink = 'local+coffee+shop';
			} else {
				$test = "It's" . $current_time_string . " booze time </br>";
				$drink = 'local+bars';
			}
			// From http://wptheming.com/2013/07/simple-ajax-example/
			// Now we'll return it to the javascript function
			// Anything outputted will be returned in the response
			echo 'https://www.google.com/maps/embed/v1/search?key=' . $key . '&zoom=13&center=' . $lat . ',' . $long . '&q=' . $drink . '';
		}
		// Always die in functions echoing ajax content
		die();
	} // end example_ajax_request

} // END class

new DF_Dash_Widget();
