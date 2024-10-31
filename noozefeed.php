<?php
/**
 * Plugin Name: Noozefeed
 * Plugin URI: http://www.noozefeed.com
 * Description: Noozefeed is a simple publication plug-in with access to multiple social media outlets and allows the user to search, manage and publish real-time feeds based on hashtags, keywords and geographic locations.
 * Version: 1.07
 * Author: Leakyfeed
 * Author URI: http://www.noozefeed.com
 * License: GPLv2 or later
 */
/*------------------ Create NoozeFeed DB --------------------------*/
/*---------------Demo Table Creation-------------------*/
global $noozefeed_db_version;
$noozefeed_db_version = '1.07';

function noozefeed_install()
{
	global $wpdb;
	global $noozefeed_db_version;
	/*------------------Create nfRequests------------------*/
	$table_name = $wpdb->prefix . 'nfRequests';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
            user_id int(1) NOT NULL,
            request_id varchar(50) NOT NULL,
            sort_by varchar(10) NULL,
            view_type varchar(10) NOT NULL,
            places varchar(300) COLLATE utf8mb4_unicode_520_ci NULL,
            keywords varchar(300) COLLATE utf8mb4_unicode_520_ci NULL,
            exclude_filter varchar(300) COLLATE utf8mb4_unicode_520_ci NULL,
            social_media varchar(300) NULL,
            latitude float DEFAULT 0,
            longitude float DEFAULT 0,
            radius int(4) DEFAULT 0,
            restrict_to_radius int(2) DEFAULT 0,
            zoom int(2) DEFAULT 9,
            results_url varchar(300) NULL,
            num_feeds_available int(5) DEFAULT 0,
            num_feeds_total int(5) DEFAULT 0,
            next_run datetime DEFAULT CURRENT_TIMESTAMP,
            table_height int(5) DEFAULT 500 NOT NULL,
            max_feeds int(5) DEFAULT 100 NOT NULL
    ) $charset_collate;

    ALTER TABLE $table_name
      ADD PRIMARY KEY (request_id),
      ADD KEY request_id (request_id);";
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);
	/*------------------Create nfDetails------------------*/
	$table_name = $wpdb->prefix . 'nfDetails';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
            user_name varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            user_id int(32) NOT NULL,
            access_key varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            subscription_level varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            request_rate int(5) NOT NULL,
            feed_limit int(5) NOT NULL,
            has_activated int(1) NOT NULL DEFAULT 0,
            install_code varchar(15) NULL,
            table_color varchar(7) COLLATE utf8mb4_unicode_520_ci DEFAULT '#ffffff' NOT NULL,
            head_text_color varchar(7) COLLATE utf8mb4_unicode_520_ci DEFAULT '#e40079' NOT NULL,
            feed_text_color varchar(7) COLLATE utf8mb4_unicode_520_ci DEFAULT '#606060' NOT NULL,
            underline_color varchar(7) COLLATE utf8mb4_unicode_520_ci DEFAULT '#e40079' NOT NULL,
            score_text_color varchar(7) COLLATE utf8mb4_unicode_520_ci DEFAULT '#0000ff' NOT NULL,
            background_color varchar(7) COLLATE utf8mb4_unicode_520_ci DEFAULT '#ffffff' NOT NULL,
            internal_radius_size int(3) NOT NULL DEFAULT 0,
            external_radius_size int(3) NOT NULL DEFAULT 0,
            save_tab_index int(2) NOT NULL DEFAULT 0,
            state int(1) NOT NULL DEFAULT 0,
            last_logon_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;
    ALTER TABLE $table_name
      ADD PRIMARY KEY (user_id),
      ADD KEY user_id (user_id);";
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);
	/*---------------Create nfFacebook-------------------*/
	$table_name = $wpdb->prefix . 'nfFacebook';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
            feed_type varchar(10) NOT NULL DEFAULT 'facebook',
            parent_id varchar(20) NOT NULL,
            parent_name varchar(1000) NULL,
            feed_id varchar(50) NOT NULL,
            message text(62500) NULL,
            name text(1000) NULL,
            created_time datetime NOT NULL,
            updated_time datetime NOT NULL,
            link varchar(2083) NULL,
            picture varchar(2083) NULL,
            keywords varchar(2000) NOT NULL,
            latitude float DEFAULT NULL,
            longitude float DEFAULT NULL,
            shares int(7) DEFAULT 0,
            like_count int(7) DEFAULT 0,
            nf_score int(3) NULL,
            relevance_score float DEFAULT 0,
            new int(1) NOT NULL DEFAULT 1,
            linked_to varchar(1000) NULL,
            linked_url varchar(2083) NULL,
            request_id varchar(50) NOT NULL
    ) $charset_collate;
    ALTER TABLE $table_name
      ADD PRIMARY KEY (feed_id),
      ADD KEY feed_id (feed_id);";
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);
	/*---------------Create nfTwitter-------------------*/
	$table_name = $wpdb->prefix . 'nfTwitter';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
            feed_type varchar(10) NOT NULL DEFAULT 'twitter',
            parent_id varchar(20) NOT NULL,
            parent_name varchar(1000) NULL,
            feed_id varchar(50) NOT NULL,
            message text(500) NULL,
            name text(1000) NULL,
            created_time datetime NOT NULL,
            updated_time datetime NOT NULL,
            link varchar(2083) NULL,
            picture varchar(2083) NULL,
            keywords varchar(2000) NOT NULL,
            latitude float DEFAULT NULL,
            longitude float DEFAULT NULL,
            shares int(7) DEFAULT 0,
            like_count int(7) DEFAULT 0,
            nf_score int(3) NULL,
            relevance_score float DEFAULT 0,
            new int(1) NOT NULL DEFAULT 1,
            linked_to varchar(1000) NULL,
            linked_url varchar(2083) NULL,
            request_id varchar(50) NULL
    ) $charset_collate;
    ALTER TABLE $table_name
      ADD PRIMARY KEY (feed_id),
      ADD KEY feed_id (feed_id);";
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);
	/*---------------Create nfArticle-------------------*/
	$table_name = $wpdb->prefix . 'nfArticle';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
            feed_type varchar(10) NOT NULL DEFAULT 'article',
            parent_id varchar(20) NOT NULL,
            parent_name varchar(1000) NULL,
            feed_id varchar(50) NOT NULL,
            message text(62500) NULL,
            name text(1000) NULL,
            created_time datetime NOT NULL,
            updated_time datetime NOT NULL,
            link varchar(2083) NULL,
            picture varchar(2083) NULL,
            keywords varchar(2000) NULL,
            latitude float DEFAULT 0,
            longitude float DEFAULT 0,
            shares int(7) DEFAULT 0,
            like_count int(7) DEFAULT 0,
            nf_score int(3) DEFAULT 0,
            relevance_score float DEFAULT 0,
            new int(1) NOT NULL DEFAULT 1,
            linked_to varchar(1000) NULL,
            linked_url varchar(2083) NULL,
            request_id varchar(50) NOT NULL
    ) $charset_collate;
    ALTER TABLE $table_name
      ADD PRIMARY KEY (feed_id),
      ADD KEY feed_id (feed_id);";
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);
	/*---------------End Table Creation-------------------*/
	add_option('noozefeed_db_version', $noozefeed_db_version);
	get_initial_noozefeeds();
}

/*---------------Create Tables on Activation-------------------*/
register_activation_hook(__FILE__, 'noozefeed_install');
/*---------------------Create Admin Menu-----------------------------*/
add_action('admin_menu', 'noozefeed_admin_menu');

function noozefeed_admin_menu()
{
	add_menu_page('Noozefeed', 'Noozefeed', 'manage_options', 'noozefeed/admin_settings_free.php', 'noozefeed_admin', plugins_url('img/icon.png', __FILE__));
}

function noozefeed_admin()
{
	include 'admin_settings_free.php';

}

function add_noozefeed_request_interval($schedules)
{
	$schedules['15min'] = array(
		'interval' => 900,
		'display' => __('Every 15mins')
	);
	return $schedules;
}

add_filter('cron_schedules', 'add_noozefeed_request_interval');
register_activation_hook(__FILE__, 'noozefeed_request_activation');

function noozefeed_request_activation()
{
	if (!wp_next_scheduled('noozefeed_request_schedule')) {
		wp_schedule_event(time() , '15min', 'noozefeed_request_schedule');
	}
}

add_action('noozefeed_request_schedule', 'noozefeeds_schedule');
register_deactivation_hook(__FILE__, 'noozefeed_request_deactivation');

function noozefeed_request_deactivation()
{
	wp_clear_scheduled_hook('noozefeed_request_schedule');
}

register_uninstall_hook(__FILE__, 'noozefeed_uninstall');

function noozefeed_uninstall()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'nfFacebook';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	$table_name = $wpdb->prefix . 'nfTwitter';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	$table_name = $wpdb->prefix . 'nfArticle';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	$table_name = $wpdb->prefix . 'nfRequests';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	$table_name = $wpdb->prefix . 'nfDetails';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
}

/*------------------INCLUDE ALL PHP FILES IN THE /INCLUDES FOLDER---------------*/
include 'includes/shortcode_free.php';

include 'includes/admin_settings_ajax.php';

include 'includes/admin_settings_ajax_free.php';

/*--------------------------------ADD SCRIPTS-----------------------------------*/

function noozefeed_user_last_login()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'nfDetails';
	$wpdb->query("UPDATE $table_name SET last_logon_date = CURRENT_TIMESTAMP WHERE user_id = 1");
}

add_action('wp_login', 'noozefeed_user_last_login', 10, 2);
/*---------------FUNCTION TO RETRIEVE NEW FEEDS ON PLUG-IN ACTIVATION-----------*/

function get_initial_noozefeeds()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'nfDetails';
	$count = $wpdb->get_var("SELECT COUNT(user_id) FROM $table_name");

        $install_code = noozefeeds_generate_random_string(15);
        
	// if no user details exist insert, otherwise update

	if ($count < 1) {
		$wpdb->insert($table_name, array(
			'user_id' => 1,
			'user_name' => 'anonymous',
			'access_key' => 'free',
			'subscription_level' => 'free',
			'request_rate' => 1,
			'feed_limit' => 50,
                        'install_code' => $install_code
		));
	}

	$geo = json_decode(file_get_contents('http://freegeoip.net/json/') , true);
	$latitude = floatval($geo['latitude']);
	$longitude = floatval($geo['longitude']);
	$table_name = $wpdb->prefix . 'nfRequests';
	$exist = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
	if ($exist < 1) {
		$wpdb->insert($table_name, array(
			'user_id' => '1',
			'request_id' => 'noozefeed',
			'sort_by' => 'new',
			'view_type' => 'list',
			'table_height' => 500,
			'places' => null,
			'keywords' => null,
			'exclude_filter' => null,
			'social_media' => 'article',
			'latitude' => $latitude,
			'longitude' => $longitude,
			'radius' => null,
			'zoom' => 9,
			'restrict_to_radius' => 2,
			'max_feeds' => 200
		));
	}

	get_noozefeeds('noozefeed');
	$table_name = $wpdb->prefix . 'nfArticle';
	$articles = $wpdb->get_results("SELECT * FROM $table_name WHERE request_id = 'noozefeed' ORDER BY created_time DESC LIMIT 3", OBJECT);
	global $wp_version;
	foreach($articles as $article) {
		$url = 'https://api.leakyfeed.com/getfeeds?&plugin=' . $install_code . '&media=twitter,facebook,article&latitude=' . $latitude . '&longitude=' . $longitude . '&keywords=' . urlencode(str_replace(',', '', $article->name));
		$result = wp_remote_get($url, $args = ['timeout' => 60, 'redirection' => 5, 'httpversion' => '1.0', 'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url() , 'blocking' => true, 'headers' => array() , 'cookies' => array() , 'body' => null, 'compress' => true, 'decompress' => true, 'sslverify' => true, 'stream' => false, 'filename' => null]);
		$json = json_decode($result['body']);
		$response = array();
		$response["result"] = $json->result;
		if ($json->result === 'success') {
			if (count($json->data) > 0) {
				$returned_feeds = 0;
				$available_feeds = 0;

				// check for available Social Media types in the response and process accordingly

				foreach($json->data as $social_media_type) {
					$returned_feeds = $returned_feeds + $social_media_type->returned_feeds;
					$available_feeds = $available_feeds + $social_media_type->available_feeds;

					// if the current Social Media type is Facebook, do

					if ($social_media_type->feed_type === 'facebook') {
						foreach($social_media_type->data as $feed) {

							// Check if the row already exists

							$table_name = $wpdb->prefix . 'nfFacebook';
							$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE feed_id = %s", $feed->post_id));
							if (count($result) < 1) {

								// Format the date into MySQL time

								$created_time = date('Y-m-d H:i:s', strtotime($feed->created_time));
								$updated_time = date('Y-m-d H:i:s', strtotime($feed->updated_time));

								// Join array of keywords and site_types if available

								$keywords = join(',', $feed->keywords);
								if (isset($feed->score)) {
									$score = $feed->score;
								}
								else {
									$score = 0;
								}

								if ($feed->message) {
									$message = $feed->message;
								}
								else
								if ($feed->description) {
									$message = $feed->description;
								}
								else
								if ($feed->story) {
									$message = $feed->story;
								}
								else {
									$message = null;
								}

								// Create row input for use in MySQL query below

								$table_name = $wpdb->prefix . 'nfFacebook';
								$feedRow = array(
									'request_id' => 'noozefeed',
									'parent_id' => $feed->site_id,
									'parent_name' => $feed->site_name,
									'feed_id' => $feed->post_id,
									'name' => $feed->name,
									'message' => $message,
									'created_time' => $created_time,
									'updated_time' => $updated_time,
									'link' => $feed->link,
									'picture' => $feed->picture,
									'like_count' => $feed->reactions->like,
									'nf_score' => $feed->lf_score,
									'keywords' => $keywords,
									'latitude' => $feed->loc->coordinates[1],
									'longitude' => $feed->loc->coordinates[0],
									'relevance_score' => $score,
									'shares' => $feed->shares,
									'linked_to' => $article->name,
                                                                        'linked_url' => $article->link
								);
								$wpdb->insert($table_name, $feedRow);
							}
						}
					}
					else
					if ($social_media_type->feed_type === 'twitter') {
						foreach($social_media_type->data as $feed) {

							// Check if the row already exists

							$table_name = $wpdb->prefix . 'nfTwitter';
							$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE feed_id = %s", $feed->post_id));
							if (count($result) < 1) {

								// Format the date into MySQL time

								$created_time = date('Y-m-d H:i:s', strtotime($feed->created_time));
								$updated_time = date('Y-m-d H:i:s', strtotime($feed->updated_time));

								// Join array of keywords and site_types if available

								$keywords = join(',', $feed->keywords);
								if (isset($feed->score)) {
									$score = $feed->score;
								}
								else {
									$score = 0;
								}

								// Create row input for use in MySQL query below

								$table_name = $wpdb->prefix . 'nfTwitter';
								$feedRow = array(
									'request_id' => 'noozefeed',
									'parent_id' => $feed->user_id,
									'parent_name' => $feed->user_name,
									'feed_id' => $feed->post_id,
									'message' => $feed->message,
									'created_time' => $created_time,
									'updated_time' => $updated_time,
									'link' => $feed->link,
									'picture' => $feed->picture,
									'like_count' => $feed->reactions->like,
									'nf_score' => $feed->lf_score,
									'keywords' => $keywords,
									'latitude' => $feed->loc->coordinates[1],
									'longitude' => $feed->loc->coordinates[0],
									'relevance_score' => $score,
									'shares' => $feed->shares,
									'linked_to' => $article->name,
                                                                        'linked_url' => $article->link
								);
								$wpdb->insert($table_name, $feedRow);
							}
						}
					}

					if ($social_media_type->feed_type === 'article') {
						foreach($social_media_type->data as $feed) {

							// Check if the row already exists

							$table_name = $wpdb->prefix . 'nfArticle';
							$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE feed_id = %s", $feed->post_id));
							if (count($result) < 1) {

								// Format the date into MySQL time

								$created_time = date('Y-m-d H:i:s', strtotime($feed->created_time));
								$updated_time = date('Y-m-d H:i:s', strtotime($feed->updated_time));

								// Join array of keywords and site_types if available

								if (isset($feed->keywords)) {
									$keywords = join(',', $feed->keywords);
								}
								else {
									$keywords = null;
								}

								if (isset($feed->score)) {
									$score = $feed->score;
								}
								else {
									$score = 0;
								}

								// Create row input for use in MySQL query below

								$table_name = $wpdb->prefix . 'nfArticle';
								$feedRow = array(
									'request_id' => 'noozefeed',
									'parent_id' => $feed->user_id,
									'parent_name' => $feed->user_name,
									'feed_id' => $feed->post_id,
									'message' => $feed->message,
									'name' => $feed->name,
									'created_time' => $created_time,
									'updated_time' => $updated_time,
									'link' => $feed->link,
									'picture' => $feed->picture,
									'like_count' => 0,
									'nf_score' => 0,
									'keywords' => $keywords,
									'latitude' => 0,
									'longitude' => 0,
									'relevance_score' => $score,
									'shares' => 0,
									'linked_to' => $article->name,
                                                                        'linked_url' => $article->link
								);
								$wpdb->insert($table_name, $feedRow);
							}
						}
					}
				}

				$nextRun = date('Y-m-d H:i:s', strtotime('+' . 1440 . ' minutes', time()));
				$table_name = $wpdb->prefix . 'nfRequests';
				$request = $wpdb->get_row("SELECT * FROM $table_name WHERE request_id = 'noozefeed'");
				$wpdb->update($table_name, array(
					'next_run' => $nextRun,
					'results_url' => $json->url,
					'num_feeds_total' => $returned_feeds + $request->num_feeds_total,
					'num_feeds_available' => $available_feeds + $request->num_feeds_available
				) , array(
					'request_id' => 'noozefeed'
				));
			}
		}
                if (count($json->data) > 0) {
                    
                    // ignore searching for feeds from additional stories if current requested feeds are found
                    
                    break;
                }
	}
}

function noozefeeds_schedule()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'nfRequests';
	$requests = $wpdb->get_results("SELECT * FROM $table_name WHERE next_run < UTC_TIMESTAMP()", OBJECT);
	foreach($requests as $request) {
		get_noozefeeds($request->request_id);
	}
}

function noozefeeds_generate_random_string($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}