<?php

if ( !class_exists( "Slushman_Toolkit_Make_Fields" ) ) {

	class Slushman_Toolkit_Make_Fields {
	
		public static $instance;
	
		function __construct() {
		
			self::$instance = $this;

		} // End of __construct()

/**
 * Creates a single checkbox field based on the params
 *
 * @params are:
 *	check - used in the checked function (example: the plugin's saved option)
 * 	class - used for the class attribute
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  name - if the name needs to be separated from ID, otherwise its the ID field
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the checkbox field
 *
 * @return	mixed	$output		A properly formatted HTML checkbox with optional label and description
 */			
		function make_checkbox( $params ) {

			$defaults 	= array( 'class' => '', 'id' => '', 'label' => '', 'name' => $params['id'] );
			$params 	= wp_parse_args( $params, $defaults );
			//$checked 	= checked( $params['check'], 1, FALSE );
			
			$output 	= sprintf( '<input type="checkbox" name="%1$s" id="%2$s" value="checkbox|_|1" class="%3$s" ' . checked( $params['check'], 1, FALSE ) . ' /> <label for="%2$s">%4$s</label>', $params['name'], $params['id'], $params['class'], $params['label'] );

			return $output;
			
		} // End of make_checkbox() 			
		
/**
 * Creates a group of checkboxes based on the params
 *
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the group
 *  name - the name of the field
 *	value - used in the checked function
 *	selections - an array of data to use as the selections in the menu
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the checkbox group
 *
 * @return	mixed	$output		A properly formatted HTML checkbox group with optional label and description
 */	
		function make_checkboxes( $params ) {
			
			$defaults 	= array( 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => '', 'value' => '' );
			$params 	= wp_parse_args( $params, $defaults );

			$output = ( empty( $params['label'] ) ? '' : sprintf( '<label for="%1$s">%2$s</label><legend>%2$s</legend>', $params['id'], $params['label'] ) );
			$output .= sprintf( '<div id="%s"><fieldset>', $params['id'] );
			
			if ( !empty( $params['selections'] ) ) {
			
				foreach ( $params['selections'] as $selection ) {

					$checked = checked( $params['value'], $selection['value'], FALSE );
				
					$output .= sprintf( '<label for="%s">', $selection['label'] );
					$output .= sprintf( '<input type="checkbox" id="%2$s" name="%3$s" value="%4$s" class="%5$s" %6$s /> <span>%1$s</span></label><br />', $selection['label'], $params['id'], $params['name'], $selection['value'], $params['class'], $checked );

				} // End of $selections foreach
				
			} // End of $selections empty check
			
			$output .= '</fieldset></div>';
			$output .= ( !empty( $params['desc'] ) ? sprintf( '<br /><span class="description"> %s</span>', $params['desc'] ) : '' );

			return $output;
						
		} // End of make_checkboxes()

/**
 * Creates an WP editor field
 *
 * @params are:
 * 	class - used for the class attribute
 *  id - ised for the if and names attributes
 *  name - used for the name span
 *	value - used in the checked function
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the textarea
 *
 * @return	mixed	$output		A properly formatted HTML textarea with optional label and description
 */
		function make_editor( $params ) {

			$defaults 	= array( 'class' => '', 'id' => '', 'name' => $params['id'], 'value' => '' );
			$params 	= wp_parse_args( $params, $defaults );

			$output = '<div id="postdivrich" class="postarea ' . $class . '">';
			
			$editor['dfw'] 					= TRUE;
			$editor['editor_height'] 		= 360;
			$editor['media_buttons'] 		= FALSE;
			$editor['tabfocus_elements'] 	= 'sample-permalink,post-preview';
			$editor['textarea_name'] 		= $params['name'];

			wp_editor( $value, $params['id'], $editor );
				
			$output .= '</div>';
			
			return $output;
			
		} // End of make_editor()		

/**
 * Creates an hidden field based on the params
 *
 * @params are:
 *  name - (optional), can be a separate value from ID
 *	value - used for the value attribute
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the hidden field
 *
 * @return	mixed	$output		A properly formatted HTML hidden field
 */			
		function make_hidden( $params ) { 
		
			$output = sprintf( '<input type="hidden" name="%s" value="%s" />', $params['name'], $params['value'] );

			return $output;
			
		} // End of make_hidden()
		
/**
 * Creates a group of radio buttons based on the params
 *
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the group
 *  name - the name of the field
 *	value - used in the checked function
 *	selections - an array of data to use as the selections in the menu
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the radio group
 *
 * @return	mixed	$output		A properly formatted HTML radio group with optional label and description
 */	
		function make_radios( $params ) {

			$defaults 	= array( 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => $params['id'], 'value' => '' );
			$params 	= wp_parse_args( $params, $defaults );
			
			$output = ( empty( $params['label'] ) ? '' : sprintf( '<label for="%1$s">%2$s</label><legend>%2$s</legend>', $params['id'], $params['label'] ) );
			$output .= sprintf( '<div id="%s"><fieldset>', $params['id'] );
			
			if ( !empty( $params['selections'] ) ) {
			
				foreach ( $params['selections'] as $selection ) {

					$checked = checked( $params['value'], $selection['value'], FALSE );
				
					$output .= sprintf( '<label for="%s">', $selection['label'] );
					$output .= sprintf( '<input type="radio" id="%2$s" name="%3$s" value="%4$s" class="%5$s" %6$s /> <span>%1$s</span></label><br />', $selection['label'], $params['id'], $params['name'], $selection['value'], $params['class'], $checked );

				} // End of $selections foreach
				
			} // End of $selections empty check
			
			$output .= '</fieldset></div>';
			$output .= ( !empty( $params['desc'] ) ? sprintf( '<br /><span class="description"> %s</span>', $params['desc'] ) : '' );
			
			return $output;
						
		} // End of make_radios()		
		
/**
 * Creates a select menu based on the params
 *
 * @params are:
 *  blank - false for none, true if you want a blank option, or enter text for the blank selector
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  name - the name of the field
 *	value - used in the selected function
 *	selections - an array of data to use as the selections in the menu
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the select menu
 *
 * @return	mixed	$output		A properly formatted HTML select menu with optional label and description
 */	
		function make_select( $params ) {

			$defaults 	= array( 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => $params['id'], 'value' => '' );
			$params 	= wp_parse_args( $params, $defaults );
			
			$output = ( !empty( $params['label'] ) ? sprintf( '<label for="%s">%s</label>', $params['id'], $params['label'] ) : '' );
			$output .= sprintf( '<select id="%s" name="%s" class="%s">', $params['id'], $params['name'], $params['class'] );
			$output .= ( !empty( $params['blank'] ) ? '<option>' . ( !is_bool( $params['blank'] ) ? __( $params['blank'] ) : '' ) . '</option>' : '' );
			
			if ( !empty( $params['selections'] ) ) {
			
				foreach ( $params['selections'] as $selection ) {
				
					$output .= sprintf( '<option value="%s" ' . selected( $params['value'], $selection['value'], FALSE ) . ' >%s</option>', $selection['value'], $selection['label'] );

				} // End of $selections foreach
				
			} // End of $selections empty check
			
			$output .= '</select>';
			$output .= ( !empty( $params['desc'] ) ? sprintf( '<br /><span class="description"> %s</span>', $params['desc'] ) : '' );
			
			return $output;
						
		} // End of make_select()		
		
/**
 * Creates an input field based on the params
 *
 * Creates an input field based on the params
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  name - (optional), can be a separate value from ID
 *  placeholder - The text that appears in th field before a value is entered.
 *  type - detemines the particular type of input field to be created
 *	value - used for the value attribute
 * 
 * Inputtype options: 
 *  email - email address
 *  text - standard text field (default)
 *  tel - phone numbers
 *  url - urls
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the text field
 *
 * @return	mixed	$output		A properly formatted HTML input field with optional label and description
 */			
		function make_text( $params ) {

			$defaults 	= array( 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => $params['id'], 'placeholder' => '', 'type' => 'text', 'value' => '' );
			$params 	= wp_parse_args( $params, $defaults );
			$value 		= ( $params['type'] == 'url' ? esc_url( $params['value'] ) : esc_attr( $params['value'] ) );
		
			$output = ( !empty( $params['label'] ) ? sprintf( '<label for="%s">%s</label>', $params['id'], $params['label'] ) : '' );
			$output .= sprintf( '<input type="%s" id="%s" name="%s" value="%s" class="%s" placeholder="%s" />', $params['type'], $params['id'], $params['name'], $value, $params['class'], $params['placeholder'] );
			$output .= ( !empty( $params['desc'] ) ? sprintf( '<br /><span class="description"> %s</span>', $params['desc'] ) : '' );

			return $output;
			
		} // End of make_text()

/**
 * Creates an HTML textarea
 *
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 *  id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  name - (optional), can be a separate value from ID
 *  placeholder - The text that appears in th field before a value is entered.
 *  size - an array with the columns and rows to size the textarea
 *	value - used in the checked function
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the textarea
 *
 * @return	mixed	$output		A properly formatted HTML textarea with optional label and description
 */
		function make_textarea( $params ) {

			$defaults 	= array( 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => $params['id'], 'placeholder' => '', 'size' => array( 'cols' => '50', 'rows' => '10' ), 'value' => '' );
			$params 	= array_replace_recursive( $defaults, $params );
			
			$output = ( !empty( $params['label'] ) ? sprintf( '<label for="%s">%s</label>', $params['id'], $params['label'] ) : '' );
			$output .= sprintf( '<textarea id="%s" name="%s" class="%s" placeholder="%s" cols="%s" rows="%s" wrap="hard">%s</textarea>', $params['id'], $params['name'], $params['class'], $params['placeholder'], $cols, $rows, esc_textarea( $params['value'] ) );
			$output .= ( !empty( $params['desc'] ) ? sprintf( '<br /><span class="description"> %s</span>', $params['desc'] ) : '' );
			
			return $output;
			
		} // End of make_textarea()

/**
 * Displays a file upload field for a settings field
 *
 * @param array   $args settings field args
 */
	    function make_file( $args ) {

	        $value 	= esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
	        $size 	= isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
	        $id 	= $args['section']  . '[' . $args['id'] . ']';
	        $js_id 	= $args['section']  . '\\\\[' . $args['id'] . '\\\\]';
	        $html 	= sprintf( '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
	        $html 	.= '<input type="button" class="button wpsf-browse" id="'. $id .'_button" value="Browse" />
	        <script type="text/javascript">
	        jQuery(document).ready(function($){
	            $("#'. $js_id .'_button").click(function() {
	                tb_show("", "media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true");
	                window.original_send_to_editor = window.send_to_editor;
	                window.send_to_editor = function(html) {
	                    var url = $(html).attr(\'href\');
	                    if ( !url ) {
	                        url = $(html).attr(\'src\');
	                    };
	                    $("#'. $js_id .'").val(url);
	                    tb_remove();
	                    window.send_to_editor = window.original_send_to_editor;
	                };
	                return false;
	            });
	        });
	        </script>';
	        $html 	.= sprintf( '<span class="description"> %s</span>', $args['desc'] );

	        echo $html;

	    } // End of callback_file()

/**
 * Displays a password field for a settings field
 *
 * Params:
 * 	desc - 
 * 	id - 
 * 	size - 
 * 	std - 
 *
 * @param array   $args settings field args
 */
	    function make_password( $params ) {

	    	extract( $params );

	        $value 	= esc_attr( $this->get_option( $id, $section, $std ) );
	        $size 	= isset( $size ) && !is_null( $size ) ? $size : 'regular';

	        $output = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $section, $id, $value );
	        $output .= ( !empty( $desc ) ? sprintf( '<br /><span class="description"> %s</span>', $desc ) : '' );

	        echo $html;

	    } // End of make_password()		

	} // End of Slushman_Toolkit_Make_Fields class

	// $make_fields = new Slushman_Toolkit_Make_Fields;
	
} // End of class_exists check

?>