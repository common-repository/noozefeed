<?php
defined('ABSPATH') or die();

function noozefeedListView($request_id, $limit)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'nfDetails';
	$details = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = 1");
	$table_color = $details->table_color;
	$table_radius = $details->external_radius_size;
	$table_name = $wpdb->prefix . 'nfRequests';
	$request = $wpdb->get_row("SELECT * from $table_name WHERE request_id = '$request_id'", OBJECT);
	$noozefeed_table = trim(' ');
	if ($request->sort_by == 'date') {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY created_time DESC LIMIT $limit");
	}
	else
	if ($request->sort_by == 'new') {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to 
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY new DESC LIMIT $limit");
	}
	else
	if ($request->sort_by == 'likes') {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY like_count DESC LIMIT $limit");
	}
	else
	if ($request->sort_by == 'relevance') {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY relevance_score DESC LIMIT $limit");
	}
	else {
		$feeds = $wpdb->get_results("SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfFacebook WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfTwitter WHERE request_id = '$request_id'
                                        UNION ALL
                                        SELECT parent_id,parent_name,feed_id,feed_type,message,name,created_time,link,picture,shares,like_count,relevance_score,new,linked_to  
                                        FROM " . $wpdb->prefix . "nfArticle WHERE request_id = '$request_id'
                                        ORDER BY shares DESC LIMIT $limit");
	}

	// insert style info here

	$noozefeed_table = $noozefeed_table . "<link type='text/css' rel='Stylesheet' href='" . plugins_url('../css/noozefeed-list.css', __FILE__) . "'>";
	$noozefeed_table = $noozefeed_table . "<div class='Ltable Ltable--1cols Ltable--collapse' style='background-color:$table_color; border-color:$table_color; border-radius:$table_radius;'>";
	foreach($feeds as $feed) {
		$noozefeed_table = $noozefeed_table . noozefeedProcessListViewFeeds($feed, $details);
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

function noozefeedProcessListViewFeeds($feed, $details)
{
	$internal_radius_size = $details->internal_radius_size;
	$table_color = $details->table_color;
	$head_text_color = $details->head_text_color;
	$feed_text_color = $details->feed_text_color;
	$underline_color = $details->underline_color;
	$score_text_color = $details->score_text_color;
	$background_color = $details->background_color;

	// fetch the Apropriate Feed Image

	$img = noozefeedFetchImg($feed);
	$hyperlink = noozefeedFetchHyperlink($feed);
	$picture = noozefeedFetchPicture($feed);
        
        $noozefeed_table = trim(' ');
        
        if ($feed->new === '1') {
            $newfeed = 'NEW FEED';
        }
        else {
            $newfeed = trim(' ');
        }
        
	global $pagenow;
	if ($pagenow === 'admin-ajax.php') {  // only display new feed within admin console
		$noozefeed_table = $noozefeed_table . "<div data-new='$feed->new' style='border-radius: $internal_radius_size; border-color:$table_color; background-color:$background_color; border-bottom: 3px solid $underline_color;' class='Ltable-list'><div class='getMoreList' style='display:none;'><input type='button' class='get_more_feeds get_more_feeds-success' id='getmore$feed->feed_id' data-title='" . htmlspecialchars($feed->name, ENT_QUOTES) . "' data-message='" . htmlspecialchars($feed->message, ENT_QUOTES) . "' data-parent_name='" . htmlspecialchars($feed->parent_name, ENT_QUOTES) . "' value='More Like This'></div><div class='newBar' data-bar='$feed->new'>$newfeed</div><style media='screen' type='text/css'>.sourceThumb {top: 40px !important;}</style>"; // Start of table-cell block
	} 
	else {
		$noozefeed_table = $noozefeed_table . "<div data-new='$feed->new' style='border-radius: $internal_radius_size; border-color:$table_color; background-color:$background_color; border-bottom: 3px solid $underline_color;' class='Ltable-list'><div class='getMoreList' style='display:none;'><input type='button' class='get_more_feeds get_more_feeds-success' id='getmore$feed->feed_id' data-title='" . htmlspecialchars($feed->name, ENT_QUOTES) . "' data-message='" . htmlspecialchars($feed->message, ENT_QUOTES) . "' data-parent_name='" . htmlspecialchars($feed->parent_name, ENT_QUOTES) . "' value='More Like This'></div>"; // Start of table-cell block
	}
	
	$noozefeed_table = $noozefeed_table . "<div style='divMinContainer'><div class='LtableImg'><a href='$hyperlink' target='_blank'><div class='LtableImgThumb' style='background-image: url($picture);'></div></a>";
	$string = wp_strip_all_tags($feed->message);
	$feed_title = $feed->name;

	// Truncate message string

	if (strlen($string) > 145) {
		$string_cut = substr($string, 0, 145);
		$string = substr($string_cut, 0, strrpos($string_cut, ' '));
		$string = "$string...<a href='$hyperlink' target='_blank'><i><font style='font-size:smaller; color:$score_text_color;'>read more</font></i></a>";
	}

	// If no title (ie Twitter)

	if ($feed->name == "") {
		$string_cut = substr($string, 0, 35);
		$feed_title = substr($string_cut, 0, strrpos($string_cut, ' '));
	}

	// $noozefeed_table = $noozefeed_table . "<header><div class='sourceThumb'><img style='max-width:75px;' align='left' src='$img'/></div></header></div>";

	$noozefeed_table = $noozefeed_table . "<header></header></div>";

	// $noozefeed_table = $noozefeed_table . "<div class='titleTable titleTable--2cols titleTable--collapse'>";

	$noozefeed_table = $noozefeed_table . "<div class='titleTable titleTable--2cols titleTable--collapse'>";
        $noozefeed_table = $noozefeed_table . "<div class='titleTable-title'><a style='text-decoration:none; color:$head_text_color;' href='$hyperlink' target='_blank'>$feed_title</a><a href='$hyperlink' target='_blank' style='text-decoration:none;'><font style='color:$score_text_color;'><div class='sourceThumb'><img align='right' src='$img'/></div></font></a></div>";
	$noozefeed_table = $noozefeed_table . "<div class='titleTable-icon'></div>";
	$noozefeed_table = $noozefeed_table . "</div>";
	$noozefeed_table = $noozefeed_table . "<div class='titleTable-source'><a href='$hyperlink' target='_blank' style='text-decoration:none;'><font style='color:$score_text_color;'>$feed->parent_name</font></a></div>";
	$noozefeed_table = $noozefeed_table . "<div class='LdivContainer'><span class='Lp' style='color:$feed_text_color'>$string</span></div>";
	$time = get_date_from_gmt($feed->created_time, 'g:i A');
	$date = $feed->created_time; // Pull your value
	if (strtotime(get_date_from_gmt($feed->created_time)) >= strtotime("today")) {
		$date = "Today";
	}
	else {
		$date = "Yesterday";
	}

	$noozefeed_table = $noozefeed_table . noozefeedListScore($date, $time, $feed->like_count, $feed->shares, $score_text_color);
	$noozefeed_table = $noozefeed_table . "</div></div></div>"; //End Gtable-cell Data BLock
	return $noozefeed_table;
}