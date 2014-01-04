<?php

// Constants
$this->constants['plug']	= 'BP Profile Widgets';
$this->constants['slug']	= 'slushman-bppw';
$this->constants['name'] 	= 'slushman_bppw_settings';
$this->constants['prefix'] 	= 'slushman_bppw_';
$this->constants['menu'] 	= 'options';

// Settings Sections
$i = 0;
$this->sections[$i]['name'] 		= 'Widget Selection';
$this->sections[$i]['underscored'] 	= 'widget_selection';
$this->sections[$i]['desc'] 		= 'Select which widgets you want to use on your site. The custom profile fields needed for these widgets will be created automatically based on your choices here. Unchecked widgets will not be available on the Widgets panel.';

// Settings Fields
$i = 0;
$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= 'Music Player Widget';
$this->fields[$i]['underscored'] 	= 'BP_profile_music_player_widget';
$this->fields[$i]['type'] 			= 'checkbox';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= 'Check to use the BP Profile Music Player widget and create the required extended profile fields.';
$i++;

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= 'Video Player Widget';
$this->fields[$i]['underscored'] 	= 'BP_profile_video_player_widget';
$this->fields[$i]['type'] 			= 'checkbox';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= 'Check to use the BP Profile Video Player widget and create the required extended profile fields.';
$i++;

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= 'Photo Gallery Widget';
$this->fields[$i]['underscored'] 	= 'BP_profile_photo_gallery_widget';
$this->fields[$i]['type'] 			= 'checkbox';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= 'Check to use the BP Profile Photo Gallery widget and create the required extended profile fields.';
$i++;

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= 'Text Box Widget';
$this->fields[$i]['underscored'] 	= 'BP_profile_text_box_widget';
$this->fields[$i]['type'] 			= 'checkbox';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= 'Check to use the BP Profile Text Box widget and create the required extended profile fields.';
$i++;

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= 'RSS Widget';
$this->fields[$i]['underscored'] 	= 'BP_profile_rss_widget';
$this->fields[$i]['type'] 			= 'checkbox';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= 'Check to use the BP Profile RSS widget and create the required extended profile fields.';
$i++;

// Custom Post Types



// Taxonomies


?>