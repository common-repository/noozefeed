<?php
defined('ABSPATH') or die();

function get_noozefeeds($request_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'nfRequests';
	$request = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE request_id = %s", $request_id));
	$table_name = $wpdb->prefix . 'nfDetails';
	$access_key = $wpdb->get_var("SELECT access_key FROM $table_name WHERE user_id = 1");
        $install_code = $wpdb->get_var("SELECT install_code FROM $table_name WHERE user_id = 1");
	$lat = $request->latitude;
	$long = $request->longitude;
	$radius = $request->radius;
	$sort_by = $request->sort_by;
	$restrict_to_radius = $request->restrict_to_radius;
	$social_media = $request->social_media;
	$places = str_replace('-', '', $request->places);
	$keywords = $request->keywords;
	$places_trim = trim($places);
	$places = str_replace(' ', '+', $places_trim);
	$keyword_filter = trim(' ');
	$exact_words = trim(' ');
	preg_match_all("/\@(.*?)@/", $keywords, $matches);
	foreach($matches[0] as $words) {
		$keywords = str_replace($words, '', $keywords);
		$words = str_replace('@', '', $words);
		if ($exact_words) {
			$exact_words = $exact_words . ',' . str_replace(' ', '+', $words);
		}
		else {
			$exact_words = str_replace(' ', '+', $words);
		}
	}

	$keywords = str_replace('@', '"', $keywords);
	$get_filter_words = ' ' . str_replace('-', '', $keywords);
	$get_filter_words = ' ' . str_replace(',', ' ', $get_filter_words);
	$keyword_array = str_getcsv($get_filter_words, ' ');
	foreach($keyword_array as $phrase) {
		$phrase_trim = trim($phrase);
		if ($phrase_trim) {
			$phrase_temp = str_replace(' ', '+', $phrase_trim);
			if ($keyword_filter) {
				$keyword_filter = $keyword_filter . ',' . $phrase_temp;
			}
			else {
				$keyword_filter = $phrase_temp;
			}
		}
	}

	if (isset($request->exclude_filter) && ($request->exclude_filter)) {
		$get_filter_words = ' ' . str_replace('-', '', $request->exclude_filter);
		$exclude_filter = null;

		// for all positive or negative keywords do

		preg_match_all("/(?<![\S\"])([^\"\s]+)(?![\S\"])/", $get_filter_words, $matches);
		foreach($matches[0] as $words) {
			$words = trim($words);
			$words = str_replace("\"", "", $words);
			if ($exclude_filter) {
				$exclude_filter = $exclude_filter . ',' . str_replace(' ', '+', $words);
			}
			else {
				$exclude_filter = str_replace(' ', '+', $words);
			}
		}

		// for each phrase

		preg_match_all("/[\b-\"]\"([^\"]+)\"/", $get_filter_words, $matches);
		foreach($matches[0] as $words) {
			$words = trim($words);
			$words = str_replace("\"", "", $words);
			if (isset($exclude_filter)) {
				$exclude_filter = $exclude_filter . ',' . str_replace(' ', '+', $words);
			}
			else {
				$exclude_filter = str_replace(' ', '+', $words);
			}
		}
	}

	$url = 'https://api.leakyfeed.com/getfeeds?&plugin=' . $install_code . '&media=' . $social_media . '&latitude=' . $lat . '&longitude=' . $long;

	// If latitude and longitude exist then add the query to the URL

	if ($restrict_to_radius === '1') {
		$url = $url . '&radius=' . $radius;
		$url = $url . '&places=' . $places;
	}
	else
	if ($restrict_to_radius === '0') {
		$url = $url . '&places=' . $places;
	}

	if (isset($keyword_filter) && ($keyword_filter != trim(' '))) {
		$url = $url . '&keywords=' . urlencode($keyword_filter);
	}

	if (isset($exclude_filter) && ($exclude_filter != trim(' '))) {
		$url = $url . '&exclude=' . urlencode($exclude_filter);
	}

	if (isset($sort_by)) {
		if (($sort_by !== 'relevance') && (isset($keyword_filter))) {
			$url = $url . '&sortby=' . $sort_by;
		}
	}

	if (isset($exact_words) && ($exact_words != trim(' '))) {
		$url = $url . '&exactkeywords=' . urlencode($exact_words);
	}

	// new HTTP GET Request using the above $url

	global $wp_version;
	$result = wp_remote_get($url, $args = ['timeout' => 60, 'redirection' => 5, 'httpversion' => '1.0', 'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url() , 'blocking' => true, 'headers' => array() , 'cookies' => array() , 'body' => null, 'compress' => true, 'decompress' => true, 'sslverify' => true, 'stream' => false, 'filename' => null]);
	$json = json_decode($result['body']);
	$response = array();
	$response["result"] = $json->result;
	global $wpdb;
	if ($json->result === 'success') {
		if (count($json->data) > 0) {
                        $linked_to = trim(' ');
                        $linked_url = trim(' '); 
			$returned_feeds = 0;
			$available_feeds = 0;
			$table_name = $wpdb->prefix . 'nfDetails';
			$subscription_level = $wpdb->get_var("SELECT subscription_level FROM $table_name WHERE user_id = 1");
			if ($subscription_level === 'free') {
				$subscription_level_frequency_in_mins = '1440';
			}
			else
			if ($subscription_level === 'standard') {
				$subscription_level_frequency_in_mins = '60';
			}
			else
			if ($subscription_level === 'premium') {
				$subscription_level_frequency_in_mins = '15';
			}
			else
			if ($subscription_level === 'test') {
				$subscription_level_frequency_in_mins = '1';
			}
			else {
				$subscription_level_frequency_in_mins = '1440';
			}

			// get the last request run by subtracting the request frequency from the current time

			$last_run = strtotime('-' . $subscription_level_frequency_in_mins . ' minutes', time());
			$last_logon_date = strtotime($wpdb->get_var("SELECT last_logon_date FROM $table_name WHERE user_id = 1"));
			if ($last_logon_date > $last_run) {

				// reset the feed 'new' value if lastLogonDate is newer than the last request

				$table_name = $wpdb->prefix . 'nfTwitter';
				$wpdb->query("UPDATE $table_name SET new = 0 WHERE new = 1");
				$table_name = $wpdb->prefix . 'nfFacebook';
				$wpdb->query("UPDATE $table_name SET new = 0 WHERE new = 1");
				$table_name = $wpdb->prefix . 'nfArticle';
				$wpdb->query("UPDATE $table_name SET new = 0 WHERE new = 1");
			}

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
								'request_id' => $request_id,
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
								'nf_score' => 0,
								'keywords' => $keywords,
								'latitude' => $feed->loc->coordinates[1],
								'longitude' => $feed->loc->coordinates[0],
								'relevance_score' => $score,
								'shares' => $feed->shares,
                                                                'linked_to' => $linked_to,
                                                                'linked_url' => $linked_url
							);
							$wpdb->insert($table_name, $feedRow);
						}

						// Echo any MySQL errors that may have occured

						$response["sql_errors"] = $wpdb->last_error;
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
								'request_id' => $request_id,
								'parent_id' => $feed->user_id,
								'parent_name' => $feed->user_name,
								'feed_id' => $feed->post_id,
								'message' => $feed->message,
								'created_time' => $created_time,
								'updated_time' => $updated_time,
								'link' => $feed->link,
								'picture' => $feed->picture,
								'like_count' => $feed->reactions->like,
								'nf_score' => 0,
								'keywords' => $keywords,
								'latitude' => $feed->loc->coordinates[1],
								'longitude' => $feed->loc->coordinates[0],
								'relevance_score' => $score,
								'shares' => $feed->shares,
                                                                'linked_to' => $linked_to,
                                                                'linked_url' => $linked_url
							);
							$wpdb->insert($table_name, $feedRow);
						}

						// Echo any MySQL errors that may have occured

						$response["sql_errors"] = $wpdb->last_error;
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

							if (isset($feed->name)) {
								$name = $feed->name;
							}
							else {
								$name = trim(' ');
							}

							// Create row input for use in MySQL query below

							$table_name = $wpdb->prefix . 'nfArticle';
							$feedRow = array(
								'request_id' => $request_id,
								'parent_id' => $feed->user_id,
								'parent_name' => $feed->user_name,
								'feed_id' => $feed->post_id,
								'name' => $name,
								'message' => $feed->message,
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
                                                                'linked_to' => $linked_to,
                                                                'linked_url' => $linked_url
							);
							$wpdb->insert($table_name, $feedRow);
						}

						// Echo any MySQL errors that may have occured

						$response["sql_errors"] = $wpdb->last_error;
					}
				}
			}

			$nextRun = date('Y-m-d H:i:s', strtotime('+' . $subscription_level_frequency_in_mins . ' minutes', time()));
			$table_name = $wpdb->prefix . 'nfRequests';
			$wpdb->update($table_name, array(
				'next_run' => $nextRun,
				'results_url' => $json->url,
				'num_feeds_total' => $returned_feeds + $request->num_feeds_total,
				'num_feeds_available' => $available_feeds + $request->num_feeds_available
			) , array(
				'request_id' => $request->request_id
			));
			$response["returned_feeds"] = $returned_feeds;
			$response["available_feeds"] = $available_feeds;
			$table_name = $wpdb->prefix . 'nfTwitter';
			$response["twitter_count"] = $wpdb->get_var("SELECT COUNT(feed_id) from $table_name");
			$table_name = $wpdb->prefix . 'nfFacebook';
			$response["facebook_count"] = $wpdb->get_var("SELECT COUNT(feed_id) from $table_name");
			$table_name = $wpdb->prefix . 'nfArticle';
			$response["article_count"] = $wpdb->get_var("SELECT COUNT(feed_id) from $table_name");
                        $response["remaining_searches"] = $json->remaining_searches;
                        $response["remaining_seconds"] = $json->reset_wait_in_seconds;
		}
	}

	return $response;
}

function noozefeed_get_shortcode_results()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		$request_id = sanitize_text_field($_POST['request_id']);
		$view_type = sanitize_text_field($_POST['view_type']);
		$height = intval($_POST['table_height']);
		$max_feeds = intval($_POST['max_feeds']);
		$sort_by = sanitize_text_field($_POST['sort_by']);
		global $wpdb;
		$table_name = $wpdb->prefix . 'nfRequests';
		$wpdb->update($table_name, array(
			'view_type' => $view_type,
			'max_feeds' => $max_feeds,
			'sort_by' => $sort_by,
		) , array(
			'request_id' => $request_id
		));
		$noozefeed_table = trim(' ');
		$height = $height . "px";
		if ($view_type === 'grid') {
			$noozefeed_table = $noozefeed_table . noozefeedGridView($request_id, $max_feeds);
		}
		else {
			$noozefeed_table = $noozefeed_table . noozefeedListView($request_id, $max_feeds);
		}

		echo $noozefeed_table;
	}

	die();
}

add_action('wp_ajax_noozefeed_get_shortcode_results', 'noozefeed_get_shortcode_results');

function save_noozefeed_settings()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		$table_height = intval($_POST['table_height']);
		$max_feeds = intval($_POST['max_feeds']);
		$table_color = sanitize_text_field($_POST['table_color']);
		$head_text_color = sanitize_text_field($_POST['head_text_color']);
		$feed_text_color = sanitize_text_field($_POST['feed_text_color']);
		$underline_color = sanitize_text_field($_POST['underline_color']);
		$score_text_color = sanitize_text_field($_POST['score_text_color']);
		$background_color = sanitize_text_field($_POST['background_color']);
		$internal_radius_size = intval($_POST['internal_radius_size']);
		$external_radius_size = intval($_POST['external_radius_size']);
		$response = array();
		global $wpdb;
		$table_name = $wpdb->prefix . 'nfDetails';
		$count = $wpdb->get_var("SELECT COUNT(user_id) FROM $table_name");

		// if no user details exist insert, otherwise update

		if ($count < 1) {
			$wpdb->insert($table_name, array(
				'user_id' => 1,
				'user_name' => 'anonymouse',
				'access_key' => 'free',
				'subscription_level' => 'free',
				'request_rate' => 1,
				'feed_limit' => 50
			));
		}
		else {
			$wpdb->update($table_name, array(
				'user_name' => 'anonymouse',
				'access_key' => 'free',
				'table_color' => $table_color,
				'head_text_color' => $head_text_color,
				'feed_text_color' => $feed_text_color,
				'underline_color' => $underline_color,
				'score_text_color' => $score_text_color,
				'background_color' => $background_color,
				'internal_radius_size' => $internal_radius_size,
				'external_radius_size' => $external_radius_size,
				'request_rate' => 1,
				'feed_limit' => 50
			) , array(
				'user_id' => 1
			));
		}

		// get feed settings below

		$request_id = sanitize_text_field($_POST['request_id']);
		$places = sanitize_text_field($_POST['places']);
		$latitude = floatval($_POST['latitude']);
		$longitude = floatval($_POST['longitude']);
		$radius = intval($_POST['radius']);
		$restrict_to_radius = intval($_POST['restrict_to_radius']);
		$zoom = intval($_POST['zoom']);

		// if a request ID has been entered and map selected (always points to IP-to-geolocation anyway), save to database and get feeds

		$keywords = stripslashes($_POST['keywords']);
		$exclude_filter = stripslashes($_POST['exclude_filter']);
		$sort_by = sanitize_text_field($_POST['sort_by']);
		$view_type = sanitize_text_field($_POST['view_type']);
		$social_media = sanitize_text_field($_POST['social_media']);
		$table_name = $wpdb->prefix . 'nfRequests';
		$wpdb->query("DELETE FROM $table_name");
		$wpdb->insert($table_name, array(
			'user_id' => '1',
			'request_id' => $request_id,
			'sort_by' => $sort_by,
			'view_type' => $view_type,
			'table_height' => $table_height,
			'places' => $places,
			'keywords' => $keywords,
			'exclude_filter' => $exclude_filter,
			'social_media' => $social_media,
			'latitude' => $latitude,
			'longitude' => $longitude,
			'radius' => $radius,
			'zoom' => $zoom,
			'restrict_to_radius' => $restrict_to_radius,
			'max_feeds' => $max_feeds
		));
		$response = get_noozefeeds($request_id);
		$response = json_encode($response);
		echo $response;
	}

	die();
}

add_action('wp_ajax_save_noozefeed_settings', 'save_noozefeed_settings');