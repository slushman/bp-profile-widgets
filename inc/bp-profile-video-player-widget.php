<?php

class slushman_bp_profile_video_player_widget extends WP_Widget {

/**
 * Register widget with WordPress.
 */
	function __construct() {
	
		$name 						= 'BP Profile Video Player';
		$widget_opts['description'] = __( 'Add a video player to your BuddyPress profile page.', 'slushman-bp-profile-video-player' );
	
		parent::__construct( false, $name, $widget_opts );
		
		// Future i10n support
		// load_plugin_textdomain( PLUGIN_LOCALE, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		
		// Form fields
		// required: name, underscored, type, & value. optional: desc, sels, size
		$this->fields[] = array( 'name' => 'Title', 'underscored' => 'title', 'type' => 'text', 'value' => 'Video Player' );
		$this->fields[] = array( 'name' => 'Width', 'underscored' => 'width', 'type' => 'text', 'value' => '' );
		$this->fields[] = array( 'name' => 'Empty Message', 'underscored' => 'emptymsg', 'type' => 'text', 'value' => 'This user has not activated their video player.' );
		$this->fields[] = array( 'name' => 'Hide widget if empty', 'underscored' => 'hide_empty', 'type' => 'checkbox', 'value' => 0 );
		$this->fields[] = array( 'name' => 'Aspect Ratio', 'underscored' => 'aspect', 'type' => 'select', 'value' => 'Normal', 'sels' => array( array( 'label' => 'Normal', 'value' => 'Normal' ), array( 'label' => 'HD', 'value' => 'HD' ) ) );
		
	} // End of __construct()

/**
 * The output of the front-end of the widget
 *
 * @param   array 	$instance  Previously saved values from database.
 *
 * @uses    xprofile_get_field_data
 * @uses    oembed_transient
 */
	function widget_output( $args, $instance ) {

		global $slushman_bp_profile_widgets;

		// Get widget options and profile data
		$service = $videoURL = $description = $width = $aspect = $multiplier = $height = $control = '';

		$videoURL		= xprofile_get_field_data( 'Video Player URL' );	
		$description 	= xprofile_get_field_data( 'Video Player Role' );
		$width 			= $instance['width'];
		$aspect 		= $instance['aspect'];

		// Determine the height from the width and aspect ratio in the Widget options

		if ( !empty( $aspect ) ) {
		
			 $multiplier = ( $aspect == 'Normal' ? .75 : ( $aspect == 'HD' ? .5625 : '' ) ); 
		
		} // End of $aspect empty check

		if ( !empty( $width ) && !empty( $multiplier ) ) {
			
			if ( !empty( $videoURL ) ) {

				$host 		= parse_url( $videoURL, PHP_URL_HOST );
			 	$exp		= explode( '.', $host );
			 	$service 	= ( count( $exp ) >= 3 ? $exp[1] : $exp[0] );
			 	
			 } // End of $videoURL check
		 	
		 	$multiplier = ( $service == 'viddler' ? .5625 : $multiplier );
			$control 	= ( $service == 'youtube' || $service == 'youtu' ? 25 : 0 );
			$height 	= ( ( $width * $multiplier ) + $control );

		} else {
			
			echo '<p>Please enter a width in the widget options.</p>';
			
		} // End of empty checks
		
		// Get the correct correct video ID based on the service
		if ( empty( $videoURL ) || empty( $service ) ) {

			echo '<p>' . ( !empty( $instance['emptymsg'] ) ? $instance['emptymsg'] : '' ) . '</p>';
		
		} else {

			$oembed = $slushman_bp_profile_widgets->oembed_transient( $videoURL, $service, $width, $height );

			if ( !$oembed && $service == 'facebook' ) {
			
				// Input Example: https://www.facebook.com/photo.php?v=10201027508430408

		 		$videoID = end( explode( '=', $videoURL ) ); ?>

				<iframe src="https://www.facebook.com/video/embed?video_id=<?php echo $videoID; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" frameborder="0"></iframe><?php
						
			} elseif ( !$oembed && $service == 'veoh' ) {
			
				// Input Example: http://www.veoh.com/watch/v21024172CTxdMmR4
				
				$videoID = end( explode( '/', $videoURL ) ); ?>
			
				<object width="<?php echo $width; ?>" height="<?php echo $height; ?>" id="veohFlashPlayer" name="veohFlashPlayer">
					<param name="movie" value="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&permalinkId=<?php echo $videoID; ?>&player=videodetailsembedded&videoAutoPlay=0&id=anonymous"></param>
					<param name="allowFullScreen" value="true"></param>
					<param name="allowscriptaccess" value="always"></param>
					<embed src="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&permalinkId=<?php echo $videoID; ?>&player=videodetailsembedded&videoAutoPlay=0&id=anonymous" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="<?php echo $width; ?>" height="<?php echo $height; ?>" id="veohFlashPlayerEmbed" name="veohFlashPlayerEmbed"></embed>
				</object><?php
				
			} elseif ( !$oembed && $service == 'ustream' ) {
			
				// Input Example: http://www.ustream.tv/recorded/31427029/highlight/343133
				
				$videoID = end( explode( '.tv/', $videoURL ) ); ?>

				<iframe width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://www.ustream.tv/embed/<?php echo $videoID; ?>?v=3&amp;wmode=direct" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe><?php

			} else {

				// Input Examples: 
				// 		http://www.youtube.com/watch?v=YYYJTlOYdm0
				// 		http://youtu.be/YYYJTlOYdm0
				// 		https://vimeo.com/37708663
				// 		http://www.flickr.com/photos/riotking/2550468661
				// 		http://blip.tv/juliansmithtv/julian-smith-lottery-6362952
				// 		http://www.dailymotion.com/video/xull3h_monster-roll_shortfilms
				// 		http://www.ustream.tv/channel/3777978
				// 		http://www.ustream.tv/recorded/32219761
				// 		http://www.funnyordie.com/videos/5764ccf637/daft-punk-andrew-the-pizza-guy?playlist=featured_videos
				// 		http://www.hulu.com/watch/486928
				// 		http://revision3.com/destructoid/bl2-dlc-leak-tiny-tinas-assault-on-dragon-keep
				// 		http://www.viddler.com/v/bdce8c7
				// 		http://qik.com/video/38782012
		
				echo $oembed;

			} // End of embed codes

		} // End of $videoURL & $service check
		
		echo '<p>' . ( isset( $description ) && !empty( $description ) ? $description : '' ) . '</p>';

	} // End of widget_output()

/**
 * Back-end widget form.
 *
 * @see WP_Widget::form()
 *
 * @param array $instance Previously saved values from database.
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
 * @see WP_Widget::widget()
 *
 * @param array $args     Widget arguments.
 * @param array $instance Saved values from database.
 */	
	function widget( $args, $instance ) {
		
		if ( bp_is_user_profile() ) {

			$url = xprofile_get_field_data( 'Video Player URL' );

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

			}

		}
		
	} // End of widget()
	
/**
 * Sanitize widget form values as they are saved.
 *
 * @see WP_Widget::update()
 *
 * @param array $new_instance Values just sent to be saved.
 * @param array $old_instance Previously saved values from database.
 *
 * @return array Updated safe values to be saved.
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
	
} // End of class slushman_bp_profile_video_player_widget()

?>