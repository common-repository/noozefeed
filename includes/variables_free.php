<?php
defined('ABSPATH') or die();
global $wpdb;
$table_name = $wpdb->prefix . 'nfTwitter';
$twitter_count = $wpdb->get_var("SELECT COUNT(feed_id) from $table_name ");
$table_name = $wpdb->prefix . 'nfFacebook';
$facebook_count = $wpdb->get_var("SELECT COUNT(feed_id) from $table_name ");
$table_name = $wpdb->prefix . 'nfArticle';
$article_count = $wpdb->get_var("SELECT COUNT(feed_id) from $table_name ");
$table_name = $wpdb->prefix . 'nfDetails';
$details = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = 1");
$noozefeed_nonce = wp_create_nonce('noozefeed-ajax-nonce');

if ($details) {
	$state = $details->state;
	$user_name = $details->user_name;
	$access_key = $details->access_key;
	$subscription_level = $details->subscription_level;
	$request_rate = $details->request_rate;
	$feed_limit = $details->feed_limit;
	$has_activated = $details->has_activated;
	$table_color = $details->table_color;
	$head_text_color = $details->head_text_color;
	$feed_text_color = $details->feed_text_color;
	$underline_color = $details->underline_color;
	$score_text_color = $details->score_text_color;
	$background_color = $details->background_color;
	$internal_radius_size = $details->internal_radius_size;
	$external_radius_size = $details->external_radius_size;
	$save_tab_index = $details->save_tab_index;
	$table_name = $wpdb->prefix . 'nfRequests';
	$request = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = 1");
	if ($request) {
		$noozefeed_id = $request->request_id;
		$places = $request->places;
		$sort_by = $request->sort_by;
		$view_type = $request->view_type;
		$num_feeds_available = $request->num_feeds_available;
		$num_feeds_total = $request->num_feeds_total;
		$latitude = $request->latitude;
		$longitude = $request->longitude;
		$radius = $request->radius;
		$restrict_to_radius = $request->restrict_to_radius;
		$zoom = $request->zoom;
		$social_media = $request->social_media;
		$keywords = $request->keywords;
		$exclude_filter = $request->exclude_filter;
		$table_height = $request->table_height;
		$max_feeds = $request->max_feeds;
	}
	else {
		$noozefeed_id = 'noozefeed';
		$places = null;
		$sort_by = null;
		$view_type = null;
		$num_feeds_available = null;
		$num_feeds_total = null;
		$latitude = null;
		$longitude = null;
		$radius = null;
		$restrict_to_radius = '2';
		$zoom = null;
		$feed_type = null;
		$social_media = 'article';
		$keywords = null;
		$exclude_filter = null;
		$table_height = 500;
		$max_feeds = 100;
	}
}
else {
	$state = null;
	$user_name = null;
	$access_key = null;
	$subscription_level = null;
	$request_rate = null;
	$feed_limit = null;
	$save_tab_index = 0;
	$has_activated = 0;
	$noozefeed_id = 'noozefeed';
	$places = null;
	$sort_by = null;
	$view_type = null;
	$num_feeds_available = 0;
	$num_feeds_total = 0;
	$latitude = null;
	$longitude = null;
	$radius = null;
	$restrict_to_radius = '2';
	$zoom = null;
	$feed_type = null;
	$social_media = 'article';
	$keywords = null;
	$exclude_filter = null;
	$table_height = 500;
	$max_feeds = 100;
	$table_color = '#ffffff';
	$head_text_color = '#e40079';
	$feed_text_color = '#606060';
	$underline_color = '#e40079';
	$score_text_color = '#0000ff';
	$background_color = '#ffffff';
	$internal_radius_size = 0;
	$external_radius_size = 0;
}