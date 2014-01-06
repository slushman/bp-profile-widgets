<?php

class slushman_bp_profile_music_player_widget extends WP_Widget {
	
/**
 *Register widget with WordPress
 */
 	function __construct() {
 	
 		$name 					= 'BP Profile Music Player';
 		$opts['description'] 	= __( 'Add a music player to your BuddyPress profile page.', 'slushman-bp-profile-music-player' );
 		
 		parent::__construct( false, $name, $opts );
 		
		// Future i10n support
		// load_plugin_textdomain( PLUGIN_LOCALE, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		
		// Form fields
		// required: name, underscored, type, & value. optional: desc, sels, size
		$this->fields[] = array( 'name' => 'Title', 'underscored' => 'title', 'type' => 'text', 'value' => 'Music Player' );
		$this->fields[] = array( 'name' => 'Width', 'underscored' => 'width', 'type' => 'text', 'value' => '200px' );
		$this->fields[] = array( 'name' => 'Height', 'underscored' => 'height', 'type' => 'text', 'value' => '320px' );
		$this->fields[] = array( 'name' => 'Empty Message', 'underscored' => 'emptymsg', 'type' => 'text', 'value' => 'This user has not activated their music player.' );
		$this->fields[] = array( 'name' => 'Hide widget if empty', 'underscored' => 'hide_empty', 'type' => 'checkbox', 'value' => 0 );

 	} // End of __construct()

/**
 * The output of the front-end of the widget
 *
 * @param   array 	$instance  Previously saved values from database.
 *
 * @uses    xprofile_get_field_data
 * @uses    oembed_transient
 * @uses    find_on_page
 */
	function widget_output( $args, $instance ) {

		global $slushman_bp_profile_widgets, $slushkit;

		$accountURL 	= xprofile_get_field_data( 'Music Player URL' );
	 	$description 	= xprofile_get_field_data( 'Music Player Role' );
	 	$width 			= $instance['width'];
	 	$height 		= $instance['height'];
	 	$service 		= $this->find_service( $accountURL );

	 	// echo '<p>$service: ' . $service . '</p>';
	 	
	 	if ( empty( $accountURL ) || !$service ) {

			echo '<p>' . ( !empty( $instance['emptymsg'] ) ? $instance['emptymsg'] : '' ) . '</p>';
		
		} else {

			$oembed = $slushman_bp_profile_widgets->oembed_transient( $accountURL, $service, $width );

			if ( !$oembed && $service == 'bandcamp' ) {

				// Input examples:
				// 	http://thevibedials.bandcamp.com/album/the-vibe-dials
				// 	http://music.afterosmosis.com/
				// 	http://music.afterosmosis.com/track/exhale
				// 	http://michaelestok.bandcamp.com/track/time-2

				$which = $this->albumortrack( $accountURL );

				if ( !$which ) {

					echo '<p>The URL you entered is not a valid BandCamp URL.</p>';

				} else {

					$bandcamp = $this->find_ID( $service, $accountURL, $which ); ?>

				 	<iframe style="border: 0; width: <?php echo $width; ?>; height: <?php echo ($width + 142); ?>px;" src="http://bandcamp.com/EmbeddedPlayer/<?php echo $which . $bandcamp; ?>/size=large/bgcol=ffffff/linkcol=0687f5/notracklist=true/transparent=true/" seamless></iframe><?php

				} // End of $which check
		 	
		 	} elseif ( !$oembed && $service == 'tunecore' ) {
		 	
		 		// Input example: http://www.tunecore.com/music/thevibedials
		 		
		 		$tunecore = $this->find_ID( $service, $accountURL ); ?>
			 	
			 	<object width="160" height="400" class="tunecore"><param name="movie" value="http://widget.tunecore.com/swf/tc_run_v_v2.swf?widget_id=<?php echo $tunecore; ?>"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://widget.tunecore.com/swf/tc_run_v_v2.swf?widget_id=<?php echo $tunecore; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="160" height="400"></embed></object><?php
		 	
		 	} elseif ( !$oembed && $service == 'reverbnation' ) {
		 	
		 		// Input example: http://www.reverbnation.com/thevibedials
		 		
		 		$reverbnation = $this->find_ID( $service, $accountURL ); ?>

		 		<iframe class="widget_iframe" src="http://www.reverbnation.com/widget_code/html_widget/artist_<?php echo $reverbnation; ?>?widget_id=50&pwc[design]=default&pwc[background_color]=%23333333&pwc[included_songs]=1&pwc[photo]=0%2C1&pwc[size]=fit" width="100%" height="<?php echo $height; ?>" frameborder="0" scrolling="no"></iframe><?php
		 	
		 	} elseif ( !$oembed && $service == 'noisetrade' ) {
		 	
		 		// Input example: http://noisetrade.com/thevibedials/

		 		$noisetrade = $this->find_ID( $service, $accountURL ); ?>
			 	
		 		<iframe src="http://noisetrade.com/service/sharewidget/?id=<?php echo $noisetrade; ?>" width="100%" height="<?php echo $height; ?>" scrolling="no" frameBorder="0"></iframe><?php
		 	
		 	} else {

				// Input Examples: 
				// 		http://soundcloud.com/christopher-joel/sets/fantasy-world-1/
				// 		http://www.mixcloud.com/MarvinHumes/marvins-jls-mixtape/
		
				echo $oembed;

			} // End of embed codes

		} // End of empty checks
	 	
	 	echo '<p>' . ( isset( $description ) && !empty( $description ) ? $description : '' ) . '</p>';

	} // End of widget_output()

/**
 * Back-end widget form.
 *
 * @see		WP_Widget::form()
 *
 * @uses	wp_parse_args
 * @uses	esc_attr
 * @uses	get_field_id
 * @uses	get_field_name
 * @uses	checked
 *
 * @param	array	$instance	Previously saved values from database.
 */ 	
 	function form( $instance ) {

		global $slushman_bp_profile_widgets;
 	
		foreach ( $this->fields as $field ) {

			$corv 				= ( $field['type'] == 'checkbox' ? 'check' : 'value' );
			$args[$corv]		= ( isset( $instance[$field['underscored']] ) ? $instance[$field['underscored']] : $field['value'] );
			$args['blank']		= ( $field['type'] == 'select' ? TRUE : '' );
			$args['class']		= $field['underscored'] . ( $field['type'] == 'text' ? ' widefat' : '' );
			$args['desc'] 		= ( !empty( $field['desc'] ) ? $field['desc'] : '' );
			$args['id'] 		= $this->get_field_id( $field['underscored'] );
			$args['label']		= $field['name'];
			$args['name'] 		= $this->get_field_name( $field['underscored'] );
			$args['selections']	= ( !empty( $field['sels'] ) ? $field['sels'] : array() );
			$args['type'] 		= ( empty( $field['type'] ) ? '' : $field['type'] );
			
			echo '<p>' . $slushman_bp_profile_widgets->create_settings( $args ) . '</p>';
			
		} // End of $fields foreach

 	} // End of form()

/**
 * Front-end display of widget.
 *
 * @see		WP_Widget::widget()
 *
 * @param	array	$args		Widget arguments.
 * @param 	array	$instance	Saved values from database.
 *
 * @uses	apply_filters
 * @uses	xprofile_get_field_data
 * @uses	find_on_page
 */	 	  
  	function widget( $args, $instance ) {

		if ( bp_is_user_profile() ) {

			$url = xprofile_get_field_data( 'Music Player URL' );

			if ( !empty( $url ) || $instance['hide_empty'] == 0 ) {

				extract( $args );

				echo $before_widget;
						
				$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
				
				echo ( empty( $title ) ? '' : $before_title . $title . $after_title );
						
				do_action( 'bp_before_sidebar_me' );
				
				echo '<div id="sidebar-me">';

				$this->widget_output( $args, $instance );

				do_action( 'bp_sidebar_me' );
					
				echo '</div>';
				
				do_action( 'bp_after_sidebar_me' );
				
				echo $after_widget;

			} // End of empty checks

		} // End of profile check

 	} // End of widget()

/**
 * Sanitize widget form values as they are saved.
 *
 * @see		WP_Widget::update()
 *
 * @param	array	$new_instance	Values just sent to be saved.
 * @param	array	$old_instance	Previously saved values from database.
 *
 * @return 	array	$instance		Updated safe values to be saved.
 */	  	
 	function update( $new_instance, $old_instance ) {
	 	
	 	$instance = $old_instance;

	 	foreach ( $this->fields as $field ) {

	 		$name = $field['underscored'];
			
			switch ( $field['type'] ) {
 			
	 			case ( 'email' )		: $instance[$name] = sanitize_email( $new_instance[$name] ); break;
	 			case ( 'number' )		: $instance[$name] = intval( $new_instance[$name] ); break;
	 			case ( 'url' ) 			: $instance[$name] = esc_url( $new_instance[$name] ); break;
	 			case ( 'text' ) 		: $instance[$name] = sanitize_text_field( $new_instance[$name] ); break;
	 			case ( 'textarea' )		: $instance[$name] = esc_textarea( $new_instance[$name] ); break;
	 			case ( 'checkgroup' ) 	: $instance[$name] = strip_tags( $new_instance[$name] ); break;
	 			case ( 'radios' ) 		: $instance[$name] = strip_tags( $new_instance[$name] ); break;
	 			case ( 'select' )		: $instance[$name] = strip_tags( $new_instance[$name] ); break;
	 			case ( 'tel' ) 			: $instance[$name] = $slushkit->sanitize_phone( $new_instance[$name] ); break;
	 			case ( 'checkbox' ) 	: $instance[$name] = ( isset( $new_instance[$name] ) ? 1 : 0 ); break;
	 			
 			} // End of $inputtype switch

		} // End of $fields foreach
	 	
	 	return $instance;
	 	
 	} // End of update()

/**
 * Determines the service from the posted URL
 *
 * @param	string			$URL		The URL from the profile field
 *
 * @return 	string | bool	$service 	The name of the service, or FALSE
 */	  	
 	function find_service( $URL ) {

 		if ( empty( $URL ) ) { return FALSE; }

 		$key 	= md5( $URL );
		$trans 	= get_transient( 'bppw_music_service_' . $key );

		if ( $trans !== FALSE ) { return $trans; }

 		$service = FALSE;
 		$service = $this->find_service_from_url( $URL );

 		if ( !$service ) {

 			$service = $this->find_service_on_page( $URL );

 		}

 		if ( $service !== FALSE ) { 

			$set = set_transient( 'bppw_music_service_' . $key, $service, HOUR_IN_SECONDS );

		}

		return $service;

 	} // End of find_service()

/**
 * Determines the service by looking at the page metadata
 *
 * @param	string			$URL		The URL from the profile field
 * @param	array			$valid		An array of service names
 *
 * @return 	string | bool	$service 	The name of the service, or FALSE
 */
 	function find_service_from_url( $URL ) {

 		$services 	= array( 'bandcamp', 'noisetrade', 'reverbnation', 'tunecore', 'soundcloud', 'mixcloud' );
 		$service 	= FALSE;

 		foreach ( $services as $valid ) {

 			$pos = stripos( $URL, $valid );

 			if ( $pos !== false ) { $service = $valid; break; }

 		} // End of $services foreach loop

 		return $service;

 	} // End of find_service_from_url() 	

/**
 * Determines the service using the Toolkit function find_on_page()
 *
 * @param	string			$URL		The URL from the profile field
 * @param	array			$valid		An array of service names
 *
 * @uses 	find_on_page
 *
 * @return 	string | bool	$service 	The name of the service or FALSE
 */
 	function find_service_on_page( $URL ) {

 		global $slushkit;

 		$i 			= 0;
 		$service 	= FALSE;

 		$bookends[$i]['start'] 	= 'twitter:player" content="https://';
 		$bookends[$i]['end'] 	= '.com/EmbeddedPlayer/v=2';
 		$i++;

		$bookends[$i]['start'] 	= '<a href="http://www.youtube.com/';
		$bookends[$i]['end'] 	= '" target="_blank"><img src="/images/youtube-header.png"';
		$i++;

		$bookends[$i]['start'] 	= 'content="https://www.';
		$bookends[$i]['end'] 	= '.com/widget_code';
		$i++;

		$bookends[$i]['start'] 	= '"http://s3assets.';
		$bookends[$i]['end'] 	= '.com.s3.amazonaws.com';
		$i++;

		$bookends[$i]['start'] 	= 'href="http://help.';
		$bookends[$i]['end'] 	= '.com" target="_blank"';
		$i++;

		$bookends[$i]['start'] 	= 'og:audio" content="http://www.';
		$bookends[$i]['end'] 	= '.com/player/facebook/"';

		foreach ( $bookends as $bookend ) {

			$args['start'] 	= $bookend['start'];
			$args['end'] 	= $bookend['end'];
			$args['url'] 	= $URL;
 			$check			= $slushkit->find_on_page( $args );

 			if ( $check == 'bandcamp' || $check == 'noisetrade' || $check == 'reverbnation' || $check == 'tunecore' || $check == 'soundcloud' || $check == 'mixcloud' ) {

 				$service = $check;
 				break;

 			}

		} // End of $bookends foreach loop

		return $service;

 	} // End of find_service_on_page()

/**
 * Determines the service ID from the URL and service using the
 * Toolkit function find_on_page().
 * If the ID is found, it is set in a transient
 *
 * @param	string			$URL		The URL from the profile field
 * @param	string			$service	The name of the service
 *
 * @uses 	get_transient
 * @uses    find_on_page
 * @uses 	set_transient
 *
 * @return 	string | bool	$ID 		The ID string or FALSE
 */
 	function find_id( $service, $URL, $start = '' ) {

 		global $slushkit;

 		if ( empty( $URL ) || empty( $service ) ) { return FALSE; }

 		$key 	= md5( $URL );
		$trans 	= get_transient( 'bppw_music_' . $key );

		if ( $trans !== FALSE ) { return $trans; }

 		if ( $service == 'bandcamp' ) {

 			$id_args['start'] 	= $start;
 			$id_args['end'] 	= '/size=large/linkcol';

 		} elseif ( $service == 'noisetrade' ) {

 			$id_args['start'] 	= 'content="http://s3.amazonaws.com/static.noisetrade.com/w/';
 			$id_args['end'] 	= '/cover1500x1500max.jpg"/>';

 		} elseif ( $service == 'reverbnation' ) {

 			$id_args['start'] 	= 'become_fan/';
 			$id_args['end'] 	= '?onbecomefan';

 		} elseif ( $service == 'tunecore' ) {

 			$id_args['start'] 	= '<embed src="http://widget.tunecore.com/swf/tc_run_h_v2.swf?widget_id=';
 			$id_args['end'] 	= '" type="application/x-shockwave-flash"';

 		} // End of $service check

 		$id_args['url'] = $URL;
 		$ID				= $slushkit->find_on_page( $id_args );

 		if ( !$ID ) { 

 			return FALSE; 

 		} else {

	 		$set = set_transient( 'bppw_music_' . $key, $ID, HOUR_IN_SECONDS );

 			return $ID;

 		} // End of $ID check

 	} // End of find_id()

/**
 * Searches the page at the URL provided for either the string "album=" or "track=".
 * Returns TRUE if found, otherwise FALSE if neither are found.
 * 
 * @param	string	$URL	The URL to search
 * 
 * @return	bool
 */
 	function albumortrack( $URL ) {

 		$remote 	= wp_remote_get( $URL );
	    $body 		= wp_remote_retrieve_body( $remote );
	    $page 		= ( !is_wp_error( $body ) ? $body : file_get_contents( $URL ) );
	    $needles 	= array( 'track=', 'album=' );

	    foreach ( $needles as $needle ) {

	    	$found = strpos( $page, $needle );

	    	if ( $found !== FALSE ) { return $needle; }

	    } // End of foreach loop

		return $found;

 	} // End of albumortrack()

} // End of slushman_bp_profile_music_player_widget class

?>