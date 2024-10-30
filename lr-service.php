<?php
/**
 * Plugin Name: LR Services
 * Version: 1.0
 * Description: LR services to the admin panel which allows you to show your service on your website the easy with deffernt styles
 * Author: Logicrays
 * Author URI: http://logicrays.com/
 */
 
define("lr-service","lr_service" );
define('lr_service_plugin_url', plugins_url('', __FILE__));
ini_set('allow_url_fopen',1);

add_action('admin_menu' , 'lr_service_settings_page');

function lr_service_settings_page() {
 add_submenu_page('edit.php?post_type=lrservice', __('Settings', 'lr-service'), __('Settings', 'lr-service'), 'manage_options', 'lr-service-setting-page', 'lr_service_setting_page');
 add_submenu_page('edit.php?post_type=lrservice', __('LR Free Plugins', 'lr-service'), __('LR Free Plugins', 'lr-service'), 'manage_options', 'lr-free-plugins', 'lr_free_plugins_page');
}
add_action( 'plugin_action_links_' . plugin_basename(__FILE__), 'lr_service_action_links' );

function lr_free_plugins_page(){
	include_once 'lr-free-plugins.php';
}
function lr_service_action_links( $links ) {
 $links = array_merge( array(
  '<a href="' . esc_url( admin_url( '/edit.php?post_type=lrservice&page=lr-service-setting-page' ) ) . '">' . __( 'Settings', 'lr-service' ) . '</a>'
 ), $links );
 return $links;
}

function lr_service_setting_page(){?>
<div class="wrap">
<div class="icon32" id="icon-options-general"><br>
</div>
<h3>LR Service Options [Shortcode: [LRSERVICES] ]</h3>
<form action="options.php" method="post">
<?php
settings_fields("section");
?>
<?php
do_settings_sections("service-options");
submit_button();
?>
</form>
</div>
<?php
}
add_action("admin_init", "lr_service_fields");
function lr_service_fields()
{
	add_settings_section("section", "All Settings", null, "service-options");	
	add_settings_field("lr_service_layout", "Service Layout", "lr_service_layout_element", "service-options", "section");
	add_settings_field("lr_service_style", "Service Style", "lr_service_style_element", "service-options", "section");
	add_settings_field("lr_service_preview", "Preview Style", "lr_service_preview_element", "service-options", "section");
	add_settings_field("lr_rmore_layout", "Read more link", "lr_service_read_more_element", "service-options", "section");
	add_settings_field("lr_service_custom_css", "Custom css", "lr_service_custom_css_element", "service-options", "section");
	
	register_setting("section", "lr_service_layout");
	register_setting("section", "lr_service_style");
	register_setting("section", "lr_service_preview");
	register_setting("section", "lr_rmore_layout");
	register_setting("section", "lr_service_custom_css");
}
function lrservice_style() {
	wp_enqueue_style('bootstrap-min', lr_service_plugin_url.'/css/bootstrap.min.css');
	wp_enqueue_style('font-awesome-min', lr_service_plugin_url.'/css/font-awesome.min.css');
	wp_enqueue_script( 'bootstrap-min', lr_service_plugin_url.'/js/bootstrap.min.js');
}
add_action( 'wp_head', 'lrservice_style' );

function lrservice_admin_style() {
	wp_enqueue_style('font-awesome-min', lr_service_plugin_url.'/css/font-awesome.min.css');
}
add_action( 'admin_head', 'lrservice_admin_style' );

include_once 'includes/lr-service-style.php';

$lr_service_style = get_option('lr_service_style');
if($lr_service_style ){
if($lr_service_style['lr_service_style'] == '1'){
	add_action( 'wp_head', 'lrservice_style1' );
}
if($lr_service_style['lr_service_style'] == '2'){
	add_action( 'wp_head', 'lrservice_style2' );
}
if($lr_service_style['lr_service_style'] == '3'){
	add_action( 'wp_head', 'lrservice_style3' );
}
if($lr_service_style['lr_service_style'] == '4'){
	add_action( 'wp_head', 'lrservice_style4' );
}
}
function lrservice_style1() {
	wp_enqueue_style('service-style1', lr_service_plugin_url.'/css/style1.css');	
}
function lrservice_style2() {
	wp_enqueue_style('service-style2', lr_service_plugin_url.'/css/style2.css');
}
function lrservice_style3() {
	wp_enqueue_style('service-style3', lr_service_plugin_url.'/css/style3.css');
}
function lrservice_style4() {
	wp_enqueue_style('service-style4', lr_service_plugin_url.'/css/style4.css');
}

add_action( 'init', 'lr_services' );
function lr_services() {
    $labels = array(
        'name' => 'LR Services',
        'singular_name' => 'LR Service',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New',
        'edit_item' => 'Edit service',
        'new_item' => 'New service',
        'view_item' => 'View service',
        'search_items' => 'Search services',
        'not_found' =>  'No services found',
        'not_found_in_trash' => 'No services in the trash'
    );
    register_post_type( 'lrservice', array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'exclude_from_search' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 10,
        'supports' => array( 'title', 'editor' ),
		'register_meta_box_cb' => 'lr_service_add_metaboxes',
  	) );
}

function lr_service_add_metaboxes(){
 add_meta_box('service_icon','Select Service Icon','service_icon_callback','lrservice','normal','high');
}
add_action('add_meta_boxes', 'lr_service_add_metaboxes');

function service_icon_callback( $post ) {
    wp_nonce_field( 'service_icon_metabox_nonce', 'service_icon_nonce'); ?>
	<?php         
    $icon = array(
			'no-icon'=> 'No-Icon',
			'fa-adjust' => 'Adjust',
			'fa-adn' => 'Adn',
			'fa-align-center' => 'Align Center',
			'fa-align-justify' => 'Align Justify',
			'fa-align-left' => 'Align Left',
			'fa-align-right' => 'Align Right',
			'fa-ambulance' => 'Ambulance',
			'fa-anchor' => 'Anchor',
			'fa-android' => 'Android',
			'fa-angle-double-down' => 'Angle Double Down',
			'fa-angle-double-left' => 'Angle Double Left',
			'fa-angle-double-right' => 'Angle Double Right',
			'fa-angle-double-up' => 'Angle Double Up',
			'fa-angle-down' => 'Angle Down',
			'fa-angle-left' => 'Angle Left',
			'fa-angle-right' => 'Angle Right',
			'fa-angle-up' => 'Angle Up',
			'fa-apple' => 'Apple',
			'fa-archive' => 'Archive',
			'fa-arrow-circle-down' => 'Arrow Circle Down',
			'fa-arrow-circle-left' => 'Arrow Circle Left',
			'fa-arrow-circle-o-down' => 'Arrow Circle O Down',
			'fa-arrow-circle-o-left' => 'Arrow Circle O Left',
			'fa-arrow-circle-o-right' => 'Arrow Circle O Right',
			'fa-arrow-circle-o-up' => 'Arrow Circle O Up',
			'fa-arrow-circle-right' => 'Arrow Circle Right',
			'fa-arrow-circle-up' => 'Arrow Circle Up',
			'fa-arrow-down' => 'Arrow Down',
			'fa-arrow-left' => 'Arrow Left',
			'fa-arrow-right' => 'Arrow Right',
			'fa-arrows' => 'Arrows',
			'fa-arrows-alt' => 'Arrows Alt',
			'fa-arrows-h' => 'Arrows H',
			'fa-arrows-v' => 'Arrows V',
			'fa-arrow-up' => 'Arrow Up',
			'fa-asterisk' => 'Asterisk',
			'fa-automobile' => 'Automobile',
			'fa-backward' => 'Backward',
			'fa-ban' => 'Ban',
			'fa-bank' => 'Bank',
			'fa-bar-chart-o' => 'Bar Chart O',
			'fa-barcode' => 'Barcode',
			'fa-bars' => 'Bars',
			'fa-bed' => 'Bed',
			'fa-bed' => 'Hotel',
			'fa-beer' => 'Beer',
			'fa-behance' => 'Behance',
			'fa-behance-square' => 'Behance Square',
			'fa-bell' => 'Bell',
			'fa-bell-o' => 'Bell O',
			'fa-bitbucket' => 'Bitbucket',
			'fa-bitbucket-square' => 'Bitbucket Square',
			'fa-bitcoin' => 'Bitcoin',
			'fa-bold' => 'Bold',
			'fa-bolt' => 'Bolt',
			'fa-bomb' => 'Bomb',
			'fa-book' => 'Book',
			'fa-bookmark' => 'Bookmark',
			'fa-bookmark-o' => 'Bookmark O',
			'fa-briefcase' => 'Briefcase',
			'fa-btc' => 'Btc',
			'fa-bug' => 'Bug',
			'fa-building' => 'Building',
			'fa-building-o' => 'Building O',
			'fa-bullhorn' => 'Bullhorn',
			'fa-bullseye' => 'Bullseye',
			'fa-buysellads' => 'Buysellads',
			'fa-cab' => 'Cab',
			'fa-calendar' => 'Calendar',
			'fa-calendar-o' => 'Calendar O',
			'fa-camera' => 'Camera',
			'fa-camera-retro' => 'Camera Retro',
			'fa-car' => 'Car',
			'fa-caret-down' => 'Caret Down',
			'fa-caret-left' => 'Caret Left',
			'fa-caret-right' => 'Caret Right',
			'fa-caret-square-o-down' => 'Caret Square O Down',
			'fa-caret-square-o-left' => 'Caret Square O Left',
			'fa-caret-square-o-right' => 'Caret Square O Right',
			'fa-caret-square-o-up' => 'Caret Square O Up',
			'fa-caret-up' => 'Caret Up',
			'fa-cart-arrow-down' => 'Cart Arrow Down',
			'fa-cart-plus' => 'Cart Plus',
			'fa-certificate' => 'Certificate',
			'fa-chain' => 'Chain',
			'fa-chain-broken' => 'Chain Broken',
			'fa-check' => 'Check',
			'fa-check-circle' => 'Check Circle',
			'fa-check-circle-o' => 'Check Circle O',
			'fa-check-square' => 'Check Square',
			'fa-check-square-o' => 'Check Square O',
			'fa-chevron-circle-down' => 'Chevron Circle Down',
			'fa-chevron-circle-left' => 'Chevron Circle Left',
			'fa-chevron-circle-right' => 'Chevron Circle Right',
			'fa-chevron-circle-up' => 'Chevron Circle Up',
			'fa-chevron-down' => 'Chevron Down',
			'fa-chevron-left' => 'Chevron Left',
			'fa-chevron-right' => 'Chevron Right',
			'fa-chevron-up' => 'Chevron Up',
			'fa-child' => 'Child',
			'fa-circle' => 'Circle',
			'fa-circle-o' => 'Circle O',
			'fa-circle-o-notch' => 'Circle O Notch',
			'fa-circle-thin' => 'Circle Thin',
			'fa-clipboard' => 'Clipboard',
			'fa-clock-o' => 'Clock O',
			'fa-cloud' => 'Cloud',
			'fa-cloud-download' => 'Cloud Download',
			'fa-cloud-upload' => 'Cloud Upload',
			'fa-cny' => 'Cny',
			'fa-code' => 'Code',
			'fa-code-fork' => 'Code Fork',
			'fa-codepen' => 'Codepen',
			'fa-coffee' => 'Coffee',
			'fa-cog' => 'Cog',
			'fa-cogs' => 'Cogs',
			'fa-columns' => 'Columns',
			'fa-comment' => 'Comment',
			'fa-comment-o' => 'Comment O',
			'fa-comments' => 'Comments',
			'fa-comments-o' => 'Comments O',
			'fa-compass' => 'Compass',
			'fa-compress' => 'Compress',
			'fa-connectdevelop' => 'Connectdevelop',
			'fa-copy' => 'Copy',
			'fa-credit-card' => 'Credit Card',
			'fa-crop' => 'Crop',
			'fa-crosshairs' => 'Crosshairs',
			'fa-css3' => 'Css3',
			'fa-cube' => 'Cube',
			'fa-cubes' => 'Cubes',
			'fa-cut' => 'Cut',
			'fa-cutlery' => 'Cutlery',
			'fa-dashboard' => 'Dashboard',
			'fa-dashcube' => 'Dashcube',
			'fa-database' => 'Database',
			'fa-dedent' => 'Dedent',
			'fa-delicious' => 'Delicious',
			'fa-desktop' => 'Desktop',
			'fa-deviantart' => 'Deviantart',
			'fa-diamond' => 'Diamond',
			'fa-digg' => 'Digg',
			'fa-dollar' => 'Dollar',
			'fa-dot-circle-o' => 'Dot Circle O',
			'fa-download' => 'Download',
			'fa-dribbble' => 'Dribbble',
			'fa-dropbox' => 'Dropbox',
			'fa-drupal' => 'Drupal',
			'fa-edit' => 'Edit',
			'fa-eject' => 'Eject',
			'fa-ellipsis-h' => 'Ellipsis H',
			'fa-ellipsis-v' => 'Ellipsis V',
			'fa-empire' => 'Empire',
			'fa-envelope' => 'Envelope',
			'fa-envelope-o' => 'Envelope O',
			'fa-envelope-square' => 'Envelope Square',
			'fa-eraser' => 'Eraser',
			'fa-eur' => 'Eur',
			'fa-euro' => 'Euro',
			'fa-exchange' => 'Exchange',
			'fa-exclamation' => 'Exclamation',
			'fa-exclamation-circle' => 'Exclamation Circle',
			'fa-exclamation-triangle' => 'Exclamation Triangle',
			'fa-expand' => 'Expand',
			'fa-external-link' => 'External Link',
			'fa-external-link-square' => 'External Link Square',
			'fa-eye' => 'Eye',
			'fa-eye-slash' => 'Eye Slash',
			'fa-facebook' => 'Facebook',
			'fa-facebook-official' => 'Facebook Official',
			'fa-facebook-square' => 'Facebook Square',
			'fa-fast-backward' => 'Fast Backward',
			'fa-fast-forward' => 'Fast Forward',
			'fa-fax' => 'Fax',
			'fa-female' => 'Female',
			'fa-fighter-jet' => 'Fighter Jet',
			'fa-file' => 'File',
			'fa-file-archive-o' => 'File Archive O',
			'fa-file-audio-o' => 'File Audio O',
			'fa-file-code-o' => 'File Code O',
			'fa-file-excel-o' => 'File Excel O',
			'fa-file-image-o' => 'File Image O',
			'fa-file-movie-o' => 'File Movie O',
			'fa-file-o' => 'File O',
			'fa-file-pdf-o' => 'File Pdf O',
			'fa-file-photo-o' => 'File Photo O',
			'fa-file-picture-o' => 'File Picture O',
			'fa-file-powerpoint-o' => 'File Powerpoint O',
			'fa-files-o' => 'Files O',
			'fa-file-sound-o' => 'File Sound O',
			'fa-file-text' => 'File Text',
			'fa-file-text-o' => 'File Text O',
			'fa-file-video-o' => 'File Video O',
			'fa-file-word-o' => 'File Word O',
			'fa-file-zip-o' => 'File Zip O',
			'fa-film' => 'Film',
			'fa-filter' => 'Filter',
			'fa-fire' => 'Fire',
			'fa-fire-extinguisher' => 'Fire Extinguisher',
			'fa-flag' => 'Flag',
			'fa-flag-checkered' => 'Flag Checkered',
			'fa-flag-o' => 'Flag O',
			'fa-flash' => 'Flash',
			'fa-flask' => 'Flask',
			'fa-flickr' => 'Flickr',
			'fa-floppy-o' => 'Floppy O',
			'fa-folder' => 'Folder',
			'fa-folder-o' => 'Folder O',
			'fa-folder-open' => 'Folder Open',
			'fa-folder-open-o' => 'Folder Open O',
			'fa-font' => 'Font',
			'fa-forumbee' => 'Forumbee',
			'fa-forward' => 'Forward',
			'fa-foursquare' => 'Foursquare',
			'fa-frown-o' => 'Frown O',
			'fa-gamepad' => 'Gamepad',
			'fa-gavel' => 'Gavel',
			'fa-gbp' => 'Gbp',
			'fa-ge' => 'Ge',
			'fa-gear' => 'Gear',
			'fa-gears' => 'Gears',
			'fa-gift' => 'Gift',
			'fa-git' => 'Git',
			'fa-github' => 'Github',
			'fa-github-alt' => 'Github Alt',
			'fa-github-square' => 'Github Square',
			'fa-git-square' => 'Git Square',
			'fa-gittip' => 'Gittip',
			'fa-glass' => 'Glass',
			'fa-globe' => 'Globe',
			'fa-google' => 'Google',
			'fa-google-plus' => 'Google Plus',
			'fa-google-plus-square' => 'Google Plus Square',
			'fa-graduation-cap' => 'Graduation Cap',
			'fa-group' => 'Group',
			'fa-hacker-news' => 'Hacker News',
			'fa-hand-o-down' => 'Hand O Down',
			'fa-hand-o-left' => 'Hand O Left',
			'fa-hand-o-right' => 'Hand O Right',
			'fa-hand-o-up' => 'Hand O Up',
			'fa-hdd-o' => 'Hdd O',
			'fa-header' => 'Header',
			'fa-headphones' => 'Headphones',
			'fa-heart' => 'Heart',
			'fa-heartbeat' => 'Heartbeat',
			'fa-heart-o' => 'Heart O',
			'fa-history' => 'History',
			'fa-home' => 'Home',
			'fa-hospital-o' => 'Hospital O',
			'fa-h-square' => 'H Square',
			'fa-html5' => 'Html5',
			'fa-image' => 'Image',
			'fa-inbox' => 'Inbox',
			'fa-indent' => 'Indent',
			'fa-info' => 'Info',
			'fa-info-circle' => 'Info Circle',
			'fa-inr' => 'Inr',
			'fa-instagram' => 'Instagram',
			'fa-institution' => 'Institution',
			'fa-italic' => 'Italic',
			'fa-joomla' => 'Joomla',
			'fa-jpy' => 'Jpy',
			'fa-jsfiddle' => 'Jsfiddle',
			'fa-key' => 'Key',
			'fa-keyboard-o' => 'Keyboard O',
			'fa-krw' => 'Krw',
			'fa-language' => 'Language',
			'fa-laptop' => 'Laptop',
			'fa-leaf' => 'Leaf',
			'fa-leanpub' => 'Leanpub',
			'fa-legal' => 'Legal',
			'fa-lemon-o' => 'Lemon O',
			'fa-level-down' => 'Level Down',
			'fa-level-up' => 'Level Up',
			'fa-life-bouy' => 'Life Bouy',
			'fa-life-ring' => 'Life Ring',
			'fa-life-saver' => 'Life Saver',
			'fa-lightbulb-o' => 'Lightbulb O',
			'fa-link' => 'Link',
			'fa-linkedin' => 'Linkedin',
			'fa-linkedin-square' => 'Linkedin Square',
			'fa-linux' => 'Linux',
			'fa-list' => 'List',
			'fa-list-alt' => 'List Alt',
			'fa-list-ol' => 'List Ol',
			'fa-list-ul' => 'List Ul',
			'fa-location-arrow' => 'Location Arrow',
			'fa-lock' => 'Lock',
			'fa-long-arrow-down' => 'Long Arrow Down',
			'fa-long-arrow-left' => 'Long Arrow Left',
			'fa-long-arrow-right' => 'Long Arrow Right',
			'fa-long-arrow-up' => 'Long Arrow Up',
			'fa-magic' => 'Magic',
			'fa-magnet' => 'Magnet',
			'fa-mail-forward' => 'Mail Forward',
			'fa-mail-reply' => 'Mail Reply',
			'fa-mail-reply-all' => 'Mail Reply All',
			'fa-male' => 'Male',
			'fa-map-marker' => 'Map Marker',
			'fa-mars' => 'Mars',
			'fa-mars-double' => 'Mars Double',
			'fa-mars-stroke' => 'Mars Stroke',
			'fa-mars-stroke-h' => 'Mars Stroke H',
			'fa-mars-stroke-v' => 'Mars Stroke V',
			'fa-maxcdn' => 'Maxcdn',
			'fa-medium' => 'Medium',
			'fa-medkit' => 'Medkit',
			'fa-meh-o' => 'Meh O',
			'fa-mercury' => 'Mercury',
			'fa-microphone' => 'Microphone',
			'fa-microphone-slash' => 'Microphone Slash',
			'fa-minus' => 'Minus',
			'fa-minus-circle' => 'Minus Circle',
			'fa-minus-square' => 'Minus Square',
			'fa-minus-square-o' => 'Minus Square O',
			'fa-mobile' => 'Mobile',
			'fa-mobile-phone' => 'Mobile Phone',
			'fa-money' => 'Money',
			'fa-moon-o' => 'Moon O',
			'fa-mortar-board' => 'Mortar Board',
			'fa-motorcycle' => 'Motorcycle',
			'fa-music' => 'Music',
			'fa-navicon' => 'Navicon',
			'fa-neuter' => 'Fa Neuter',
			'fa-openid' => 'Openid',
			'fa-outdent' => 'Outdent',
			'fa-pagelines' => 'Pagelines',
			'fa-paperclip' => 'Paperclip',
			'fa-paper-plane' => 'Paper Plane',
			'fa-paper-plane-o' => 'Paper Plane O',
			'fa-paragraph' => 'Paragraph',
			'fa-paste' => 'Paste',
			'fa-pause' => 'Pause',
			'fa-paw' => 'Paw',
			'fa-pencil' => 'Pencil',
			'fa-pencil-square' => 'Pencil Square',
			'fa-pencil-square-o' => 'Pencil Square O',
			'fa-phone' => 'Phone',
			'fa-phone-square' => 'Phone Square',
			'fa-photo' => 'Photo',
			'fa-picture-o' => 'Picture O',
			'fa-pied-piper' => 'Pied Piper',
			'fa-pied-piper-alt' => 'Pied Piper Alt',
			'fa-pied-piper-square' => 'Pied Piper Square',
			'fa-pinterest' => 'Pinterest',
			'fa-pinterest-p' => 'Pinterest P',
			'fa-pinterest-square' => 'Pinterest Square',
			'fa-plane' => 'Plane',
			'fa-play' => 'Play',
			'fa-play-circle' => 'Play Circle',
			'fa-play-circle-o' => 'Play Circle O',
			'fa-plus' => 'Plus',
			'fa-plus-circle' => 'Plus Circle',
			'fa-plus-square' => 'Plus Square',
			'fa-plus-square-o' => 'Plus Square O',
			'fa-power-off' => 'Power Off',
			'fa-print' => 'Print',
			'fa-puzzle-piece' => 'Puzzle Piece',
			'fa-qq' => 'Qq',
			'fa-qrcode' => 'Qrcode',
			'fa-question' => 'Question',
			'fa-question-circle' => 'Question Circle',
			'fa-quote-left' => 'Quote Left',
			'fa-quote-right' => 'Quote Right',
			'fa-ra' => 'Ra',
			'fa-random' => 'Random',
			'fa-rebel' => 'Rebel',
			'fa-recycle' => 'Recycle',
			'fa-reddit' => 'Reddit',
			'fa-reddit-square' => 'Reddit Square',
			'fa-refresh' => 'Refresh',
			'fa-renren' => 'Renren',
			'fa-reorder' => 'Reorder',
			'fa-repeat' => 'Repeat',
			'fa-reply' => 'Reply',
			'fa-reply-all' => 'Reply All',
			'fa-retweet' => 'Retweet',
			'fa-rmb' => 'Rmb',
			'fa-road' => 'Road',
			'fa-rocket' => 'Rocket',
			'fa-rotate-left' => 'Rotate Left',
			'fa-rotate-right' => 'Rotate Right',
			'fa-rouble' => 'Rouble',
			'fa-rss' => 'Rss',
			'fa-rss-square' => 'Rss Square',
			'fa-rub' => 'Rub',
			'fa-ruble' => 'Ruble',
			'fa-rupee' => 'Rupee',
			'fa-save' => 'Save',
			'fa-scissors' => 'Scissors',
			'fa-search' => 'Search',
			'fa-search-minus' => 'Search Minus',
			'fa-search-plus' => 'Search Plus',
			'fa-sellsy' => 'Sellsy',
			'fa-send' => 'Send',
			'fa-send-o' => 'Send O',
			'fa-server' => 'Fa Server',
			'fa-share' => 'Share',
			'fa-share-alt' => 'Share Alt',
			'fa-share-alt-square' => 'Share Alt Square',
			'fa-share-square' => 'Share Square',
			'fa-share-square-o' => 'Share Square O',
			'fa-shield' => 'Shield',
			'fa-ship' => 'Ship',
			'fa-shirtsinbulk' => 'Shirtsinbulk',
			'fa-shopping-cart' => 'Shopping Cart',
			'fa-signal' => 'Signal',
			'fa-sign-in' => 'Sign In',
			'fa-sign-out' => 'Sign Out',
			'fa-simplybuilt' => 'Simplybuilt',
			'fa-sitemap' => 'Sitemap',
			'fa-skyatlas' => 'Skyatlas',
			'fa-skype' => 'Skype',
			'fa-slack' => 'Slack',
			'fa-sliders' => 'Sliders',
			'fa-smile-o' => 'Smile O',
			'fa-sort' => 'Sort',
			'fa-sort-alpha-asc' => 'Sort Alpha Asc',
			'fa-sort-alpha-desc' => 'Sort Alpha Desc',
			'fa-sort-amount-asc' => 'Sort Amount Asc',
			'fa-sort-amount-desc' => 'Sort Amount Desc',
			'fa-sort-asc' => 'Sort Asc',
			'fa-sort-desc' => 'Sort Desc',
			'fa-sort-down' => 'Sort Down',
			'fa-sort-numeric-asc' => 'Sort Numeric Asc',
			'fa-sort-numeric-desc' => 'Sort Numeric Desc',
			'fa-sort-up' => 'Sort Up',
			'fa-soundcloud' => 'Soundcloud',
			'fa-space-shuttle' => 'Space Shuttle',
			'fa-spinner' => 'Spinner',
			'fa-spoon' => 'Spoon',
			'fa-spotify' => 'Spotify',
			'fa-square' => 'Square',
			'fa-square-o' => 'Square O',
			'fa-stack-exchange' => 'Stack Exchange',
			'fa-stack-overflow' => 'Stack Overflow',
			'fa-star' => 'Star',
			'fa-star-half' => 'Star Half',
			'fa-star-half-empty' => 'Star Half Empty',
			'fa-star-half-full' => 'Star Half Full',
			'fa-star-half-o' => 'Star Half O',
			'fa-star-o' => 'Star O',
			'fa-steam' => 'Steam',
			'fa-steam-square' => 'Steam Square',
			'fa-step-backward' => 'Step Backward',
			'fa-step-forward' => 'Step Forward',
			'fa-stethoscope' => 'Stethoscope',
			'fa-stop' => 'Stop',
			'fa-street-view' => 'Street View',
			'fa-strikethrough' => 'Strikethrough',
			'fa-stumbleupon' => 'Stumbleupon',
			'fa-stumbleupon-circle' => 'Stumbleupon Circle',
			'fa-subscript' => 'Subscript',
			'fa-subway' => 'Fa Subway',
			'fa-suitcase' => 'Suitcase',
			'fa-sun-o' => 'Sun O',
			'fa-superscript' => 'Superscript',
			'fa-support' => 'Support',
			'fa-table' => 'Table',
			'fa-tablet' => 'Tablet',
			'fa-tachometer' => 'Tachometer',
			'fa-tag' => 'Tag',
			'fa-tags' => 'Tags',
			'fa-tasks' => 'Tasks',
			'fa-taxi' => 'Taxi',
			'fa-tencent-weibo' => 'Tencent Weibo',
			'fa-terminal' => 'Terminal',
			'fa-text-height' => 'Text Height',
			'fa-text-width' => 'Text Width',
			'fa-th' => 'Th',
			'fa-th-large' => 'Th Large',
			'fa-th-list' => 'Th List',
			'fa-thumbs-down' => 'Thumbs Down',
			'fa-thumbs-o-down' => 'Thumbs O Down',
			'fa-thumbs-o-up' => 'Thumbs O Up',
			'fa-thumbs-up' => 'Thumbs Up',
			'fa-thumb-tack' => 'Thumb Tack',
			'fa-ticket' => 'Ticket',
			'fa-times' => 'Times',
			'fa-times-circle' => 'Times Circle',
			'fa-times-circle-o' => 'Times Circle O',
			'fa-tint' => 'Tint',
			'fa-toggle-down' => 'Toggle Down',
			'fa-toggle-left' => 'Toggle Left',
			'fa-toggle-right' => 'Toggle Right',
			'fa-toggle-up' => 'Toggle Up',
			'fa-train' => 'Train',
			'fa-transgender' => 'Transgender',
			'fa-transgender-alt' => 'Transgender Alt',
			'fa-trash-o' => 'Trash O',
			'fa-tree' => 'Tree',
			'fa-trello' => 'Trello',
			'fa-trophy' => 'Trophy',
			'fa-truck' => 'Truck',
			'fa-try' => 'Try',
			'fa-tumblr' => 'Tumblr',
			'fa-tumblr-square' => 'Tumblr Square',
			'fa-turkish-lira' => 'Turkish Lira',
			'fa-twitter' => 'Twitter',
			'fa-twitter-square' => 'Twitter Square',
			'fa-umbrella' => 'Umbrella',
			'fa-underline' => 'Underline',
			'fa-undo' => 'Undo',
			'fa-university' => 'University',
			'fa-unlink' => 'Unlink',
			'fa-unlock' => 'Unlock',
			'fa-unlock-alt' => 'Unlock Alt',
			'fa-unsorted' => 'Unsorted',
			'fa-upload' => 'Upload',
			'fa-usd' => 'Usd',
			'fa-user' => 'User',
			'fa-user-md' => 'User Md',
			'fa-user-plus' => 'User Plus',
			'fa-users' => 'Users',
			'fa-user-secret' => 'User Secret',
			'fa-user-times' => 'User Times',
			'fa-venus' => 'Venus',
			'fa-venus-double' => 'Venus Double',
			'fa-venus-mars' => 'Venus Mars',
			'fa-viacoin' => 'Viacoin',
			'fa-video-camera' => 'Video Camera',
			'fa-vimeo-square' => 'Vimeo Square',
			'fa-vine' => 'Vine',
			'fa-vk' => 'Vk',
			'fa-volume-down' => 'Volume Down',
			'fa-volume-off' => 'Volume Off',
			'fa-volume-up' => 'Volume Up',
			'fa-warning' => 'Warning',
			'fa-wechat' => 'Wechat',
			'fa-weibo' => 'Weibo',
			'fa-weixin' => 'Weixin',
			'fa-whatsapp' => 'Whatsapp',
			'fa-wheelchair' => 'Wheelchair',
			'fa-windows' => 'Windows',
			'fa-won' => 'Won',
			'fa-wordpress' => 'Wordpress',
			'fa-wrench' => 'Wrench',
			'fa-xing' => 'Xing',
			'fa-xing-square' => 'Xing Square',
			'fa-yahoo' => 'Yahoo',
			'fa-yen' => 'Yen',
			'fa-youtube' => 'Youtube',
			'fa-youtube-play' => 'Youtube Play',
			'fa-youtube-square' => 'Youtube Square',
		);		
		$service_icon = get_post_meta( $post->ID, 'service_icon', true );
	?>
    <p>
    <select name="service_icon" id="service_icon">
    <?php
    foreach($icon as $key => $value){
	?>	
    <option value='<?php echo $key; ?>' <?php selected( $service_icon, $key ); ?>><?php echo $value; ?></option>
    <?php } ?>
    </select>
    </p>
    <style>.serviceBox .service-icon {font-size: 30px;}</style>
    <div class="serviceBox">
    <div class="service-icon"><i class="fa <?php echo $service_icon; ?>"></i></div>
    </div>
<?php }
function service_icon_save_meta( $post_id ) {

  if( !isset( $_POST['service_icon_nonce'] ) || !wp_verify_nonce( $_POST['service_icon_nonce'],'service_icon_metabox_nonce') ) 
    return;
  if ( !current_user_can( 'edit_post', $post_id ))
    return;
  if ( isset($_POST['service_icon']) ) {        
    update_post_meta($post_id, 'service_icon', sanitize_text_field( $_POST['service_icon']));      
  }
}
add_action('save_post', 'service_icon_save_meta');

function lr_service_custom_css_element()
{
$options = get_option('lr_service_custom_css');
?>
<textarea id="lr_service_custom_css" name="lr_service_custom_css" rows="8" cols="90"  placeholder=".test{ font-size:16px;}">
<?php echo $options; ?></textarea>
<p class="description"><?php _e( 'Enter any custom css you want to apply on this services.
Note: Please Do Not Use Style Tag With Custom CSS.' ); ?>
</p>
<?php
}
function lr_service_layout_element()
{
$options = get_option('lr_service_layout');
?>
<select id="lr_service_layout" name='lr_service_layout[lr_service_layout]'>
<option value='4' <?php selected( $options['lr_service_layout'], '4' ); ?>><?php _e( 'Column 3', 'lr-service'); ?></option>
<option value='3' <?php selected( $options['lr_service_layout'], '3' ); ?>><?php _e( 'Column 4', 'lr-service'); ?></option>
</select>
<p class="description">Please select layout</p>
<?php
}
function lr_service_read_more_element()
{
$options = get_option('lr_rmore_layout');
?>
<select id="lr_rmore_layout" name='lr_rmore_layout[lr_rmore_layout]'>
<option value='0' <?php selected( $options['lr_rmore_layout'], '0' ); ?>><?php _e( 'No', 'lr-service'); ?></option>
<option value='1' <?php selected( $options['lr_rmore_layout'], '1' ); ?>><?php _e( 'Yes', 'lr-service'); ?></option>
</select>
<?php
}
function lr_service_style_element()
{
$options = get_option('lr_service_style');
?>
<select id="lr_service_style" name='lr_service_style[lr_service_style]'>
<option value='1' <?php selected( $options['lr_service_style'], '1' ); ?>><?php _e( 'Style 1', 'lr-service'); ?></option>
<option value='2' <?php selected( $options['lr_service_style'], '2' ); ?>><?php _e( 'Style 2', 'lr-service'); ?></option>
<option value='3' <?php selected( $options['lr_service_style'], '3' ); ?>><?php _e( 'Style 3', 'lr-service'); ?></option>
<option value='4' <?php selected( $options['lr_service_style'], '4' ); ?>><?php _e( 'Style 4', 'lr-service'); ?></option>
</select>
<p class="description">Please select style</p>
<?php
}
function lr_service_preview_element(){

$lr_service_style = get_option('lr_service_style');

	if($lr_service_style['lr_service_style'] == '1'){
		?>
        <img src="<?php echo lr_service_plugin_url ?>/images/1.png" title="Style 1" width="600"/>
        <?php
	}
	if($lr_service_style['lr_service_style'] == '2'){
		?>
        <img src="<?php echo lr_service_plugin_url ?>/images/2.png" title="Style 2" width="600"/>
        <?php
	}
	if($lr_service_style['lr_service_style'] == '3'){
		?>
        <img src="<?php echo lr_service_plugin_url ?>/images/3.png" title="Style 3" width="600"/>
        <?php
	}
	if($lr_service_style['lr_service_style'] == '4'){
		?>
        <img src="<?php echo lr_service_plugin_url ?>/images/4.png" title="Style 4" width="600"/>
        <?php
	}
}