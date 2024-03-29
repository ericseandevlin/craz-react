=== thePlatform Video Manager ===
Developed By: thePlatform LLC
Tags: embedding, video, embed, portal, theplatform, shortcode
Requires at least: 3.7
Tested up to: 4.3
Stable tag: 2.1.0
Contributors: thePlatform

Manage your content hosted by thePlatform and embed media in WordPress posts.

== Description ==
View your content hosted by thePlatform and easily embed videos from your library in WordPress posts, modify media metadata, and upload new media.

== Installation ==
Copy the folder "thePlatform-video-manager" with all included files into the "wp-content/plugins" folder of WordPress. Activate the plugin and set your mpx credentials in the plugin settings interface.

== Screenshots ==
Manage and Edit Media in mpx
Upload Media to mpx directly from WordPress
Choosing different metadata fields
Easily embed videos from mpx into your posts

== Changelog ==

= 2.1.0 =
Added Advanced Settings
Added new shortcode parameters
Simplified account settings
Load Data Service URLs from the Account Registry
Added a new option to embed the PDK external controller in posts with our video player
Fixed bug in which categories containing a colon or comma in their names were not handled correctly during the media fetch

= 2.0.0 =
Removed 3rd party libraries in favor of native Wordpress look and feel
Refactor the plugin to prevent incompatabilities with other Wordpress plugins
Simplified user capabilities

= 1.4.0 =
Added the ability to Publish, Revoke and Add Files to existing Media via the Edit dialog
Added Thumbnailing functionality
Support sorting search results
Updating media no longer requires to refresh the entire media browser
Added a button to reset plugin settings in the About page
Uploads have been greatly sped up

= 1.3.2 =
Fixed uploads in Firefox
Correctly set the preview player in the Media Browser

= 1.3.1 =
Fixed an issue with the update method copying Basic Metadata settings incorrectly.

= 1.3.0 =
Allow multiple files to be uploaded
Complete update to the plugin UX. Fixed numerous layouting issues across all the different pages
The video upload dialog has been completely redesigned
Video uploads should no longer fail randomly
Support a wider range of file formats
Admins can choose the where the embed button should appear - media_buttons, tinymce plugin or both
Fixed an issue where the shortcode did not append correctly in the text editor
Accessing the plugin settings is now about 40% faster
Media outside the availability window will now show in the media browser
Admins can choose to display either the username, full name or email address instead of the numerical user id.
Fixed the autoPlay shortcode attribute
Admins can choose the Player embed type - either full player or a single embedded player
Disabled players no longer show up in the Players dropdown

= 1.2.3 =
Fix uploads sporadically not working in HTTPS

= 1.2.2 =
Changed thePlatform's menu order number
Fix references to ajaxurl
Fix an issue where the Wordpress bar disappears in the about page

= 1.2.1 =
Renamed tabs in the plugin settings
Disabled oLark in the plugin AJAX loaded pages

= 1.2.0 =
Account settings are now separate from the rest of the plugin preferences - Note this will require reconfiguring the plugin
Added an About page
Plugin settings are now cleaned up when switching accounts or deactivating
Plugin settings now gracefully fall back when login fails
Added support for EU accounts
Updated metadata and upload field settings to allow Read/Write/Hide
Default values are now provided for player ID and upload server ID when account is selected
Fixed a bug where publishing profiles didn't work if they existing in more than one authorized account
Added a new setting section - Embedding options
Removed Full Video/Embed only setting
Categories are now sorted by title instead of fullTitle
Moved embed and edit buttons from the media into the metadata container
Added a feature to set the featured image from the video thumbnail
Completely redesigned the Upload, Browse, Edit and Embed pages
Reworked plugin settings to match the new UI
Verified up to WordPress 3.9
Fixed Uploading issues
Disabled unsupported Metadata fields
Moved all mpx related functionality to it's own Menu slug
Finer control over user capabilities:
'tp_viewer_cap', 'edit_posts' - View the mpx Media Browser
'tp_embedder_cap', 'edit_posts' - Embed mpx media into a post
'tp_editor_cap', 'upload_files' - Edit mpx Media
'tp_uploader_cap' - 'upload_files' - Upload mpx media
'tp_admin_cap', 'manage_options' - Manage thePlatform's plugin settings
Moved the embedding button into a TinyMCE plugin

= 1.1.1 =
Fixed an issue where files would not always upload

= 1.1.0 =
Added an option to submit the Wordpress User ID into a custom field and filter by it
Moved uploads to a popup window
Added Pagination to the media views.
Support for custom fields in editing and uploading.
Add multiple categories during upload and editing.
Added a filter for our embed output, tp_embed_code - The complete embed code
Added a filter for our base embed URL, tp_base_embed_url - Just the player URL
Added a filter for our full embed URL, tp_full_embed_url - The player URL with all parameters, applied after tp_base_embed_url
Added filters for user capabilities:
'tp_publisher_cap' - 'upload_files' - Upload mpx media
'tp_editor_cap', 'upload_files' - Edit mpx Media and display the Media Manager
'tp_admin_cap', 'manage_options' - Manage thePlatform's plugin settings
'tp_embedder_cap', 'edit_posts' - Embed mpx media into a post
Embed shortcode now supports arbitary parameters
Removed Query by custom fields
Removed mpx Namespace option
Fixed over-zealous cap checks - This should fix the user invite workflow issues
Fixed settings page being loaded on every adming page request
Resized the media preview in edit mode
Cleaned up the options page, hiding PID options
Cleaned up some API calls
Layout and UX enhancements
Upload/custom fields default to Omit instead of Allow

= 1.0.0 =
Initial release

== Short code parameters ==
account	 - (optional) - Account PID to use in the embed code, if omitted it will be taken from the account settings. It is highly recommended to keep this on all shortcodes
player	 - (required) - Player PID to use in the embed code
media	 - (required) - Release PID to load in the player
width	 - (optional) - Player width, if omitted the default value will be taken from the embedding preferences
height	 - (optional) - Player height, if omitted the default value will be taken from the embedding preferences
mute	 - (optional) - Force the player to be muted
autoplay - (optional) - Force autoplay on /embed/ players, if omitted the default value will be taken from the embedding preferences
tag		 - (optional) - iframe/script, if omitted the value will be taken from the embedding preferences
embedded - (optional) - true/false, if true the player will have /embed in the URI
playall - (optional) - true/false, if true, the player will attempt to play all videos in its feed
instance - (optional) - Appends the value as an instance parameter on the player
params	 - (optional) - Custom string that will be appended to the embed URL

== Configuration ==
This plugin requires an account with thePlatform's mpx. Please contact your Account Manager for additional information.

= mpx Account Options =
mpx Username - The mpx username to use for all of the plugin capabilities
mpx Password - The password for the entered mpx username
mpx Account - The mpx account to upload and retrieve media from

= Embedding Preferences =
Default Player - The default player used for embedding and in the Media Browser
Embed Tag Type - IFrame or Script embed
Use PDK External Controller - Load the external controller in posts that have our as an IFrame
Media Embed Type - Embed Media by the Media PID, Release PID or Media GUID.
Player Embed Type - Video Only (/embed/) or Full Player
RSS Embed Type - In an RSS feed, provide a link back to the Article, or an iframe/script tag
Force Autoplay - Pass the autoplay parameter to embedded players
Default Player Width - Initially based on the current theme content width
Default Player Height - a 1.78 aspect ratio value based on the content width

= General Preferences =
Filter Users Own Video - Filter by the User ID custom field, ignored if the User ID is blank
User ID Custom Field - Name of the Custom Field to store the Wordpress User ID, (None) to disable
Show User ID as - If the User ID Custom Field is visible to editors, we will substitute it by either the user Full Name, Email, Nickname or Username
Plugin Embed button type - Determine if thePlatform button should appear as a media_button, a TinyMCE button, both or not at all
mpx Upload Server - Default mpx server to upload new media to, Default Server will attempt to intelligently pick a server
Default Publish Profile - If set, uploaded media will automatically publish to the selected profile
Thumbnail Encoding Profile - The Encoding Profile to use when generating new thumbnails via the plugin

= Advanced Settings =
Media per Page - The total number of Media to return for each page in the Media Browser
Upload Fragment Size - Controls the size of each file fragments during upload. Smaller fragments may be faster on small videos.
Append Players with a random Instance parameter - This will automatically append an Instance parameter on every player when the shortcode is evaluated

= Filters =
tp_base_embed_url - Just the player URL
tp_full_embed_url - The player URL with all parameters, applied after tp_base_embed_url
tp_embed_code - The complete embed code, with surrounding HTML, applied after tp_full_embed_url
tp_rss_embed_code - The full embed code used for a RSS feed
tp_editor_cap, default - 'edit_posts' - Edit mpx Media
tp_uploader_cap - default - 'upload_files' - Upload mpx media
tp_admin_cap, default - 'manage_options' - Manage thePlatform's plugin settings
