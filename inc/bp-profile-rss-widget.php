<?php

class slushman_BP_profile_rss_widget extends WP_Widget {
	
/**
 *Register widget with WordPress
 */
 	function __construct() {
 	
 		$name 					= __( 'BP Profile Display RSS' );
 		$opts['description'] 	= __( 'Add an RSS or Atom feed to your BuddyPress profile page.', 'slushman-bp-profile-rss-widget' );
 		
 		parent::__construct( false, $name, $opts );
 		
		// Future i10n support
		// load_plugin_textdomain( PLUGIN_LOCALE, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		
		// Create $selects for how many items select menu
 		for ( $i = 0; $i <= 20; $i++  ) {

 			$item_selects[] = array( 'label' => $i, 'value' => $i );

 		} // End of for loop

		// Form fields
		// required: name, underscored, type, & value. optional: desc, sels, size
		$this->fields[] = array( 'name' => 'Feed Title (optional)', 'underscored' => 'title', 'type' => 'text', 'value' => 'RSS Feed' );
		$this->fields[] = array( 'name' => 'How many items?', 'underscored' => 'items', 'type' => 'select', 'value' => 10, 'sels' => $item_selects );
		$this->fields[] = array( 'name' => 'Display item content?', 'underscored' => 'show_summary', 'type' => 'checkbox', 'value' => 0 );
		$this->fields[] = array( 'name' => 'Display item author if available?', 'underscored' => 'show_author', 'type' => 'checkbox', 'value' => 0 );
		$this->fields[] = array( 'name' => 'Display item date?', 'underscored' => 'show_date', 'type' => 'checkbox', 'value' => 0 );
		$this->fields[] = array( 'name' => 'Hide widget if empty', 'underscored' => 'hide_empty', 'type' => 'checkbox', 'value' => 0 );

 	} // End of __construct()

/**
 * The output of the front-end of the widget
 *
 * @param   string 	$rss		The feed URL
 * @param   array 	$instance 	Previously saved values from database
 *
 * @uses    xprofile_get_field_data
 * @uses    oembed_transient
 * @uses    find_on_page
 */
	function widget_output( $rss, $instance = array() ) {

		if ( is_string( $rss ) ) {

			$rss = fetch_feed( $rss );

		} elseif ( is_array( $rss ) && isset( $rss['url'] ) ) {

			$args 	= $rss;
			$rss 	= fetch_feed( $rss['url'] );

		} elseif ( !is_object( $rss ) ) {

			return;
		
		} // End of $rss check

		if ( is_wp_error( $rss ) ) {

			if ( is_admin() || current_user_can( 'manage_options' ) ) {

				echo '<p>' . sprintf( __( '<strong>RSS Error</strong>: %s' ), $rss->get_error_message() ) . '</p>';

			}

			return;
		
		}

		$default_args 	= array( 'show_author' => 0, 'show_date' => 0, 'show_summary' => 0, 'hide_empty' => 0 );
		$args 			= wp_parse_args( $args, $default_args );

		extract( $args, EXTR_SKIP );

		if ( $instance['items'] < 1 || 20 < $instance['items'] ) {

			$instance['items'] = 10;

		}

		if ( !$rss->get_item_quantity() ) {

			echo '<p>' . __( 'An error has occurred, which probably means the feed is down. Try again later.' ) . '</p>';
			
			$rss->__destruct();
			
			unset( $rss );
			
			return;
		
		} // End of $rss error check
		
		foreach ( $rss->get_items( 0, $instance['items'] ) as $item ) {

			$author = $date = '';
			$link 	= $item->get_link();
		
			while ( stristr( $link, 'http' ) != $link ) {
		
				$link = substr( $link, 1 );

			} // End of $link while loop
		
			$link 	= esc_url( strip_tags( $link ) );
			$title 	= esc_attr( strip_tags( $item->get_title() ) );

			if ( empty( $title ) ) {
			
				$title = __( 'Untitled' );

			} // End of title empty check

			$desc 		= str_replace( array( "\n", "\r" ), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option( 'blog_charset' ) ) ) ) );
			$excerpt 	= wp_html_excerpt( $desc, 360 );

			// Append ellipsis. Change existing [...] to [&hellip;].
			if ( '[...]' == substr( $excerpt, -5 ) ) {

				$excerpt = substr( $excerpt, 0, -5 ) . '[&hellip;]';

			} elseif ( '[&hellip;]' != substr( $excerpt, -10 ) && $desc != $excerpt ) {

				$excerpt .= ' [&hellip;]';

			} // End of ellipsis check

			$excerpt = esc_html( $excerpt );

			if ( $instance['show_date'] == 1 ) {

				$date = $item->get_date( 'U' );

				if ( $date ) {

					$date = ' <span class="rss_date" style="font-size: 0.8rem;margin-left:0.8rem;">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>';

				}

			}

			if ( $instance['show_author'] == 1 ) {

				$author = $item->get_author();

				if ( is_object( $author ) ) {

					$writer = $author->get_name();
					$author = ' <cite>' . esc_html( strip_tags( $writer ) ) . '</cite>';

				} // End of object check

			} // End of show_author check

			$title 		= ( $link == '' ? $title : '<a class="rss_link" href="' . $link . '" title="' . $desc . '">' . $title. '</a>' );
			$summary 	= ( $instance['show_summary'] == 1 ? '<span class="rss_summary" style="display:block;">' . $excerpt . '</span>' : '' );

			echo '<p>' . $title . $date . $summary . $author . '</p>';

		} // End of $rss items foreach loop

		$rss->__destruct();

		unset( $rss );

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

  			$url = xprofile_get_field_data( 'RSS Feed URL' );

			if ( $instance['hide_empty'] == 0 && !empty( $url ) ) {

				extract( $args, EXTR_SKIP );

				while ( stristr( $url, 'http' ) != $url ) {

					$url = substr( $url, 1 );

				}

				if ( empty( $url ) ) { return; }

				// self-url destruction sequence
				if ( in_array( untrailingslashit( $url ), array( site_url(), home_url() ) ) ) { return; }

				$rss 	= fetch_feed( $url );
				$title 	= $instance['title'];
				$desc 	= $link = '';
				$error 	= false;
					
				if ( is_wp_error( $rss ) ) {
				
					$error = $rss->get_error_message();
				
				} else {

					$desc = esc_attr( strip_tags( @html_entity_decode( $rss->get_description(), ENT_QUOTES, get_option( 'blog_charset' ) ) ) );

					if ( empty( $title ) ) {
					
						$title = esc_html( strip_tags( $rss->get_title() ) );
					
					}

					$link = esc_url( strip_tags( $rss->get_permalink() ) );

					while ( stristr( $link, 'http' ) != $link ) {

						$link = substr( $link, 1 );

					} // End of while loop

				} // End of WP error check

				$title 	= apply_filters( 'widget_title', $title, $instance, $this->id_base );
				$url 	= esc_url( strip_tags( $url ) );
				$icon 	= includes_url( 'images/rss.png' );
				
				if ( $title ) {

					$title = "<a class='rsswidget' href='$url' title='" . esc_attr__( 'Syndicate this content' ) ."'><img style='border:0' width='14' height='14' src='$icon' alt='RSS' /></a> <a class='rsswidget' href='$link' title='$desc'>$title</a>";

				}

				echo $before_widget;
				
				echo ( empty( $title ) ? ( empty( $desc ) ? __( 'Unknown Feed' ) : $desc ) : $before_title . $title . $after_title );

				do_action( 'bp_before_sidebar_me' );
						
				echo '<div id="sidebar-me">';

				if ( empty( $error ) ) {

					$this->widget_output( $rss, $instance );

				} else {

					echo '<p>' . $error . '</p>';

				} // End of $error check

				do_action( 'bp_sidebar_me' );
							
				echo '</div>';
				
				do_action( 'bp_after_sidebar_me' );
				
				echo $after_widget;

				if ( !is_wp_error( $rss ) ) {
				
					$rss->__destruct();
				
				}

				unset( $rss );

			} // End of profile check

		} // End of BP profile check

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
 	function update( $new, $old ) {
	 	
	 	$instance = $old;

	 	foreach ( $this->fields as $field ) {

	 		if ( $new['items'] < 1 || 20 < $new['items'] ) {

				$new['items'] = 10;

			}

	 		$name = $field['underscored'];
			
			switch ( $field['type'] ) {
 			
	 			case ( 'email' )		: $instance[$name] = sanitize_email( $new[$name] ); break;
	 			case ( 'number' )		: $instance[$name] = intval( $new[$name] ); break;
	 			case ( 'url' ) 			: $instance[$name] = esc_url_raw( $new[$name] ); break;
	 			case ( 'text' ) 		: $instance[$name] = sanitize_text_field( $new[$name] ); break;
	 			case ( 'textarea' )		: $instance[$name] = esc_textarea( $new[$name] ); break;
	 			case ( 'checkgroup' ) 	: $instance[$name] = strip_tags( $new[$name] ); break;
	 			case ( 'radios' ) 		: $instance[$name] = strip_tags( $new[$name] ); break;
	 			case ( 'select' )		: $instance[$name] = strip_tags( $new[$name] ); break;
	 			case ( 'tel' ) 			: $instance[$name] = $slushkit->sanitize_phone( $new[$name] ); break;
	 			case ( 'checkbox' ) 	: $instance[$name] = ( isset( $new[$name] ) ? 1 : 0 ); break;
	 			
 			} // End of $inputtype switch

		} // End of $fields foreach
	 	
	 	return $instance;
	 	
 	} // End of update()
 		
} // End of slushman_bp_profile_rss_widget class

?>