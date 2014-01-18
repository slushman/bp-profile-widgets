<?php

class slushman_bp_profile_video_player_widget extends WP_Widget {

/**
 * Register widget with WordPress.
 */
	function __construct() {
	
		$this->i18n					= 'bp-profile-widgets';
		$name 						= __( 'BP Profile Video Player', $this->i18n );
		$widget_opts['description'] = __( 'Add a video player to your BuddyPress profile page.', $this->i18n );
	
		parent::__construct( false, $name, $widget_opts );
		
		// Form fields
		// required: name, underscored, type, & value. optional: desc, sels, size
		$this->fields[] = array( 'name' => __( 'Title', $this->i18n ), 'underscored' => 'title', 'type' => 'text', 'value' => 'Video Player' );
		$this->fields[] = array( 'name' => __( 'Width', $this->i18n ), 'underscored' => 'width', 'type' => 'text', 'value' => '' );
		$this->fields[] = array( 'name' => __( 'Empty Message', $this->i18n ), 'underscored' => 'emptymsg', 'type' => 'text', 'value' => __( 'This user has not activated their video player.', $this->i18n ) );
		$this->fields[] = array( 'name' => __( 'Hide widget if empty', $this->i18n ), 'underscored' => 'hide_empty', 'type' => 'checkbox', 'value' => 0 );
		$this->fields[] = array( 'name' => __( 'Aspect Ratio', $this->i18n ), 'underscored' => 'aspect', 'type' => 'select', 'value' => __( 'Normal', $this->i18n ), 'sels' => array( array( 'label' => __( 'Normal', $this->i18n ), 'value' => __( 'Normal', $this->i18n ) ), array( 'label' => __( 'HD', $this->i18n ), 'value' => __( 'HD', $this->i18n ) ) ) );

		$this->options 	= (array) get_option( 'slushman_bppw_settings' );
		$quantity 		= $this->options['BP_profile_video_player_widget'];

		// Create $selects for how many items select menu
		for ( $i = 1; $i <= $quantity; $i++ ) {

			$instance_selects[] = array( 'label' => $i, 'value' => $i );

		} // End of for loop

		$this->fields[] = array( 'name' => __( 'If you use multiple widgets: which one is this?', $this->i18n ), 'underscored' => 'instance_number', 'type' => 'select', 'value' => 1, 'sels' => $instance_selects );
		
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
		$service = $url = $desc = $width = $aspect = $multiplier = $height = $control = '';

		$urlfield 	= __( 'Video Player URL', $this->i18n );
		$rolefield	= __( 'Video Player Role', $this->i18n );
		$url 		= $slushman_bp_profile_widgets->bppw_get_profile_data( $instance, $urlfield );
		$desc 		= $slushman_bp_profile_widgets->bppw_get_profile_data( $instance, $rolefield );
		$width 		= $instance['width'];
		$aspect		= $instance['aspect'];

		// Determine the height from the width and aspect ratio in the Widget options

		if ( !empty( $aspect ) ) {
		
			 $multiplier = ( $aspect == 'Normal' ? .75 : ( $aspect == 'HD' ? .5625 : '' ) ); 
		
		} // End of $aspect empty check

		if ( !empty( $width ) && !empty( $multiplier ) ) {
			
			if ( !empty( $url ) ) {

				$host 		= parse_url( $url, PHP_URL_HOST );
			 	$exp		= explode( '.', $host );
			 	$service 	= ( count( $exp ) >= 3 ? $exp[1] : $exp[0] );
			 	
			 } // End of $url check
		 	
		 	$multiplier = ( $service == 'viddler' ? .5625 : $multiplier );
			$control 	= ( $service == 'youtube' || $service == 'youtu' ? 25 : 0 );
			$height 	= ( ( $width * $multiplier ) + $control );

		} else {
			
			echo '<p>Please enter a width in the widget options.</p>';
			
		} // End of empty checks
		
		// Get the correct correct video ID based on the service
		if ( empty( $url ) || empty( $service ) ) {

			echo '<p>' . ( !empty( $instance['emptymsg'] ) ? $instance['emptymsg'] : '' ) . '</p>';
		
		} else {

			$oembed = $slushman_bp_profile_widgets->oembed_transient( $url, $service, $width, $height );

			if ( !$oembed && $service == 'facebook' ) {
			
				// Input Example: https://www.facebook.com/photo.php?v=10201027508430408

				$explode = explode( '=', $url );
		 		$videoID = end( $explode );

				?><iframe src="https://www.facebook.com/video/embed?video_id=<?php echo $videoID; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" frameborder="0"></iframe><?php
						
			} elseif ( !$oembed && $service == 'veoh' ) {
			
				// Input Example: http://www.veoh.com/watch/v21024172CTxdMmR4
				
				$explode = explode( '/', $url );
				$videoID = end( $explode );
			
				?><object width="<?php echo $width; ?>" height="<?php echo $height; ?>" id="veohFlashPlayer" name="veohFlashPlayer">
					<param name="movie" value="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&permalinkId=<?php echo $videoID; ?>&player=videodetailsembedded&videoAutoPlay=0&id=anonymous"></param>
					<param name="allowFullScreen" value="true"></param>
					<param name="allowscriptaccess" value="always"></param>
					<embed src="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&permalinkId=<?php echo $videoID; ?>&player=videodetailsembedded&videoAutoPlay=0&id=anonymous" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="<?php echo $width; ?>" height="<?php echo $height; ?>" id="veohFlashPlayerEmbed" name="veohFlashPlayerEmbed"></embed>
				</object><?php
				
			} elseif ( !$oembed && $service == 'ustream' ) {
			
				// Input Example: http://www.ustream.tv/recorded/31427029/highlight/343133
				
				$explode = explode( '.tv/', $url );
				$videoID = end( $explode );

				?><iframe width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://www.ustream.tv/embed/<?php echo $videoID; ?>?v=3&amp;wmode=direct" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe><?php

			} elseif ( !$oembed && $service == 'vine' ) {

				// Input example: https://vine.co/v/bjHh0zHdgZT

				$explode = explode( '/', $url );
				$videoID = end( $explode );

				?><iframe class="vine-embed" src="https://vine.co/v/<?php echo $videoID; ?>/embed/simple" width="<?php echo $width; ?>" height="<?php echo $height; ?>" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script><?php

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
				// 		http://home.wistia.com/medias/e4a27b971d
				// 		http://wordpress.tv/2013/10/26/chris-wilcoxson-how-to-build-your-first-widget/
		
				echo $oembed;

			} // End of embed codes

		} // End of $url & $service check
		
		echo '<p>' . ( isset( $desc ) && !empty( $desc ) ? $desc : '' ) . '</p>';

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
		
		global $slushman_bp_profile_widgets;

		if ( bp_is_user_profile() ) {

			$urlfield 	= __( 'Video Player URL', $this->i18n );
			$url 		= $slushman_bp_profile_widgets->bppw_get_profile_data( $instance, $urlfield );

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