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
				// 	http://music.afterosmosis.com/track/perpetual

				$starts = array( 'album=', 'track=' );

				foreach ( $starts as $start ) {

			 		$bandcamp = $this->find_ID( $service, $accountURL, $start );

			 		if ( is_numeric( $bandcamp ) ) { 

			 			$which = $start;
			 			break; 

			 		} // End of $bandcamp check

				} ?>
			 	
			 	<iframe style="border: 0; width: <?php echo $width; ?>; height: <?php echo ($width + 142); ?>px" src="http://bandcamp.com/EmbeddedPlayer/<?php echo $which . $bandcamp; ?>/size=large/bgcol=ffffff/linkcol=0687f5/notracklist=true/transparent=true/" seamless></iframe><?php
		 	
		 	} elseif ( !$oembed && $service == 'tunecore' ) {
		 	
		 		// Input example: http://www.tunecore.com/music/thevibedials
		 		
		 		$tunecore = $this->find_ID( $service, $accountURL ); ?>
			 	
			 	<object width="160" height="400" class="tunecore"><param name="movie" value="http://widget.tunecore.com/swf/tc_run_v_v2.swf?widget_id=<?php echo $tunecore; ?>"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://widget.tunecore.com/swf/tc_run_v_v2.swf?widget_id=<?php echo $tunecore; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="160" height="400"></embed></object><?php
		 	
		 	} elseif ( !$oembed && $service == 'reverbnation' ) {
		 	
		 		// Input example: http://www.reverbnation.com/thevibedials
		 		
		 		$reverbnation = $this->find_ID( $service, $accountURL ); ?>

		 		<iframe class="widget_iframe" src="http://www.reverbnation.com/widget_code/html_widget/artist_<?php echo $reverbnation; ?>?widget_id=50&pwc[design]=default&pwc[background_color]=%23333333&pwc[included_songs]=1&pwc[photo]=0%2C1&pwc[size]=fit" width="100%" height="320px" frameborder="0" scrolling="no"></iframe><?php
		 	
		 	} elseif ( !$oembed && $service == 'noisetrade' ) {
		 	
		 		// Input example: http://noisetrade.com/thevibedials/

		 		$noisetrade = $this->find_ID( $service, $accountURL ); ?>
			 	
		 		<iframe src="http://noisetrade.com/service/sharewidget/?id=<?php echo $noisetrade; ?>" width="100%" height="400" scrolling="no" frameBorder="0"></iframe><?php
		 	
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

			if ( $instance['hide_empty'] == 0 && !empty( $url ) ) {

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

 		$service 	= FALSE;
 		$tags 		= array();
 		$services 	= array( array( 'bandcamp', 'BandCamp' ), array( 'noisetrade', 'NoiseTrade' ), array( 'reverbnation', 'ReverbNation' ), array( 'tunecore', 'TuneCore' ), array( 'soundcloud', 'SoundCloud' ), array( 'mixcloud', 'Mixcloud' ) );

 		$service = $this->new_find_service_from_url( $URL );

 		if ( $service !== FALSE ) { 

			$set = set_transient( 'bppw_music_service_' . $key, $service, HOUR_IN_SECONDS );

			return $service; 

		}

 		$service = ( ini_get( 'allow_url_fopen' ) == 1 ? $this->service_via_dom( $URL, $valid ) : $this->find_service_on_page( $URL ) );
	
		if ( $service !== FALSE ) { 

			$set = set_transient( 'bppw_music_service_' . $key, $service, HOUR_IN_SECONDS );

			return $service; 

		}

 		/*foreach ( $services as $valid ) {

 			//$service = $this->find_service_from_url( $URL, $valid );
 			$service = $this->new_find_service_from_url( $URL );

			if ( $service !== FALSE ) { break; }

			$service = ( ini_get( 'allow_url_fopen' ) == 1 ? $this->service_via_dom( $URL, $valid ) : $this->find_service_on_page( $URL ) );
	
			if ( $service !== FALSE ) { break; }

 		} // End of $services foreach loop

 		if ( !$service ) { return FALSE; }
*/
		$set = set_transient( 'bppw_music_service_' . $key, $service, HOUR_IN_SECONDS );

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
 	function find_service_from_url( $URL, $valid ) {

 		$service 	= FALSE;
 		$pos 		= stripos( $URL, $valid[0] );

		if ( !$pos ) { return FALSE; }

		return $valid[0];

 	} // End of find_service_from_url()

/**
 * Determines the service by looking at the page metadata
 *
 * @param	string			$URL		The URL from the profile field
 * @param	array			$valid		An array of service names
 *
 * @return 	string | bool	$service 	The name of the service, or FALSE
 */
 	function new_find_service_from_url( $URL ) {

 		$services 	= array( 'bandcamp', 'noisetrade', 'reverbnation', 'tunecore', 'soundcloud', 'mixcloud' );
 		$service 	= FALSE;

 		foreach ( $services as $valid ) {

 			$pos = stripos( $URL, $valid );

 			if ( $pos !== false ) { $service = $valid; break; }

 		} // End of $services foreach loop

 		return $service;

 	} // End of new_find_service_from_url() 	

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
 * Determines the service by looking at the page metadata
 *
 * @param	string			$URL		The URL from the profile field
 * @param	array			$valid		An array of service names
 *
 * @return 	string | bool	$service 	The name of the service, or FALSE
 */
 	function service_via_dom( $URL, $valid ) {

 		$service 	= FALSE;
 		$doc 		= new DOMDocument();
 		$data 		= array();

		$doc->loadHTMLFile( $URL );

		$items = $doc->getElementsByTagName( 'meta' );

		foreach ( $items as $item ) {

			$data[$item->getAttribute( 'property' )] = $item->getAttribute( 'content' );

		} // End of $items foreach loop

		foreach ( $items as $item ) {

			$data[$item->getAttribute( 'name' )] = $item->getAttribute( 'content' );

		} // End of $items foreach loop

		// Handles ReverbNation, NoiseTrade, SoundCloud, Mixcloud
		if ( isset( $data['og:site_name'] ) && $data['og:site_name'] == $valid[1] ) {

			$service = $valid[0];

		// Handles BandCamp
		} elseif ( isset( $data['twitter:site'] ) && $data['twitter:site'] == $valid[0] ) {

			$service = $valid[0];

		// Handles TuneCore
		} else {

			$titles = $doc->getElementsByTagName( 'title' );

			foreach ( $titles as $title ) {

				$pos = stripos( $title->nodeValue, $valid[1] );

				if ( $pos !== FALSE ) {

					$service = $valid[0];
					break;

				}

			} // End of $items foreach loop

		} // End of $data check

		return $service;

 	} // End of service_via_dom()

/**
 * Determines the service ID from the URL and service
 * If the ID is found, it is set in a transient
 *
 * @param	string			$URL		The URL from the profile field
 * @param	string			$service	The name of the service
 *
 * @uses 	get_transient
 * @uses    find_ID_on_page
 * @uses 	find_ID_via_dom
 *
 * @return 	string | bool	$ID 		The ID string or FALSE
 */
 	function find_id( $URL, $service ) {

 		if ( empty( $URL ) || empty( $service ) ) { return FALSE; }

 		$key 	= md5( $URL );
		$trans 	= get_transient( 'bppw_music_' . $key );

		if ( $trans !== FALSE ) { return $trans; }

 		$ID = FALSE;

 		if ( ini_get( 'allow_url_fopen' ) == 0 ) {

			$ID = $this->find_ID_on_page( $URL, $service );

		} else {

			if ( $service != 'tunecore' ) { 

				$ID = $this->find_ID_via_dom( $URL, $service );

			} // End of $service check

		} // End of PHP config check

		if ( !$ID ) { return FALSE; }

		$ID = $this->find_ID_via_dom( $URL, $service );

		return $ID;

 	} // End of find_id()

/**
 * Determines the service ID using the Toolkit function find_on_page()
 *
 * @param	string			$service	The name of the service
 * @param	string			$URL		The URL from the profile field
 *
 * @uses 	find_on_page
 *
 * @return 	string | bool	$ID 		The ID string or FALSE
 */
 	function find_ID_on_page( $URL, $service ) {

 		global $slushkit;

 		if ( $service == 'bandcamp' ) {

 			$id_args['start'] 	= 'item_id=';
 			$id_args['end'] 	= '&item_type=';

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

 		return $ID;

 	} // End of find_ID_on_page()

/**
 * Determines the service ID by looking for a transient
 * then the page metadata, if there isn't a transient
 *
 * @param	string			$service	The name of the service
 * @param	string			$URL		The URL from the profile field
 * @param	array			$valid		An array of service names
 *
 * @uses 	DOMDocument
 *
 * @return 	string | bool	$target 	The ID string or FALSE
 */
 	function find_ID_via_dom( $service, $URL, $startstr = '', $endstr = '' ) {

 		if ( empty( $service ) || empty( $URL ) ) { return FALSE; }

 		$doc 	= new DOMDocument();
 		$data 	= array();
 		$target = FALSE;

		$doc->loadHTMLFile( $URL );

		$items = $doc->getElementsByTagName( 'meta' );

		foreach ( $items as $item ) {

			$att 		= ( $item->hasAttribute( 'name' ) ? $item->getAttribute( 'name' ) : $item->getAttribute( 'property' ) );
			$data[$att] = $item->getAttribute( 'content' );

		} // End of $items foreach loop

		if ( $service == 'bandcamp' ) {

			$source = 'twitter:player';
			$start 	= ( empty( $startstr ) ? 'album=' : $startstr );
			$end 	= ( empty( $endstr ) ? '/size=large' : $endstr );

		} elseif ( $service == 'reverbnation' ) {

			$source = 'twitter:player';
			$start 	= ( empty( $startstr ) ? 'artist_' : $startstr );
			$end 	= ( empty( $endstr ) ? '?widget' : $endstr );

		} elseif ( $service == 'noisetrade' ) {

			$source = 'og:image';
			$start 	= ( empty( $startstr ) ? 'com/w/' : $startstr );
			$end 	= ( empty( $endstr ) ? '/cover' : $endstr );

		}

		if ( empty( $data[$source] ) ) { return FALSE; }
		        
        // Calculate the length of start
        $startlength = strlen( $start );
        
        // Find where the target begins
        $targetStart = strpos( $data[$source], $start ) + $startlength;
        
        // Find how long the playerID is
		$targetLength = strpos( $data[$source], $end ) - $targetStart;
        
        // Extract playerID from $page
		$target = substr( $data[$source], $targetStart, $targetLength );

		return $target;

 	} // End of find_ID_via_dom() 

} // End of slushman_bp_profile_music_player_widget class

?>