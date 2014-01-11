<?php
/*
Plugin Name: Slushman Toolkit
Plugin URI: http://slushman.com/plugins/slushman-toolkit
Description: A suite of functions that can supplement plugins and themes.
Version: 5.23.2013
Author: Slushman
Author URI: http://slushman.com
License: GPL2

**************************************************************************

  Copyright (C) 2013 Slushman

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General License for more details.

  You should have received a copy of the GNU General License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************

*/

// require 'inc/plugin-update-checker.php';
// require( 'demo.php' );

/*
$ExampleUpdateChecker = new PluginUpdateChecker(
	'http://slushman.com/toolkit/info.json', 
	__FILE__
);

//Here's how you can add query arguments to the URL.
function addSecretKey( $query ) {

	$query['secret'] = 'hpbEfJaG7bc7XGq2B5fV';

	return $query;

} // End of addSecretKey()

$ExampleUpdateChecker->addQueryArgFilter( 'addSecretKey' );

*/

// Script version, used to add version for scripts and styles
if ( !defined( 'TOOLKIT_VER' ) ) { define( 'TOOLKIT_VER', '0.1' ); }

//http://stackoverflow.com/questions/8668458/php-class-as-a-plugin-in-wordpress
 
/**
 *This is a collection of functions that I've found useful in many projects, 
 * so I decided to separate them from the projects to make them easier to use.
 */
 
if ( !class_exists( 'Slushman_Toolkit' ) ) {

	class Slushman_Toolkit {
	
		public static $instance;
	
		function __construct() {
		
			self::$instance = $this;
			
			// Check for updates
			// add_action( 'init', 'update_plugin' );
			
			// Enqueue up the scripts
			// add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ), 1000 );
			
			// Enqueue up the styles
			// add_action( 'admin_enqueue_scripts', array( $this, 'load_styles' ), 1000 );
			
			// Add the scripts to the admin footer
			// add_action( 'admin_footer', array( $this, 'admin_footer_stuff' ) );
									
		} // End of __construct()

/**
 * Adds this plugin to WordPress's auto-update
 *
 * @uses	plugin_basename
 * @uses	wp_auto_update
 */		
		function update_plugin() { 
		 
		    require_once ( 'inc/wp_autoupdate.php' );  
		    
		    $current_version 	= '10.22.2012';  
		    $remote_path 		= 'http://slushman.com/toolkit/update.php';  
		    $slug 				= plugin_basename( __FILE__ );  
		    
		    new wp_auto_update ( $current_version, $remote_path, $slug );  
		
		} // End of update_plugin()
		
		function admin_footer_stuff() { ?>
			
<script type="text/javascript">
	jQuery(document).ready(function(){
		// colorpicker field
		jQuery('.toolkit_color_picker').each(function(){
			var $this = jQuery(this),
				id = $this.attr('rel');
	 
			$this.farbtastic('#' + id);
		});
	});
</script>

<script type="text/javascript">
	jQuery(document).ready(function(){
    	jQuery('.toolkit_date_picker').datepicker({
        	dateFormat : 'D, m/d/yy'
        });
    });
</script><?php
			
		} // End of admin_footer_stuff()
		
		function load_scripts() {
			
			wp_enqueue_script( 'toolkit-color', DEV_PLUGINS_URL . 'cemb-seminar/js/color.js', array( 'farbtastic' ), TOOLKIT_VER, TRUE );
			
			//wp_enqueue_script( 'toolkit-datepicker', DEV_PLUGINS_URL . 'cemb-seminar/js/datepicker.js', array( 'jquery-ui-datepicker' ), TOOLKIT_VER, TRUE );
			
			// wp_enqueue_script( 'jquery-ui-datepicker' );
			
		} // End of load_scripts()
		
		function load_styles() {
			
			wp_enqueue_style( 'farbtastic' );
			
			// wp_enqueue_style( 'jquery.ui.theme', DEV_PLUGINS_URL . 'cemb-seminar/css/jquery-ui-1.8.17.custom.css' );
			
			wp_enqueue_style( 'jquery.ui.theme', DEV_PLUGINS_URL . 'cemb-seminar/css/jquery-ui-1.8.17.custom.css' );
			
		} // End of load_styles()
		

		function admin_enqueue_scripts() {
			$screen = get_current_screen();
		
			// Enqueue scripts and styles for registered pages (post types) only
			if ( 'post' != $screen->base || ! in_array( $screen->post_type, $this->meta_box['pages'] ) )
				return;
		
			wp_enqueue_style( 'rwmb', RWMB_CSS_URL . 'style.css', RWMB_VER );
		
			if ( $this->validation ) {
				wp_enqueue_script( 'jquery-validate', RWMB_JS_URL . 'jquery.validate.min.js', array( 'jquery' ), RWMB_VER, true );
				wp_enqueue_script( 'rwmb-validate', RWMB_JS_URL . 'validate.js', array( 'jquery-validate' ), RWMB_VER, true );
			}
		}
		
/**
 * Adds the page to the Settings admin menu
 *
 * @since	0.1
 * 
 * @uses	add_options_page
 */				
		function add_menu() {
		
			add_options_page( 
				'Slushman Toolkit Demo', 
				'Slushman Toolkit Demo', 
				'manage_options', 
				'slushman-toolkit', 
				array( $this, 'demo_page' ) 
			);
		
		} // End of add_menu()
		
/**
 * Cleans an array, removing blank values
 *
 * From WP Alchemy
 *
 * @static
 *
 * @since	1.0
 *
 * @access	public
 *
 * @param	array	the array to clean
 */
	function clean_array( $array ) {
	
		if ( is_array( $array ) ) {
		
			foreach ( $array as $i => $v ) {
			
				if ( is_array( $array[$i] ) ) {
					
					$this->clean_array( $array[$i] );
	 
					if ( !count( $array[$i] ) ) {
						
						unset( $array[$i] );
					
					}
				
				} elseif ( '' == trim( $array[$i] ) OR is_null( $array[$i] ) ) {
					
					unset( $array[$i] );
					
				} // End of array check
				
			} // End of $array foreach

			if ( !count( $array ) ) {
			
				$array = array();
			
			} else {
			
				$keys = array_keys( $array );

				$is_numeric = TRUE;

				foreach ( $keys as $key ) {
				
					if ( !is_numeric( $key ) ) {
					
						$is_numeric = FALSE;
						break;
					
					}
					
				} // End of $keys foreach

				if ( $is_numeric ) {
				
					$array = array_values( $array );
				
				}
				
			} // End of count check
			
		} // End of array check
		
		return $array;
		
	} // End of clean_array			
		
/**
 * Counts the number of elements in a multi-dimensional array
 *
 * Start by assigning $count and $arrays as 0. Then get the recursive count of the array,
 * which would include any arrays inside the array.  Go through each value in the root array,
 * if the $value is an array, add one to $arrays and send it through the function again.
 * 
 * Once finished, subtract $arrays from the recursive count, which returns the number of elements.
 *
 * @param	array	$array	An array to be counted
 *
 * @return	int		$count	The number of elements in the array
 */	
		public function count_elements_only( $array ) {
		
			if ( empty( $array ) ) { return FALSE; }
			
			$count = $arrays = 0;
	
			$recur = count( $array, COUNT_RECURSIVE );
		
			foreach ( $array as $key => $value ) {
			
				if ( is_array( $value ) ) {
				
					$arrays++;
				
					$this->count_elements_only( $value );
				
				} // End of array check
				
			} // End of $array foreach
			
			$count = $recur - $arrays;
			
			return $count;
		
		} // End of count_elements_only()		

/**
 * Gets a string on a webpage between two points
 *
 * Start by assigning $count and $arrays as 0. Then get the recursive count of the array,
 * which would include any arrays inside the array.  Go through each value in the root array,
 * if the $value is an array, add one to $arrays and send it through the function again.
 * 
 * Once finished, subtract $arrays from the recursive count, which returns the number of elements.
 *
 * @param	array	$params		The URL of the webpage to search, start and end of the string to find
 *
 * @return	string	$found		The item found on the page, false if not found
 */			
		function find_on_page( $params ) {
		
			foreach ( $params as $param ) {
				
				if ( empty( $param ) ) { return false; } // End of empty check
				
			} // End of $params foreach
			
			extract( $params );
			
			// Store the page
			$remote = wp_remote_get( $url );
	        $body 	= wp_remote_retrieve_body( $remote );
	        $page 	= ( !is_wp_error( $body ) ? $body : file_get_contents( $url ) );

	        if ( empty( $page ) ) { return FALSE; }
	        
	        // Calculate the length of start
	        $startlength = strlen( $start );
	        
	        // Find where the target begins
	        $targetStart = strpos( $page, $start ) + $startlength;
	        
	        // Find how long the playerID is
			$targetLength = strpos( $page, $end ) - $targetStart;
	        
	        // Extract playerID from $page
			$target = substr( $page, $targetStart, $targetLength );
			
			return $target;
		        
		} // End of find_on_page()

/**
 * Find a user by their meta data
 *
 * The metakey and metavalue are used with get_users to fins the user by that data.
 * If no match is found, it returns FALSE.
 * If a match is found, it returns the user's ID.
 *
 * @uses	get_users
 *
 * @param	int		$metakey The key for the meta data to search by
 * @param	string	$metavalue The value of the key to search by
 *
 * @return	int		The user's ID or FALSE if the user doesn't exist
 */		
		function get_user_by_metadata( $metakey, $metavalue ) {
		
			$users = get_users( array( 'meta_key' => $metakey, 'meta_value' => $metavalue ) );
			
			if ( empty( $users ) ) { return FALSE; }
			
			foreach ($users as $user) {
				
				$id = $user->ID;
				
		        return $id;
		        
		    } // End of $users foreach
			    
		} // End of get_user_by_metadata()
 
/**
 * Convert a string into an array
 *
 * @param	string	$string String of data to convert
 *
 * @return	mixed	If the param is a string, returns an array, otherwise returns FALSE
 */		
		public function make_array( $string ) {
		
			if ( empty( $string ) || !is_string( $string ) ) { return FALSE; }
		
			return ( substr( $string, -1 ) == ',' ? explode( ',', $string, -1 ) : explode( ',', $string ) );
			
		} // End of make_array()
 
/**
 * Check for the existence of a meta data item
 *
 * Call get_users to find any students with the meta data passed into the function
 * If the list of users is empty, it returns FALSE, otherwise it return TRUE
 *
 * @uses	get_users
 *
 * @param	array	$params		$meta = the meta key to check, $data = the data to check
 *
 * @return	bool	TRUE if it finds a student, FALSE if not
 */			
		public function meta_exists( $params ) {
		
			extract( $params );
		
			$users 	= get_users( array( 'meta_key' => $meta, 'meta_value' => $data ) );
			$exists = ( empty( $users ) ? FALSE : TRUE );
			
			return $exists;
			
		} // End of meta_exists()

/**
 * Check an array for any empty values
 *
 * Call the function like this:
 *
 * $this->none_empty( array( $item1, $item2, $item3, etc... ) )
 *
 * The output of this function is boolean, so you can use it in an if statement to check multiple values
 * instead of a long string of: !empty( $item1 ) && !empty( $item2 ) && etc..
 *
 * @param	array	$array		An array of data to check
 *
 * @return	bool	TRUE if all items are not empty, FALSE if any are empty
 */		
		function none_empty( $array ) {
		
			foreach ( $array as $item ) {
				
				if ( !empty( $item ) ) {
					
					continue;
					
				} else {
					
					return FALSE;
					
				}
				
			} // End of $array foreach
			
			return TRUE;
			
		} // End of none_empty()
		
/**
 * Converts number to its ordinal English form. Copyright (c) 2002-2006, Akelos Media, S.L. http://www.akelos.org
 *
 * This method converts 13 to 13th, 2 to 2nd ...
 *
 * @access	public
 * @static
 * @param	int		$number    Number to get its ordinal value
 * @return	string	Ordinal representation of given string.
 */
		public function ordinalize( $number ) {
    
	        if ( in_array( ( $number % 100 ),range( 11,13 ) ) ) {
	        
	            return $number.'th';
	        
	        } else {
	            
	            switch ( ( $number % 10 ) ) {
	            
	                case 1 	: return $number.'st'; break;
	                case 2 	: return $number.'nd'; break;
	                case 3 	: return $number.'rd'; break;
	                default : return $number.'th'; break;
	                
	            } // End of switch
	            
	        } // End of in_array check
        
		} // End of ordinalize
		
/**
 * Pluralizes English nouns. Copyright (c) 2002-2006, Akelos Media, S.L. http://www.akelos.org
 *
 * @access	public
 * @static
 * @param	string	$word    English noun to pluralize
 * @return	string	Plural noun
 */
 		public function pluralize( $word ) {
    
	        $plural['/(quiz)$/i'] 				= '1zes';
	        $plural['/^(ox)$/i'] 				= '1en';
	        $plural['/([m|l])ouse$/i'] 			= '1ice';
	        $plural['/(matr|vert|ind)ix|ex$/i'] = '1ices';
	        $plural['/(x|ch|ss|sh)$/i'] 		= '1es';
	        $plural['/([^aeiouy]|qu)ies$/i'] 	= '1y';
	        $plural['/([^aeiouy]|qu)y$/i'] 		= '1ies';
	        $plural['/(hive)$/i'] 				= '1s';
	        $plural['/(?:([^f])fe|([lr])f)$/i'] = '12ves';
	        $plural['/sis$/i'] 					= 'ses';
	        $plural['/([ti])um$/i'] 			= '1a';
	        $plural['/(buffal|tomat)o$/i'] 		= '1oes';
	        $plural['/(bu)s$/i'] 				= '1ses';
	        $plural['/(alias|status)/i'] 		= '1es';
	        $plural['/(octop|vir)us$/i'] 		= '1i';
	        $plural['/(ax|test)is$/i'] 			= '1es';
	        $plural['/s$/i'] 					= 's';
	        $plural['/$/'] 						= 's';
	
	        $uncountable = array( 'equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep' );
	
	        $irregular['person'] 	= 'people';
	        $irregular['man'] 		= 'men';
	        $irregular['child'] 	= 'children';
	        $irregular['sex'] 		= 'sexes';
	        $irregular['move'] 		= 'moves';
	
	        $lowercased_word = strtolower( $word );
	
	        foreach ( $uncountable as $cannot ){
	        
	            if( substr( $lowercased_word,( -1*strlen( $cannot ) ) ) == $cannot ) {
	            
	                return $word;
	            
	            } // End of uncountable check
	            
	        } // End of $uncountable foreach
	
	        foreach ( $irregular as $plural=> $singular ) {
	        
	            if ( preg_match( '/('.$plural.')$/i', $word, $arr ) ) {
	            
	                return preg_replace( '/('.$plural.')$/i', substr( $arr[0],0,1 ).substr( $singular,1 ), $word );
	            
	            } // End of $_plural check
	        
	        } // End of $irregular foreach
	
	        foreach ( $plural as $rule => $replacement ) {
	        
	            if ( preg_match( $rule, $word ) ) {
	        
	                return preg_replace( $rule, $replacement, $word );
	        
	            } // End of $rule check
	        
	        } // End of $plural foreach
	        
	        return false;
    
 		} // End of pluralize()

/**
 * Display an array in a nice format
 *
 * @param	array	The array you wish to view
 */			
		public function print_array( $array ) {

		  echo '<pre>';
		  
		  print_r( $array );
		  
		  echo '</pre>';
		
		} // End of print_array()
		
/**
 * Validates a phone number
 * 
 * @since	0.1
 *
 * @link	http://jrtashjian.com/2009/03/code-snippet-validate-a-phone-number/
 * 
 * @return	mixed	$phone | FALSE		Returns the valid phone number, FALSE if not
 */
		function sanitize_phone( $phone ) {
		
			if ( empty( $phone ) ) { return FALSE; }
			
			if( preg_match( '/^[+]?([0-9]?)[(|s|-|.]?([0-9]{3})[)|s|-|.]*([0-9]{3})[s|-|.]*([0-9]{4})$/', $phone ) ) {
			
				return trim( $phone );
			
			} // End of $phone validation
			
			return FALSE;
			
		} // End of sanitize_phone()		
		
/**
 * Singularizes English nouns. Copyright (c) 2002-2006, Akelos Media, S.L. http://www.akelos.org
 *
 * @access	public
 *
 * @static
 *
 * @param	string	$word    English noun to singularize
 *
 * @return	string	Singular noun.
 */
		public function singularize( $word ) {

	        $singular['/(quiz)zes$/i'] 														= '\1';
	        $singular['/(matr)ices$/i'] 													= '\1ix';
	        $singular['/(vert|ind)ices$/i'] 												= '\1ex';
	        $singular['/^(ox)en/i'] 														= '\1';
	        $singular['/(alias|status)es$/i'] 												= '\1';
	        $singular['/([octop|vir])i$/i'] 												= '\1us';
	        $singular['/(cris|ax|test)es$/i']												= '\1is';
	        $singular['/(shoe)s$/i'] 														= '\1';
	        $singular['/(o)es$/i'] 															= '\1';
	        $singular['/(bus)es$/i'] 														= '\1';
	        $singular['/([m|l])ice$/i'] 													= '\1ouse';
	        $singular['/(x|ch|ss|sh)es$/i'] 												= '\1';
	        $singular['/(m)ovies$/i'] 														= '\1ovie';
	        $singular['/(s)eries$/i'] 														= '\1eries';
	        $singular['/([^aeiouy]|qu)ies$/i'] 												= '\1y';
	        $singular['/([lr])ves$/i'] 														= '\1f';
	        $singular['/(tive)s$/i'] 														= '\1';
	        $singular['/(hive)s$/i'] 														= '\1';
	        $singular['/([^f])ves$/i'] 														= '\1fe';
	        $singular['/(^analy)ses$/i'] 													= '\1sis';
	        $singular['/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'] 	= '\1\2sis';
	        $singular['/([ti])a$/i'] 														= '\1um';
	        $singular['/(n)ews$/i'] 														= '\1ews';
	        $singular['/s$/i'] 																= '';
	
	        $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep');
	
	        $irregular['person'] 	= 'people';
	        $irregular['man'] 		= 'men';
	        $irregular['child'] 	= 'children';
	        $irregular['sex'] 		= 'sexes';
	        $irregular['move'] 		= 'moves';
	
	        $lowercased_word = strtolower( $word );
	
	        foreach ( $uncountable as $cannot ){
	        
	            if( substr( $lowercased_word,( -1*strlen( $cannot ) ) ) == $cannot ) {
	        
	                return $word;
	        
	            } // End of $_uncountable check
	        
	        } // End of $uncountable foreach
	
	        foreach ( $irregular as $plural=> $singular ) {
	        
	            if ( preg_match( '/('.$singular.')$/i', $word, $arr ) ) {
	        
	                return preg_replace( '/('.$singular.')$/i', substr( $arr[0],0,1 ).substr( $plural,1 ), $word );
	        
	            } // End of $_singular check
	        
	        } // End of $irregular foreach
	
	        foreach ( $singular as $rule => $replacement ) {
	        
	            if ( preg_match( $rule, $word ) ) {
	        
	                return preg_replace( $rule, $replacement, $word );
	        
	            } // End of $rule check
	        
	        } // End of singular foreach
	
	        return $word;
        
		} // End of singularize()

/**
 * Sort by last name, then by first name
 *
 * It sorts the found users array by last name.  If there is
 * more than one of that last name, it sorts by the first name so the report is
 * sorted by last name, then first name when displayed.
 *
 * @param	array	$data		The array of data to sort
 * @param	array	$field1		The first field to sort by
 * @param	array	$field2		The second field to sort by
 *
 * @return	array	$return		Returns the sorted names, by the first field, then second field (if needed)
 */			
		public function sort_by_name( $data, $field1, $field2 ) {

		    usort( $data, array( new nameSorter( $field1, $field2 ), 'call' ) );
		
		} // End of sort_by_name()
		
/**
 * Get user by their role
 *
 * Get user by their role, sorted by display name in ascending order. Includes all user meta.
 *
 * @since 	0.1
 *
 * @uses 	WP_User_Query
 * @uses	get_results
 * 
 * @params 	array			$roles		An array of the user roles of the users you want to find
 * 
 * @return	array | bool	$userlist	Either an array of user objects or false
 */
		function users_by_role( $roles ) {
		
			$userlist = array();

			foreach ( $roles as $role ) {
				
				$args['role'] 		= $role;
				$args['orderby'] 	= 'display_name';
				$args['order']		= 'ASC';
				$args['fields']		= 'all_with_meta';
				
				$userquery 			= new WP_User_Query( $args );
				$users 				= $userquery->get_results();
				
				if ( $users ) { $userlist = array_merge( $userlist, $users ); }
				
			} // End of $types foreach
			
			$userlist = ( !empty( $userlist ) ? $userlist : false );
			
			return $userlist;
				
		} // End of users_by_role()			
		
/**
 * Get the BuddyPress XProfile field group ID by the group's name
 *
 * Check the WP and BP databases. 
 * 
 * This function will return 0 under these conditions:
 *  If profile->table_name_fields is empty
 *  If the $field_name param is not set
 *  If the database search returns nothing
 * 
 * Otherwise, the BuddyPress xProfile tables are searched for the name of the 
 * field group name entered as the param.
 *
 * @uses	get_var
 *
 * @param	string	$group_name The name of the field group to find
 *
 * @return	bool | int		The field group's ID - FALSE if the group doesn't exist
 */	
		function xprofile_get_group_id_from_name( $group_name ) {
			
			global $wpdb, $bp;

			if ( empty( $bp->profile->table_name_fields ) || !isset( $group_name ) ) { return FALSE; }

			$return = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$bp->profile->table_name_groups} WHERE name = %s", $group_name ) );
			
			return ( empty( $return ) ? FALSE : $return );
		
		} // End of get_xprofile_group_id_from_name()



/**** Fields Creation functions *****/



// http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-1-intro-and-basic-fields/
// http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-2-advanced-fields/
// http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-3-extra-fields/
// http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-4-using-the-data/

/**
 * Builds a form based on the $params array
 *
 * The params array contains multiple arrays with the info for each field needed for the form
 * The details for those arrays can be found in each field's function below.
 * The only difference is each field will need a type (text, checkbox, etc) to make sure
 * the correct function gets called.
 * 
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the form fields
 *
 * @return	mixed	A properly formatted HTML table with fields specified by the params
 */	
 	
 	function build_form( $form, $fields ) {
 	
 		extract( $form );
 	
	 	$output = ( !empty( $nonce ) ? wp_nonce_field( basename( __FILE__ ), $nonce ) : '' );
	 	$output .= '<table class="' . $tableclass . '">';
	 	
	 	foreach ( $fields as $field ) {
	 	
	 		extract( $field );
	 	
	 		if ( $type == 'price' || $type == 'time_fields' ) {

		 		$args = $field;

		 	} else {
		 	
		 		$checks = array( 'blank', 'check', 'class', 'desc', 'fieldtype', 'grouptype', 'id', 'inputtype', 'selections' );
		 	
		 		foreach ( $checks as $check ) {
			 		
		 			$args[$check] = ( !empty( $field[$check] ) ? $field[$check] : '' );
			 		
		 		} // End of $param foreach
		 		
		 		if ( $type == 'post' ) {
	 		
			 		global $post;
			 		
			 		$args['value'] = get_post_meta( $post->ID, $id, TRUE );
			 		
		 		} elseif ( is_object( $type ) ) {
		 		
			 		$args['value'] = $type->$id;
			 		
		 		} else {
			 		
			 		$args['value'] = $value;
			 		
		 		} // End of ID check
		 	
			} // End of $type check
			
			$output .= '<tr><th><label for="' . $id . '">' . $label . '</label></th>';
		 	$output .= '<td>' . $this->$type( $args ) . '</td></tr>';
		 	
	 	} // End of $params foreach
	 	
	 	$output .= '</table>';
	 	
	 	return $output;
	 	
 	} // End of build_form()
		
/**
 * Creates a single, or set of, repeatable field(s)
 *
 * Creates a single, or set of, repeatable field(s)
 * 
 * The params are in three groups - one for the the group, one for currencies() and one for input_field():
 *  class - the class used for the group of fields
 * 	desc - description used for the description span
 *  include - an array with fields you want included (hours, minutes, ampm, timezones)
 *	label - the label to use in front of the field
 *
 * @since 0.1
 * 
 * @param array $params An array of the data for the states dropmenu field
 *
 * @return mixed a properly formatted HTML dropmenu of US states and territories with optional label and description
 */		
		function repeatable( $params ) {
		
			extract( $params );
			
			$output = ( !empty( $label ) ? '<label for="' . $id . '">' . $label . '</label>' : '' );
			$output .= '<a class="repeatable-add button" href="#">+</a>';
			$output .= '<ul id="' . $id . '-repeatable" class="custom_repeatable' . $class . '">';
			
			$i = 0;
			
			if ( $meta ) {
			  
		        foreach( $meta as $row ) {
		          
		            $output .= '<li><span class="sort hndle">|||</span>';
		    
	   				foreach( $fields as $field ) {
	   				
			            $output .= '<input type="text" name="writer' . '[' . $i . ']" id="writer" value="' . $row . '[' . $i . ']" size="30" />';
			            $output .= '<input type="text" name="publisher' . '[' . $i . ']" id="publisher" value="' . $row . '[' . $i . ']" size="30" />';
			            $output .= '<input type="text" name="pro' . '[' . $i . ']" id="pro" value="' . $row . '[' . $i . ']" size="30" />';

				        
				    } // End of $fields foreach
				    
				    $output .= '<a class="repeatable-remove button" href="#">-</a></li>';
					
		            $i++;  
		    
		        }  
		    
		    } else {
		    
		    	$output .= '<li><span class="sort hndle">|||</span>';
		    
   				foreach( $fields as $key => $field ) {
   				
   					$field['id'] = $key . '[' . $i . ']';
   				
			    	$output .= '<input type="text" name="writer' . '[' . $i . ']" id="writer" value="" size="30" />';
			        $output .= '<input type="text" name="publisher' . '[' . $i . ']" id="publisher" value="" size="30" />';
			        $output .= '<input type="text" name="pro' . '[' . $i . ']" id="pro" value="" size="30" />';
		            
			    } // End of $fields foreach
			    
			    $output .= '<a class="repeatable-remove button" href="#">-</a></li>';
		    
		    } // End of 
			
			
			
			
			foreach( $fields as $field ) {
			
				$output .= '<li><span class="sort hndle">|||</span>';
				$output .= '<input type="text" name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '" value="' . $row . '" size="30" />';
				$output .= '<a class="repeatable-remove button" href="#">-</a></li>';
				
				$i++;
				
			} // End of $meta foreach
				
			$output .= ( !empty( $desc ) ? '</ul><span class="description">' . $desc . '</span>' : '' );
			
			return $output;
			
		} // End of repeatable()	



/***** Special Fields *****/



/**
 * Creates a dropmenu for AM or PM
 *
 * Creates a dropmenu for AM or PM
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *	value - used in the selected function
 *
 * How to use directly:
 * 
 * $ampm_args['class'] 	= '';
 * $ampm_args['desc'] 	= '';
 * $ampm_args['id'] 	= '';
 * $ampm_args['label'] 	= '';
 * $ampm_args['value'] 	= '';
 * 
 * echo Slushman_Toolkit::ampm( $ampm_args );
 * 
 * 
 *
 * How to use with build_form():
 * 
 * $forms_args[0]['type'] 	= 'ampm';
 * $forms_args[0]['class'] 	= '';
 * $forms_args[0]['desc'] 	= '';
 * $forms_args[0]['id'] 	= '';
 * $forms_args[0]['label'] 	= '';
 * $forms_args[0]['value'] 	= '';
 * 
 *
 *
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the minutes dropmenu field
 *
 * @return	mixed	A properly formatted HTML dropmenu of minutes with optional label and description
 */		
		function ampm( $params ) {
			
			extract( $params );

			$input_group['class'] 		= 'ampm_dropmenu ' . $class;
			$input_group['desc'] 		= ( !empty( $desc ) ? $desc : '' );
			$input_group['id'] 			= $id;
			$input_group['grouptype'] 	= 'dropmenu';
			$input_group['label'] 		= ( !empty( $label ) ? $label : '' );
			$input_group['value'] 		= $value;
			
			$i = 0;
			
			$selections = array( 'AM', 'PM' );
						
			foreach ( $selections as $selection ) {
							
					$input_group['selections'][$i]['label'] = $selection;
					$input_group['selections'][$i]['value'] = $selection;
				
					$i++;
			
			} // End of $selections foreach
			
			return $this->input_group( $input_group );

		} // End of ampm()	

/**
 * Creates a dropmenu of countries based on the params
 *
 * Creates a dropmenu of countries based on the params
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 *  first - which country do you want to appear first on the list
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *	value - used in the selected function
 *
 * How to use:
 * 
 * $countries_args['class'] = '';
 * $countries_args['desc'] 	= '';
 * $countries_args['first']	= '';
 * $countries_args['id'] 	= '';
 * $countries_args['label'] = '';
 * $countries_args['value'] = '';
 * 
 * echo Slushman_Toolkit::countries( $countries_args );
 * 
 * 
 *
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the dropmenu
 *
 * @return	mixed	A properly formatted HTML dropmenu of countries with optional label and description
 */		
		function countries( $params ) {
		
			$countries = array( 'AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria','AS' => 'American Samoa','AD' => 'Andorra','AO' => 'Angola','AI' => 'Anguilla','AQ' => 'Antarctica','AG' => 'Antigua And Barbuda','AR' => 'Argentina','AM' => 'Armenia','AW' => 'Aruba','AU' => 'Australia','AT' => 'Austria','AZ' => 'Azerbaijan','BS' => 'Bahamas','BH' => 'Bahrain','BD' => 'Bangladesh','BB' => 'Barbados','BY' => 'Belarus','BE' => 'Belgium','BZ' => 'Belize','BJ' => 'Benin','BM' => 'Bermuda','BT' => 'Bhutan','BO' => 'Bolivia','BA' => 'Bosnia And Herzegowina','BW' => 'Botswana','BV' => 'Bouvet Island','BR' => 'Brazil','IO' => 'British Indian Ocean Territory','BN' => 'Brunei Darussalam','BG' => 'Bulgaria','BF' => 'Burkina Faso','BI' => 'Burundi','KH' => 'Cambodia','CM' => 'Cameroon','CA' => 'Canada','CV' => 'Cape Verde','KY' => 'Cayman Islands','CF' => 'Central African Republic','TD' => 'Chad','CL' => 'Chile','CN' => 'China','CX' => 'Christmas Island','CC' => 'Cocos (Keeling) Islands','CO' => 'Colombia','KM' => 'Comoros','CG' => 'Congo','CD' => 'Congo, The Democratic Republic Of The','CK' => 'Cook Islands','CR' => 'Costa Rica','CI' => 'Cote D\'Ivoire','HR' => 'Croatia (Local Name: Hrvatska)','CU' => 'Cuba','CY' => 'Cyprus','CZ' => 'Czech Republic','DK' => 'Denmark','DJ' => 'Djibouti','DM' => 'Dominica','DO' => 'Dominican Republic','TP' => 'East Timor','EC' => 'Ecuador','EG' => 'Egypt','SV' => 'El Salvador','GQ' => 'Equatorial Guinea','ER' => 'Eritrea','EE' => 'Estonia','ET' => 'Ethiopia','FK' => 'Falkland Islands (Malvinas)','FO' => 'Faroe Islands','FJ' => 'Fiji','FI' => 'Finland','FR' => 'France','FX' => 'France, Metropolitan','GF' => 'French Guiana','PF' => 'French Polynesia','TF' => 'French Southern Territories','GA' => 'Gabon','GM' => 'Gambia','GE' => 'Georgia','DE' => 'Germany','GH' => 'Ghana','GI' => 'Gibraltar','GB' => 'United Kingdom','GR' => 'Greece','GL' => 'Greenland','GD' => 'Grenada','GP' => 'Guadeloupe','GU' => 'Guam','GT' => 'Guatemala','GN' => 'Guinea','GW' => 'Guinea-Bissau','GY' => 'Guyana','HT' => 'Haiti','HM' => 'Heard And Mc Donald Islands','VA' => 'Holy See (Vatican City State)','HN' => 'Honduras','HK' => 'Hong Kong','HU' => 'Hungary','IS' => 'Iceland','IN' => 'India','ID' => 'Indonesia','IR' => 'Iran (Islamic Republic Of)','IQ' => 'Iraq','IE' => 'Ireland','IL' => 'Israel','IT' => 'Italy','JM' => 'Jamaica','JP' => 'Japan','JO' => 'Jordan','KZ' => 'Kazakhstan','KE' => 'Kenya','KI' => 'Kiribati','KP' => 'Korea, Democratic People\'s Republic Of','KR' => 'Korea, Republic Of','KW' => 'Kuwait','KG' => 'Kyrgyzstan','LA' => 'Lao People\'s Democratic Republic','LV' => 'Latvia','LB' => 'Lebanon','LS' => 'Lesotho','LR' => 'Liberia','LY' => 'Libyan Arab Jamahiriya','LI' => 'Liechtenstein','LT' => 'Lithuania','LU' => 'Luxembourg','MO' => 'Macau','MK' => 'Macedonia, Former Yugoslav Republic Of','MG' => 'Madagascar','MW' => 'Malawi','MY' => 'Malaysia','MV' => 'Maldives','ML' => 'Mali','MT' => 'Malta','MH' => 'Marshall Islands','MQ' => 'Martinique','MR' => 'Mauritania','MU' => 'Mauritius','YT' => 'Mayotte','MX' => 'Mexico','FM' => 'Micronesia, Federated States Of','MD' => 'Moldova, Republic Of','MC' => 'Monaco','MN' => 'Mongolia','MS' => 'Montserrat','MA' => 'Morocco','MZ' => 'Mozambique','MM' => 'Myanmar','NA' => 'Namibia','NR' => 'Nauru','NP' => 'Nepal','NL' => 'Netherlands','AN' => 'Netherlands Antilles','NC' => 'New Caledonia','NZ' => 'New Zealand','NI' => 'Nicaragua','NE' => 'Niger','NG' => 'Nigeria','NU' => 'Niue','NF' => 'Norfolk Island','MP' => 'Northern Mariana Islands','NO' => 'Norway','OM' => 'Oman','PK' => 'Pakistan','PW' => 'Palau','PA' => 'Panama','PG' => 'Papua New Guinea','PY' => 'Paraguay','PE' => 'Peru','PH' => 'Philippines','PN' => 'Pitcairn','PL' => 'Poland','PT' => 'Portugal','PR' => 'Puerto Rico','QA' => 'Qatar','RE' => 'Reunion','RO' => 'Romania','RU' => 'Russian Federation','RW' => 'Rwanda','KN' => 'Saint Kitts And Nevis','LC' => 'Saint Lucia','VC' => 'Saint Vincent And The Grenadines','WS' => 'Samoa','SM' => 'San Marino','ST' => 'Sao Tome And Principe','SA' => 'Saudi Arabia','SN' => 'Senegal','SC' => 'Seychelles','SL' => 'Sierra Leone','SG' => 'Singapore','SK' => 'Slovakia (Slovak Republic)','SI' => 'Slovenia','SB' => 'Solomon Islands','SO' => 'Somalia','ZA' => 'South Africa','GS' => 'South Georgia, South Sandwich Islands','ES' => 'Spain','LK' => 'Sri Lanka','SH' => 'St. Helena','PM' => 'St. Pierre And Miquelon','SD' => 'Sudan','SR' => 'Suriname','SJ' => 'Svalbard And Jan Mayen Islands','SZ' => 'Swaziland','SE' => 'Sweden','CH' => 'Switzerland','SY' => 'Syrian Arab Republic','TW' => 'Taiwan','TJ' => 'Tajikistan','TZ' => 'Tanzania, United Republic Of','TH' => 'Thailand','TG' => 'Togo','TK' => 'Tokelau','TO' => 'Tonga','TT' => 'Trinidad And Tobago','TN' => 'Tunisia','TR' => 'Turkey','TM' => 'Turkmenistan','TC' => 'Turks And Caicos Islands','TV' => 'Tuvalu','UG' => 'Uganda','UA' => 'Ukraine','AE' => 'United Arab Emirates','UM' => 'United States Minor Outlying Islands','US' => 'United States','UY' => 'Uruguay','UZ' => 'Uzbekistan','VU' => 'Vanuatu','VE' => 'Venezuela','VN' => 'Viet Nam','VG' => 'Virgin Islands (British)','VI' => 'Virgin Islands (U.S.)','WF' => 'Wallis And Futuna Islands','EH' => 'Western Sahara','YE' => 'Yemen','YU' => 'Yugoslavia','ZM' => 'Zambia','ZW' => 'Zimbabwe'
);
			
			extract( $params );
			
			$input_group['blank']		= 'Select country';
			$input_group['class'] 		= 'country_dropmenu ' . $class;
			$input_group['desc'] 		= ( !empty( $desc ) ? $desc : '' );
			$input_group['id'] 			= $id;
			$input_group['grouptype'] 	= 'dropmenu';
			$input_group['label'] 		= ( !empty( $label ) ? $label : '' );
			$input_group['value'] 		= ( !empty( $value) ? $value : '' );
			
			if ( !empty( $first ) ) {
			
				$input_group['selections'][0]['label'] = $countries[$first];
				$input_group['selections'][0]['value'] = $first;
				
				unset( $countries[$first] );
				
				$i = 1;
				
			} else {
				
				$i = 0;
				
			} // End of $first check
			
			foreach ( $countries as $abb => $country ) {
							
					$input_group['selections'][$i]['label'] = $country;
					$input_group['selections'][$i]['value'] = $abb;
				
					$i++;
			
			} // End of $selections foreach
			
			return $this->input_group( $input_group );
			
		} // End of countries()
	
/**
 * Creates a dropmenu of world currency symbols
 *
 * Creates a dropmenu of world currency symbols
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 *  first - which currency symbol do you want to appear first on the list
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  show - if you want descriptions for the currency selection or not (true for yes, false for no)
 *	value - used in the selected function
 *
 * How to use:
 * 
 * $currency_args['class'] 	= '';
 * $currency_args['desc'] 	= '';
 * $currency_args['first'] 	= '';
 * $currency_args['id'] 	= '';
 * $currency_args['label'] 	= '';
 * $currency_args['show'] 	= '';
 * $currency_args['value'] 	= '';
 * 
 * echo Slushman_Toolkit::currencies( $currency_args );
 * 
 * 
 *
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the currencies dropmenu field
 *
 * @return	mixed	A properly formatted HTML dropmenu of world currency symbols with optional label and description
 */		
		function currencies( $params ) {
			
			$currencies = array( 'Afghani' => '؋', 'Ariary' => 'Ar', 'Austral' => '₳', 'Baht' => '฿', 'Balboa' => 'B/.', 'Bharaini Dinar' => 'ب.د', 'Birr' => 'Br', 'Boliviano' => 'Bs.', 'Cedi' => '₵', 'Colon' => '₡', 'Cordoba' => 'C$', 'Cruzeiro' => '₢', 'Dalasi' => 'D', 'Denar' => 'ден', 'Dinar' => 'د.ج', 'Dirham' => 'د.إ', 'Dobra' => 'Db', 'Dollar' => '$', 'Dong' => '₫', 'Drachma' => '₯', 'Dram' => 'դր.', 'Euro' => '€', 'Forint' => 'Ft', 'Franc' => '₣', 'Gourde' => 'G', 'Guarani' => '₲', 'Guider' => 'ƒ', 'Hryvnia' => '₴', 'Iraqi Dinar' => 'ع.د', 'Jordanian Dinar' => 'د.ا', 'Kip' => '₭', 'Koruna' => 'Kč', 'Krone' => 'kr', 'Kuna' => 'kn', 'Kuwaiti Dinar' => 'د.ك', 'Kwanza' => 'Kz', 'Kyat' => 'K', 'Lari' => 'ლ', 'Lats' => 'Ls', 'Lebanese Pound' => 'ل.ل', 'Leone' => 'Le', 'Leu' => 'L', 'Lira' => '₤', 'Litas' => 'Lt', 'Lybian Dinar' => 'ل.د', 'Malawian Kwacha' => 'MK', 'Manat' => 'm', 'Metical' => 'MT', 'Moroccan Dirham' => 'د.م.', 'Nafka' => 'Nfk', 'Naira' => '₦', 'New Sheqel' => '₪', 'Ngultrum' => 'Nu.', 'Nuevo Sol' => 'S/.', 'Oman Rial' => 'ر.ع.', 'Ouguiya' => 'UM', 'Paanga' => 'T$', 'Peseta' => '₧', 'Peso' => '₱', 'Pound' => '£', 'Pula' => 'P', 'Quetzal' => 'Q', 'Rand' => 'R', 'Real' => 'R$', 'Rial' => '﷼', 'Riel' => '៛', 'Ringgit' => 'RM', 'Riyal' => 'ر.ق', 'Romanian Leu' => 'RON', 'Ruble' => 'p.', 'Rufiyaa' => 'ރ.', 'Rupee' => '₨', 'Rupiah' => 'Rp', 'Saudi Riyal' => 'ر.س', 'Serbian Dinar' => 'RSD', 'Shilling' => 'Sh', 'Som' => 'лв', 'Somoni' => 'ЅМ', 'Tala' => 'T', 'Taka' => '৳', 'Tenge' => 'Т', 'Tugrik' => '₮', 'Tunisian Dinar' => 'د.ت', 'Vatu' => 'Vt', 'Venezuelan Bolivar' => 'Bs F', 'Won' => '₩', 'Yen' => '¥', 'Zambian Kwacha' => 'ZK', 'Zloty' => 'zł' );

			extract( $params );
			
			$input_group['blank']		= 'Select currency';
			$input_group['class'] 		= 'currency_dropmenu ' . $class;
			$input_group['desc'] 		= ( !empty( $desc ) ? $desc : '' );
			$input_group['id'] 			= $id;
			$input_group['grouptype'] 	= 'dropmenu';
			$input_group['label'] 		= ( !empty( $label ) ? $label : '' );
			$input_group['value'] 		= $value;
			
			if ( !empty( $first ) ) {
			
				$display = ( $show == TRUE ? $currencies[$first] . ' - ' . $first : $currencies[$first] );
			
				$input_group['selections'][0]['label'] = $display;
				$input_group['selections'][0]['value'] = $first;
				
				unset( $currencies[$first] );
				
				$i = 1;
				
			} else {
				
				$i = 0;
				
			} // End of $first check
			
			foreach ( $currencies as $name => $symbol ) {
			
				$display = ( $show == TRUE ? $symbol . ' - ' . $name : $symbol );
							
				$input_group['selections'][$i]['label'] = $display;
				$input_group['selections'][$i]['value'] = $name;
			
				$i++;
		
			} // End of $selections foreach
			
			return $this->input_group( $input_group );
			
		} // End of currencies()
		
/**
 * Creates a date picker field
 *
 * Creates a date picker field with the jQuery datepicker
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *	value - used in the checked function
 *
 * How to use:
 * 
 * $datepicker_args['class'] 		= '';
 * $datepicker_args['desc'] 	= '';
 * $datepicker_args['id'] 			= '';
 * $datepicker_args['label'] 		= '';
 * $datepicker_args['value'] 		= '';
 * 
 * echo Slushman_Toolkit::date_picker( $datepicker_args );
 * 
 * 
 * 
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the date picker field
 *
 * @return	mixed	A properly formatted HTML date field with jQuery datepicker and optional label and description
 */	
		function date_picker( $params ) {
		
			extract( $params );
			
			$input_args['class'] 		= 'toolkit_date_picker ' . $class;
			$input_args['desc'] 		= ( !empty( $desc ) ? $desc : '' );
			$input_args['id'] 			= $id;
			$input_args['inputtype']	= 'text';
			$input_args['label'] 		= ( !empty( $label ) ? $label : '' );
			$input_args['value'] 		= ( !empty( $value ) ? $value : '' );
			
			return $this->input_field( $input_args );
			
		} // End of date_picker()			
		
/**
 * Creates a dropmenu for hours
 *
 * Creates a dropmenu for hours
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  military - do you want a 12-hour or 24-hour clock, true for 24-hours, false for not
 *	value - used in the selected function
 *
 * How to use:
 * 
 * $hours_args['class'] 			= '';
 * $hours_args['desc'] 	= '';
 * $hours_args['id'] 			= '';
 * $hours_args['label'] 			= '';
 * $hours_args['military']			= '';
 * $hours_args['value'] 			= '';
 * 
 * echo Slushman_Toolkit::hours( $hours_args );
 * 
 * 
 *
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the hours dropmenu field
 *
 * @return	mixed	A properly formatted HTML dropmenu of hours with optional label and description
 */		
		function hours( $params ) {
			
			extract( $params );
			
			$class 	= ( !empty( $class ) ? ' class="currency_dropmenu ' . $class . '"' : '' );
			$showid = ( !empty( $id ) ? ' name="' . $id . '" id="' . $id .'"' : '' );
			
			$output = ( !empty( $label ) ? '<label for="' . $id . '">' . $label . '</label>' : '' );
			$output .= '<select' . $showid . $class . '>';
			$output .= '<option>' . __( 'Select hour' ) . '</option>';
			
			$start 	= ( $military ? 0 : 1 );
			$end 	= ( $military ? 24 : 12 );
			
			for ( $i = $start; $i < $end; $i++ ) {

				$output .= '<option value="' . $i . '" ' . selected( $value, $i, FALSE ) . '>' . $i . '</option>';
				
			} // End of for loop
			
/*
			if ( $military == true ) {
				
				for ( $i = 0; $i < 24; $i++ ) {

					$output .= '<option value="' . $i . '" ' . selected( $value, $i, FALSE ) . '>' . $i . '</option>';
					
				} // End of for loop
								
			} else {
				
				for ( $i = 1; $i <= 12; $i++ ) {
					
					$output .= '<option value="' . $i . '" ' . selected( $value, $i, FALSE ) . '>' . $i . '</option>';
					
				} // End of for loop
				
			} // End of $military check
*/
			
			$output .= '</select>';
			
			if ( !empty( $desc ) ) {
				
				$output .= '<br /><span class="description">' . $desc . '</span>';
				
			} // End of $desc empty check
			
			return $output;
			
		} // End of hours()
		
/**
 * Creates a dropmenu for minutes
 *
 * Creates a dropmenu for minutes
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  mintype - which type fo minute list would you prefer - quarters, tens, five
 *	value - used in the selected function
 *
 * How to use directly:
 * 
 * $minutes_args['class'] 			= '';
 * $minutes_args['desc'] 	= '';
 * $minutes_args['id'] 			= '';
 * $minutes_args['label'] 			= '';
 * $minutes_args['mintype']			= '';
 * $minutes_args['value'] 			= '';
 * 
 * echo Slushman_Toolkit::minutes( $minutes_args );
 * 
 * 
 *
 * How to use with build_form():
 * 
 * $forms_args[0]['type'] 			= 'minutes';
 * $forms_args[0]['class'] 			= '';
 * $forms_args[0]['desc'] 	= '';
 * $forms_args[0]['id'] 			= '';
 * $forms_args[0]['label'] 			= '';
 * $forms_args[0]['mintype']		= '';
 * $forms_args[0]['value'] 			= '';
 * 
 *
 *
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the minutes dropmenu field
 *
 * @return	mixed	A properly formatted HTML dropmenu of minutes with optional label and description
 */		
		function minutes( $params ) {
			
			extract( $params );
			
			$class 	= ( !empty( $class ) ? ' class="currency_dropmenu ' . $class . '"' : '' );
			$showid = ( !empty( $id ) ? ' name="' . $id . '" id="' . $id .'"' : '' );
			
			$output = ( !empty( $label ) ? '<label for="' . $id . '">' . $label . '</label>' : '' );
			$output .= '<select' . $showid . $class . '>';
			$output .= '<option>' . __( 'Select minute' ) . '</option>';
			
			switch ( $mintype ) {
				
				case ( 'quarters' ) : $end = 45; $interval = 15; break;
				case ( 'tens' ) 	: $end = 50; $interval = 10; break;
				case ( 'fives' ) 	: $end = 55; $interval = 5; break;
				default 			: $end = 59; $interval = 1; break;
				
			} // End of $mintype switch
			
			for ( $i = 00; $i <= $end; $i += $interval ) {
			
				$date = ( $i < 10 ? '0'. $i : $i );
		
				$output .= '<option value="' . $date . '"' . selected( $value, $date, FALSE ) . ' >' . $date . '</option>';
				
			} // End of for loop
			
			$output .= '</select>';
			
			if ( !empty( $desc ) ) {
				
				$output .= '<br /><span class="description">' . $desc . '</span>';
				
			} // End of $desc empty check
			
			return $output;
			
		} // End of minutes()			
		
/**
 * Creates a dropmenu of currencies and an input field for the price
 *
 * Creates a dropmenu of currencies and an input field for the price
 * 
 * The params are in three groups - one for the the group, one for currencies() and one for input_field():
 * 	desc - description used for the description span
 *	label - the label to use in front of the field
 *
 * How to use directly:
 * 
 * $price_args['desc'] 			= '';
 * $price_args['label'] 				= '';
 * $price_args['type']					= 'price';
 *
 * $price_args['currency']['class'] 	= '';
 * $price_args['currency']['id'] 		= '';
 * $price_args['currency']['value'] 	= ''; 
 *
 * $price_args['price']['class'] 		= '';
 * $price_args['price']['id'] 			= '';
 * $price_args['price']['inputtype']	= 'text';
 * $price_args['price']['value'] 		= ''; 
 *
 * echo Slushman_Toolkit::price( $price_args );
 * 
 * 
 * 
 * How to use with build_form():
 * 
 * $form_args[0]['desc'] 		= '';
 * $form_args[0]['label'] 				= '';
 * $form_args[0]['type']				= 'price';
 *
 * $form_args[0]['currency']['class'] 	= '';
 * $form_args[0]['currency']['id'] 		= '';
 * $form_args[0]['currency']['value'] 	= ''; 
 *
 * $form_args[0]['price']['class'] 		= '';
 * $form_args[0]['price']['id'] 		= '';
 * $form_args[0]['price']['inputtype']	= 'text';
 * $form_args[0]['price']['value'] 		= ''; 
 *
 *
 *
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the states dropmenu field
 *
 * @return	mixed	A properly formatted HTML dropmenu of US states and territories with optional label and description
 */			
		function price( $params ) {
						
			$output = ( !empty( $label ) ? '<label for="' . $id . '">' . $label . '</label>' : '' );
			$output .= $this->currencies( $params['currency'] );
			$output .= $this->input_field( $params['price'] );
			
			if ( !empty( $params['desc'] ) ) {
				
				$output .= '<br /><span class="description">' . $params['desc'] . '</span>';
				
			} // End of $desc empty check
			
			return $output;
			
		} // End of price()

/**
 * Creates a dropmenu of states and US territories
 *
 * Creates a dropmenu of states and US territories
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 *  first - which group appears first in the dropmenu (US States, Canadian provinces, etc)
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  only - show only this set of states
 *	value - used in the selected function
 *
 * How to use:
 * 
 * $states_args['class']	= '';
 * $states_args['desc'] 	= '';
 * $states_args['first'] 	= '';
 * $states_args['id'] 		= '';
 * $states_args['label'] 	= '';
 * $states_args['only'] 	= '';
 * $states_args['value'] 	= '';
 * 
 * echo Slushman_Toolkit::states( $states_args );
 * 
 * 
 *
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the states dropmenu field
 *
 * @return	mixed	A properly formatted HTML dropmenu of US states and territories with optional label and description
 */		
		function states( $params ) {
		
			// Look at adding optgroups to the input group dropmenu processing
			// and how to process deeper arrays
			// put each country's states in a separate array in the $selections array
			
			$selections['Austraila'] = array( 'AU-ACT' => 'Australian Capital Territory', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'HM' => 'Heard Island and McDonald Islands', 'AU-NSW' => 'New South Wales', 'NF' => 'Norfolk Island', 'AU-NT' => 'Northern Territory', 'AU-QLD' => 'Queensland', 'AU-SA' => 'South Australia', 'AU-TAS' => 'Tasmania', 'AU-VIC' => 'Victoria', 'AU-WA' => 'Western Australia' );
			
			$selections['Canada'] = array( 'CA-AB' => 'Alberta', 'CA-BC' => 'British Columbia', 'CA-MB' => 'Manitoba', 'CA-NB' => 'New Brunswick', 'CA-NL' => 'Newfoundland and Labrador', 'CA-NS' => 'Nova Scotia', 'CA-ON' => 'Ontario', 'CA-PE' => 'Prince Edward Island', 'CA-QC' => 'Quebec', 'CA-SK' => 'Saskatchewan' );
			
			$selections['China'] = array( 'CN-AH' => 'Anhui Province', 'CN-BJ' => 'Beijing Municipality', 'CN-CQ' => 'Chongqing Municipality', 'CN-FJ' => 'Fujian Province', 'CN-GD' => 'Guangdong Province', 'CN-GS' => 'Gansu Province', 'CN-GX' => 'Guangxi Zhuang Autonomous Region', 'CN-GZ' => 'Guizhou Province', 'CN-HA' => 'Henan Province', 'CN-HB' => 'Hubei Province', 'CN-HE' => 'Hebei Province', 'CN-HI' => 'Hainan Province', 'CN-HK' => 'Hong Kong Special Administrative Region', 'CN-HL' => 'Heilongjiang Province', 'CN-HN' => 'Hunan Province', 'CN-JL' => 'Jilin Province', 'CN-JS' => 'Jiangsu Province', 'CN-JX' => 'Jiangxi Province', 'CN-LN' => 'Liaoning Province', 'CN-MC' => 'Macau Special Administrative Region', 'CN-NM' => 'Inner Mongolia Autonomous Region', 'CN-NX' => 'Ningxia Hui Autonomous Region', 'CN-QH' => 'Qinghai Province', 'CN-SC' => 'Sichuan Province', 'CN-SD' => 'Shandong Province', 'CN-SH' => 'Shanghai Municipality', 'CN-SN' => 'Shaanxi Province', 'CN-SX' => 'Shanxi Province', 'CN-TJ' => 'Tianjin Municipality', 'CN-TW' => 'Taiwan Province', 'CN-XJ' => 'Xinjiang Uyghur Autonomous Region', 'CN-XZ' => 'Tibet Autonomous Region', 'CN-YN' => 'Yunnan Province', 'CN-ZJ' => 'Zhejiang Province' );
			
			$selections['France'] = array( 'FR-A' => 'Alsace', 'FR-B' => 'Aquitaine', 'FR-C' => 'Auvergne', 'FR-D' => 'Bourgogne', 'FR-E' => 'Bretagne', 'FR-F' => 'Centre', 'FR-G' => 'Champagne-Ardenne', 'FR-GF' => 'Guyane', 'FR-GP' => 'Guadeloupe', 'FR-H' => 'Corse', 'FR-I' => 'Franche-Comté', 'FR-J' => 'Île-de-France', 'FR-K' => 'Languedoc-Roussillon', 'FR-L' => 'Limousin', 'FR-M' => 'Lorraine', 'FR-MQ' => 'Martinique', 'FR-N' => 'Midi-Pyrénées', 'FR-O' => 'Nord-Pas-de-Calais', 'FR-P' => 'Basse-Normandie', 'FR-Q' => 'Haute-Normandie', 'FR-R' => 'Pays de la Loire', 'FR-RE' => 'La Réunion', 'FR-S' => 'Picardie', 'FR-T' => 'Poitou-Charentes', 'FR-U' => 'Provence-Alpes-Côte d\'Azur', 'FR-V' => 'Rhône-Alpes', 'FR-YT' => 'Mayotte' );
			
			$selections['Germany'] = array( 'DE-BW' => 'Baden-Württemberg', 'DE-BY' => 'Bavaria', 'DE-BE' => 'Berlin', 'DE-BB' => 'Brandenburg', 'DE-HB' => 'Bremen', 'DE-HH' => 'Hamburg', 'DE-HE' => 'Hesse', 'DE-MV' => 'Mecklenburg-Vorpommern', 'DE-NI' => 'Lower Saxony', 'DE-NW' => 'North Rhine-Westphalia', 'DE-RP' => 'Rhineland-Palatinate', 'DE-SL' => 'Saarland', 'DE-SN' => 'Saxony', 'DE-ST' => 'Saxony-Anhalt', 'DE-SH' => 'Schleswig-Holstein', 'DE-TH' => 'Thuringia' );
			
			$selections['India'] = array( 'IN-AN' => 'Andaman and Nicobar Islands', 'IN-AP' => 'Andhra Pradesh', 'IN-AR' => 'Arunachal Pradesh', 'IN-AS' => 'Assam', 'IN-BR' => 'Bihar', 'IN-CH' => 'Chandigarh', 'IN-CT' => 'Chhattisgarh', 'IN-DD' => 'Daman and Diu', 'IN-DL' => 'National Capital Territory of Delhi', 'IN-DN' => 'Dadra and Nagar Haveli', 'IN-GA' => 'Goa', 'IN-GJ' => 'Gujarat', 'IN-HP' => 'Himachal Pradesh', 'IN-HR' => 'Haryana', 'IN-JH' => 'Jharkhand', 'IN-JK' => 'Jammu and Kashmir', 'IN-KA' => 'Karnataka', 'IN-KL' => 'Kerala', 'IN-LD' => 'Lakshadweep', 'IN-MH' => 'Maharashtra', 'IN-ML' => 'Meghalaya', 'IN-MN' => 'Manipur', 'IN-MP' => 'Madhya Pradesh', 'IN-MZ' => 'Mizoram', 'IN-NL' => 'Nagaland', 'IN-OR' => 'Orissa', 'IN-PJ' => 'Punjab', 'IN-PY' => 'Pondicherry', 'IN-RJ' => 'Rajasthan', 'IN-SK' => 'Sikkim', 'IN-TN' => 'Tamil Nadu', 'IN-TR' => 'Tripura', 'IN-UP' => 'Uttar Pradesh', 'IN-UT' => 'Uttarakhand', 'IN-WB' => 'West Bengal' );
			
			$selections['Italy'] = array( 'IT-AG' => 'Agrigento','IT-AL' => 'Alessandria','IT-AN' => 'Ancona','IT-AO' => 'Aosta / Aoste','IT-AR' => 'Arezzo','IT-AP' => 'Ascoli Piceno','IT-AT' => 'Asti','IT-AV' => 'Avellino','IT-BA' => 'Bari','IT-BT' => 'Barletta-Andria-Trani','IT-BL' => 'Belluno','IT-BN' => 'Benevento','IT-BG' => 'Bergamo','IT-BI' => 'Biella','IT-BO' => 'Bologna','IT-BZ' => 'Bolzano / Bozen','IT-BS' => 'Brescia','IT-BR' => 'Brindisi','IT-CA' => 'Cagliari','IT-CL' => 'Caltanissetta','IT-CB' => 'Campobasso','IT-CI' => 'Carbonia-Iglesias','IT-CE' => 'Caserta','IT-CT' => 'Catania','IT-CZ' => 'Catanzaro','IT-CH' => 'Chieti','IT-CO' => 'Como','IT-CS' => 'Cosenza','IT-CR' => 'Cremona','IT-KR' => 'Crotone','IT-CN' => 'Cuneo','IT-EN' => 'Enna','IT-FM' => 'Fermo','IT-FE' => 'Ferrara','IT-FI' => 'Firenze','IT-FG' => 'Foggia','IT-FC' => 'Forlì-Cesena','IT-FR' => 'Frosinone','IT-GE' => 'Genova','IT-GO' => 'Gorizia','IT-GR' => 'Grosseto','IT-IM' => 'Imperia','IT-IS' => 'Isernia','IT-SP' => 'La Spezia','IT-AQ' => 'L\'Aquila','IT-LT' => 'Latina','IT-LE' => 'Lecce','IT-LC' => 'Lecco','IT-LI' => 'Livorno','IT-LO' => 'Lodi','IT-LU' => 'Lucca','IT-MC' => 'Macerata','IT-MN' => 'Mantova','IT-MS' => 'Massa-Carrara','IT-MT' => 'Matera','IT-VS' => 'Medio Campidano','IT-ME' => 'Messina','IT-MI' => 'Milano','IT-MO' => 'Modena','IT-MB' => 'Monza e Brianza','IT-NA' => 'Napoli','IT-NO' => 'Novara','IT-NU' => 'Nuoro','IT-OG' => 'Ogliastra','IT-OT' => 'Olbia-Tempio','IT-OR' => 'Oristano','IT-PD' => 'Padova','IT-PA' => 'Palermo','IT-PR' => 'Parma','IT-PV' => 'Pavia','IT-PG' => 'Perugia','IT-PU' => 'Pesaro e Urbino','IT-PE' => 'Pescara','IT-PC' => 'Piacenza','IT-PI' => 'Pisa','IT-PT' => 'Pistoia','IT-PN' => 'Pordenone','IT-PZ' => 'Potenza','IT-PO' => 'Prato','IT-RG' => 'Ragusa','IT-RA' => 'Ravenna','IT-RC' => 'Reggio Calabria','IT-RE' => 'Reggio Emilia','IT-RI' => 'Rieti','IT-RN' => 'Rimini','IT-RM' => 'Roma','IT-RO' => 'Rovigo','IT-SA' => 'Salerno','IT-SS' => 'Sassari','IT-SV' => 'Savona','IT-SI' => 'Siena','IT-SR' => 'Siracusa','IT-SO' => 'Sondrio','IT-TA' => 'Taranto','IT-TE' => 'Teramo','IT-TR' => 'Terni','IT-TO' => 'Torino','IT-TP' => 'Trapani','IT-TN' => 'Trento','IT-TV' => 'Treviso','IT-TS' => 'Trieste','IT-UD' => 'Udine','IT-VA' => 'Varese','IT-VE' => 'Venezia','IT-VB' => 'Verbano-Cusio-Ossola','IT-VC' => 'Vercelli','IT-VR' => 'Verona','IT-VV' => 'Vibo Valentia','IT-VI' => 'Vicenza','IT-VT' => 'Viterbo' );
			
			$selections['Japan'] = array( 'JP-23' => 'Aiti / Aichi', 'JP-05' => 'Akita', 'JP-02' => 'Aomori', 'JP-38' => 'Ehime', 'JP-21' => 'Gihu / Gifu', 'JP-10' => 'Gunma', 'JP-34' => 'Hirosima / Hiroshima', 'JP-01' => 'Hokkaidô / Hokkaido', 'JP-18' => 'Hukui / Fukui', 'JP-40' => 'Hukuoka / Fukuoka', 'JP-07' => 'Hukusima / Fukushima', 'JP-28' => 'Hyôgo / Hyogo', 'JP-08' => 'Ibaraki', 'JP-17' => 'Isikawa / Ishikawa', 'JP-03' => 'Iwate', 'JP-37' => 'Kagawa', 'JP-46' => 'Kagosima / Kagoshima', 'JP-14' => 'Kanagawa', 'JP-39' => 'Kôti / Kochi', 'JP-43' => 'Kumamoto', 'JP-26' => 'Kyôto / Kyoto', 'JP-24' => 'Mie', 'JP-04' => 'Miyagi', 'JP-45' => 'Miyazaki', 'JP-20' => 'Nagano', 'JP-42' => 'Nagasaki', 'JP-29' => 'Nara', 'JP-15' => 'Niigata', 'JP-44' => 'Ôita / Oita', 'JP-33' => 'Okayama', 'JP-47' => 'Okinawa', 'JP-27' => 'Ôsaka / Osaka', 'JP-41' => 'Saga', 'JP-11' => 'Saitama', 'JP-25' => 'Siga / Shiga', 'JP-32' => 'Simane / Shimane', 'JP-22' => 'Sizuoka / Shizuoka', 'JP-12' => 'Tiba / Chiba', 'JP-09' => 'Totigi / Tochigi', 'JP-36' => 'Tokusima / Tokushima', 'JP-13' => 'Tôkyô / Tokyo', 'JP-31' => 'Tottori', 'JP-16' => 'Toyama', 'JP-30' => 'Wakayama', 'JP-06' => 'Yamagata', 'JP-35' => 'Yamaguti / Yamaguchi', 'JP-19' => 'Yamanasi / Yamanashi' );
			
			$selections['Mexico'] = array( 'MX-AG' => 'Aguascalientes', 'MX-BC' => 'Baja California', 'MX-BS' => 'Baja California Sur', 'MX-CM' => 'Campeche', 'MX-CS' => 'Chiapas', 'MX-CH' => 'Chihuahua', 'MX-CO' => 'Coahuila', 'MX-CL' => 'Colima', 'MX-DF' => 'Federal District', 'MX-DG' => 'Durango', 'MX-GT' => 'Guanajuato', 'MX-GR' => 'Guerrero', 'MX-HG' => 'Hidalgo', 'MX-JA' => 'Jalisco', 'MX-ME' => 'Mexico State', 'MX-MI' => 'Michoacán', 'MX-MO' => 'Morelos', 'MX-NA' => 'Nayarit', 'MX-NL' => 'Nuevo León', 'MX-OA' => 'Oaxaca', 'MX-PB' => 'Puebla', 'MX-QE' => 'Querétaro', 'MX-QR' => 'Quintana Roo', 'MX-SL' => 'San Luis Potosí', 'MX-SI' => 'Sinaloa', 'MX-SO' => 'Sonora', 'MX-TB' => 'Tabasco', 'MX-TM' => 'Tamaulipas', 'MX-TL' => 'Tlaxcala', 'MX-VE' => 'Veracruz', 'MX-YU' => 'Yucatán', 'MX-ZA' => 'Zacatecas' );
			
			$selections['Russia'] = array( 'RU-AD' => 'Adygeya, Respublika', 'RU-AL' => 'Altay, Respublika', 'RU-BA' => 'Bashkortostan, Respublika', 'RU-BU' => 'Buryatiya, Respublika', 'RU-CE' => 'Chechenskaya Respublika', 'RU-CU' => 'Chuvashskaya Respublika', 'RU-DA' => 'Dagestan, Respublika', 'RU-IN' => 'Ingushetiya, Respublika', 'RU-KB' => 'Kabardino-Balkarskaya Respublika', 'RU-KL' => 'Kalmykiya, Respublika', 'RU-KC' => 'Karachayevo-Cherkesskaya Respublika', 'RU-KR' => 'Kareliya, Respublika', 'RU-KK' => 'Khakasiya, Respublika', 'RU-KO' => 'Komi, Respublika', 'RU-ME' => 'Mariy El, Respublika', 'RU-MO' => 'Mordoviya, Respublika', 'RU-SA' => 'Sakha, Respublika', 'RU-SE' => 'Severnaya Osetiya-Alaniya, Respublika', 'RU-TA' => 'Tatarstan, Respublika', 'RU-TY' => 'Tyva, Respublika', 'RU-UD' => 'Udmurtskaya Respublika', 'RU-ALT' => 'Altayskiy kray', 'RU-KAM' => 'Kamchatskiy kray', 'RU-KHA' => 'Khabarovskiy kray', 'RU-KDA' => 'Krasnodarskiy kray', 'RU-KYA' => 'Krasnoyarskiy kray', 'RU-PER' => 'Permskiy kray', 'RU-PRI' => 'Primorskiy kray', 'RU-STA' => 'Stavropol\'skiy kray', 'RU-ZAB' => 'Zabaykal\'skiy kray', 'RU-AMU' => 'Amurskaya oblast', 'RU-ARK' => 'Arkhangel\'skaya oblast', 'RU-AST' => 'Astrakhanskaya oblast', 'RU-BEL' => 'Belgorodskaya oblast', 'RU-BRY' => 'Bryanskaya oblast', 'RU-CHE' => 'Chelyabinskaya oblast', 'RU-IRK' => 'Irkutskaya oblast', 'RU-IVA' => 'Ivanovskaya oblast', 'RU-KGD' => 'Kaliningradskaya oblast', 'RU-KLU' => 'Kaluzhskaya oblast', 'RU-KEM' => 'Kemerovskaya oblast', 'RU-KIR' => 'Kirovskaya oblast', 'RU-KOS' => 'Kostromskaya oblast', 'RU-KGN' => 'Kurganskaya oblast', 'RU-KRS' => 'Kurskaya oblast', 'RU-LEN' => 'Leningradskaya oblast', 'RU-LIP' => 'Lipetskaya oblast', 'RU-MAG' => 'Magadanskaya oblast', 'RU-MOS' => 'Moskovskaya oblast', 'RU-MUR' => 'Murmanskaya oblast', 'RU-NIZ' => 'Nizhegorodskaya oblast', 'RU-NGR' => 'Novgorodskaya oblast', 'RU-NVS' => 'Novosibirskaya oblast', 'RU-OMS' => 'Omskaya oblast', 'RU-ORE' => 'Orenburgskaya oblast', 'RU-ORL' => 'Orlovskaya oblast', 'RU-PNZ' => 'Penzenskaya oblast', 'RU-PSK' => 'Pskovskaya oblast', 'RU-ROS' => 'Rostovskaya oblast', 'RU-RYA' => 'Ryazanskaya oblast', 'RU-SAK' => 'Sakhalinskaya oblast', 'RU-SAM' => 'Samarskaya oblast', 'RU-SAR' => 'Saratovskaya oblast', 'RU-SMO' => 'Smolenskaya oblast', 'RU-SVE' => 'Sverdlovskaya oblast', 'RU-TAM' => 'Tambovskaya oblast', 'RU-TOM' => 'Tomskaya oblast', 'RU-TUL' => 'Tul\'skaya oblast', 'RU-TVE' => 'Tverskaya oblast', 'RU-TYU' => 'Tyumenskaya oblast', 'RU-ULY' => 'Ul\'yanovskaya oblast', 'RU-VLA' => 'Vladimirskaya oblast', 'RU-VGG' => 'Volgogradskaya oblast', 'RU-VLG' => 'Vologodskaya oblast', 'RU-VOR' => 'Voronezhskaya oblast', 'RU-YAR' => 'Yaroslavskaya oblast', 'RU-MOW' => 'Moskva', 'RU-SPE' => 'Sankt-Peterburg', 'RU-YEV' => 'Yevreyskaya avtonomnaya oblast', 'RU-CHU' => 'Chukotskiy avtonomnyy okrug', 'RU-KHM' => 'Khanty-Mansiyskiy avtonomnyy okrug-Yugra', 'RU-NEN' => 'Nenetskiy avtonomnyy okrug', 'RU-YAN' => 'Yamalo-Nenetskiy avtonomnyy okrug' );
			
			$selections['South Africa'] = array( 'ZA-EC' => 'Eastern Cape / Oos-Kaap', 'ZA-FS' => 'Free State / Vrystaat', 'ZA-GP' => 'Gauteng', 'ZA-ZN' => 'KwaZulu-Natal', 'ZA-LP' => 'Limpopo', 'ZA-MP' => 'Mpumalanga', 'ZA-NC' => 'Northern Cape / Noord-Kaap', 'ZA-NW' => 'North West / Noordwes', 'ZA-WC' => 'Western Cape / Wes-Kaap' );
			
			$selections['Spain'] = array( 'ES-AN' => 'Andalucía','ES-AR' => 'Aragón','ES-AS' => 'Asturias, Principado de','ES-CN' => 'Canarias','ES-CB' => 'Cantabria','ES-CM' => 'Castilla-La Mancha','ES-CL' => 'Castilla y León','ES-CT' => 'Catalunya / Cataluña','ES-EX' => 'Extremadura','ES-GA' => 'Galicia / Galicia','ES-IB' => 'Illes Balears / Islas Baleares','ES-RI' => 'La Rioja','ES-MD' => 'Madrid, Comunidad de','ES-MC' => 'Murcia, Región de','ES-NC' => 'Navarra, Comunidad Foral de / Nafarroako Foru Komunitatea','ES-PV' => 'País Vasco / Euskal Herria','ES-VC' => 'Valenciana, Comunidad / Valenciana, Comunitat','ES-CE' => 'Ceuta','ES-ML' => 'Melilla','ES-C' => 'A Coruña / La Coruña','ES-VI' => 'Álava / Araba','ES-AB' => 'Albacete','ES-A' => 'Alicante / Alacant','ES-AL' => 'Almería','ES-O' => 'Asturias','ES-AV' => 'Ávila','ES-BA' => 'Badajoz','ES-PM' => 'Balears / Baleares','ES-B' => 'Barcelona / Barcelona','ES-BU' => 'Burgos','ES-CC' => 'Cáceres','ES-CA' => 'Cádiz','ES-S' => 'Cantabria','ES-CS' => 'Castellón / Castelló','ES-CR' => 'Ciudad Real','ES-CO' => 'Córdoba','ES-CU' => 'Cuenca','ES-GI' => 'Girona / Gerona','ES-GR' => 'Granada','ES-GU' => 'Guadalajara','ES-SS' => 'Guipúzcoa / Gipuzkoa','ES-H' => 'Huelva','ES-HU' => 'Huesca','ES-J' => 'Jaén','ES-LO' => 'La Rioja','ES-GC' => 'Las Palmas','ES-LE' => 'León','ES-L' => 'Lleida / Lérida','ES-LU' => 'Lugo / Lugo','ES-M' => 'Madrid','ES-MA' => 'Málaga','ES-MU' => 'Murcia','ES-NA' => 'Navarra / Nafarroa','ES-OR' => 'Ourense / Orense','ES-P' => 'Palencia','ES-PO' => 'Pontevedra / Pontevedra','ES-SA' => 'Salamanca','ES-TF' => 'Santa Cruz de Tenerife','ES-SG' => 'Segovia','ES-SE' => 'Sevilla','ES-SO' => 'Soria','ES-T' => 'Tarragona / Tarragona','ES-TE' => 'Teruel','ES-TO' => 'Toledo','ES-V' => 'Valencia / València','ES-VA' => 'Valladolid','ES-BI' => 'Vizcaya / Bizkaia','ES-ZA' => 'Zamora','ES-Z' => 'Zaragoza' );
			
			$selections['Great Britian'] = array( 'GB-ABD' => 'Aberdeenshire' ,'GB-ABE' => 'Aberdeen City', ' GB-AGB' => 'Argyll and Bute', ' GB-AGY' => 'Isle of Anglesey', ' GB-ANS' => 'Angus', ' GB-ANT' => 'Antrim', ' GB-ARD' => 'Ards', ' GB-ARM' => 'Armagh', ' GB-ATA' => 'Abertawe', ' GB-BAS' => 'Bath and North East Somerset', ' GB-BBD' => 'Blackburn with Darwen', ' GB-BDF' => 'Bedford', ' GB-BDG' => 'Barking and Dagenham', ' GB-BEN' => 'Brent', ' GB-BEX' => 'Bexley', ' GB-BFS' => 'Belfast', ' GB-BGE' => 'Bridgend', ' GB-BGW' => 'Blaenau Gwent', ' GB-BIR' => 'Birmingham', ' GB-BKM' => 'Buckinghamshire', ' GB-BLA' => 'Ballymena', ' GB-BLY' => 'Ballymoney', ' GB-BMG' => 'Bro Morgannwg', ' GB-BMH' => 'Bournemouth', ' GB-BNB' => 'Banbridge', ' GB-BNE' => 'Barnet', ' GB-BNF' => 'Sir Benfro', ' GB-BNH' => 'Brighton and Hove', ' GB-BNS' => 'Barnsley', ' GB-BOL' => 'Bolton', ' GB-BPL' => 'Blackpool', ' GB-BRC' => 'Bracknell Forest', ' GB-BRD' => 'Bradford', ' GB-BRY' => 'Bromley', ' GB-BST' => 'Bristol, City of', ' GB-BUR' => 'Bury', ' GB-CAF' => 'Caerffili', ' GB-CAM' => 'Cambridgeshire', ' GB-CAY' => 'Caerphilly', ' GB-CBF' => 'Central Bedfordshire', ' GB-CGN' => 'Ceredigion / Sir Ceredigion', ' GB-CGV' => 'Craigavon', ' GB-CHE' => 'Cheshire East', ' GB-CHW' => 'Cheshire West and Chester', ' GB-CKF' => 'Carrickfergus', ' GB-CKT' => 'Cookstown', ' GB-CLD' => 'Calderdale', ' GB-CLK' => 'Clackmannanshire', ' GB-CLR' => 'Coleraine', ' GB-CMA' => 'Cumbria', ' GB-CMD' => 'Camden', ' GB-CMN' => 'Carmarthenshire', ' GB-CNW' => 'Casnewydd', ' GB-CON' => 'Cornwall', ' GB-COV' => 'Coventry', ' GB-CRD' => 'Caerdydd', ' GB-CRF' => 'Cardiff', ' GB-CRY' => 'Croydon', ' GB-CSR' => 'Castlereagh', ' GB-CTL' => 'Castell-nedd Port Talbot', ' GB-CWY' => 'Conwy', ' GB-DAL' => 'Darlington', ' GB-DBY' => 'Derbyshire', ' GB-DDB' => 'Sir Ddinbych', ' GB-DEN' => 'Denbighshire', ' GB-DER' => 'Derby', ' GB-DEV' => 'Devon', ' GB-DGN' => 'Dungannon and South Tyrone', ' GB-DGY' => 'Dumfries and Galloway', ' GB-DNC' => 'Doncaster', ' GB-DND' => 'Dundee City', ' GB-DOR' => 'Dorset', ' GB-DOW' => 'Down', ' GB-DRY' => 'Derry', ' GB-DUD' => 'Dudley', ' GB-DUR' => 'Durham, County', ' GB-EAL' => 'Ealing', ' GB-EAY' => 'East Ayrshire', ' GB-EDH' => 'Edinburgh, City of', ' GB-EDU' => 'East Dunbartonshire', ' GB-ELN' => 'East Lothian', ' GB-ELS' => 'Eilean Siar', ' GB-ENF' => 'Enfield', ' GB-ERW' => 'East Renfrewshire', ' GB-ERY' => 'East Riding of Yorkshire', ' GB-ESS' => 'Essex', ' GB-ESX' => 'East Sussex', ' GB-FAL' => 'Falkirk', ' GB-FER' => 'Fermanagh', ' GB-FFL' => 'Sir y Fflint', ' GB-FIF' => 'Fife', ' GB-FLN' => 'Flintshire', ' GB-FYN' => 'Sir Fynwy', ' GB-GAT' => 'Gateshead', ' GB-GFY' => 'Sir Gaerfyrddin', ' GB-GLG' => 'Glasgow City', ' GB-GLS' => 'Gloucestershire', ' GB-GRE' => 'Greenwich', ' GB-GWN' => 'Gwynedd', ' GB-HAL' => 'Halton', ' GB-HAM' => 'Hampshire', ' GB-HAV' => 'Havering', ' GB-HCK' => 'Hackney', ' GB-HEF' => 'Herefordshire', ' GB-HIL' => 'Hillingdon', ' GB-HLD' => 'Highland', ' GB-HMF' => 'Hammersmith and Fulham', ' GB-HNS' => 'Hounslow', ' GB-HPL' => 'Hartlepool', ' GB-HRT' => 'Hertfordshire', ' GB-HRW' => 'Harrow', ' GB-HRY' => 'Haringey', ' GB-IOW' => 'Isle of Wight', ' GB-ISL' => 'Islington', ' GB-IVC' => 'Inverclyde', ' GB-KEC' => 'Kensington and Chelsea', ' GB-KEN' => 'Kent', ' GB-KHL' => 'Kingston upon Hull', ' GB-KIR' => 'Kirklees', ' GB-KTT' => 'Kingston upon Thames', ' GB-KWL' => 'Knowsley', ' GB-LAN' => 'Lancashire', ' GB-LBH' => 'Lambeth', ' GB-LCE' => 'Leicester', ' GB-LDS' => 'Leeds', ' GB-LEC' => 'Leicestershire', ' GB-LEW' => 'Lewisham', ' GB-LIN' => 'Lincolnshire', ' GB-LIV' => 'Liverpool', ' GB-LMV' => 'Limavady', ' GB-LND' => 'London, City of', ' GB-LRN' => 'Larne', ' GB-LSB' => 'Lisburn', ' GB-LUT' => 'Luton', ' GB-MAN' => 'Manchester', ' GB-MDB' => 'Middlesbrough', ' GB-MDW' => 'Medway', ' GB-MFT' => 'Magherafelt', ' GB-MIK' => 'Milton Keynes', ' GB-MLN' => 'Midlothian', ' GB-MON' => 'Monmouthshire', ' GB-MRT' => 'Merton', ' GB-MRY' => 'Moray', ' GB-MTU' => 'Merthyr Tudful', ' GB-MTY' => 'Merthyr Tydfil', ' GB-MYL' => 'Moyle', ' GB-NAY' => 'North Ayrshire', ' GB-NBL' => 'Northumberland', ' GB-NDN' => 'North Down', ' GB-NEL' => 'North East Lincolnshire', ' GB-NET' => 'Newcastle upon Tyne', ' GB-NFK' => 'Norfolk', ' GB-NGM' => 'Nottingham', ' GB-NLK' => 'North Lanarkshire', ' GB-NLN' => 'North Lincolnshire', ' GB-NSM' => 'North Somerset', ' GB-NTA' => 'Newtownabbey', ' GB-NTH' => 'Northamptonshire', ' GB-NTL' => 'Neath Port Talbot', ' GB-NTT' => 'Nottinghamshire', ' GB-NTY' => 'North Tyneside', ' GB-NWM' => 'Newham', ' GB-NWP' => 'Newport', ' GB-NYK' => 'North Yorkshire', ' GB-NYM' => 'Newry and Mourne District', ' GB-OLD' => 'Oldham', ' GB-OMH' => 'Omagh', ' GB-ORK' => 'Orkney Islands', ' GB-OXF' => 'Oxfordshire', ' GB-PEM' => 'Pembrokeshire', ' GB-PKN' => 'Perth and Kinross', ' GB-PLY' => 'Plymouth', ' GB-POG' => 'Pen-y-bont ar Ogwr', ' GB-POL' => 'Poole', ' GB-POR' => 'Portsmouth', ' GB-POW' => 'Powys', ' GB-PTE' => 'Peterborough', ' GB-RCC' => 'Redcar and Cleveland', ' GB-RCH' => 'Rochdale', ' GB-RCT' => 'Rhondda, Cynon, Taff', ' GB-RDB' => 'Redbridge', ' GB-RDG' => 'Reading', ' GB-RFW' => 'Renfrewshire', ' GB-RIC' => 'Richmond upon Thames', ' GB-ROT' => 'Rotherham', ' GB-RUT' => 'Rutland', ' GB-SAW' => 'Sandwell', ' GB-SAY' => 'South Ayrshire', ' GB-SCB' => 'Scottish Borders, The', ' GB-SFK' => 'Suffolk', ' GB-SFT' => 'Sefton', ' GB-SGC' => 'South Gloucestershire', ' GB-SHF' => 'Sheffield', ' GB-SHN' => 'St. Helens', ' GB-SHR' => 'Shropshire', ' GB-SKP' => 'Stockport', ' GB-SLF' => 'Salford', ' GB-SLG' => 'Slough', ' GB-SLK' => 'South Lanarkshire', ' GB-SND' => 'Sunderland', ' GB-SOL' => 'Solihull', ' GB-SOM' => 'Somerset', ' GB-SOS' => 'Southend-on-Sea', ' GB-SRY' => 'Surrey', ' GB-STB' => 'Strabane', ' GB-STE' => 'Stoke-on-Trent', ' GB-STG' => 'Stirling', ' GB-STH' => 'Southampton', ' GB-STN' => 'Sutton', ' GB-STS' => 'Staffordshire', ' GB-STT' => 'Stockton-on-Tees', ' GB-STY' => 'South Tyneside', ' GB-SWA' => 'Swansea', ' GB-SWD' => 'Swindon', ' GB-SWK' => 'Southwark', ' GB-TAM' => 'Tameside', ' GB-TFW' => 'Telford and Wrekin', ' GB-THR' => 'Thurrock', ' GB-TOB' => 'Torbay', ' GB-TOF' => 'Torfaen / Tor-faen', ' GB-TRF' => 'Trafford', ' GB-TWH' => 'Tower Hamlets', ' GB-VGL' => 'Vale of Glamorgan, The', ' GB-WAR' => 'Warwickshire', ' GB-WBK' => 'West Berkshire', ' GB-WDU' => 'West Dunbartonshire', ' GB-WFT' => 'Waltham Forest', ' GB-WGN' => 'Wigan', ' GB-WIL' => 'Wiltshire', ' GB-WKF' => 'Wakefield', ' GB-WLL' => 'Walsall', ' GB-WLN' => 'West Lothian', ' GB-WLV' => 'Wolverhampton', ' GB-WND' => 'Wandsworth', ' GB-WNM' => 'Windsor and Maidenhead', ' GB-WOK' => 'Wokingham', ' GB-WOR' => 'Worcestershire', ' GB-WRC' => 'Wrecsam', ' GB-WRL' => 'Wirral', ' GB-WRT' => 'Warrington', ' GB-WRX' => 'Wrexham', ' GB-WSM' => 'Westminster', ' GB-WSX' => 'West Sussex', ' GB-YNM' => 'Sir Ynys Môn', ' GB-YOR' => 'York', ' GB-ZET' => 'Shetland Islands' );
			
			$selections['United States'] = array( 'AL' => 'Alabama', 'AK' => 'Alaska', 'AS' => 'American Somoa', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District Of Columbia', 'FL' => 'Florida', 'FM' => 'Federated States of Micronesia', 'GA' => 'Georgia', 'GU' => 'Guam', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MH' => 'Marshall Islands', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MP' => 'Northern Mariana Islands', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'PR' => 'Puerto Rico', 'PW' => 'Palau', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'VI' => 'Virgin Islands', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming' );
			
			extract( $params );
			
			if ( !empty( $only ) ) {
			
				$input_group['blank'] 		= 'Select state';
				$input_group['class'] 		= 'states_dropmenu ' . $class;
				$input_group['desc'] 		= ( !empty( $desc ) ? $desc : '' );
				$input_group['id'] 			= $id;
				$input_group['grouptype'] 	= 'dropmenu';
				$input_group['label'] 		= ( !empty( $label ) ? $label : '' );
				$input_group['value'] 		= $value;
				
				$i = 0;
				
				foreach ( $selections[$only] as $abb => $state ) {
								
						$input_group['selections'][$i]['label'] = $state;
						$input_group['selections'][$i]['value'] = $abb;
					
						$i++;
				
				} // End of $selections foreach
				
				return $this->input_group( $input_group );
			
			} else {
			
				$class = ( !empty( $class ) ? $class : '' );
			
				if ( !empty( $label ) ) {
					
					$output = '<label for="' . $id . '">' . $label . '</label>';
					
				} // End of $label empty check
				
				$output .= '<select name="' . $id . '" id="' . $id . '" class="states_dropmenu ' . $class . '">';
				
				$output .= '<option>' . __( 'Select state' ) . '</option>';
				
				if ( !empty( $first ) ) {
				
					foreach ( $selections[$first] as $abb => $state ) {
						
						$output .= '<option value="' . $abb . '"' . selected( $value, $abb ) . ' >' . $state . '</option>';
						
					} // End of $selections[$first] foreach
					
					$output .= '<option></option>';
					
					unset( $selections[$first] ); 
					
				} // End of $first check
				
				foreach ( $selections as $country => $states ) {
					
					$output .= '<optgroup label="' . $country . '">';
					
					foreach ( $states as $abb => $state ) {
					
						$output .= '<option value="' . $abb . '"' . selected( $value, $abb ) . ' >' . $state . '</option>';
						
					} // End of $states foreach
					
					$output .= '</optgroup>';
					
				} // End of $selections foreach
				
				$output .= '</select>';
				
				if ( !empty( $desc ) ) {
					
					$output .= '<br /><span class="description">' . $desc . '</span>';
					
				} // End of $desc empty check
				
				return $output;
				
			} // End of $only check
				
		} // End of states()
		
/**
 * Creates dropmenus for hours, minutes, amd am/pm
 *
 * Creates dropmenus for hours, minutes, amd am/pm
 * 
 * The params are in three groups - one for the the group, one for currencies() and one for input_field():
 *  class - the class used for the group of fields
 * 	desc - description used for the description span
 *  include - an array with fields you want included (hours, minutes, ampm, timezones)
 *	label - the label to use in front of the field
 *
 * How to use directly:
 * 
 * $time_args['class'] 					= '';
 * $time_args['desc'] 			= '';
 * $time_args['id']						= '';
 * $time_args['label'] 					= '';
 *
 * $time_args['hours']['class'] 		= '';
 * $time_args['hours']['id'] 			= '';
 * $time_args['hours']['military']		= '';
 * $time_args['hours']['value'] 		= ''; 
 *
 * $time_args['minutes']['class'] 		= '';
 * $time_args['minutes']['id'] 			= '';
 * $time_args['minutes']['mintype']		= '';
 * $time_args['minutes']['value'] 		= ''; 
 *
 * $time_args['ampm']['class'] 			= '';
 * $time_args['ampm']['id'] 			= '';
 * $time_args['ampm']['military']		= '';
 * $time_args['ampm']['value'] 			= ''; 
 *
 * echo Slushman_Toolkit::time_fields( $time_args );
 * 
 * 
 *
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the states dropmenu field
 *
 * @return	mixed	A properly formatted HTML dropmenu of US states and territories with optional label and description
 */			
		function time_fields( $params ) {
		
			extract( $params );
						
			$output = ( !empty( $label ) ? '<label for="' . $id . '">' . $label . '</label>' : '' );
			$output .= '<span class="' . $class . '">';
			$output .= $this->hours( $hours );
			$output .= $this->minutes( $minutes );
			$output .= ( $hours['military'] != true ? $this->ampm( $ampm ) : '' );
			$output .= '</span>';
			
			if ( !empty( $desc ) ) {
				
				$output .= '<br /><span class="description">' . $desc . '</span>';
				
			} // End of $desc empty check
			
			return $output;
			
		} // End of time_fields()	

/**
 * Creates a time picker field
 *
 * Creates a time picker field with the jQuery timepicker
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *	value - used in the checked function
 *
 * How to use:
 * 
 * $colorpicker_args['class'] 		= '';
 * $colorpicker_args['desc'] 	= '';
 * $colorpicker_args['id'] 			= '';
 * $colorpicker_args['label'] 		= '';
 * $colorpicker_args['value'] 		= '';
 * 
 * echo Slushman_Toolkit::color_picker( $colorpicker_args );
 * 
 * 
 * 
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the colorpicker field
 *
 * @return	mixed	A properly formatted HTML text field with the Farbtastic color picker and optional label and description
 */	
		function color_picker( $params ) {
		
			extract( $params );
			
			$input_args['class'] 		= 'toolkit_color_picker ' . $class;
			$input_args['desc'] 		= ( !empty( $desc ) ? $desc : '' );
			$input_args['id'] 			= $id;
			$input_args['inputtype']	= 'text';
			$input_args['label'] 		= ( !empty( $label ) ? $label : '' );
			$input_args['value'] 		= ( !empty( $value ) ? $value : '' );
			
			return $this->input_field( $input_args );
			
		} // End of color_picker()

/**
 * Creates select menu items for the make_select function.
 * Increments the items based on the params.
 *
 * @param	$startlabel		string		(optional) The label for the first item
 * @param	$start			int			(optional) The starting int
 * @param	$end			int			(optional) The ending int
 * @param	$interval		int			(optional) How much to increase each item by
 *
 * @since	0.1
 */
   		function incremental_sels( $startlabel = '', $start = '', $end = '', $interval = '' ) {

   			$sels = array();

   			for ( $i = $start; $i <= $end; $i += $interval ) {

   				$label = ( $i == 0 ? $startlabel : $i );

   				$sels[$i] = array( 'label' => $label, 'value' => $i );
				$i++;

   			}

   			return $sels;

   		} // End of incremental_sels



/*****************************		End of Functions		*****************************/   				
		
	} // End of Slushman_Toolkit class

	$slushkit = new Slushman_Toolkit;
	
} // End of class_exists check

class nameSorter {

    private $field1;
    private $field2;

    function __construct( $field1, $field2 ) {

        $this->field1 = $field1;
        $this->field2 = $field2;

    } // End of __construct()

    function call( $a, $b ) {

        return sort_cmp( $a, $b, $this->field1, $this->field2 );

    } // End of call()
    
    function sort_cmp( $a, $b, $field1, $field2 ) {

	    $return = strnatcmp( $a[$field1], $b[$field1] );
		
		if ( !$return && isset( $field2 ) ) {
		
			return strnatcmp( $a[$field2], $b[$field2] );
		
		} // End of 
		
		return $return;

	} // End of sort_cmp()

} // End of nameSorter class

?>