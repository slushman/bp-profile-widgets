<?php

class slushman_bp_profile_text_box_widget extends WP_Widget {

/**
 * Register widget with WordPress.
 */
	function __construct() {
	
		$this->i18n					= 'bp-profile-widgets';
		$name 						= __( 'BP Profile Text Box', $this->i18n );
		$widget_opts['description'] = __( 'Add a text box to your BuddyPress profile page.', $this->i18n );
	
		parent::WP_Widget( false, $name, $widget_opts );

		// Form fields
		// required: name, underscored, type, & value. optional: desc, sels, size
		$this->fields[] = array( 'name' => __( 'Title', $this->i18n ), 'underscored' => 'title', 'type' => 'text', 'value' => 'Text Box' );
		$this->fields[] = array( 'name' => __( 'Automatically add paragraphs', $this->i18n ), 'underscored' => 'filter', 'type' => 'checkbox', 'value' => 0 );
		$this->fields[] = array( 'name' => __( 'Hide widget if empty', $this->i18n ), 'underscored' => 'hide_empty', 'type' => 'checkbox', 'value' => 0 );

		$this->options 	= (array) get_option( 'slushman_bppw_settings' );
		$quantity 		= $this->options['BP_profile_text_box_widget'];

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
 */
	function widget_output( $args, $instance ) {

		global $slushman_bp_profile_widgets;

		$textfield 	= __( 'Custom Text Box', $this->i18n );
		$text 		= $slushman_bp_profile_widgets->bppw_get_profile_data( $instance, $textfield );

		echo '<div class="bpcustomtextwidget">' . ( !empty( $instance['filter'] ) ? wpautop( $text ) : $text ) . '</div>';

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
 * @uses	apply_filters
 * @uses	do_action
 * @uses	wpautop
 *
 * @param	array	$args		Widget arguments.
 * @param 	array	$instance	Saved values from database.
 */		
	function widget( $args, $instance ) {

		global $slushman_bp_profile_widgets;

		if ( bp_is_user_profile() ) {

			$textfield 	= __( 'Custom Text Box', $this->i18n );
			$text 		= $slushman_bp_profile_widgets->bppw_get_profile_data( $instance, $textfield );

			if ( !empty( $text ) || $instance['hide_empty'] == 0 ) {

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

} // End of slushman_bp_profile_text_box_widget()

?>