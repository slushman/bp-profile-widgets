<?php

if ( !class_exists( 'Slushman_Toolkit_Make_Settings' ) ) {

	class Slushman_Toolkit_Make_Settings {

/**
 * settings tabs array
 *
 * @var 	array
 */
	    private $tabs = array();

/**
 * settings columns array
 *
 * @var 	array
 */
	    private $columns = array();

/**
 * settings boxes array
 *
 * @var 	array
 */
	    private $boxes = array();

/**
 * settings sections array
 *
 * @var 	array
 */
	    private $sections = array();

/**
 * Settings fields array
 *
 * @var 	array
 */
    	private $fields = array();

/**
 * Settings constants
 *
 * @var 	array
 */
    	private $constants = array();

/**
 * Plugin options
 *
 * @var 	array
 */
    	private $options = array();    	

/**
 * Settings prefix
 *
 * @var 	string
 */
    	private $make_fields = '';

/**
 * Settings prefix
 *
 * @var 	string
 */
    	private $slushkit = '';    	

/**
 * Singleton instance
 *
 * @var 	object
 */
   		private static $_instance;

/**
 * Constructor
 */
	    public function __construct( $params ) {

	    	// Instantiate make_fields
	    	$this->make_fields 	= new Slushman_Toolkit_Make_Fields;
	    	$this->slushkit 	= new Slushman_Toolkit;

	    	foreach ( $params as $key => $value ) {

	    		$this->$key = ( empty( $params[$key] ) ? '' : $value );

	    	} // End of $params foreach loop

	        // add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	    	//sub $menu
	      	if( !is_array( $args['menu'] ) ) {

		        if( is_object( $args['menu'] ) ) {
		        	
		        	$this->Top_Slug = $args['menu']->Top_Slug;
		        
		        } else {
		        
		          switch( $args['menu'] ) {

		            case 'posts': 		$this->Top_Slug = 'edit.php'; break;
		            case 'dashboard': 	$this->Top_Slug = 'index.php'; break;
		            case 'media':		$this->Top_Slug = 'upload.php'; break;
		            case 'links':		$this->Top_Slug = 'link-manager.php'; break;
		            case 'pages':		$this->Top_Slug = 'edit.php?post_type=page'; break;
		            case 'comments':	$this->Top_Slug = 'edit-comments.php'; break;
		            case 'theme':		$this->Top_Slug = 'themes.php'; break;
		            case 'plugins':		$this->Top_Slug = 'plugins.php'; break;
		            case 'users':		$this->Top_Slug = 'users.php'; break;
		            case 'tools':		$this->Top_Slug = 'tools.php'; break;
		            case 'settings':	$this->Top_Slug = 'options-general.php'; break;
		            default:			$this->Top_Slug = ( post_type_exists( $args['menu'] ) ? 'edit.php?post_type='.$args['menu'] : $args['menu'] );

		        	} // End of switch
		        
		        } // End of object check
	        
	        	add_action( 'admin_menu', array( $this, 'AddMenuSubPage' ) );
	      
	      } else {
	      
	        //top page
	        $this->Top_Slug = $args['menu']['top'];

	        add_action( 'admin_menu', array( $this, 'AddMenuTopPage' ) );
	      
	      } // End of array check

	    } // End of __construct()

/**
 * Enqueue scripts and styles
 */
	    function admin_enqueue_scripts() {

	        wp_enqueue_script( 'jquery' );
	        wp_enqueue_script( 'media-upload' );
	        wp_enqueue_script( 'thickbox' );
	    
	    } // End of admin_enqueue_scripts()

/**
 * Set settings tabs
 *
 * @param 	array   $tabs 	setting tabs array
 */
	    function set_tabs( $tabs ) {
	    
	        $this->$tabs = $tabs;

	        return $this;
	    
	    } // End of set_tabs()

/**
 * Set settings columns
 *
 * @param 	array   $columns 	settings columns array
 */
	    function set_columns( $columns ) {
	        
	        $this->columns = $columns;

	        return $this;

	    } // End of set_columns()

/**
 * Set settings boxes
 *
 * @param 	array   $boxes 		settings boxes array
 */
	    function set_boxes( $boxes ) {
	        
	        $this->boxes = $boxes;

	        return $this;

	    } // End of set_boxes()

/**
 * Set settings sections
 *
 * @param 	array   $sections 	setting sections array
 */
	    function set_sections( $sections ) {
	    
	        $this->sections = $sections;

	        return $this;
	    
	    } // End of set_sections()

/**
 * Set settings fields
 *
 * @param 	array   $fields 	settings fields array
 */
	    function set_fields( $fields ) {
	        
	        $this->fields = $fields;

	        return $this;

	    } // End of set_fields()

/**
 * Set settings constants
 *
 * @param 	array   $constants 	settings constants array
 */
	    function set_constants( $constants ) {
	        
	        $this->constants = $constants;

	        return $this;

	    } // End of set_constants()

/**
 * Add a value to the constants array
 *
 * @param 	string   $constant
 */
    function add_constant( $name, $constant ) {

        $this->constants[$name] = $constant;

        return $this;
    
    } // End of add_constant();

/**
 * Creates the plugin settings
 *
 * Creates an array containing each setting and sets the default values to blank.
 * Then saves the array in the plugin option.
 *
 * @since	0.1
 * 
 * @uses	update_option
 */		
		function settings_init() {

			foreach ( $this->fields as $field ) {

				$settings[$field['underscored']] = $field['value'];

			} // End of $fields foreach loop
		
			update_option( $this->constants['name'], $settings );
			
		} // End of settings_init()

/**
 * Registers the plugin option, settings, and sections
 *
 * Instead of writing the registration for each field, I used a foreach loop to write them all.
 * add_settings_field has an argument that can pass data to the callback, which I used to send the specifics
 * of each setting field to the callback that actually creates the setting field. 
 *
 * @since	0.1
 * 
 * @uses	register_setting
 * @uses	add_settings_section
 * @uses	add_settings_field
 */	
		function settings_reg() {

			$options = (array) get_option( $this->constants['name'] );

			register_setting( 
				$this->constants['name'], 
				$this->constants['name'],
				array( $this, 'validate_options' )
			);

			foreach ( $this->sections as $section ) {

				if ( isset( $section['desc'] ) && !empty( $section['desc'] ) ) {
	        
	                $section['desc'] 	= '<div class="inside">' . $section['desc'] . '</div>';
	                $callback 			= create_function( '', 'echo "' . str_replace( '"', '\"', $section['desc'] ) . '";' );
	        
	            } else {
	        
	                $callback = '__return_false';
	        
	            } // End of description check

				add_settings_section( 
					$this->constants['prefix'] . $section['underscored'], 
					$section['name'] . ' Settings', 
					$callback, 
					$this->constants['name']
				);

			} // End of $sections foreach loop
			
			foreach ( $this->fields as $field ) {

				$defaults 	= array( 'desc' => '', 'id' => '', 'type' => '', 'sels' => array(), 'size' => '' );
				$field 		= wp_parse_args( $field, $defaults );

				$corv 				= ( $field['type'] == 'checkbox' ? 'check' : 'value' );
				$dorl				= ( $field['type'] == 'checkbox' ? 'label' : 'desc' );
				$args[$corv] 		= $options[$field['underscored']];
				$args[$dorl] 		= $field['desc'];
				$args['blank']		= ( $field['type'] == 'select' ? TRUE : '' );
				$args['class']		= $this->constants['prefix'] . $field['underscored'];
				$args['id'] 		= $field['underscored'];
				$args['name'] 		= $this->constants['name'] . '[' . $field['underscored'] . ']';
				$args['selections']	= $field['sels'];
				$args['size']		= $field['size'];
				$args['type'] 		= $field['type'];
				
				add_settings_field(
					$this->constants['prefix'] . $field['underscored'] . '_field', 
					$field['name'], 
					array( $this, 'create_settings' ), 
					$this->constants['name'],
					$this->constants['prefix'] . $field['section'],
					$args
				);
				
			} // End of $fields foreach

		} // End of settings_reg()

/**
 * Creates the settings fields
 *
 * Accepts the $params from settings_reg() and creates a setting field
 *
 * @since	0.1
 *
 * @params	$params		The data specific to this setting field, comes from settings_reg()
 * 
 * @uses	checkbox
 */	
 		function create_settings( $params ) {

 			$defaults 	= array( 'blank' => '', 'check' => '', 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => '', 'selections' => '', 'size' => '', 'type' => '', 'value' => '' );
 			$args 		= wp_parse_args( $params, $defaults );
 					
 			switch ( $args['type'] ) {
	 			
	 			case ( 'email' )		:
	 			case ( 'number' )		:
	 			case ( 'tel' ) 			: 
	 			case ( 'url' ) 			: 
	 			case ( 'text' ) 		: echo $this->make_fields->make_text( $args ); break;
	 			case ( 'checkbox' ) 	: echo $this->make_fields->make_checkbox( $args ); break;
	 			case ( 'textarea' )		: echo $this->make_fields->make_textarea( $args ); break;
	 			case ( 'checkboxes' ) 	: echo $this->make_fields->make_checkboxes( $args ); break;
	 			case ( 'radios' ) 		: echo $this->make_fields->make_radios( $args ); break;
	 			case ( 'select' )		: echo $this->make_fields->make_select( $args ); break;
	 			case ( 'file' )			: echo $this->make_fields->make_file( $args ); break;
	 			case ( 'password' )		: echo $this->make_fields->make_password( $args ); break;
	 			
 			} // End of $inputtype switch
			
		} // End of create_settings_fn()

/**
 * Validates the plugin settings before they are saved
 *
 * Loops through each plugin setting and sanitizes the data before returning it.
 *
 * @since	0.1
 *
 * @uses    sanitize_email
 * @uses    esc_url
 * @uses    sanitize_text_field
 * @uses    esc_textarea
 * @uses    sanitize_phone
 */				
		function validate_options( $input ) {

			foreach ( $this->fields as $field ) {

				$name = $field['underscored'];
			
				switch ( $field['type'] ) {
	 			
		 			case ( 'email' )		: $valid[$name] = sanitize_email( $input[$name] ); break;
		 			case ( 'number' )		: $valid[$name] = intval( $input[$name] ); break;
		 			case ( 'url' ) 			: $valid[$name] = esc_url( $input[$name] ); break;
		 			case ( 'text' ) 		: $valid[$name] = sanitize_text_field( $input[$name] ); break;
		 			case ( 'textarea' )		: $valid[$name] = esc_textarea( $input[$name] ); break;
		 			case ( 'checkgroup' ) 	: 
		 			case ( 'radios' ) 		: 
		 			case ( 'select' )		: $valid[$name] = strip_tags( $input[$name] ); break;
		 			case ( 'tel' ) 			: $valid[$name] = $this->slushkit->sanitize_phone( $input[$name] ); break;
		 			case ( 'checkbox' ) 	: $valid[$name] = ( isset( $input[$name] ) ? 1 : 0 ); break;
		 			
	 			} // End of $inputtype switch
			
			} // End of $checks foreach

			return $valid;
		
		} // End of validate_options()

/**
 * Creates the settings page
 *
 * @since	0.1
 *
 * @uses	get_plugin_data
 * @uses	plugins_url
 * @uses	settings_fields
 * @uses	do_settings_sections
 * @uses	submit_button
 */					
		function get_page() {

			$plugin = get_plugin_data( $this->constants['file'] ); ?>
			<div class="wrap">
			<div class="icon32" style="background-image:url(<?php echo plugins_url( 'images/logo.png', $this->constants['file'] ); ?>); background-repeat:no-repeat;"></div>
			<h2><?php echo $plugin['Name']; ?></h2><?php
			//settings_errors();
			?><form method="post" action="options.php"><?php
			
				settings_fields( $this->constants['name'] );
				do_settings_sections( $this->constants['name'] );
				echo '<br />'; 
				submit_button(); ?>
				
			</form>
			</div><?php

			echo '<pre>';
			print_r( $_POST );
			echo '</pre>';

		} // End of get_page()

/**
 * Adds a link to the plugin settings page to the plugin's listing on the plugin page
 *
 * @since	0.1
 * 
 * @uses	admin_url
 */			
		function settings_link( $links ) {
		
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=' . $this->constants['name'] ), __( 'Settings' ) );
			
			array_unshift( $links, $settings_link );
			
			return $links;
			
		} // End of settings_link()

/**
 * Adds the plugin settings page to the appropriate admin menu
 *
 * @since	0.1
 * 
 * @uses	add_options_page
 */				
		function add_menu() {

			if ( $this->constants['menu'] == 'options' ) {

				add_options_page( 
					__( $this->constants['plug'] . ' Settings' ), 
					__( $this->constants['plug'] ), 
					'manage_options', 
					$this->constants['name'], 
					array( $this, 'get_page' ) 
				);

			} elseif ( $this->constants['menu'] == 'submenu' ) {

				add_submenu_page(
					'edit.php?post_type=' . $this->constants['cpt'],
					__( $this->constants['plug'] . ' Settings' ),
					__( 'Settings' ),
					'edit_posts',
					$this->constants['slug'] . '-settings',
					array( $this, 'get_page' )
				);

			} // End of menu check
		
		} // End of add_menu()

/**
 * Show navigations as tab
 *
 * Shows all the settings section labels as tab
 */
	    function show_navigation() {

	        $html = '<h2 class="nav-tab-wrapper">';

	        foreach ( $this->settings_sections as $tab ) {
	            $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
	        }

	        $html .= '</h2>';

	        echo $html;

	    } // End of show_navigation()

/**
 * Show the section settings forms
 *
 * This function displays every sections in a different form
 */
	    function show_forms() { ?>

	        <div class="metabox-holder">
	            <div class="postbox">
	                <?php foreach ( $this->settings_sections as $form ) { ?>
	                    <div id="<?php echo $form['id']; ?>" class="group">
	                        <form method="post" action="options.php">

	                            <?php do_action( 'wsa_form_top_' . $form['id'], $form ); ?>
	                            <?php settings_fields( $form['id'] ); ?>
	                            <?php do_settings_sections( $form['id'] ); ?>
	                            <?php do_action( 'wsa_form_bottom_' . $form['id'], $form ); ?>

	                            <div style="padding-left: 10px">
	                                <?php submit_button(); ?>
	                            </div>
	                        </form>
	                    </div>
	                <?php } ?>
	            </div>
	        </div>
	        <?php
	        $this->script();

	    } // End of show_forms()

/**
 * Tabbable JavaScript codes
 *
 * This code uses localstorage for displaying active tabs
 */
	    function script() { ?>

	        <script>
	            jQuery(document).ready(function($) {
	                // Switches option sections
	                $('.group').hide();
	                var activetab = '';
	                if (typeof(localStorage) != 'undefined' ) {
	                    activetab = localStorage.getItem("activetab");
	                }
	                if (activetab != '' && $(activetab).length ) {
	                    $(activetab).fadeIn();
	                } else {
	                    $('.group:first').fadeIn();
	                }
	                $('.group .collapsed').each(function(){
	                    $(this).find('input:checked').parent().parent().parent().nextAll().each(
	                    function(){
	                        if ($(this).hasClass('last')) {
	                            $(this).removeClass('hidden');
	                            return false;
	                        }
	                        $(this).filter('.hidden').removeClass('hidden');
	                    });
	                });

	                if (activetab != '' && $(activetab + '-tab').length ) {
	                    $(activetab + '-tab').addClass('nav-tab-active');
	                }
	                else {
	                    $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
	                }
	                $('.nav-tab-wrapper a').click(function(evt) {
	                    $('.nav-tab-wrapper a').removeClass('nav-tab-active');
	                    $(this).addClass('nav-tab-active').blur();
	                    var clicked_group = $(this).attr('href');
	                    if (typeof(localStorage) != 'undefined' ) {
	                        localStorage.setItem("activetab", $(this).attr('href'));
	                    }
	                    $('.group').hide();
	                    $(clicked_group).fadeIn();
	                    evt.preventDefault();
	                });
	            });
	        </script><?php

	    } // End of script()	    

	} // End of class Slushman_Toolkit_Make_Settings

} // End of class exists check

?>