<?php

/**
 * Create the XProfile field group for the widgets
 *
 * Check options for if any widgets are selected. If at least is selected, see if the field group 
 * already exists. If not, create it.
 *
 * @since	0.1
 * 
 * @uses	get_xprofile_group_id_from_name
 * @uses	xprofile_insert_field_group
 */		
		function create_profile_group() {
			
			$sel = 0;
			
			foreach ( $this->fields as $field ) {
			
				if ( $this->options[$field['underscored']] == 1 ) { 
					
						$sel++;
					
				} // End of option check
				
			} // End of $fields foreach

			$groupcheck = $this->xprofile_get_group_id_from_name( 'Profile Widgets' );

			if ( $sel > 0 && $groupcheck == 0 ) {
				
				$group_args['name'] = 'Profile Widgets';
				
				xprofile_insert_field_group( $group_args );
				
			} // End of $sel check
			
		} // End of 
		
/**
 * Create the custom XProfile fields for each widget
 *
 * Checks for the existance of the Profile Widgets field group. Checks each option field to see which are 
 * selected and if they already exists. If its selected, but doesn't exist, it gets created.
 * 
 * If a widget is not selected, it gets deleted.
 *
 * @since	0.1
 * 
 * @uses	get_xprofile_group_id_from_name
 * @uses	xprofile_insert_field
 * @uses	xprofile_delete_field
 */
		function create_profile_fields() {
		
			$group_id 	= $this->xprofile_get_group_id_from_name( 'Profile Widgets' );
			$i 			= 0;

			if ( $group_id == 0 ) { return; }
							
			$fields[$i]['widget'] 	= 'Music Player';
			$fields[$i]['name'] 	= 'Music Player URL';
			$fields[$i]['type'] 	= 'textbox';
			$fields[$i]['desc'] 	= 'Please enter the URL for your album / set / profile from any of the following services: Bandcamp, SoundCloud, Reverbnation, Tunecore, Mixcloud, or Noisetrade.';
			$i++;

			$fields[$i]['widget'] 	= 'Music Player';
			$fields[$i]['name'] 	= 'Music Player Role';
			$fields[$i]['type'] 	= 'textbox';
			$fields[$i]['desc'] 	= 'Please explain your role in the music (artist, writer, player, producer, etc).';
			$i++;
			
			$fields[$i]['widget'] 	= 'Video Player';
			$fields[$i]['name'] 	= 'Video Player URL';
			$fields[$i]['type'] 	= 'textbox';
			$fields[$i]['desc'] 	= 'Please enter the URL to the YouTube, Vimeo, Veoh, DailyMotion, Blip.tv, uStream, or Facebook video you want to display on your profile.';
			$i++;
			
			$fields[$i]['widget'] 	= 'Video Player';
			$fields[$i]['name'] 	= 'Video Player Role';
			$fields[$i]['type'] 	= 'textbox';
			$fields[$i]['desc'] 	= 'Please explain your role in the video (actor, writer, crew, producer, etc).';
			$i++;
			
			$fields[$i]['widget'] 	= 'Photo Gallery';
			$fields[$i]['name'] 	= 'Photo Gallery URL';
			$fields[$i]['type'] 	= 'textbox';
			$fields[$i]['desc'] 	= 'Please enter the URL for the set / gallery / album / profile from any of the following services: Flickr, Picasa, Photobucket, Fotki, dotPhoto, or Imgur.';
			$i++;

			$fields[$i]['widget'] 	= 'Photo Gallery';
			$fields[$i]['name'] 	= 'Photo Gallery Role';
			$fields[$i]['type'] 	= 'textbox';
			$fields[$i]['desc'] 	= 'Please explain your role in the gallery (model, photographer, editor, etc).';
			$i++;
			
			$fields[$i]['widget'] 	= 'Text Box';
			$fields[$i]['name'] 	= 'Custom Text Box';
			$fields[$i]['type'] 	= 'textarea';
			$fields[$i]['desc'] 	= 'Please enter the text you want to appear on your profile. HTML is allowed.';
			$i++;
			
			foreach ( $fields as $key => $field ) {
				
				$under 		= str_replace( ' ', '_', strtolower( $field['widget'] ) );
				$selcheck 	= $this->options['BP_profile_' . $under . '_widget'];

				if ( $selcheck == 1 ) {
				
					$field_args['field_group_id'] 		= $group_id;
					$field_args['name'] 				= $field['name'];
					$field_args['can_delete'] 			= false;
					$field_args['field_order'] 			= $key + 1;
					$field_args['is_required'] 			= false;
					$field_args['type'] 				= $field['type'];
					$field_args['description']			= $field['desc'];
					$fields_args['default-visibility'] 	= 'adminsonly';
					
					$exists = xprofile_get_field_id_from_name( $field['name'] );
						
					if ( empty( $exists ) ) {
					
						$field_id = xprofile_insert_field( $field_args );

					} // End of $textfield empty check
				
					if ( !empty( $field_id ) ) {
						
						bp_xprofile_update_field_meta( $field_id, 'default_visibility', 'adminsonly' );
						bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', 'disabled' );

					} // End of $field_id check
				
				} elseif ( $selcheck == 0 ) {
					
					$id = xprofile_get_field_id_from_name( $field['name'] );
					
					if ( !empty( $id ) ) {
				
						xprofile_delete_field( $id );
						
					} // End of $id1 empty check
					
				} // End of $selcheck check
				
			} // End of $fields foreach

		} // End of create_profile_fields()

/**
 * Adds uStream to the list of oEmbed providers
 * 
 * @since	0.1
 *
 * @uses wp_oembed_add_provider
 */
		function enable_oembed_ustream() {

			wp_oembed_add_provider( '#http://(www\.)?ustream.tv/*#i', 'http://www.ustream.tv/oembed', true );

		} // End of enable_oembed_ustream()

/**
 * Returns the oEmbed code from either a saved transient or from the service's site
 * 
 * @since	0.1
 *
 * @param 	string 			$url 		The URL of the item being requested
 * @param 	string 			$width 		The width of the media player being requested
 * @param 	string 			$height 	The height of the media player being requested
 *
 * @uses 	get_transient
 * @uses  	wp_oembed_get
 * @uses   	set_transient
 * 
 * @return 	bool | mixed 	$oembed		Returns FALSE or the oEmbed code
 */
		function oembed_transient( $url, $service = '', $width = '', $height = '' ) {

			require_once( ABSPATH . WPINC . '/class-oembed.php' );

			if ( empty( $url ) ) { return FALSE; }

			$key 	= md5( $url );
			$oembed = get_transient( 'bppw_' . $key );

			if ( !$oembed && $url ) {

				if ( $service == 'viddler' ) {

					$oem 	= _wp_oembed_get_object();
					$json 	= 'http://www.viddler.com/oembed/?format=json&amp;url=' . urlencode( $url );
					$return = $oem->fetch( $json, $url, array( 'width' => $width, 'height' => $height ) );
					$oembed = $return->html;

				} else {

					$oembed = wp_oembed_get( $url, array( 'width' => $width, 'height' => $height ) );

				}

				if ( !$oembed ) { return FALSE; }

				set_transient( 'bppw_' . $key, $oembed, HOUR_IN_SECONDS );

			}

			return $oembed;

		} // End of oembed_transient()

/**
 * Initiates widgets based on the plugin options
 *
 * Checks each option field to see which widgets are selected. If selected, it gets registered.
 * If not, it gets unregistered.
 *
 * @since	0.1
 * 
 * @uses	register_widget
 * @uses	unregister_widget
 */		
		function widgets_init() {

			foreach ( $this->fields as $field ) {
				
				if ( $this->options[$field['underscored']] == 1 ) { 
				
					register_widget( 'slushman_' . $field['underscored'] );
			
				} elseif ( $this->options[$field['underscored']] == 0 ) {
					
					unregister_widget( 'slushman_' . $field['underscored'] );
					
				} // End of options check
				
			} // End of $fields foreach
			
        } // End of widgets_init()

?>