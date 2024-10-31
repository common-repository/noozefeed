<?php
defined('ABSPATH') or die();

function noozefeed_user_feedback()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		if ((isset($_POST['feedback'])) || (isset($_POST['rating']))) {
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'nfDetails';
			$install_code = $wpdb->get_var("SELECT install_code FROM $table_name WHERE user_id = 1");
			$feedback = sanitize_text_field($_POST['feedback']);
			$rating = intval($_POST['rating']);
			$url = 'https://api.leakyfeed.com/feedback?plugin=' . $install_code;
			if (isset($feedback)) {
				$url = $url . '&feedback=' . $feedback;
			}

			if (isset($rating)) {
				$url = $url . '&rating=' . $rating;
			}

			// new HTTP POST Request using the above $url

			$result = wp_remote_post($url);
			$json = json_decode($result['body']);
			if (isset($json->result) && ($json->result === 'success')) {
				$response["result"] = $json->result;
				$response["message"] = $json->message;
			}
			else {
				$response["result"] = 'error';
			}

			$response = json_encode($response);
		}
		else {
			$response["result"] = 'error';
		}
	}
        
        echo $response;

	die();
}

add_action('wp_ajax_noozefeed_user_feedback', 'noozefeed_user_feedback');

function noozefeed_reset_feed_scores()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		global $wpdb;
		$table_name = $wpdb->prefix . 'nfTwitter';
		$wpdb->query("UPDATE $table_name SET relevance_score = 0");
		$table_name = $wpdb->prefix . 'nfFacebook';
		$wpdb->query("UPDATE $table_name SET relevance_score = 0");
		$table_name = $wpdb->prefix . 'nfArticle';
		$wpdb->query("UPDATE $table_name SET relevance_score = 0");
	}

	die();
}

add_action('wp_ajax_noozefeed_reset_feed_scores', 'noozefeed_reset_feed_scores');

function noozefeed_reset_display_settings()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		global $wpdb;
		$table_name = $wpdb->prefix . 'nfRequests';
		$wpdb->update($table_name, array(
			'table_height' => 500,
			'max_feeds' => 100
		) , array(
			'user_id' => 1
		));
		$table_name = $wpdb->prefix . 'nfDetails';
		$wpdb->update($table_name, array(
			'table_color' => '#ffffff',
			'head_text_color' => '#e40079',
			'feed_text_color' => '#606060',
			'underline_color' => '#e40079',
			'score_text_color' => '#0000ff',
			'background_color' => '#ffffff',
			'internal_radius_size' => 0,
			'external_radius_size' => 0
		) , array(
			'user_id' => 1
		));
	}

	die();
}

add_action('wp_ajax_noozefeed_reset_display_settings', 'noozefeed_reset_display_settings');

function noozefeed_mark_feeds_as_old()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		global $wpdb;
		$table_name = $wpdb->prefix . 'nfTwitter';
		$wpdb->query("UPDATE $table_name SET new = 0 WHERE new = 1");
		$table_name = $wpdb->prefix . 'nfFacebook';
		$wpdb->query("UPDATE $table_name SET new = 0 WHERE new = 1");
		$table_name = $wpdb->prefix . 'nfArticle';
		$wpdb->query("UPDATE $table_name SET new = 0 WHERE new = 1");
	}

	die();
}

add_action('wp_ajax_noozefeed_mark_feeds_as_old', 'noozefeed_mark_feeds_as_old');

function noozefeed_delete_all_feeds()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		if ((isset($_POST['delete_feeds'])) && (isset($_POST['request_id']))) {
			$request_id = sanitize_text_field($_POST['request_id']);
			$confirmed_id = sanitize_text_field($_POST['delete_feeds']);
			if ($confirmed_id === $request_id) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'nfRequests';
				$wpdb->query($wpdb->prepare("UPDATE $table_name SET num_feeds_available = 0, num_feeds_total = 0 WHERE request_id = %s", $request_id));
				$table_name = $wpdb->prefix . 'nfFacebook';
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE request_id = %s", $request_id));
				$table_name = $wpdb->prefix . 'nfTwitter';
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE request_id = %s", $request_id));
				$table_name = $wpdb->prefix . 'nfArticle';
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE request_id = %s", $request_id));
			}
		}
	}

	die();
}

add_action('wp_ajax_noozefeed_delete_all_feeds', 'noozefeed_delete_all_feeds');

function noozefeed_update_shortcode_colors()
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
		global $wpdb;
		$table_name = $wpdb->prefix . 'nfRequests';
		$wpdb->update($table_name, array(
			'table_height' => $table_height,
			'max_feeds' => $max_feeds
		) , array(
			'user_id' => 1
		));
		$table_name = $wpdb->prefix . 'nfDetails';
		$wpdb->update($table_name, array(
			'table_color' => $table_color,
			'head_text_color' => $head_text_color,
			'feed_text_color' => $feed_text_color,
			'underline_color' => $underline_color,
			'score_text_color' => $score_text_color,
			'background_color' => $background_color,
			'internal_radius_size' => $internal_radius_size,
			'external_radius_size' => $external_radius_size
		) , array(
			'user_id' => 1
		));
	}

	die();
}

add_action('wp_ajax_noozefeed_update_shortcode_colors', 'noozefeed_update_shortcode_colors');

function noozefeed_updated_shortcode()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		$request_id_before = sanitize_text_field($_POST['request_id_before']);
		$request_id_after = sanitize_text_field($_POST['request_id_after']);
		global $wpdb;
		$table_name = $wpdb->prefix . 'posts';
		$wpdb->query($wpdb->prepare("UPDATE $table_name SET post_content = REPLACE (post_content,'[nf id=\"%s','[nf id=\"%s') WHERE post_status = 'publish';", $request_id_before, $request_id_after));
	}

	die();
}

add_action('wp_ajax_noozefeed_updated_shortcode', 'noozefeed_updated_shortcode');

function noozefeed_save_tab_index()
{
	if (current_user_can('administrator')) {
		check_ajax_referer('noozefeed-ajax-nonce', 'security');
		$tab_index = intval($_POST['tab_index']);
		global $wpdb;
		$table_name = $wpdb->prefix . 'nfDetails';
		$wpdb->update($table_name, array(
			'save_tab_index' => $tab_index
		) , array(
			'user_id' => 1
		));
	}

	die();
}

add_action('wp_ajax_noozefeed_save_tab_index', 'noozefeed_save_tab_index');