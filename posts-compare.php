<?php
/*
Plugin Name: Posts Compare
Version: 1.0
Plugin URI: http://www.zhenskayalogika.ru/
Description: This plugin allows you to check your posts for getting copied.
Author: Serafim Panov
Author URI: http://www.spnova.org/
*/

function postscompare_get_options()
{
  $postscompare_options = get_option('postscompare_options');

  if (!isset($postscompare_options["settings_sentance_from_post"])) {
    $postscompare_options["settings_sentance_from_post"] = 3;
  }
  
  if (empty($postscompare_options["settings_words_in_result"])) {
    $postscompare_options["settings_words_in_result"] = 8;
  }
  
  if (empty($postscompare_options["settings_post_at_once"])) {
    $postscompare_options["settings_post_at_once"] = 1;
  }
  
  if (empty($postscompare_options["settings_check_cache_time"])) {
    $postscompare_options["settings_check_cache_time"] = 48;
  }
  
  if (!isset($postscompare_options["settings_searchengine_yandex"])) {
    $postscompare_options["settings_searchengine_yandex"] = false;
  }
  
  if (!isset($postscompare_options["settings_post_status_pending"])) {
    $postscompare_options["settings_post_status_pending"] = false;
  }
  
  add_option('postscompare_options', $postscompare_options);
  return $postscompare_options;
}

function postscompare_add_page()
{
  add_options_page('Posts Compare', 'Posts Compare', 6, __FILE__, 'postscompare_configuration_page');
}
add_action('admin_menu', 'postscompare_add_page');

$postscompare_db_version = "1.0";

function postscompare_install () {
   global $wpdb;
   global $postscompare_db_version;

   $table_name = $wpdb->prefix . "postscompare_search_result";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      postid bigint(11) DEFAULT '0' NOT NULL,
      searchtext tinytext NOT NULL,
      resulttext text NOT NULL,
      url VARCHAR(255) NOT NULL,
      cacheurl VARCHAR(255) NOT NULL,
      time bigint(11) DEFAULT '0' NOT NULL,
      searchengine VARCHAR(255) NOT NULL,
      UNIQUE KEY id (id)
    );";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      $welcome_name = "Mr. Wordpress";
      $welcome_text = "Congratulations, you just completed the installation!";

      add_option("postscompare_db_version", $postscompare_db_version);
   }
}

register_activation_hook(__FILE__,'postscompare_install');

function init_textdomain_posts_compare() {
    load_plugin_textdomain('posts-compare', PLUGINDIR . '/' . dirname(plugin_basename (__FILE__)) . '/lang');
}

add_action('init', 'init_textdomain_posts_compare');

function postscompare_configuration_page()
{
  $postscompare_options = postscompare_get_options();
  
  if (isset($_POST['submit'])) {

    if (empty($postscompare_options["settings_sentance_from_post"])) {
      $postscompare_options["settings_sentance_from_post"] = 3;
    }
  
    if (empty($postscompare_options["settings_words_in_result"])) {
      $postscompare_options["settings_words_in_result"] = 8;
    }
  
    if (empty($postscompare_options["settings_post_at_once"])) {
      $postscompare_options["settings_post_at_once"] = 1;
    }
    
    if (empty($postscompare_options["settings_check_cache_time"])) {
      $postscompare_options["settings_check_cache_time"] = 48;
    }
    
    isset($_POST['settings_searchengine_yandex']) ? $_POST['settings_searchengine_yandex'] = true : $_POST['settings_searchengine_yandex'] = false;
    isset($_POST['settings_post_status_pending']) ? $_POST['settings_post_status_pending'] = true : $_POST['settings_post_status_pending'] = false;
  
    $postscompare_options = array(
        "settings_sentance_from_post"     => $_POST['settings_sentance_from_post'],
        "settings_words_in_result"        => $_POST['settings_words_in_result'],
        "settings_sites_filter"           => $_POST['settings_sites_filter'],
        "settings_post_at_once"           => $_POST['settings_post_at_once'],
        "settings_check_cache_time"       => $_POST['settings_check_cache_time'],
        "settings_searchengine_yandex"    => $_POST['settings_searchengine_yandex'],
        "settings_post_status_pending"    => $_POST['settings_post_status_pending']
    );
    update_option('postscompare_options', $postscompare_options);
  }

?>

<div class="wrap">
<h2><?php _e('Posts Compare', 'posts-compare'); ?></h2>

<form method="post" action="">
<p><a href="http://www.zhenskayalogika.ru/files/donate.html" target="_blank"><img src="../wp-content/plugins/posts-compare/img/donate.png" border="0" width="44" height="46" /></a></p>

<p><strong><?php _e('Instructions', 'posts-compare'); ?>:</strong> <?php _e('Don\'t try to make any changes in the settings below if you are not sure, and the script will work perfectly.', 'posts-compare'); ?> <?php _e('After you set up cron job to compare-cron.php file, the plugin will start working checking your post one by one. Later you will see the result in the bottom of this page.', 'posts-compare'); ?><br />
<i><?php _e('Plugin was checked on', 'posts-compare'); ?> <a href="http://www.zhenskayalogika.ru/">http://www.zhenskayalogika.ru/</a> <?php _e('and helped us to find several plagiarists.', 'posts-compare'); ?></i><br />
<i>Support page with questions and answers: <a href="http://www.zhenskayalogika.ru/2009/11/13/plagin-posts-compare-%E2%80%93-na-strazhe-kontenta/">http://www.zhenskayalogika.ru/2009/11/13/plagin-posts-compare-%E2%80%93-na-strazhe-kontenta/</a>
(Please use google translator for other languages or wirte in English)</i><br />

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:250px;text-align:right;padding-right:6px;" for="settings_searchengine_google"><?php _e('Include searchengine google?', 'posts-compare'); ?></label>
<div style="float:left;"><input type="checkbox" id="settings_searchengine_google" name="settings_searchengine_google" checked='on' disabled="disabled"/>&nbsp;&nbsp;<label for="settings_searchengine_google"></label></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:250px;text-align:right;padding-right:6px;" for="settings_searchengine_yandex"><?php _e('Include searchengine yandex.ru?', 'posts-compare'); ?></label> 
<div style="float:left;"><input type="checkbox" id="settings_searchengine_yandex" name="settings_searchengine_yandex" <?php if ($postscompare_options["settings_searchengine_yandex"]) print "checked='on'"; ?>/> <?php echo postscompare_yandex_check_status(); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:250px;text-align:right;padding-right:6px;" for="settings_post_status_pending"><?php _e('Check only pending posts?', 'posts-compare'); ?></label> 
<div style="float:left;"><input type="checkbox" id="settings_post_status_pending" name="settings_post_status_pending" <?php if ($postscompare_options["settings_post_status_pending"]) print "checked='on'"; ?>/></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:250px;text-align:right;padding-right:6px;" for="settings_sentance_from_post"><?php _e('Amount of sentences from one post.', 'posts-compare'); ?></label>
<div style="float:left;"><input type="text" id="settings_sentance_from_post" name="settings_sentance_from_post" size="1" maxlength="2" <?php if ($postscompare_options["settings_sentance_from_post"]) print "value='" . $postscompare_options["settings_sentance_from_post"] . "'"; ?>/></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:250px;text-align:right;padding-right:6px;" for="settings_words_in_result"><?php _e('Amount of words one by one in result for compare.', 'posts-compare'); ?></label>
<div style="float:left;"><input type="text" id="settings_words_in_result" name="settings_words_in_result" size="1" maxlength="2" <?php if ($postscompare_options["settings_words_in_result"]) print "value='" . $postscompare_options["settings_words_in_result"] . "'"; ?>/></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:250px;text-align:right;padding-right:6px;" for="settings_post_at_once"><?php _e('Check posts at once for one time.', 'posts-compare'); ?></label>
<div style="float:left;"><input type="text" id="settings_post_at_once" name="settings_post_at_once" size="1" maxlength="2" <?php if ($postscompare_options["settings_post_at_once"]) print "value='" . $postscompare_options["settings_post_at_once"] . "'"; ?>/></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:250px;text-align:right;padding-right:6px;" for="settings_check_cache_time"><?php _e('Check posts again every (hours)', 'posts-compare'); ?></label>
<div style="float:left;"><input type="text" id="settings_check_cache_time" name="settings_check_cache_time" size="1" maxlength="2" <?php if ($postscompare_options["settings_check_cache_time"]) print "value='" . $postscompare_options["settings_check_cache_time"] . "'"; ?>/></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:250px;text-align:right;padding-right:6px;padding-top:7px;" for="settings_sites_filter"><?php _e('Filter sites (one site in one line) which should not be displayed.', 'posts-compare'); ?><br/></label>
<div style="float:left;"><textarea id="settings_sites_filter" name="settings_sites_filter" rows="4" cols="50" style="float:left;"><?php if ($postscompare_options["settings_sites_filter"]) print stripslashes(htmlspecialchars($postscompare_options["settings_sites_filter"])); ?></textarea>
</div>

</div>

<div style="clear: both;padding-top:10px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="Update Options &raquo;" /></p>
</div>
</form>
</div>

<?php
    postscompare_print_result();
}

function postscompare_hourly() {
    global $wpdb;
    global $postscompare_db_version;
    
    $table_name = $wpdb->prefix . "postscompare_search_result";
    
    $postscompare_options = postscompare_get_options();

    if ($postscompare_options['settings_post_status_pending']) {
      $pending = "pending";
    }
    else
    {
      $pending = null;
    }

	$args = array(
		'numberposts' => -1,
		'post_status' => $pending
		); 
	
    $posts = get_posts($args);

    $k = 0;
    foreach ($posts as $post) {
        $taketime = time() - 3600 * $postscompare_options["settings_check_cache_time"];
        
        $results = 0;
        
        $select = "SELECT * FROM {$table_name} WHERE postid = '{$post->ID}' and time > {$taketime}";
        $results = $wpdb->query( $select );
        if ($results == 0 && $k < $postscompare_options["settings_post_at_once"]) {  // Only "x" records for one time
            $wpdb->query("DELETE FROM {$table_name} WHERE postid = '{$post->ID}'");
            $k++;
            $posttitles[$post->ID] = $post->post_title;
            $c = 0;
            $sentanses = explode(".", strip_tags($post->post_content));
            foreach ($sentanses as $sentanse) {
                if (str_my_word_count($sentanse) >= $postscompare_options["settings_words_in_result"] && $c < $postscompare_options["settings_sentance_from_post"] ) {
                    if ($resgoogle = postscompare_google_check($sentanse)) {
                        foreach ($resgoogle as $resnumber => $resdata) {
                            if (postscompare_checkcontent($resdata)) {
                                $unicalgoogle[$post->ID][$c][$resnumber] = $resdata;
                            }
                        }
                    }
                    if ($postscompare_options['settings_searchengine_yandex']) {
                        if ($resyandex = postscompare_yandex_check($sentanse)) {
                            foreach ($resyandex as $resnumber => $resdata) {
                                if (postscompare_checkcontent($resdata)) {
                                    $unicalyandex[$post->ID][$c][$resnumber] = $resdata;
                                }
                            }
                        }
                    }
                    $c++;
                }
            }
        }
    }
    
    if (is_array($unicalgoogle) && is_array($unicalyandex)) {
        foreach ($posttitles as $postid => $posttitle) {
            $searchengresult[$postid] = array_merge($unicalgoogle[$postid], $unicalyandex[$postid]);
        }
    } else if (is_array($unicalgoogle)) {
        $searchengresult = $unicalgoogle;
    } else if (is_array($unicalyandex)) {
        $searchengresult = $unicalyandex;
    }
    
    if (is_array($searchengresult)) {
        foreach ($searchengresult as $postid => $result) {
            foreach ($result as $listofsites) {
                foreach ($listofsites as $site) {
                  $select = "SELECT * FROM {$table_name} WHERE postid = '{$postid}' and url = '{$site['urls']}'";
                  $results = $wpdb->query( $select );
                  if ($results == 0) {
                    $insert = "INSERT INTO {$table_name}
                            (postid, searchtext, resulttext, url, cacheurl, time, searchengine) " .
                            "VALUES ('{$postid}', '" . $wpdb->escape($site['realtext']) . "', '" . $wpdb->escape($site['contents']) . "', '" . $wpdb->escape($site['urls']) . "', '" . $wpdb->escape($site['cacheUrl']) . "', '" . time() . "', '{$site['searchengine']}')";
                
                    $results = $wpdb->query( $insert );
                  }
                }
            }
        }
    }
    
    if (is_array($posttitles)) {
        foreach ($posttitles as $postid => $posttitle) {
          if (!is_array($searchengresult[$postid])) {
            $insert = "INSERT INTO {$table_name}
                    (postid, searchtext, resulttext, url, cacheurl, time, searchengine) " .
                    "VALUES ('{$postid}', '', '', '" . get_option('siteurl') . "', '', '" . time() . "', 'nofound')";
            
            $results = $wpdb->query( $insert );
          }
        }
    }
}

function postscompare_google_check($query) {
    $url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=".urlencode($query);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, trackback_url(false));
    $body = curl_exec($ch);
    curl_close($ch);
    if (!function_exists('Services_JSON')) require_once(dirname(__FILE__) . '/JSON.php');
    $json = new Services_JSON();
    $json = $json->decode($body);
    
    if (is_array($json->responseData->results)) {
        foreach ($json->responseData->results as $key => $resultjson) {
            $result_google[$key]['urls']         = $resultjson->url;
            $result_google[$key]['contents']     = $resultjson->content;
            $result_google[$key]['cacheUrl']     = $resultjson->cacheUrl;
            $result_google[$key]['realtext']     = $query;
            $result_google[$key]['searchengine'] = "google";
        }
    }
    
    print_r ($result_google);
    
    return $result_google;
}

function postscompare_yandex_check($query) {
    $url = "http://xmlsearch.yandex.ru/xmlsearch?query=".str_replace (" ", "%20", $query)."&groupby=attr%3Dd.mode%3Ddeep.groups-on-page%3D10.docs-in-group%3D1";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, trackback_url(false));
    $xml = curl_exec($ch);
    curl_close($ch);

    $groups = explode("<group>", $xml);
    foreach ($groups as $group) {
        if (preg_match("!<url>(.*?)</url>!si",$group,$url)) $url=$url[1];
        if (preg_match("!<domain>(.*?)</domain>!si",$group,$domain)) $domain=$domain[1];
        if (preg_match("!<title>(.*?)</title>!si",$group,$title)) $title=$title[1];
        if (preg_match("!<passages>(.*?)</passages>!si",$group,$passages)) $passages=$passages[1];
        
        $passages = preg_replace('!<hlword priority="strict">(.*?)</hlword>!si', "<b>\\1</b>", $passages);
        $passages = preg_replace('!(</b>(\S\s|\s)<b>)!i'," ",$passages);
        
        if (!empty($url) && !empty($title)) $yaresult[] = array($url, $domain, $title, trim($passages));
    }

    if (is_array($yaresult)) {
        foreach ($yaresult as $key => $resultjson) {
            $result_yandex[$key]['urls']         = $resultjson[0];
            $result_yandex[$key]['contents']     = $resultjson[3];
            $result_yandex[$key]['cacheUrl']     = "";
            $result_yandex[$key]['realtext']     = $query;
            $result_yandex[$key]['searchengine'] = "yandex";
        }
    }
    else
    {
        return array();
    }
    
    return $result_yandex;
}


function postscompare_yandex_check_status() {
    $url = "http://xmlsearch.yandex.ru/xmlsearch";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, trackback_url(false));
    $xml = curl_exec($ch);
    curl_close($ch);

    if (preg_match("!<error>(.*?)</error>!si",$xml,$error)) $error=$error[1];
    if (preg_match("!<request>(.*?)</request>!si",$xml,$request)) $request=$request[1];
    
    if (!empty($request)) {
        return "Yandex ready";
    }
    else
    {
        return "Yandex status: " . $error . " <a href=\"http://xml.yandex.ru/ip.xml\" target=\"_blank\">Set/Change your server IP here, need for search by yandex.</a>";
    }
}


function str_my_word_count($sentanse) {
    $newsentense = explode(" ", $sentanse);
    $c = 0;
    foreach ($newsentense as $newsentense_) {
        if (strlen($newsentense_) > 1) {
            $c++;
        }
    }
    
    return $c;
}


function postscompare_checkcontent($res) {

    $postscompare_options = postscompare_get_options();

    $resarray = explode("<b>", $res['contents']);
    foreach ($resarray as $resarray_) {
        if (strstr($resarray_, "</b>")) {
            $text = explode("</b>", $resarray_);
            $text = $text[0];
            if (strlen($text) > 5) {
                if (str_my_word_count($text) >= $postscompare_options["settings_words_in_result"]) {
                    return true;
                }
            }
        }
    }
    
    return false;
}


function postscompare_print_result() {
    static $count = 0;
    global $wpdb;
    global $postscompare_db_version;
    
    $postscompare_options = postscompare_get_options();
    
    $table_name = $wpdb->prefix . "postscompare_search_result";
        
    $count++; 
    
    $select = "SELECT * FROM {$table_name}";
    $results = $wpdb->get_results( $select );
    
    //$nositesneed = array(get_option('siteurl'));
    $nositesneed = array();
    $settings_sites_filters = explode("\r\n", $postscompare_options['settings_sites_filter']);
    foreach ($settings_sites_filters as $settings_sites_filter) {
        $nositesneed[] = $settings_sites_filter;
    }

    //echo "Size: ".count($results)."<br />";
    
    foreach ($results as $site) {
        $nomark = false;
        foreach ($nositesneed as $nositesneed_) {
            if (!empty($nositesneed_)) {
                preg_match("/^(http:\/\/)?([^\/]+)/i", $site->url, $matches);
                $nositesneed_ = str_replace("http://", "", $nositesneed_);
                $nositesneed_ = str_replace("www.", "", $nositesneed_);
                if (strstr($matches[2], $nositesneed_)) {
                    $nomark = true;
                }
            }
        }
        if ($nomark == false) $printresults[$site->postid][$site->url][] = array($site->cacheurl, $site->searchtext, $site->resulttext, $site->searchengine);
    }

    if (is_array($printresults)) {
        foreach ($printresults as $postid => $printresult) {
            $p = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ID = %s", $postid));
            $post = & get_post($p);
?>
<div id="site-<?php echo $count; ?>" class="postbox">
    <h2><span><a href="<?php echo post_permalink($postid); ?>" target="_blank"><?php echo $post->post_title; ?></a></span></h2>
    <div class="inside"><ul>
<?php
    foreach ($printresult as $url => $restext) {
        foreach ($restext as $site) {
        ?>
            <li><?php 
            echo '<img src="../wp-content/plugins/posts-compare/img/';
            switch ($site[3]) { 
               case "google": 
                   echo 'google-ico.gif" alt="Google.com"'; 
                   break; 
               case "yandex": 
                   echo 'yandex-ico.gif" alt="Yandex.ru"'; 
                   break; 
            } 
            echo ' width="16" height="16" />';
            
            ?> <a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a> 
            <?php
            if (!empty($site[0])) {
                echo '(<a href="'.$site[0].'" target="_blank">CACHE</a>)';
            }
            ?><br /><b>Search:</b> <i><?php echo $site[1]; ?></i><br /><b>Result: </b><?php echo $site[2]; ?><br /><hr /></li>
        <?php
        }
    }
?></ul>
    </div>
</div>
<?php
        }
    }
    else {
        echo "<br /><h3>";
        _e('No posts matching found.', 'posts-compare');
        echo "</h3><br />";
    }
}

?>
