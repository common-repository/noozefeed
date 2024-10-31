jQuery(document).ready(function ($) {
  UpdateShortcodeResults($, function () {})  
  var social_media_array = social_media.value.split(',')
  facebook_check_box.checked = false
  twitter_check_box.checked = false
  article_check_box.checked = false
  for (var i = 0; i < social_media_array.length; i++) {
    if (social_media_array[i] === 'facebook') {
      facebook_check_box.checked = true
    } else if (social_media_array[i] === 'twitter') {
      twitter_check_box.checked = true
    } else if (social_media_array[i] === 'article') {
      article_check_box.checked = true
    }
  }
  jQuery.ajax({
        url: '//freegeoip.net/json/',
        type: 'POST',
        dataType: 'jsonp',
        success: function(location) {
            if (!$('#latitude').val()) {
              $('#latitude').val(location.latitude)
            }
            var lng = 0
            if (!$('#longitude').val()) {
              $('#longitude').val(location.longitude)
            }
        }
   })  
  if (save_tab_index.value) {
    var index = save_tab_index.value
  }  
  $('#get_location').load(function(){
      if ((radius) && (radius.value) && (zoom) && (zoom.value)) {
        document.getElementById('get_location').contentWindow.postMessage({ radius: radius.value, latitude: latitude.value, longitude: longitude.value, zoom: zoom.value }, '*')
      } else {
        document.getElementById('get_location').contentWindow.postMessage({ radius: 150, latitude: latitude.value, longitude: longitude.value, zoom: 9 }, '*')  
      }    
  })
  $('#search_feeds').css('font-size', '150%')
  $('#search_feeds').click(function () {
      $.LoadingOverlay('show')
      SearchLeakyFeeds($, function (err) {
        if (err) {
        } else {
          $('#tabs').tabs({ active: 3 })
        }
        $.LoadingOverlay('hide')
      })
  })
  $('#save_custom_settings').css('font-size', '150%')
  $('#save_custom_settings').click(function () {
      jQuery.ajax({
        method: 'POST',
        url: ajaxurl,
        data: { action: 'noozefeed_update_shortcode_colors',
          security: $('#noozefeed_nonce').val(),
          table_height: table_height.value,
          max_feeds: max_feeds.value,
          table_color: table_color.value,
          head_text_color: head_text_color.value,
          feed_text_color: feed_text_color.value,
          underline_color: underline_color.value,
          score_text_color: score_text_color.value,
          background_color: background_color.value,
          internal_radius_size: internal_radius_size.value,
          external_radius_size: external_radius_size.value }
      })
            .done(function (data) {
              UpdateShortcodeResults($, function () {
                $('#tabs').tabs({ active: 3 })
              })
            })
            .fail(function (data) {
            })
  })
  $('#tabs').tabs({ active: index })
  $('#tabs').click('tabsselect', function (event, ui) {
    var selected_tab = $('#tabs').tabs('option', 'active')
    jQuery.ajax({
      method: 'POST',
      url: ajaxurl,
      data: { action: 'noozefeed_save_tab_index',
        security: $('#noozefeed_nonce').val(),
        tab_index: selected_tab }
    })
        .done(function (data) {
        })
        .fail(function (data) {
        })
  })
  $('input:radio[name=view_type][value=list]').click(function () {
    UpdateShortcodeResults($, function () {})
  })
  $('input:radio[name=view_type][value=grid]').click(function () {
    UpdateShortcodeResults($, function () {})
  })
  $('input:radio[name=sort_by][value=date]').click(function () {
    UpdateShortcodeResults($, function () {})
  })
  $('input:radio[name=sort_by][value=relevance]').click(function () {
    UpdateShortcodeResults($, function () {})
  })
  $('input:radio[name=sort_by][value=location]').click(function () {
    UpdateShortcodeResults($, function () {})
  })
  $('input:radio[name=sort_by][value=likes]').click(function () {
    UpdateShortcodeResults($, function () {})
  })
  $('input:radio[name=sort_by][value=new]').click(function () {
    UpdateShortcodeResults($, function () {})
  })
  $('input:radio[name=sort_by][value=shares]').click(function () {
    UpdateShortcodeResults($, function () {})
  })
  $('[data-bar="1"]').livequery(function () {
    $(this).removeClass('oldBar')
    $(this).addClass('newBar')
  })
  $('[data-bar="0"]').livequery(function () {
    $(this).removeClass('newBar')
    $(this).addClass('oldBar')
  })
  $('.getMoreList').livequery(function () {
    $(this).show()
  })
  $('.getMoreGrid').livequery(function () {
    $(this).show()
  })
  $('.get_more_feeds').livequery(function () {
    $(this).on('click', function () {
      var title = $(this).attr('data-title')
      var message = $(this).attr('data-message')
      title = title.replace(/\'/g,'')
      title = title.replace(/\@/g,'')
      if (title) {
        GetMoreFeeds($, '"' + title + '"')
      }      
    })
  })
  $('#delete_feeds').click(function () {
    $.confirm({
      title: 'Reset Search Results',
      content: 'Do you wish clear the current feed list?',
      type: 'dark',
      boxWidth: '30%',
      useBootstrap: false,
      buttons: {
          yes: function () {
              jQuery.ajax({
                method: 'POST',
                url: ajaxurl,
                data: { action: 'noozefeed_delete_all_feeds',
                  security: $('#noozefeed_nonce').val(),
                  request_id: noozefeed_id.value,
                  delete_feeds: noozefeed_id.value }
              })
                .done(function (data) {
                  $('#keywords').val('')
                  $('#exclude_filter').val('')
                  $('#twitter_check_box').prop('checked', true)
                  $('#facebook_check_box').prop('checked', true)
                  $('#article_check_box').prop('checked', true)
                  $('#global_search').prop('checked', true)
                  $('#sort_by_new').prop('checked', true) 
                  $('#tabs').tabs({ active: 1 })
                  $('#facebook_count').val('0')
                  $('#twitter_count').val('0')
                  $('#article_count').val('0')
                  $('#num_feeds_total').val('0')
                  $('#num_feeds_available').val('0')
                  UpdateShortcodeResults($, function () {})
                })
                .fail(function (data) {
                })
          },
          no: function () {
                    // close
          }
        }
    })
  })
  $('#reset_search').click(function () {
    $.confirm({
      title: 'Reset Search Results',
      content: 'Do you wish revert back to the out-of-box experience?',
      type: 'dark',
      boxWidth: '30%',
      useBootstrap: false,
      buttons: {
        yes: function () {
          $.LoadingOverlay('show')
          jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: { action: 'noozefeed_delete_all_feeds',
              security: $('#noozefeed_nonce').val(),  
              request_id: noozefeed_id.value,
              delete_feeds: noozefeed_id.value }
          })
            .done(function (data) {
              $('#keywords').val('')
              $('#exclude_filter').val('')
              $('#feed_limit').val('100')
              $('#sort_by_date').prop('checked', true)
              $('#view_type_list').prop('checked', true)
              $('#twitter_check_box').prop('checked', false)
              $('#facebook_check_box').prop('checked', false)
              $('#article_check_box').prop('checked', true)
              $('#global_search').prop('checked', true)
              SearchLeakyFeeds($, function (err) {
                  $.LoadingOverlay('hide')
              })
            })
            .fail(function (data) {
              $.LoadingOverlay('hide')
            })
        },
        no: function () {
          $.LoadingOverlay('hide')
        }
      }
    })
  })
  $('#reset_display').click(function () {
    $.confirm({
      title: 'Reset Display Settings',
      content: 'Do you wish revert back to the default display settings?',
      type: 'dark',
      boxWidth: '30%',
      useBootstrap: false,
      buttons: {
        yes: function () {
          jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: { action: 'noozefeed_reset_display_settings',
                security: $('#noozefeed_nonce').val() }
          })
            .done(function (data) {
              $('#table_color').val('#ffffff')
              $('#table_color').wpColorPicker('color', '#ffffff')
              $('#underline_color').val('#e40079')
              $('#underline_color').wpColorPicker('color', '#e40079')
              $('#head_text_color').val('#e40079')
              $('#head_text_color').wpColorPicker('color', '#e40079')
              $('#feed_text_color').val('#606060')
              $('#feed_text_color').wpColorPicker('color', '#606060')
              $('#score_text_color').val('#0000ff')
              $('#score_text_color').wpColorPicker('color', '#0000ff')
              $('#background_color').val('#ffffff')
              $('#background_color').wpColorPicker('color', '#ffffff')
              $('#table_height').val('500')
              $('#max_feeds').val('100')
              $('#internal_radius_size').val('0')
              $('#external_radius_size').val('0')
              UpdateShortcodeResults($, function () {})
            })
            .fail(function (data) {
            })
        },
        no: function () {
        }
      }
    })
  })
  shortcode_plug.value = '[noozefeed id=\"' + noozefeed_id.value + '\"]';
  shortcode_plug.style = 'text-align: center; font-weight: bold; font-size: 150%; color: #808080;';
  
  (function(e){var t,o={className:"autosizejs",append:"",callback:!1,resizeDelay:10},i='<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; padding: 0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden; transition:none; -webkit-transition:none; -moz-transition:none;"/>',n=["fontFamily","fontSize","fontWeight","fontStyle","letterSpacing","textTransform","wordSpacing","textIndent"],s=e(i).data("autosize",!0)[0];s.style.lineHeight="99px","99px"===e(s).css("lineHeight")&&n.push("lineHeight"),s.style.lineHeight="",e.fn.autosize=function(i){return this.length?(i=e.extend({},o,i||{}),s.parentNode!==document.body&&e(document.body).append(s),this.each(function(){function o(){var t,o;"getComputedStyle"in window?(t=window.getComputedStyle(u,null),o=u.getBoundingClientRect().width,e.each(["paddingLeft","paddingRight","borderLeftWidth","borderRightWidth"],function(e,i){o-=parseInt(t[i],10)}),s.style.width=o+"px"):s.style.width=Math.max(p.width(),0)+"px"}function a(){var a={};if(t=u,s.className=i.className,d=parseInt(p.css("maxHeight"),10),e.each(n,function(e,t){a[t]=p.css(t)}),e(s).css(a),o(),window.chrome){var r=u.style.width;u.style.width="0px",u.offsetWidth,u.style.width=r}}function r(){var e,n;t!==u?a():o(),s.value=u.value+i.append,s.style.overflowY=u.style.overflowY,n=parseInt(u.style.height,10),s.scrollTop=0,s.scrollTop=9e4,e=s.scrollTop,d&&e>d?(u.style.overflowY="scroll",e=d):(u.style.overflowY="hidden",c>e&&(e=c)),e+=w,n!==e&&(u.style.height=e+"px",f&&i.callback.call(u,u))}function l(){clearTimeout(h),h=setTimeout(function(){var e=p.width();e!==g&&(g=e,r())},parseInt(i.resizeDelay,10))}var d,c,h,u=this,p=e(u),w=0,f=e.isFunction(i.callback),z={height:u.style.height,overflow:u.style.overflow,overflowY:u.style.overflowY,wordWrap:u.style.wordWrap,resize:u.style.resize},g=p.width();p.data("autosize")||(p.data("autosize",!0),("border-box"===p.css("box-sizing")||"border-box"===p.css("-moz-box-sizing")||"border-box"===p.css("-webkit-box-sizing"))&&(w=p.outerHeight()-p.height()),c=Math.max(parseInt(p.css("minHeight"),10)-w||0,p.height()),p.css({overflow:"hidden",overflowY:"hidden",wordWrap:"break-word",resize:"none"===p.css("resize")||"vertical"===p.css("resize")?"none":"horizontal"}),"onpropertychange"in u?"oninput"in u?p.on("input.autosize keyup.autosize",r):p.on("propertychange.autosize",function(){"value"===event.propertyName&&r()}):p.on("input.autosize",r),i.resizeDelay!==!1&&e(window).on("resize.autosize",l),p.on("autosize.resize",r),p.on("autosize.resizeIncludeStyle",function(){t=null,r()}),p.on("autosize.destroy",function(){t=null,clearTimeout(h),e(window).off("resize",l),p.off("autosize").off(".autosize").css(z).removeData("autosize")}),r())})):this}})(window.jQuery||window.$);

  var __slice=[].slice;(function(e,t){var n;n=function(){function t(t,n){var r,i,s,o=this;this.options=e.extend({},this.defaults,n);this.$el=t;s=this.defaults;for(r in s){i=s[r];if(this.$el.data(r)!=null){this.options[r]=this.$el.data(r)}}this.createStars();this.syncRating();this.$el.on("mouseover.starrr","span",function(e){return o.syncRating(o.$el.find("span").index(e.currentTarget)+1)});this.$el.on("mouseout.starrr",function(){return o.syncRating()});this.$el.on("click.starrr","span",function(e){return o.setRating(o.$el.find("span").index(e.currentTarget)+1)});this.$el.on("starrr:change",this.options.change)}t.prototype.defaults={rating:void 0,numStars:5,change:function(e,t){}};t.prototype.createStars=function(){var e,t,n;n=[];for(e=1,t=this.options.numStars;1<=t?e<=t:e>=t;1<=t?e++:e--){n.push(this.$el.append("<span class='glyphicon .glyphicon-star-empty'></span>"))}return n};t.prototype.setRating=function(e){if(this.options.rating===e){e=void 0}this.options.rating=e;this.syncRating();return this.$el.trigger("starrr:change",e)};t.prototype.syncRating=function(e){var t,n,r,i;e||(e=this.options.rating);if(e){for(t=n=0,i=e-1;0<=i?n<=i:n>=i;t=0<=i?++n:--n){this.$el.find("span").eq(t).removeClass("glyphicon-star-empty").addClass("glyphicon-star")}}if(e&&e<5){for(t=r=e;e<=4?r<=4:r>=4;t=e<=4?++r:--r){this.$el.find("span").eq(t).removeClass("glyphicon-star").addClass("glyphicon-star-empty")}}if(!e){return this.$el.find("span").removeClass("glyphicon-star").addClass("glyphicon-star-empty")}};return t}();return e.fn.extend({starrr:function(){var t,r;r=arguments[0],t=2<=arguments.length?__slice.call(arguments,1):[];return this.each(function(){var i;i=e(this).data("star-rating");if(!i){e(this).data("star-rating",i=new n(e(this),r))}if(typeof r==="string"){return i[r].apply(i,t)}})}})})(window.jQuery,window);$(function(){return $(".starrr").starrr()})

  $(function(){
      $('#new-feedback').autosize({append: "\n"})
      var feedbackBox = $('#post-feedback-box')
      var newFeedback = $('#new-feedback')
      var openReviewBtn = $('#open-feedback-box')
      var closeReviewBtn = $('#close-feedback-box')
      var ratingsField = $('#ratings-hidden')
      openReviewBtn.click(function(e)
      {
        feedbackBox.slideDown(400, function()
          {
            $('#new-feedback').trigger('autosize.resize')
            newFeedback.focus()
          })
        openReviewBtn.fadeOut(100)
        closeReviewBtn.show()
      })
      closeReviewBtn.click(function(e)
      {
        e.preventDefault()
        feedbackBox.slideUp(300, function()
          {
            newFeedback.focus()
            openReviewBtn.fadeIn(200)
          })
        closeReviewBtn.hide()
      })
      $('.starrr').on('starrr:change', function(e, value){
        ratingsField.val(value)
      })
    })
  
  $('#submit_feedback').click(function () {
    var feedbackBox = $('#post-feedback-box')
    var newFeedback = $('#new-feedback')
    var openReviewBtn = $('#open-feedback-box')
    var closeReviewBtn = $('#close-feedback-box')
    var feedback = $('#feedback').val()
    var rating = $('#ratings-hidden').val()
    if ((rating) || (feedback)) {
        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: { action: 'noozefeed_user_feedback',
                    security: $('#noozefeed_nonce').val(),
                    rating: rating,
                    feedback: feedback }
          })
          .done(function (data) {
            if (data.result) {
              if (data.result === 'success') {
                $.alert({
                  title: 'Feedback Provided',
                  content: 'Thanks for your feedback! We hope you continue to enjoy the plugin!',
                  type: 'dark',
                  boxWidth: '30%',
                  useBootstrap: false
                })  
                feedbackBox.slideUp(300, function()
                {
                  newFeedback.focus()
                  openReviewBtn.fadeIn(200)
                })
                closeReviewBtn.hide()  
                $('#feedback').val('')
                $('#ratings-hidden').val('0')
              }
            }       
          })
          .fail(function (data) {
          })    
    }
  })
  
})

function GetMoreFeeds ($, search) {
  $.LoadingOverlay('show')
  $('#keywords').val(search)
  $('#exclude_filter').val('')
  $('#twitter_check_box').prop('checked', true)
  $('#facebook_check_box').prop('checked', true)
  $('#article_check_box').prop('checked', true)
  $('#global_search').prop('checked', true)
  $('#sort_by_relevance').prop('checked', true)
  jQuery.ajax({
    method: 'POST',
    url: ajaxurl,
    data: { action: 'noozefeed_mark_feeds_as_old',
        security: $('#noozefeed_nonce').val() }
  })
    .done(function (data) {
      $.confirm({
        title: 'New Feeds',
        content: 'Do you wish to add feeds or create a new search list?',
        type: 'dark',
        boxWidth: '30%',
        useBootstrap: false,
        buttons: {
          create: function () {
            jQuery.ajax({
              method: 'POST',
              url: ajaxurl,
              data: { action: 'noozefeed_delete_all_feeds',
                security: $('#noozefeed_nonce').val(),
                request_id: noozefeed_id.value,
                delete_feeds: noozefeed_id.value }
            })
                .done(function (data) {
                  $('#facebook_count').val('0')
                  $('#twitter_count').val('0')
                  $('#article_count').val('0')
                  $('#num_feeds_total').val('0')
                  $('#num_feeds_available').val('0')
                  SearchLeakyFeeds($, function (err) {
                    if (err) {
                    } else {
                    }
                    UpdateShortcodeResults($, function () {
                      $.LoadingOverlay('hide')
                    })
                  })
                })
                .fail(function (data) {
                  $.LoadingOverlay('hide')
                })
          },
          add: function () {
            jQuery.ajax({
              method: 'POST',
              url: ajaxurl,
              data: { action: 'noozefeed_reset_feed_scores',
                security: $('#noozefeed_nonce').val() }
            })
                .done(function (data) {
                  SearchLeakyFeeds($, function (err) {
                    if (err) {
                    } else {
                    }
                    UpdateShortcodeResults($, function () {
                      $.LoadingOverlay('hide')
                    })
                  })
                })
                .fail(function (data) {
                  $.LoadingOverlay('hide')
                })             
          },
          cancel: function () {
            $.LoadingOverlay('hide')
          }
        }
      })
    })
    .fail(function (data) {
    })
}

function SearchLeakyFeeds ($, callback) {
  if (sort_by_date.checked) {
    var sort_by = 'date'
  } else if (sort_by_relevance.checked) {
    var sort_by = 'relevance'
  } else if (sort_by_location.checked) {
    var sort_by = 'distance'
  } else if (sort_by_likes.checked) {
    var sort_by = 'likes'
  } else if (sort_by_shares.checked) {
    var sort_by = 'shares'
  } else if (sort_by_new.checked) {
    var sort_by = 'new'
  }
  if (view_type_list.checked) {
    var view_type = 'list'
  } else if (view_type_grid.checked) {
    var view_type = 'grid'
  }
  if (disable_radius_restriction.checked) {
    var restrict_to_radius = 0
    if (!places) {
      $.alert({
        title: 'Map Selection',
        content: 'Please select an area on the map to continue',
        type: 'dark',
        boxWidth: '30%',
        useBootstrap: false
      })
    }
  } else if (enable_radius_restriction.checked) {
    var restrict_to_radius = 1
    if (!places) {
      $.alert({
        title: 'Map Selection',
        content: 'Please select an area on the map to continue',
        type: 'dark',
        boxWidth: '30%',
        useBootstrap: false
      })
    }
  } else if (global_search.checked) {
    var restrict_to_radius = 2
  }
  var social_media = null
  if (facebook_check_box.checked) {
    social_media = 'facebook'
  }
  if (twitter_check_box.checked) {
    if (social_media) {
      social_media = social_media + ',twitter'
    } else {
      social_media = 'twitter'
    }
  }
  if (article_check_box.checked) {
    if (social_media) {
      social_media = social_media + ',article'
    } else {
      social_media = 'article'
    }
  }
  var lat = 0
  if ($('#latitude').val()) {
    lat = $('#latitude').val()
  }
  var lng = 0
  if ($('#longitude').val()) {
    lng = $('#longitude').val()
  }
  var zoom_level = 9
  if ($('#zoom').val()) {
    zoom_level = $('#zoom').val()
  }
  var radius_in_metres = 1000000
  if ($('#radius').val()) {
    radius_in_metres = $('#radius').val()
  }
  jQuery.ajax({
    method: 'POST',
    url: ajaxurl,
    dataType: 'json',
    data: { action: 'save_noozefeed_settings',
      security: $('#noozefeed_nonce').val(),
      request_id: noozefeed_id.value,
      places: places.value,
      keywords: keywords.value,
      exclude_filter: exclude_filter.value,
      social_media: social_media,
      sort_by: sort_by,
      view_type: view_type,
      radius: radius_in_metres,
      restrict_to_radius: restrict_to_radius,
      latitude: lat,
      longitude: lng,
      table_height: table_height.value,
      max_feeds: max_feeds.value,
      table_color: table_color.value,
      head_text_color: head_text_color.value,
      feed_text_color: feed_text_color.value,
      underline_color: underline_color.value,
      score_text_color: score_text_color.value,
      background_color: background_color.value,
      zoom: zoom_level,
      internal_radius_size: internal_radius_size.value,
      external_radius_size: external_radius_size.value }
  })
    .done(function (data) {
      if (data) {
        if (data.result === 'success') {
          if ((data.hasOwnProperty('subscription_level')) && (data.subscription_level)) {
            subscription_level.value = data.subscription_level
          }
          if ((data.hasOwnProperty('request_rate')) && (data.request_rate)) {
            request_rate.value = data.request_rate
          }
          if ((data.hasOwnProperty('feed_limit')) && (data.feed_limit)) {
            feed_limit.value = data.feed_limit
          }
          if ((data.hasOwnProperty('available_feeds')) && (data.available_feeds)) {
            if (data.available_feeds === 0) {
              $.alert({
                title: 'No Feeds Found',
                content: 'No feeds available based on the current search! Please check and try again.',
                type: 'dark',
                boxWidth: '30%',
                useBootstrap: false
              })
            } else {
              num_feeds_available.value = data.available_feeds
            }  
          } else if (data.remaining_searches < 0) {
              $.alert({
                title: 'Maximum Searches Reached',
                content: 'You\'ve reached your maximum search requests within your allotted time period. Please wait approximately ' + Math.floor(data.remaining_seconds/3600) + ' hours to retrieve new results!',
                type: 'dark',
                boxWidth: '30%',
                useBootstrap: false
              })  
          } else {
            $.alert({
              title: 'No Feeds Found',
              content: 'No feeds available based on the current search! Please check and try again.',
              type: 'dark',
              boxWidth: '30%',
              useBootstrap: false
            })
          }
          if ((data.hasOwnProperty('returned_feeds')) && (data.returned_feeds)) {
            num_feeds_total.value = data.returned_feeds
            facebook_count.value = data.facebook_count
            twitter_count.value = data.twitter_count
            article_count.value = data.article_count
          }
          if (noozefeed_id.value) {
            if (view_type_list.checked) {
              var view_type = 'list'
            } else if (view_type_grid.checked) {
              var view_type = 'grid'
            }
            shortcode_plug.value = '[noozefeed id=\"' + noozefeed_id.value + '\"]'
            shortcode_plug.style = 'text-align: center; font-weight: bold; font-size: 150%; color: #808080;'
          }
        } else if (data.result === 'maximum request exceeded') {
          $.alert({
            title: 'Over Limit',
            content: 'Maximum request limit reached, time remaining until next available request in seconds: ' + data.remaining_wait_time_seconds,
            type: 'dark',
            boxWidth: '30%',
            useBootstrap: false
          })
        }
      }
      UpdateShortcodeResults($, function (err) {
        if (err) {
        } else {
        }
        callback(null)
      })
    })
    .fail(function (data) {
      callback('error')
    })
}

function UpdateShortcodeResults ($, callback) {
  if (view_type_list.checked) {
    var view_type = 'list'
  } else {
    var view_type = 'grid'
  }
  if (sort_by_date.checked) {
    var sort_by = 'date'
  } else if (sort_by_relevance.checked) {
    var sort_by = 'relevance'
  } else if (sort_by_location.checked) {
    var sort_by = 'distance'
  } else if (sort_by_likes.checked) {
    var sort_by = 'likes'
  } else if (sort_by_shares.checked) {
    var sort_by = 'shares'
  } else if (sort_by_new.checked) {
    var sort_by = 'new'
  }
  var request_id = noozefeed_id.value
  jQuery.ajax({
    method: 'POST',
    url: ajaxurl,
    data: { action: 'noozefeed_get_shortcode_results',
      security: $('#noozefeed_nonce').val(),
      request_id: request_id,
      view_type: view_type,
      max_feeds: max_feeds.value,
      sort_by: sort_by,
      table_height: table_height.value }
  })
    .done(function (data) {
      $('#shortcode_results').html(data)
      callback(null)
    })
    .fail(function (data) {
      callback('error')
    })
}

window.addEventListener('message', function (e) {
  radius.value = e.data.radius
  latitude.value = e.data.latitude
  longitude.value = e.data.longitude
  zoom.value = e.data.zoom
  places.value = e.data.geocities
  if (e.data.zoom >= 12) {
    population.value = 1000
  } else if (e.data.zoom > 10) {
    population.value = 5000
  } else {
    population.value = 15000
  }
}, false)
