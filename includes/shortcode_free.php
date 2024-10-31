<?php
defined('ABSPATH') or die();
/*----------------------------------------Shortcode Files---------------------------------------------*/
require "shortcode_list_free.php";

require "shortcode_grid_free.php";

/*----------------------------------------Code--------------------------------------------------------*/

function noozefeed($atts)
{

	// set default options

	$request_id = null;
	$limit = 10000;

	// check each key/value pair and grab the values

	foreach($atts as $key => $value) {
		if ($key == 'id') {
			$request_id = $value;
		}
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'nfRequests';
	$request = $wpdb->get_row("SELECT * from $table_name WHERE request_id = '$request_id'", OBJECT);
	$height = $request->table_height;
	$view_type = $request->view_type;
	$limit = $request->max_feeds;
	if (!$request) {
		$height = 500;
		$view_type = 'list';
		$limit = 30;
	}

	// make sure request_id is specified before proceeding

	if ($request_id == null) {
		echo 'Please specify a search name (ID)';
	}
	else {
		global $wpdb;
		$table_name = $wpdb->prefix . 'nfRequests';
		$request = $wpdb->get_row("SELECT * from $table_name WHERE request_id = '$request_id'", OBJECT);

		// wrap shortcode html in an iframe element using Wordpress htmlspecialchars function

		$height = $height . "px";
		$noozefeed_table = "<meta name='viewport' content='width=device-width, initial-scale=1'><div style='overflow: auto; and -webkit-overflow-scrolling:touch;'><iframe id='shortcodeIframe' height='$height' style='min-width: 100%; body::-webkit-scrollbar-thumb' frameborder='0' srcdoc=\"";
		if ($request == null) {
			echo "Unable to locate search name" . $atts[0];
		}
		else {
			if ($view_type == 'grid') {
				$noozefeed_table = $noozefeed_table . htmlspecialchars(noozefeedGridView($request_id, $limit));
			}
			else {
				$noozefeed_table = $noozefeed_table . htmlspecialchars(noozefeedListView($request_id, $limit));
			}

			$noozefeed_table = $noozefeed_table . "\"></iframe></div>";
		}
	}

	return $noozefeed_table;
}

add_shortcode('noozefeed', 'noozefeed');

function noozefeedFetchHyperlink($feed)
{
	if ($feed->feed_type === 'facebook') {
		$hyperlink = "http://facebook.com/$feed->feed_id";
	}
	else
	if ($feed->feed_type === 'twitter') {
		$hyperlink = "https://twitter.com/api/status/$feed->feed_id";
	}
	else
	if ($feed->feed_type === 'article') {
		$hyperlink = "$feed->link";
	}

	return $hyperlink;
}

function noozefeedFetchPicture($feed)
{
	if ($feed->feed_type === 'facebook') {
		if (!$feed->picture) {
			$picture = plugins_url('../img/Facebook_logo.png', __FILE__);
		}
		else {
			$picture = $feed->picture;
		}

		return $picture;
	}
	else
	if ($feed->feed_type === 'twitter') {
		if (!$feed->picture) {
			$picture = plugins_url('../img/Twitter_Logo_White_On_Blue.png', __FILE__);
		}
		else {
			$picture = $feed->picture;
		}
	}
	else
	if ($feed->feed_type === 'article') {
		if (!$feed->picture) {
			$picture = plugins_url('../img/news.png', __FILE__);
		}
		else {
			$picture = $feed->picture;
		}
	}

	return $picture;
}

function noozefeedListScore($date, $time, $likes, $shares, $score_text_color)
{
	$likes_icon = "<img src='" . plugins_url('../img/likes.png', __FILE__) . "'/>";
	$shares_icon = "<img src='" . plugins_url('../img/shares.png', __FILE__) . "'/>";
	$time_icon = "<img src='" . plugins_url('../img/time.png', __FILE__) . "'/>";
	$date_icon = "<img src='" . plugins_url('../img/date.png', __FILE__) . "'/>";
	$score_table = "<div style='margin:0 auto;'><div class='LStable LStable--8cols LStable--collapse' style='margin: 0 auto;'>" . "<div class='LStable-icon'>$likes_icon</div>" . "<div class='LStable-score' style='color:$score_text_color;'>$likes</div>" . "<div class='LStable-icon'>$shares_icon</div>" . "<div class='LStable-score' style='color:$score_text_color;'>$shares</div>" . "<div class='LStable-icon'>$date_icon</div>" . "<div class='LStable-score' style='color:$score_text_color;'>$date</div>" . "<div class='LStable-icon'>$time_icon</div>" . "<div class='LStable-score' style='color:$score_text_color;'>$time</div>" . "</div>";
	return $score_table;
}

function noozefeedGridScore($date, $time, $likes, $shares, $score_text_color)
{
	$likes_icon = "<img src='" . plugins_url('../img/likes.png', __FILE__) . "'/>";
	$shares_icon = "<img src='" . plugins_url('../img/shares.png', __FILE__) . "'/>";
	$time_icon = "<img src='" . plugins_url('../img/time.png', __FILE__) . "'/>";
	$date_icon = "<img src='" . plugins_url('../img/date.png', __FILE__) . "'/>";
	$score_table = "<div class='GStable GStable--4cols' style='margin: 0 auto;'>" . "<div class='GStable-score'>$likes_icon </br><span class='Gscore' style='color:$score_text_color;'>$likes</span></div>" . "<div class='GStable-score'>$shares_icon </br><span class='Gscore' style='color:$score_text_color;'>$shares</span></div>" . "<div  class='GStable-score'>$date_icon </br><span class='Gscore' style='color:$score_text_color;'>$date</span></div>" . "<div  class='GStable-score'>$time_icon </br><span class='Gscore' style='color:$score_text_color;'>$time</span></div>" . "</div>";
	return $score_table;
}

function noozefeedFetchImg($feed)
{
	if ($feed->feed_type === 'facebook') {
		$img = plugins_url('../img/fb.png', __FILE__);
	}
	else
	if ($feed->feed_type === 'twitter') {
		$img = plugins_url('../img/tw.png', __FILE__);
	}
	else {
		switch ($feed->parent_name) {
		case "ABC News (AU)":
			$img = plugins_url('../img/abc-news-au.png', __FILE__);
			break;

		case "Ars Technica":
			$img = plugins_url('../img/ars-technica.png', __FILE__);
			break;

		case "Associated Press":
			$img = plugins_url('../img/associated-press.png', __FILE__);
			break;

		case "BBC News":
			$img = plugins_url('../img/bbc-news.png', __FILE__);
			break;

		case "BBC Sport":
			$img = plugins_url('../img/bbc-sport.png', __FILE__);
			break;

		case "Bloomberg":
			$img = plugins_url('../img/bloomberg.png', __FILE__);
			break;

		case "Business Insider (UK)":
			$img = plugins_url('../img/business-insider-uk.png', __FILE__);
			break;

		case "Business Insider":
			$img = plugins_url('../img/business-insider.png', __FILE__);
			break;

		case "Buzzfeed":
			$img = plugins_url('../img/buzzfeed.png', __FILE__);
			break;

		case "CNBC":
			$img = plugins_url('../img/cnbc.png', __FILE__);
			break;

		case "CNN":
			$img = plugins_url('../img/cnn.png', __FILE__);
			break;

		case "Daily Mail":
			$img = plugins_url('../img/daily-mail.png', __FILE__);
			break;

		case "Engadget":
			$img = plugins_url('../img/engadget.png', __FILE__);
			break;

		case "Entertainment Weekly":
			$img = plugins_url('../img/entertainment-weekly.png', __FILE__);
			break;

		case "ESPN Cric Info":
			$img = plugins_url('../img/espn-cric-info.png', __FILE__);
			break;

		case "ESPN":
			$img = plugins_url('../img/espn.png', __FILE__);
			break;

		case "Financial Times":
			$img = plugins_url('../img/financial-times.png', __FILE__);
			break;

		case "Football Italia":
			$img = plugins_url('../img/football-italia.png', __FILE__);
			break;

		case "Fortune":
			$img = plugins_url('../img/fortune.png', __FILE__);
			break;

		case "FourFourTwo":
			$img = plugins_url('../img/four-four-two.png', __FILE__);
			break;

		case "Fox Sports":
			$img = plugins_url('../img/fox-sports.png', __FILE__);
			break;

		case "Google News":
			$img = plugins_url('../img/google-news.png', __FILE__);
			break;

		case "Hacker News":
			$img = plugins_url('../img/hacker-news.png', __FILE__);
			break;

		case "IGN":
			$img = plugins_url('../img/ign.png', __FILE__);
			break;

		case "Independent":
			$img = plugins_url('../img/independent.png', __FILE__);
			break;

		case "Mashable":
			$img = plugins_url('../img/mashable.png', __FILE__);
			break;

		case "Metro":
			$img = plugins_url('../img/metro.png', __FILE__);
			break;

		case "Mirror":
			$img = plugins_url('../img/mirror.png', __FILE__);
			break;

		case "MTV News (UK)":
			$img = plugins_url('../img/mtv-news-uk.png', __FILE__);
			break;

		case "MTV News":
			$img = plugins_url('../img/mtv-news.png', __FILE__);
			break;

		case "National Geographic":
			$img = plugins_url('../img/national-geographic.png', __FILE__);
			break;

		case "New Scientist":
			$img = plugins_url('../img/new-scientist.png', __FILE__);
			break;

		case "New York Magazine":
			$img = plugins_url('../img/new-york-magazine.png', __FILE__);
			break;

		case "Newsweek":
			$img = plugins_url('../img/newsweek.png', __FILE__);
			break;

		case "NFL News":
			$img = plugins_url('../img/nfl-news.png', __FILE__);
			break;

		case "Polygon":
			$img = plugins_url('../img/polygon.png', __FILE__);
			break;

		case "Recode":
			$img = plugins_url('../img/recode.png', __FILE__);
			break;

		case "Reddit /r/all":
			$img = plugins_url('../img/reddit-r-all.png', __FILE__);
			break;

		case "Reuters":
			$img = plugins_url('../img/reuters.png', __FILE__);
			break;

		case "Sky News":
			$img = plugins_url('../img/sky-news.png', __FILE__);
			break;

		case "Sky Sports News":
			$img = plugins_url('../img/sky-sports-news.png', __FILE__);
			break;

		case "TalkSport":
			$img = plugins_url('../img/talksport.png', __FILE__);
			break;

		case "TechCrunch":
			$img = plugins_url('../img/techcrunch.png', __FILE__);
			break;

		case "TechRadar":
			$img = plugins_url('../img/techradar.png', __FILE__);
			break;

		case "The Economist":
			$img = plugins_url('../img/the-economist.png', __FILE__);
			break;

		case "The Guardian (AU)":
			$img = plugins_url('../img/the-guardian-au.png', __FILE__);
			break;

		case "The Hindu":
			$img = plugins_url('../img/the-hindu.png', __FILE__);
			break;

		case "The Huffington Post":
			$img = plugins_url('../img/the-huffington-post.png', __FILE__);
			break;

		case "The Lad Bible":
			$img = plugins_url('../img/the-lad-bible.png', __FILE__);
			break;

		case "The New York Times":
			$img = plugins_url('../img/the-new-york-times.png', __FILE__);
			break;

		case "The Next Web":
			$img = plugins_url('../img/the-next-web.png', __FILE__);
			break;

		case "The Sport Bible":
			$img = plugins_url('../img/the-sport-bible.png', __FILE__);
			break;

		case "The Telegraph":
			$img = plugins_url('../img/the-telegraph.png', __FILE__);
			break;

		case "The Times of India":
			$img = plugins_url('../img/the-times-of-india.png', __FILE__);
			break;

		case "The Verge":
			$img = plugins_url('../img/the-verge.png', __FILE__);
			break;

		case "The Wall Street Journal":
			$img = plugins_url('../img/the-wall-street-journal.png', __FILE__);
			break;

		case "The Washington Post":
			$img = plugins_url('../img/the-washington-post.png', __FILE__);
			break;

		case "the-guardian-au.png":
			$img = plugins_url('../img/the-guardian-uk.png', __FILE__);
			break;

		case "Time":
			$img = plugins_url('../img/time.png', __FILE__);
			break;

		case "USA Today":
			$img = plugins_url('../img/usa-today.png', __FILE__);
			break;

		default:
			$img = "";
			break;
		}
	}

	return $img;
}
