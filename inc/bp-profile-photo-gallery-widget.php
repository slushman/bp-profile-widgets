<?php

class slushman_bp_profile_photo_gallery_widget extends WP_Widget {

/**
 * Register widget with WordPress.
 */
	function __construct() {
	
		$name 						= 'BP Profile Photo Gallery';
		$widget_opts['description'] = __( 'Add a photo gallery to your BuddyPress profile page.', 'slushman-bp-profile-photo-gallery' );
	
		parent::__construct( false, $name, $widget_opts );

		// Future i10n support
		// load_plugin_textdomain( PLUGIN_LOCALE, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		// Form fields
		// required: name, underscored, type, & value. optional: desc, sels, size
		$this->fields[] = array( 'name' => 'Title', 'underscored' => 'title', 'type' => 'text', 'value' => 'Photo Gallery' );
		$this->fields[] = array( 'name' => 'Width', 'underscored' => 'width', 'type' => 'text', 'value' => '' );
		$this->fields[] = array( 'name' => 'Height', 'underscored' => 'height', 'type' => 'text', 'value' => '' );
		$this->fields[] = array( 'name' => 'Empty Message', 'underscored' => 'emptymsg', 'type' => 'text', 'value' => 'This user has not activated their photo gallery.' );
		$this->fields[] = array( 'name' => 'Hide widget if empty', 'underscored' => 'hide_empty', 'type' => 'checkbox', 'value' => 0 );
			
	} // End of __construct()

/**
 * The output of the front-end of the widget
 *
 * @param   array 	$instance  Previously saved values from database.
 *
 * @uses    xprofile_get_field_data
 */
	function widget_output( $args, $instance ) {

		global $slushman_bp_profile_widgets, $slushkit;

		$galleryURL 	= xprofile_get_field_data( 'Photo Gallery URL' );
		$description 	= xprofile_get_field_data( 'Photo Gallery Role' );
		$width 			= ( empty( $instance['width'] ) ? '' : $instance['width'] );
		$height 		= ( empty( $instance['height'] ) ? '' : $instance['height'] );
		
		if ( !empty( $galleryURL ) ) {
			
			list( $prefix,, $site ) = explode( '/', $galleryURL );
			$parts 					= explode( '.', $site );

			$checks = array( 'flickr', 'picasaweb', 'photobucket', 'fotki', 'dotphoto', 'imgur', 'smugmug' );

			foreach ( $checks as $check ) {

				if ( in_array( $check, $parts ) ) {

					$service = ( $check == 'picasaweb' ? 'picasa' : $check );
					break;

				}

			} // End of $services foreach loop

		} // End of empty check

		if ( empty( $galleryURL ) || empty( $service ) ) {

			echo '<p>' . ( !empty( $instance['emptymsg'] ) ? $instance['emptymsg'] : '' ) . '</p>';
		
		} else {

			$oembed = $slushman_bp_profile_widgets->oembed_transient( $galleryURL, $width, $height ); 

			if ( !$oembed && $service == 'flickr' ) { 
			
				// Input example: http://www.flickr.com/photos/christopherjoel/sets/72157617395267762

				$url 										= parse_url( $galleryURL );
		 		list( $site, $a, $username, $b, $setID ) 	= explode( '/', $url['path'] ); ?>
			
				<object width="<?php echo $width; ?>" height="<?php echo $height; ?>">
					<param name="flashvars" value="offsite=true&lang=en-us&page_show_url=%2Fphotos%2F<?php echo $username; ?>%2Fsets%2F<?php echo $setID; ?>%2Fshow%2F&page_show_back_url=%2Fphotos%2F<?php echo $username; ?>%2Fsets%2F<?php echo $setID; ?>%2F&set_id=<?php echo $setID; ?>&jump_to="></param>
					<param name="movie" value="http://www.flickr.com/apps/slideshow/show.swf?v=124984"></param>
					<param name="allowFullScreen" value="true"></param>
					<embed type="application/x-shockwave-flash" src="http://www.flickr.com/apps/slideshow/show.swf?v=124984" allowFullScreen="true" flashvars="offsite=true&lang=en-us&page_show_url=%2Fphotos%2F<?php echo $username; ?>%2Fsets%2F<?php echo $setID; ?>%2Fshow%2F&page_show_back_url=%2Fphotos%2F<?php echo $username; ?>%2Fsets%2F<?php echo $setID; ?>%2F&set_id=<?php echo $setID; ?>&jump_to=" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></embed>
				</object><?php
				
			} elseif ( !$oembed && $service == 'picasa' ) {
			
				// Input example: https://picasaweb.google.com/114838808449834204603/Fender72ThinlineRI?authuser=0&feat=directlink

				$url 						= parse_url( $galleryURL );
		 		list( $site, $albumID, $a ) = explode( '/', $url['path'] ); ?>

				<embed type="application/x-shockwave-flash" src="https://picasaweb.google.com/s/c/bin/slideshow.swf" width="288" height="192" flashvars="host=picasaweb.google.com&hl=en_US&feat=flashalbum&RGB=0x000000&feed=https%3A%2F%2Fpicasaweb.google.com%2Fdata%2Ffeed%2Fapi%2Fuser%2F<?php echo $albumID; ?>%3Falt%3Drss%26kind%3Dphoto%26access%3Dpublic%26psc%3DF%26q%26uname%3D<?php echo $albumID; ?>" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed><?php
				
			} elseif ( !$oembed && $service == 'photobucket' ) {
			
				// Input example: http://s262.photobucket.com/user/mandy_surfergirl91/library/CARS

				$rss_args['url'] 	= $galleryURL;
				$rss_args['start'] 	= 'rssFeed=http%3A%2F%2Ffeed';
				$rss_args['end'] 	= '%2Ffeed.rss\"';
				$rssfeed			= $slushkit->find_on_page( $rss_args ); 
				$rss 				= 'http%3A%2F%2Ffeed' . $rssfeed . '%2Ffeed.rss'; ?>
				
				<div style="width:<?php echo $width; ?>px;text-align:right;">
					<embed width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://static.pbsrc.com/flash/rss_slideshow.swf" flashvars="rssFeed=<?php echo $rss; ?>" type="application/x-shockwave-flash" wmode="transparent" />
				</div><?php

			} elseif ( !$oembed && $service == 'fotki' ) { 
				
				// Input example: http://public.fotki.com/sandylferguson/eagle-scout-project/
				
				$path 					= parse_url( $galleryURL, PHP_URL_PATH );			
				list( $a, $user, $b ) 	= explode( '/', $path );
				
				$id_args['start'] 	= 'href="http://feeds.fotki.com/' . $user . '/album_';
				$id_args['end'] 	= '.rss?p=1">';
				$id_args['url'] 	= $galleryURL;
				$albumID 			= $slushkit->find_on_page( $id_args ); ?>
			
				<object type="application/x-shockwave-flash" data="http://images.fotki.com/flash/FlipBook-1.0.swf" width="<?php echo $width; ?>" height="<?php echo $height; ?>" style="display:block">
				<param name="movie" value="http://images.fotki.com/flash/FlipBook-1.0.swf" />
				<param name="wmode" value="transparent" />
				<param name="quality" value="best" />
				<param name="flashvars" value="url=http%3A//feeds.fotki.com/<?php echo $user; ?>/album_<?php echo $albumID; ?>.rss%3Fcobr%3D0%26widget%3Ddfrrrrsdqftg&amp;linkcolor=%235471B9&amp;bgcolor=%23DFE5F4&amp;rows=4&amp;cols=3&amp;el_size=45" />
				</object><?php
			
			} elseif ( !$oembed && $service == 'dotphoto' ) { 
				
				// Input example: http://www.dotphoto.com/go.asp?l=hubble&SID=245780
				
		 		$albumID = end( explode( '=', $galleryURL ) ); ?>
			
				<object width="<?php echo $width; ?>" height="<?php echo $width; ?>" align="middle" id="show_<?php echo $albumID; ?>" data="http://www.dotphoto.com/FlashTool/player.swf" allowFullScreen="true" allownetworking="all" allowscriptaccess="always" type="application/x-shockwave-flash" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" >
					<param name="allowScriptAccess" value="always"/>
					<param name="allowNetworking" value="all"/>
					<param name="allowFullScreen" value="true"/>
					<param name="quality" value="high"/>
					<param name="bgcolor" value="#ffffff"/>
					<param name="wmode" value="transparent"/>
					<param name="swliveconnect" value="true"/>
					<param name="flashvars" value="playerStyle=embeddedWidget&SID=<?php echo $albumID; ?>&password="/>
					<param name="movie" value="http://www.dotphoto.com/FlashTool/player.swf"/>
					<embed src="http://www.dotphoto.com/FlashTool/player.swf" quality="high" allowFullScreen="true" allowNetworking="all" allowScriptAccess="always" bgcolor="#ffffff" width="<?php echo $width; ?>" height="<?php echo $width; ?>" flashvars="playerStyle=embeddedWidget&SID=<?php echo $albumID; ?>&password=" name="show_<?php echo $albumID; ?>" align="middle" wmode="transparent" type="application/x-shockwave-flash" pluginspace="http://www.macromedia.com/go/getflashplayer"></embed>
				</object><?php
			
			} elseif ( !$oembed && $service == 'imgur' ) {

				// Input example: http://imgur.com/a/eG4dY?gallery 

				$url 		= parse_url( $galleryURL );
		 		$albumID 	= end( explode( '/', $url['path'] ) ); ?>

				<iframe class="imgur-album" width="<?php echo $width; ?>" height="<?php echo $height; ?>" frameborder="0" src="http://imgur.com/a/<?php echo $albumID; ?>/embed"></iframe><?php

			} else {

				// Input Examples: 
				// 	http://belmontphoto.smugmug.com/BelmontStockPhotos/Campus/Construction-Belmont-Heights/17820170_drJWcW
		
				echo $oembed;

			} // End of embed codes

		} // End of empty checks
		
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

			$url = xprofile_get_field_data( 'Photo Gallery URL' );

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

} // End of slushman_bp_profile_photo_gallery_widget

?>