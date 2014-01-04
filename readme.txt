=== BP Profile Widgets ===
Contributors: slushman
Donate link: http://slushman.com/
Tags: buddypress, widget, music, player, Bandcamp, Tunecore, Reverbnation, SoundCloud, Noisetrade, embed, profile, video, YouTube, Vimeo, Veoh, uStream, Blip.tv, Blip, DailyMotion, gallery, photo, photos, slideshow, Flickr, Picasa, Photobucket, Facebook, dotPhoto, Fotki, text, HTML, Smugmug, funnyordie.com, Revision3, Hulu, Viddler, Qik, Mixcloud, oEmbed
Requires at least: 2.9.1, Buddypress plugin
Tested up to: 3.6
Stable tag: 0.4.2
License: GPLv2

BP Profile Widgets allows BuddyPress users to embed a music player, video player, photo gallery, and/or a custom text widget on the sidebar of the user's profile page using custom profile fields from their profile form. This plugin requires that BuddyPress be installed and activated.

== Description ==

These widgets were originally developed for the Towermix Network at Belmont University's Curb College of Entertainment and Music Business. The BP Profile Widgets allow BuddyPress users to embed a music player, video player, photo gallery, and/or a custom text widget on the sidebar of the user's profile page using custom profile fields from their profile form. This plugin requires that BuddyPress be installed and activated. This plugin will create all the custom profile fields needed, based on which widgets are selected for use.

Features

* Users can embed one of the following music players: Bandcamp, Tunecore, Reverbnation, Noisetrade, Mixcloud, or SoundCloud.
* Users can embed one of the following slideshow galleries: Flickr, Picasa, Photobucket, Fotki, Smugmug, or dotPhoto.
* Users can embed one of the following video players: YouTube, Vimeo, Veoh, DailyMotion, Facebook, Flickr, Blip.tv, funnyordie.com, Hulu, Revision3.com, Viddler, Qik, and uStream
* Users can display a customizable text message, including HTML, in the Text Box widget
* Users can display a RSS feed on their profile in the RSS widget
* A description field is included for each media widget allowing the user to explain their involvement.
* Widget options for width and height of the slideshow.
* Widget options for width and aspect ratio of the video player.
* Widget options for item quantity for the RSS feed display.
* Uses WordPress functions to fetch the oEmbed media players.
* Uses WordPress transients to speed up loading players
* Widgets only appear on Profile pages
* Widgets can be hidden if they don't contain data

== Installation ==

1. Install and activate BuddyPress
2. Upload the BP Profile Widgets folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Check which widget(s) you want to use on the BP Profile Widgets page under the 'Settings' menu
5. Drag one of the BP Profile widgets to a sidebar on the 'Widgets' page under the 'Appearance' menu

== Frequently Asked Questions ==

= What will I need to do if I'm switching over from the previous versions of the BP Profile Widgets? =

This is a ground-up rewrite of each of the following plugins:
* BP Profile Music Widget
* BP Profile Video Widget
* BP Profile Text Widget
* BP Profile Gallery Widget

This plugin is mutually exclusive from those and will require your members to enter the proper URLs to make these players work. Fortunately, this new information is much simpler to find than digging through code to find obscure codes and IDs.


= Do I need to create any custom profile fields to make these widgets work? =

No. The plugin will create the "Profile Widgets" field group and any fields needed for the widget(s) you select on the plugin settings page.


= How do my users use these widgets? =

They will have two fields to fill out for each widget (except the Text Box widget): URL and Role.

The URL field will contain the URL for whatever relates to that widget.

The Role field is just a description of how the person relates to the content being shown. For example, on the Video Player widget, if they display a video, the Role field would explain what they did in/for the video: actor, writer, producer, etc.


= What players are available for the Music Player widget? =

Bandcamp, SoundCloud, Tunecore, Reverbnation, Mixcloud, or Noisetrade. The user will need to enter the correct URL to make it work properly:

* Bandcamp album: http://thevibedials.bandcamp.com/album/the-vibe-dials
* SoundCloud set: http://soundcloud.com/christopher-joel/sets/fantasy-world-1/
* Noisetrade public profile: http://noisetrade.com/thevibedials
* Reverbnation public profile: http://www.reverbnation.com/thevibedials
* Tunecore public profile: http://www.tunecore.com/music/thevibedials
* Mixcloud: http://www.mixcloud.com/MarvinHumes/marvins-jls-mixtape/


= What players are available for the Video Player widget? =

YouTube, Vimeo, Veoh, DailyMotion, Facebook, Flickr, Blip.tv, funnyordie.com, Hulu, Revision3.com, Viddler, Qik, and uStream. The user will need to enter the correct URL to make it work properly:

* YouTube long: http://www.youtube.com/watch?v=YYYJTlOYdm0
* Youtube short: http://youtu.be/YYYJTlOYdm0
* Vimeo: https://vimeo.com/37708663
* Veoh: http://www.veoh.com/watch/v21024172CTxdMmR4
* DailyMotion: http://www.dailymotion.com/video/xull3h_monster-roll_shortfilms
* Facebook: https://www.facebook.com/photo.php?v=10151182922174245
* Flickr: http://www.flickr.com/photos/riotking/2550468661
* Blip.tv: http://blip.tv/juliansmithtv/julian-smith-lottery-6362952
* uStream highlight: http://www.ustream.tv/recorded/26382325/highlight/299813
* uStream channel: http://www.ustream.tv/channel/3777978
* uStream: http://www.ustream.tv/recorded/32219761
* funnyordie.com: http://www.funnyordie.com/videos/5764ccf637/daft-punk-andrew-the-pizza-guy?playlist=featured_videos
* Hulu: http://www.hulu.com/watch/486928
* Revision3: http://revision3.com/destructoid/bl2-dlc-leak-tiny-tinas-assault-on-dragon-keep
* Viddler: http://www.viddler.com/v/bdce8c7
* Qik: http://qik.com/video/38782012


= What galleries are available for the Photo Gallery widget? =

Flickr, Picasa, Photobucket, Fotki, Smugmug, and dotPhoto. The user will need to enter the correct URL to make it work properly:

* Flickr set: http://www.flickr.com/photos/christopherjoel/sets/72157617395267762
* Picasa gallery: https://picasaweb.google.com/114838808449834204603/Fender72ThinlineRI?authuser=0&feat=directlink
* Photobucket album: http://s262.beta.photobucket.com/user/mandy_surfergirl91/library/CARS
* Fotki public gallery: http://public.fotki.com/sandylferguson/eagle-scout-project/
* dotPhoto album link: http://www.dotphoto.com/go.asp?l=hubble&SID=245780&Show=Y&p=
* Smugmug: http://belmontphoto.smugmug.com/BelmontStockPhotos/Campus/Construction-Belmont-Heights/17820170_drJWcW


= Can people use HTML in the Text Box Widget? =

Yes.


= Could I use the Text Widget for another player of some kind? =

Unfortunately not. The santization for the text field removes embed codes and the like. Simple HTML (like links) still works.


= How do I hide these custom profile fields so they don't show on people's profile pages? =

The profile fields created by this plugin are set to only be seen by the user and admin's only. People looking at a user's profile page shouldn't see any of these fields.


= How do I make one or all of the widgets appear only on the user's profile page? =

This has changed as of version 0.2. Now, the widgets will only appear on the profile pages.



== Screenshots ==

1. Music Player Widget options
2. Photo Gallery Widget options
3. Video Player Widget options
4. Text Widget options
5. Bandcamp player
6. Tunecore player
7. Reverbnation player
8. Noisetrade player
9. Soundcloud Set player
10. Flickr Slideshow
11. Picasa Slideshow
12. Photobucket Slideshow
13. YouTube player
14. Vimeo player
15. Facebook player
16. Text Widget on page

== Changelog ==

= 0.4.2 =
* BUG FIX: Changed find_service_on_page() to actually work.

= 0.4.1 =
* BUG FIX: Added PHP config checking to resolve server errors with fetching service and IDs from URLs
* Separated find_ID_on_page into three separate functions to allow for PHP config differences

= 0.4 =
* Added RSS feed widget to display an RSS feed on a profile page
* Changed Music Player widget to support custom URLs (like for Bandcamp)
* Added find_service() function to expand options for determining the service
* Added find_service_from_url() function to music player widget
* Added find_service_on_page() function to music player widget
* Added find_ID_on_page() function to music player widget
* Made wp_remote_get() a separate variable in the toolkit function find_on_page()
* Music player service and ID are stored in a transients to make page reloading faster
* BUG FIX: Removed URL echo from video widget - oops.

= 0.3.1 =
* BUG FIX: Updated the album ID search parameters for the version 2 player.

= 0.3 =
* BUG FIX: Profile fields were leaving data in the database when widgets were unchecked. This has now been resolved, all databases entries related to a profile field are removed when a widget is unchecked.
* Each widget can now be hidden if the data for that widget is empty instead of displaying the empty message.

= 0.2 =
* Widgets now only appear on profile pages.

= 0.11 =
* Added config.php.

= 0.1 =
* Plugin created.

== Upgrade Notice ==

= 0.4.2 =
* BUG FIX: Changed find_service_on_page() to work with server configs.

= 0.4.1 =
BUG FIX: Fixed server config errors with fetching service and IDs from URLs in music player widget

= 0.4 =
Added an RSS feed widget and bug fixes

= 0.3.1 =
BUG FIX: Updated the album ID search parameters for the version 2 player.

= 0.3 =
BUG FIX: All data for profile fields are now removed when widgets are unchecked.

= 0.2 =
Widgets now only appear on profile pages.

= 0.11 =
Added config.php to get rid of include error.

= 0.1 =
Plugin released.