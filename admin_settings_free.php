<?PHP

defined( 'ABSPATH' ) or die();

wp_enqueue_style( 'noozefeed', plugins_url( 'css/noozefeed-admin.css', __FILE__ ) );
wp_enqueue_style( 'noozefeed-tabs.min', plugins_url( 'css/tabs.min.css', __FILE__ ) );
wp_enqueue_style( 'noozefeed-list', plugins_url( 'css/noozefeed-list.css', __FILE__ ) );
wp_enqueue_style( 'noozefeed-grid', plugins_url( 'css/noozefeed-grid.css', __FILE__ ) );
wp_enqueue_style( 'bootstrap', plugins_url( 'css/bootstrap.min.css', __FILE__ ) ); 
wp_enqueue_script( 'jquery-ui-tabs', array ( 'jquery' ), false, true );
wp_enqueue_script( 'jquery.livequery', plugins_url( 'js/jquery.livequery.js', __FILE__ ), array ( 'jquery' ), false, true);
wp_enqueue_script( 'loadingoverlay.min', plugins_url( 'js/loadingoverlay.min.js', __FILE__ ), array ( 'jquery' ), false, true);
wp_enqueue_script( 'admin_settings_free', plugins_url( 'js/admin_settings_free.js', __FILE__ ), array ( 'jquery' ), false, true);
wp_enqueue_script( 'jquery-confirm.min', plugins_url( 'js/jquery-confirm.min.js', __FILE__ ), array ( 'jquery' ), false, true);
wp_enqueue_style( 'jquery-confirm.min', plugins_url( 'css/jquery-confirm.min.css', __FILE__ ) );
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'color-picker', plugins_url( 'js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

include 'includes/variables_free.php';

?>
           
<div class="divLfOffer">   
<div class="tabBorder">
    <div class="Rtable Rtable--2cols Rtable--collapse" style="width: 100% !important;height: 100%;">
        <div class="Rtable-logo">
            <img class='nf-logo' style="left:20px;" src='<?php echo plugins_url( 'img/noozefeed.png', __FILE__ ); ?>' /></br>
        </div>
        <div class="Rtable-feedback">
                <div class="text-right">
                <a class="btn btn-success btn-green" href="#feedback-anchor" id="open-feedback-box">Feedback</a>
                </div>
                <div class="row" id="post-feedback-box" style="display:none;">
                    <div class="col-md-12">
                        <input id="ratings-hidden" name="rating" type="hidden"> 
                        <textarea class="form-control animated" cols="50" id="feedback" name="comment" placeholder="Enter your feedback or give us a star rating or both!" rows="2"></textarea>
                        <div class="text-right">
                            <div class="stars starrr" data-rating="0"></div>
                            <a class="btn btn-danger btn-sm" href="#" id="close-feedback-box" style="display:none; margin-right: 10px;">
                            <span class="glyphicon glyphicon-remove"></span>Cancel</a>
                            <button id="submit_feedback" class="btn btn-success btn-lg">Save</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tabs" style="border-radius: 15px 70px 15px 70px;border: 15px solid #e0e0e0;">
      <ul>
        <li><a href="#tabs-1" style="font-size: 100%; font-stretch: 2px;">Global Settings</a></li>
        <li><a href="#tabs-2" style="font-size: 100%; font-stretch: 2px;">Search</a></li>
        <li><a href="#tabs-3" style="font-size: 100%; font-stretch: 2px;">Display</a></li>
        <li><a href="#tabs-4" style="font-size: 100%; font-stretch: 2px;">Results</a></li>
      </ul>
      <div id="tabs-1" style="margin:auto;">
        
        <div class="Rtable Rtable--2cols Rtable--collapse" style="width: 100% !important; padding-top: 20px;">
            <div class="Rtable-cell Rtable-cell--head" >Subscription Level:</div>
            <div class="Rtable-data" align="left"><input size="120" type="account" value="free" name="subscription_level" id="subscription_level" readonly></div>
            <div class="Rtable-cell Rtable-cell--head" >Request Interval (mins):</div>
            <div class="Rtable-data" align="left"><input size="120" type="account" value="daily" name="request_rate" id="request_rate" readonly></div>
            <div class="Rtable-cell Rtable-cell--head" >Feed Limit Per Request:</div>
            <div class="Rtable-data"><input size="120" type="account" value="50" name="feed_limit" id="feed_limit" readonly></div>
            <input name="save_tab_index" id="save_tab_index" value="<?php echo $save_tab_index; ?>" hidden="true"/>   
            <input name="noozefeed_nonce" id="noozefeed_nonce" value="<?php echo $noozefeed_nonce; ?>" hidden="true"/>  
            <div class="Rtable-cell"></div>
        </div>
        <div class="rTableHead">Get Pro today to experience all these features</div>
        
        <!-- VALUE PROPOSITION -->

        <div class="Rtable Rtable--3cols Rtable--collapse" style="max-width: 600px; border-radius: 25px; background-color: #f8f8f8; padding: 25px 0px 25px 45px;">
            <div class="Rtable-valueTag"></div>
            <div class="Rtable-valueProp"><div class="rTableHead">FREE</div></div>
            <div class="Rtable-valueProp"><div class="rTableHead">PRO</div></div>

            <div class="Rtable-valueTag">Frequency of Updates</div>
            <div class="Rtable-valueProp">Daily</div>
            <div class="Rtable-valueProp">Hourly</div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>

            <div class="Rtable-valueTag">Number of feeds returned</div>
            <div class="Rtable-valueProp">50</div>
            <div class="Rtable-valueProp">100</div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>

            <div class="Rtable-valueTag">Number of keywords per search</div>
            <div class="Rtable-valueProp">1</div>
            <div class="Rtable-valueProp">3</div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>

            <div class="Rtable-valueTag">Number of places per search</div>
            <div class="Rtable-valueProp">1</div>
            <div class="Rtable-valueProp">3</div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>

            <div class="Rtable-valueTag">Add / Remove Feed</div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/N.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/Y.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div><div class="Rtable-valueTag">Add / Remove Publisher</div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/N.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/Y.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div><div class="Rtable-valueTag">Manual / Auto Publish Feeds</div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/N.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/Y.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div><div class="Rtable-valueTag">Disable / Enable Feeds</div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/N.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/Y.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>

            <div class="Rtable-valueTag">Omit duplicate Tweets</div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/N.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/Y.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>

            <div class="Rtable-valueTag">Show latest feed per source</div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/N.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/Y.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>
            <div class="Rtable-valueSpace"></div>

            <div class="Rtable-valueTag">Support</div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/N.png', __FILE__ ); ?>'/></div>
            <div class="Rtable-valueProp"><img src='<?php echo plugins_url( 'img/Y.png', __FILE__ ); ?>'/></div>
        </div>
        <div class="divTag"><a href="http://noozefeed.com/buy/" target="_blank">Sign Me Up!</a></div>
      </div>
       <div id="tabs-2" style="margin:auto;">
           <div style="position: absolute; z-index: 2;"><span style="font-size:30px;cursor:pointer; color:#5cb85c" onclick="openSlideNav()">&#9776;</span></div>
           <div id="mySlidenav" class="slidenav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeSlideNav()">&times;</a>
                        <div class="rTableHead" style="padding-top:10px; width:100%; font-size:120%; text-align: center; ">Exclude Any Keywords</div>
                        <div class="Rtable Rtable--collapse" style="width: 100%; margin: 0 auto;">
                            <div class="searchField"><textarea rows="2" size="250" width="100%" length="100%" align="center" value="<?php echo htmlspecialchars(stripslashes($exclude_filter)); ?>" name="exclude_filter" id="exclude_filter" placeholder=""></textarea></div>
                        </div> 
                        <!-- Feed Sources -->
                        <div class="rTableHead" style="width:100%; font-size:120%; text-align: center; margin-bottom: 0px; margin-top: 5px;">Include Feed Sources</div>
                        <div class="Rtable Rtable--6cols Rtable--collapse" style="width: 100% !important; position: relative; padding-left: 20px;">
                            <div class="Rtable-check" ><input style="padding-left:20px; vertical-align: middle; margin-bottom: 10px;" type="checkbox" name="Facebook" id="facebook_check_box" value="facebook" <?php if (!isset($social_media)) { echo "checked"; }?>></div>
                            <div id="photo_container" class="Rtable-icon" style="margin-top:3px;"><img src="<?php echo plugins_url( 'img/fb.png', __FILE__ ); ?>" /></div>
                            <div class="Rtable-check" ><input style="padding-left:20px; vertical-align: middle; margin-bottom: 10px;" type="checkbox" name="Twitter" id="twitter_check_box" value="twitter" <?php if (!isset($social_media)) { echo "checked"; }?>></div>
                            <div id="photo_container" class="Rtable-icon" style="margin-top:3px;"><img src="<?php echo plugins_url( 'img/tw.png', __FILE__ ); ?>" /></div>
                            <div class="Rtable-check" ><input style="padding-left:20px; vertical-align: middle; margin-bottom: 10px;" type="checkbox" name="Article" id="article_check_box" value="article" <?php if (!isset($social_media)) { echo "checked"; }?>></div>
                            <div id="photo_container" class="Rtable-icon" style="margin-top:3px;"><img src="<?php echo plugins_url( 'img/article.png', __FILE__ ); ?>" /></div>        
                        </div>
                        <!--Set geographic regions--> 
                        <div class="rTableHead" style="padding-top:10px; width: 100%; font-size: 120%; text-align: center; margin-bottom: 0px;">Enter a Geography</div>
                        <div class="Rtable Rtable--collapse" style="width: 100%; margin: 0 auto;">
                            <div class="searchField"><textarea rows="2" name="places" id="places" size="250" width="100%" length="100%" align="center" placeholder="Or enter a region or geographical location"><?php echo $places; ?></textarea></div>
                        </div>
                        <!--Disable/enable Geo Locations--> 
                        <div class="rTableHead" style="padding-top:10px; width:100%; font-size:120%; text-align: center; margin: 10px 0px 0px 20px;">Set Geographical Restrictions</div>
                        <div class="Rtable Rtable--3cols Rtable--collapse" style="margin: 0 auto; width: 100%; max-width:600px !important;">
                            <div id="left_container" class="Rtable-geo" ><a href="#" style="font-size: 85%;">Global<span>Includes </br></br> Keyword + Coordinates (supports all sort options including distance) </br></br> Keyword search only (relevance, time, shares, likes only)</span></a><div class="Rtable-slideRadio"><input type="radio" id="global_search" name="restrict_to_radius" <?php if ($restrict_to_radius==='2') { echo "checked"; }?> ></div></div>
                            
                            <div id="middle_container" class="Rtable-geo" ><a href="#" style="font-size: 85%;">Include Geo<span>Includes </br></br> Keyword+place search + coordinates (supports all sort options including distance) </br></br> Keyword+place search only (relevance, time, shares, likes only)</span></a><div class="Rtable-slideRadio" ><input type="radio" id="disable_radius_restriction" name="restrict_to_radius" <?php if ($restrict_to_radius==='0') { echo "checked"; }?> ></div></div>
                            
                            <div id="right_container" class="Rtable-geo" ><a href="#" style="font-size: 85%;">Geo-restrict<span>Includes </br></br> keyword search + coordinates + radius (supports all sort options including distance) </br></br> keyword+place search + coordinates + radius (supports all sort options including distance)</span></a><div class="Rtable-slideRadio" ><input type="radio" id="enable_radius_restriction" name="restrict_to_radius" <?php if ($restrict_to_radius==='1') { echo "checked"; }?> ></div></div>
                                                                              
                        </div>
                </div>
           <div class="Rtable-data" style="position:relative;">
            <!--<div class="divTag">Set your query location, and the results will be filtered to this area.</div>-->
            <div class="Rtable Rtable--2cols Rtable--collapse" style="width: 100% !important;height: 100%;">
                <!--Name / Entry--> 
                <div style="position:relative; padding: 0px; top:0px; right:0px;" class="Rtable-map Rtable-cell--head">
                
                    <!--SIDENAV--> 
                <div class="rTableHead" style="width:100%; font-size:120%; text-align: center; margin-bottom: 10px;">Search for the latest news<span id="nf_search" class="NFsearchHelp" ><a href="#" style="font-size: 85%;"><img src='<?php echo plugins_url( 'img/searchHelp_smal.png', __FILE__ ); ?>'/><span>Search terms can be entered as standard text with spaces (OR), wrapped with double quotes (AND) or surrounded with @ symbol (EXACT). For example, to search for feeds with both 'Sydney' and 'Events' enter "Sydney events". If you are searching for a person or thing surround with @ symbols, @Donald Trump@.</span></a></span></div>
                <div class="Rtable Rtable--collapse" style="width: 100%; margin: 0 auto;">
                    <div class="Rtable-cell Rtable-cell--head" style="display:none;">Search Name:</div>
                    <div class="Rtable-data" style="display:none;"><input  maxlength="500" type="text" value="<?php echo $noozefeed_id; ?>" name="noozefeed_id" id="noozefeed_id" placeholder="Enter a unique search name (ID)" ></div>
                    <div class="searchField"><textarea rows="2" name="keywords" id="keywords" placeholder=""><?php echo htmlspecialchars(stripslashes($keywords)); ?></textarea> </div>
                </div>
                <div style="margin-top: 15px;" class="Rtable-map">
                    <!--<div class="rTableHead" style="padding-top:0px; width:100%; font-size:120%; text-align: center; margin-bottom: 10px;">Where Are You Searching?</div>-->
                    <iframe src="https://noozefeed.com/get_location.html" id="get_location" name="get_location" align="center" ></iframe>
                    <input name="latitude" id="latitude" value="<?php echo $latitude; ?>" hidden="true"/>
                    <input name="longitude" id="longitude" value="<?php echo $longitude; ?>" hidden="true"/>
                    <input name="radius" id="radius" value="<?php echo $radius; ?>" hidden="true"/>
                    <input name="zoom" id="zoom" value="<?php echo $zoom; ?>" hidden="true"/>
                    <input name="population" id="population" size="20" hidden="true"/>
                    <input name="has_activated" id="has_activated" value="<?php echo $has_activated; ?>"hidden="true"/>                
                </div>
                <div class="clickButton"><input style="max-width: 800px;" type="button" class="btn btn-success btn-green" id='search_feeds' value="Search" /></div>
          

                <script>
                function openSlideNav() {
                    document.getElementById("mySlidenav").style.width = "300px";
                }

                function closeSlideNav() {
                    document.getElementById("mySlidenav").style.width = "0";
                }
                </script>
                    <!--<div style="margin: 0 auto; align-content: center; margin: 0 auto; padding-top:10px; text-align: left;" class="tooltip"><font color="green">What do these do?</font>
                        <span class="tooltiptext">By setting this, you can restrict the results to only those that are set within the geographic region.</span>
                    </div>-->
                </div>
                <!--MAP-->
                
                </div>
      </div>
       </div>               
      <div id="tabs-3">
        <div id="mySideSettings" class="sideSettings">
             <a href="javascript:void(0)" class="closebtn" onclick="closeSideSettings()">&times;</a>
             <div align="center" style="margin:0 auto;"><input class="btn btn-success btn-green" id="reset_display" value="Reset Display"></div>
        </div>
        <div style="position: absolute; z-index: 2;"><span style="font-size:30px;cursor:pointer; color:#5cb85c" onclick="openSideSettings()">&#9776;</span></div>
        <div class="Rtable-data">
            <!--<div class="divTag">Choose how you want the query results to be displayed</div>-->
            <div class="Rtable Rtable--3cols Rtable--collapse" style="width: 100% !important;">
                <div class="Rtable-displaySettings" style="position:relative; padding: 10px; padding-top:0px; top:0px; right:0px;">
                    <!-- Left Column -->
                    <div class="rTableHead" style="width: 100%; font-size: 120%; padding-bottom: 10px;">Sort</div>
                    <div class="Rtable Rtable--2cols">
                        <div class="Rtable-radioText Rtable-cell--head">Recent Search:&nbsp;</div>
                        <div class="Rtable-radio"><input type="radio" id="sort_by_new" name="sort_by" <?php if (isset($sort_by) && $sort_by==="new" || $sort_by===null) { echo "checked"; }?> value="new"></div>
                        <div class="Rtable-radioText Rtable-cell--head">Most Recent:&nbsp;</div>
                        <div class="Rtable-radio"><input type="radio" id="sort_by_date" name="sort_by" <?php if (isset($sort_by) && $sort_by==="date") { echo "checked"; }?> value="date"></div>
                        <div class="Rtable-radioText Rtable-cell--head">Relevance:&nbsp;</div>
                        <div class="Rtable-radio"><input type="radio" id="sort_by_relevance" name="sort_by" <?php if (isset($sort_by) && $sort_by==="relevance") { echo "checked"; }?> value="relevance"></div>
                        <div class="Rtable-radioText Rtable-cell--head">Distance:&nbsp;</div>
                        <div class="Rtable-radio"><input type="radio" id="sort_by_location" name="sort_by" <?php if (isset($sort_by) && $sort_by==="location") { echo "checked"; }?> value="location"></div>
                        <div class="Rtable-radioText Rtable-cell--head">Likes/Favorites:&nbsp;</div>
                        <div class="Rtable-radio"><input type="radio" id="sort_by_likes" name="sort_by" <?php if (isset($sort_by) && $sort_by==="likes") { echo "checked"; }?> value="likes"></div>
                        <div class="Rtable-radioText Rtable-cell--head">Shares/Retweets:&nbsp;</div>
                        <div class="Rtable-radio"><input type="radio" id="sort_by_shares" name="sort_by" <?php if (isset($sort_by) && $sort_by==="shares") { echo "checked"; }?> value="shares"></div>
                    </div>
                    <div class="rTableHead" style=" padding-bottom: 10px; margin-top: 20px; width: 100%; font-size: 120%;">Display</div>
                    <div class="Rtable Rtable--3cols"style="max-width:230px;">
                        <div class="Rtable-radioText" style="margin-top:20px;">List&nbsp;</div>
                        <div class="Rtable-radio" style="margin-top:5px;"><img src="<?php echo plugins_url( 'img/list.png', __FILE__ ); ?>" /></div>
                        <div class="Rtable-radio" style="margin-top:20px;"><input type="radio" id="view_type_list" name="view_type" <?php if (isset($view_type) && $view_type==="list" || $view_type===null) { echo "checked"; }?> value="list"></div>
                        <div class="Rtable-radioText" style="margin-top:20px;">Grid&nbsp;</div>
                        <div class="Rtable-radio" style="margin-top:5px;"><img src="<?php echo plugins_url( 'img/grid.png', __FILE__ ); ?>" /></div>
                        <div class="Rtable-radio" style="margin-top:20px;"><input type="radio" id="view_type_grid" name="view_type" <?php if (isset($view_type) && $view_type==="grid") { echo "checked"; }?> value="grid"></div>
                    </div>
                    <div class="Rtable Rtable--2cols">
                        
                    </div>
                </div>
                <div style="padding: 10px;" class="Rtable-displayColor">
                    <!-- Right Column -->
                    <div class="rTableHead" style="padding-top: 0px; padding-bottom: 10px; width: 100%; font-size: 120%;">Colors</div>
                    <div class="Rtable Rtable--2cols Rtable--collapse" style="width: 100% !important">
                        <div class="Rtable-colorText Rtable-cell--head" >Table Color</div>
                        <div class="Rtable-color" ><input type="text" class="my-color-field" value="<?php echo $table_color; ?>" name="table_color" id="table_color" data-default-color="#e40079"></div>
                        <div class="Rtable-colorText Rtable-cell--head" >Row Underline</div>
                        <div class="Rtable-color" ><input type="text" class="my-color-field" value="<?php echo $underline_color; ?>" name="underline_color" id="underline_color" data-default-color="#e40079"></div>
                        <div class="Rtable-colorText Rtable-cell--head" >Feed Title</div>
                        <div class="Rtable-color" ><input type="text" class="my-color-field" value="<?php echo $head_text_color; ?>" name="head_text_color" id="head_text_color" data-default-color="#e40079"></div>
                        <div class="Rtable-colorText Rtable-cell--head" >Feed Text</div>
                        <div class="Rtable-color" ><input class="my-color-field" value="<?php echo $feed_text_color; ?>" name="feed_text_color" id="feed_text_color" data-default-color="#606060"></div>
                        <div class="Rtable-colorText Rtable-cell--head" >Score Text</div>
                        <div class="Rtable-color" ><input type="text" class="my-color-field" value="<?php echo $score_text_color; ?>" name="score_text_color" id="score_text_color" data-default-color="#0000ff"></div>
                        <div class="Rtable-colorText Rtable-cell--head" >Background Color</div>
                        <div class="Rtable-color" ><input type="text" class="my-color-field" value="<?php echo $background_color; ?>" name="background_color" id="background_color" data-default-color="#ffffff"></div>
                    </div>
                </div>
                <div style="padding: 10px;" class="Rtable-displaySettings">
                    <!-- Right Column -->
                    <div class="rTableHead" style="padding-top: 0px; padding-bottom: 10px; width: 100%; font-size: 120%;">Settings</div>
                    <div class="Rtable Rtable--2cols Rtable--collapse" style="width: 100% !important">
                        <div class="Rtable-settings Rtable-cell--head" style="width: 50%">Maximum Feeds</div>
                        <div class="Rtable-data" style="width: 50%"><input type="text" style="width:100px; max-width:100%;" value="<?php echo $max_feeds; ?>" name="max_feeds" id="max_feeds" placeholder="100"></div>
                        <div class="Rtable-settings Rtable-cell--head" style="width: 50%">Table Height</div>
                        <div class="Rtable-data" style="width: 50%"><input type="text" style="width:100px; max-width:100%;" value="<?php echo $table_height; ?>" name="table_height" id="table_height" placeholder="500">&nbsp;&nbsp;<span style='color:#c6c6c6;'>px</span></div>
                        <div class="Rtable-settings Rtable-cell--head" style="width: 50%">Cell Border Radius</div>
                        <div class="Rtable-data" style="width: 50%"><input type="text" style="width:100px; max-width:100%;" value="<?php echo $internal_radius_size; ?>" name="internal_radius_size" id="internal_radius_size" placeholder="0">&nbsp;&nbsp;<span style='color:#c6c6c6;'>px</span></div>
                        <div class="Rtable-settings Rtable-cell--head" style="width: 50%">Table Border Radius</div>
                        <div class="Rtable-data" style="width: 50%"><input type="text" style="width:100px; max-width:100%;" value="<?php echo $external_radius_size; ?>" name="external_radius_size" id="external_radius_size" placeholder="0">&nbsp;&nbsp;<span style='color:#c6c6c6;'>px</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div align="center" style="margin:auto;">
        <script>
            /* Set the width of the side navigation to 250px */
            function openSideSettings() {
                document.getElementById("mySideSettings").style.width = "250px";
            }

            /* Set the width of the side navigation to 0 */
            function closeSideSettings() {
                document.getElementById("mySideSettings").style.width = "0";
            }
        </script>  
        <input style="max-width: 600px;" type="button" class="btn btn-success btn-green" id='save_custom_settings' value="Save Settings" />
        </div>    
    </div>
    <div id="tabs-4" style="margin:auto;">
        <!-- SLIDER -->
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div align="center" style="margin-top: 10px;">
                <div id="sidenav_button"><input class="btn btn-success btn-green" id="delete_feeds" value="Delete Feeds"></div>
            </div>
            <div align="center" style="margin-top: 10px;">
                <div id="sidenav_button"><input class="btn btn-success btn-green" id="reset_search" value="Reset Search"></div>
            </div>
        </div>
        <div style="position: absolute; z-index: 2;"><span style="font-size:30px;cursor:pointer; color:#5cb85c" onclick="openNav()">&#9776;</span></div>
            
            <!--   END SLIDER  -->
        <!--Feeds Found feedback Loop;-->
        <div class="rTableHead" style="width: 100%; font-size: 120%; text-align: center;">Feeds Found</div>
        <div class="Ftable Ftable--10cols Ftable--collapse" style="width: 100% !important; margin-bottom: 10px;">
            <!-- Feeds Found -->
            <div class="Ftable-cell" style="margin-top:0px;"><img src="<?php echo plugins_url( 'img/fb.png', __FILE__ ); ?>" /></div>
            <div class="Ftable-data" ><input maxlength="300" type="mapResults" value="<?php echo $facebook_count; ?>" name="facebook_count" id="facebook_count"></div>
            <div class="Ftable-cell" style="margin-top:0px;"><img src="<?php echo plugins_url( 'img/tw.png', __FILE__ ); ?>" /></div>
            <div class="Ftable-data" ><input maxlength="300" type="mapResults" value="<?php echo $twitter_count; ?>" name="twitter_count" id="twitter_count"></div>
            <div class="Ftable-cell" style="margin-top:0px;"><img src="<?php echo plugins_url( 'img/article.png', __FILE__ ); ?>" /></div>
            <div class="Ftable-data" ><input maxlength="300" type="mapResults" value="<?php echo $article_count; ?>" name="article_count" id="article_count"></div>
            <div class="Ftable-cell" >Total feeds</br>received</div>
            <div class="Ftable-data" ><input maxlength="300" type="mapResults" value="<?php echo $num_feeds_total; ?>" name="num_feeds_total" id="num_feeds_total"></div>
            <div class="Ftable-cell" >Total feeds</br>available</div>
            <div class="Ftable-data" ><input maxlength="300" type="mapResults" value="<?php echo $num_feeds_available; ?>" name="num_feeds_available" id="num_feeds_available"></div>
            <!--hidden field-->
            <input type="text" value="<?php echo $noozefeed_id; ?>" name="noozefeed_id_hidden" id="noozefeed_id_hidden" hidden="true">
        </div>
        <script>
            /* Set the width of the side navigation to 250px */
            function openNav() {
                document.getElementById("mySidenav").style.width = "250px";
            }

            /* Set the width of the side navigation to 0 */
            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
            }
        </script>    
        <div id="shortcode_results"></div>
    </div>    
    </div>
</div>
<input type="text" value="<?php echo $social_media; ?>" name="social_media" id="social_media" hidden="true"> 
<div style="margin: 0 auto; text-align: center;">
    <div><br></div>
    <div class="shortcodeSnippetHead">Cut and paste this text into any page or post and start feeding your subscribers with leaks from your community, country or around the globe!</div>
    <div class="Rtable Rtable--2cols Rtable--collapse" style="width: 100% !important">
        <div class="Rtable-cell Rtable-cell--head" ><span style="font-size: 120%;" class="rTableHead">Shortcode </span></div>
        <div class="Rtable-data" style="padding-top: 12px;"><input style="text-align: center; font-weight: bold; color: red;" maxlength="200" type="text" id="shortcode_plug" placeholder="Set a Title, Location & Keywords" ></div>
    </div>
</div>                     