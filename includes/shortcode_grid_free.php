<?php
defined('ABSPATH') or die();

function noozefeedGridView($request_id, $limit)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'nfDetails';
	$nfDetails = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = 1");
	$table_color = $nfDetails->table_color;
	$table_radius = $nfDetails->external_radius_size;
	$table_name = $wpdb->prefix . 'nfRequests';
	$request = $wpdb->get_row("SELECT * from $table_name WHERE request_id = '$request_id'", OBJECT);
	$noozefeed_table = trim(' ');
	if ($request->sort_by == 'date') {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new   
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'   
                                        ORDER BY created_time DESC LIMIT $limit");
	}
	else
	if ($request->sort_by == 'new') {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new   
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY new DESC LIMIT $limit");
	}
	else
	if ($request->sort_by == 'likes') {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new   
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY like_count DESC LIMIT $limit");
	}
	else
	if ($request->sort_by == 'relevance') {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new   
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY relevance_score DESC LIMIT $limit");
	}
	else {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new   
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new 
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY shares DESC LIMIT $limit");
	}

	// insert style info here

	$noozefeed_table = "<link type='text/css' rel='Stylesheet' href='" . plugins_url('../css/noozefeed-grid.css', __FILE__) . "'>";
	$noozefeed_table = $noozefeed_table . "<div style='background-color:$table_color; border-color:$table_color; border-radius:$table_radius;' class='Gtable Gtable--3cols Gtable--collapse'>";
	foreach($feeds as $feed) {
		$noozefeed_table = $noozefeed_table . noozefeedProcessGridViewFeeds($feed, $nfDetails);
	}

	$noozefeed_table = $noozefeed_table . "</div>"; //End Gtable Block
	$noozefeed_logo = "
        <div align='center' style='padding-bottom: 30px;'><div style='margin-top: 5px; max-width: 400px;'>
        <a href='https://noozefeed.com' target='_blank'><img class='Lnf-banner' src='" . plugins_url('../img/poweredby.png', __FILE__) . "'/></a></br>
        </div></div>
        <div style='height:150px';></div>";
	$noozefeed_table = $noozefeed_table . $noozefeed_logo;
	return $noozefeed_table;
}

function noozefeedProcessGridViewFeeds($feed, $nfDetails)
{
	$internal_radius_size = $nfDetails->internal_radius_size;
	$table_color = $nfDetails->table_color;
	$head_text_color = $nfDetails->head_text_color;
	$feed_text_color = $nfDetails->feed_text_color;
	$underline_color = $nfDetails->underline_color;
	$score_text_color = $nfDetails->score_text_color;
	$background_color = $nfDetails->background_color;

	// fetch the Apropriate Feed Image

	$img = noozefeedFetchImg($feed);
	$hyperlink = noozefeedFetchHyperlink($feed);
	$picture = noozefeedFetchPicture($feed);
        
        global $pagenow;
	if (($feed->new === '1') && ($pagenow === 'admin-ajax.php')) {  // only display new feed within admin console
		$newfeed = 'NEW FEED';
	}
	else {
		$newfeed = trim(' ');
	}
        
	$noozefeed_table = '';
	$noozefeed_table = $noozefeed_table . "<div data-new='$feed->new' style='border-radius: $internal_radius_size;border-color:$table_color; background-color:$background_color; border-bottom: 3px solid $underline_color;' class='Gtable-grid'><div class='getMoreGrid' style='display:none;'><input type='button' class='get_more_feeds get_more_feeds-success' id='getmore$feed->feed_id' data-title='$feed->name' data-message='$feed->message' data-parent_name='$feed->parent_name' value='More Like This'></div><div class='newBar' data-bar='$feed->new'>$newfeed</div>"; // Start of Gtable-cell block

	// $noozefeed_table = $noozefeed_table . "<div style='max-width: 500px; margin: 0 auto;'><div class='GtableImg' style='background-image: url($feed->picture);'>";

	$noozefeed_table = $noozefeed_table . "<div style='divMinContainer'><div class='GtableImg'><a href='$hyperlink' target='_blank'><div class='GtableImgThumb' style='background-image: url($picture);'></div></a>";
	$string = wp_strip_all_tags($feed->message);
	$feedTitle = $feed->name;

	// Truncate message string

	if (strlen($string) > 140) {
		$stringCut = substr($string, 0, 140);
		$string = substr($stringCut, 0, strrpos($stringCut, ' '));
		$string = "$string......<a href='$hyperlink' target='_blank'><i><font style='font-size:smaller'>Read More</font></i></a>";
	}

	// If no title (ie Twitter)

	if ($feed->name == "") {
		$stringCut = substr($string, 0, 35);
		$feedTitle = substr($stringCut, 0, strrpos($stringCut, ' '));
	}

	$string = "$string <div style='padding-top:10px; text-align:center;'><a href='$hyperlink' target='_blank' style='text-decoration:none;'><span style='color:$head_text_color;'>Source: </span><font style='color:$score_text_color;'>$feed->parent_name</font></a></div>";
	$noozefeed_table = $noozefeed_table . "<Gheader class='Gheader'><img align='left' src='$img' /></Gheader></div>";
	$noozefeed_table = $noozefeed_table . "<div><a style='text-decoration:none;' href='$hyperlink' target='_blank'><span class='headText' style='color:$head_text_color;'>$feedTitle</span></a></div><div class='GdivContainer'><span class='Gp' style='color:$feed_text_color'>$string</span></div>";
	$time = get_date_from_gmt($feed->created_time, 'g:i A');
	$date = $feed->created_time; // Pull your value
	if (strtotime(get_date_from_gmt($feed->created_time)) >= strtotime("today")) {
		$date = "Today";
	}
	else {
		$date = "Yesterday";
	}

	$noozefeed_table = $noozefeed_table . "<div align='center'>";
	$noozefeed_table = $noozefeed_table . noozefeedGridScore($date, $time, $feed->like_count, $feed->shares, $score_text_color);
	$noozefeed_table = $noozefeed_table . "</div></div>";
	$noozefeed_table = $noozefeed_table . "</div>"; //End Gtable-cell Data BLock
	return $noozefeed_table;
}