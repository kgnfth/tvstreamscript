<?php
@set_time_limit(0);
require_once("../../vars.php");
require_once("./includes/simplepie.class.php");
require_once("./includes/blogger.class.php");
require_once("../../includes/curl.php");
require_once("../../includes/settings.class.php");
/*
$feed_url = "http://ricokerosine.blogspot.com/feeds/posts/default";
$feed_url = "http://feeds.feedburner.com/sorozataddikt";
*/
/*
$feed_data = array();
$feed_data['language'] = "hu";
$feed_data['url'] = "http://feeds.feedburner.com/sorozataddikt";
*/
$blogger = new Blogger();
$curl = new Curl();
$max_items = 10;


$feeds = $blogger->getFeeds();
if (!count($feeds)){
    print("no feeds\n"); exit();
}

foreach($feeds as $feed_id => $feed_data){
    
    $now = date("Y-m-d H:i:s");
    if ($now < date("Y-m-d H:i:s", strtotime("+{$feed_data['frequency']} minutes", strtotime($feed_data['last_checked'])))){
        print("no need to check just yet\t{$feed_data['url']}\n");
        continue;
    }
    
    $feed = new SimplePie();
    $feed->enable_order_by_date(false); 
    $feed->set_feed_url($feed_data['url']);
    $feed->set_item_limit($max_items);
    $feed->set_stupidly_fast(true);
    $feed->enable_cache(false);    
    $feed->init();
    $feed->handle_content_type(); 
    
    if ($feed->error()){
        print($feed->error());
    } else {
        $items = $feed->get_items();
        foreach($items as $key => $item){
            $title = $item->get_title();
            $content = $item->get_content();
            $permalink = $item->get_permalink();
            $categories = $item->get_categories();
            
            $check = $blogger->getPosts(1,1,array("original_url" => $permalink));
            if (!$check || !count($check)){
                
                $post_data = array();
                $post_data['thumbnail'] = $blogger->getThumbnail($content, $curl);
                $post_data['title'] = $title;
                $post_data['content'] = $content;
                $post_data['language'] = $feed_data['language'];
                $post_data['original_url'] = $permalink;
                
                $clean_text = strip_tags($post_data['content']);
                
                if (strlen($clean_text) >= 300){
	                if (count($categories)){
	                    $post_data['tags'] = array();
	                    foreach($categories as $key => $category){
	                        $post_data['tags'][] = $category->term;
	                    }
	                    $post_data['tags'] = implode(",", $post_data['tags']);
	                }           
	                
	                $blogger->addPost($post_data);
	                print("inserted\t".$title."\t".$permalink."\n");  
                }
            }
        }
    }
    
    $blogger->setFeedDate($feed_id);

}


?>