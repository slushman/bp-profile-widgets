<?php

// Constants
$this->constants['plug']	= __( 'BP Profile Widgets' );
$this->constants['slug']	= 'slushman-bppw';
$this->constants['name'] 	= 'slushman_bppw_settings';
$this->constants['prefix'] 	= 'slushman_bppw_';
$this->constants['menu'] 	= 'options';
$this->constants['i18n'] 	= 'bp-profile-widgets';

// Settings Sections
$i = 0;
$this->sections[$i]['name'] 		= __( 'Widget Selection', $this->constants['i18n'] );
$this->sections[$i]['underscored'] 	= 'widget_selection';
$this->sections[$i]['desc'] 		= __( 'Select which widgets you want to use on your site. The custom profile fields needed for these widgets will be created automatically based on your choices here. Unchecked widgets will not be available on the Widgets panel.', $this->constants['i18n'] );

// Settings Fields
$i = 0;

for ( $i = 0; $i <= 5; $i++ ) {

	$selects[] = array( 'label' => $i, 'value' => $i );

} // End of for loop

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= __( 'Music Player Widget', $this->constants['i18n'] );
$this->fields[$i]['underscored'] 	= 'BP_profile_music_player_widget';
$this->fields[$i]['type'] 			= 'select';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= __( 'How many Music Player widgets would you to create?', $this->constants['i18n'] );
$this->fields[$i]['sels'] 			= $selects;
$this->fields[$i]['blank'] 			= FALSE;
$i++;

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= __( 'Video Player Widget', $this->constants['i18n'] );
$this->fields[$i]['underscored'] 	= 'BP_profile_video_player_widget';
$this->fields[$i]['type'] 			= 'select';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= __( 'Check to use the BP Profile Video Player widget and create the required extended profile fields.', $this->constants['i18n'] );
$this->fields[$i]['sels'] 			= $selects;
$i++;

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= __( 'Photo Gallery Widget', $this->constants['i18n'] );
$this->fields[$i]['underscored'] 	= 'BP_profile_photo_gallery_widget';
$this->fields[$i]['type'] 			= 'select';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= __( 'Check to use the BP Profile Photo Gallery widget and create the required extended profile fields.', $this->constants['i18n'] );
$this->fields[$i]['sels'] 			= $selects;
$i++;

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= __( 'Text Box Widget', $this->constants['i18n'] );
$this->fields[$i]['underscored'] 	= 'BP_profile_text_box_widget';
$this->fields[$i]['type'] 			= 'select';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= __( 'Check to use the BP Profile Text Box widget and create the required extended profile fields.', $this->constants['i18n'] );
$this->fields[$i]['sels'] 			= $selects;
$i++;

$this->fields[$i]['section'] 		= 'widget_selection';
$this->fields[$i]['name'] 			= __( 'RSS Widget', $this->constants['i18n'] );
$this->fields[$i]['underscored'] 	= 'BP_profile_rss_widget';
$this->fields[$i]['type'] 			= 'select';
$this->fields[$i]['value'] 			= 0;
$this->fields[$i]['desc'] 			= __( 'Check to use the BP Profile RSS widget and create the required extended profile fields.', $this->constants['i18n'] );
$this->fields[$i]['sels'] 			= $selects;
$i++;

// Custom Post Types



// Taxonomies


// Custom
$this->profiles['music_player']['URL'] 		= array( 'type' => 'textbox', 'desc' => __( 'Please enter the URL for your album / set / profile from any of the following services: Bandcamp, SoundCloud, Reverbnation, Tunecore, Mixcloud, or Noisetrade.', $this->constants['i18n'] ) );
$this->profiles['music_player']['role'] 	= array( 'type' => 'textbox', 'desc' => __( 'Please explain your role in the music (artist, writer, player, producer, etc).', $this->constants['i18n'] ) );
$this->profiles['video_player']['URL'] 		= array( 'type' => 'textbox', 'desc' => __( 'Please enter the URL to the YouTube, Vimeo, Veoh, DailyMotion, Blip.tv, uStream, or Facebook video you want to display on your profile.', $this->constants['i18n'] ) );
$this->profiles['video_player']['role'] 	= array( 'type' => 'textbox', 'desc' => __( 'Please explain your role in the video (actor, writer, crew, producer, etc).', $this->constants['i18n'] ) );
$this->profiles['photo_gallery']['URL'] 	= array( 'type' => 'textbox', 'desc' => __( 'Please enter the URL for the set / gallery / album / profile from any of the following services: Flickr, Picasa, Photobucket, Fotki, dotPhoto, or Imgur.', $this->constants['i18n'] ) );
$this->profiles['photo_gallery']['role'] 	= array( 'type' => 'textbox', 'desc' => __( 'Please explain your role in the gallery (model, photographer, editor, etc).', $this->constants['i18n'] ) );
$this->profiles['custom_text']['box'] 		= array( 'type' => 'textarea', 'desc' => __( 'Please enter the text you want to appear on your profile. HTML is allowed.', $this->constants['i18n'] ) );
$this->profiles['rss']['feed_URL'] 			= array( 'type' => 'textbox', 'desc' => __( 'Please enter the URL for the RSS or Atom feed you want to appear on your profile.', $this->constants['i18n'] ) );

?>