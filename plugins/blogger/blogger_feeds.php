<?php
if (!defined("IN_SCRIPT")){
    print("<script> window.location = 'index.php'; </script>");
    exit();
}

require_once($basepath."/plugins/blogger/includes/blogger.class.php");
require_once($basepath."/plugins/blogger/includes/simplepie.class.php");

$blogger_license_key = $settings->getSetting("blogger_license_key");
if (empty($blogger_license_key)){
    $blogger_license_key = '';
}

$blogger = new Blogger($blogger_license_key);

if (!$blogger->valid){
    print("<script> window.location = 'index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_config'; </script>");
    exit();
}

if (isset($delete)){
    $blogger->deleteFeed($delete);
}

if (isset($_POST['add_feed'])){
    $params = $_POST;
    $errors = $blogger->validateFeed($params, false);
    if (!count($errors)){
        $feed = new SimplePie();
        $feed->enable_order_by_date(false); 
        $feed->set_feed_url($_POST['url']);
        $feed->set_item_limit(1);
        $feed->set_stupidly_fast(true);
        $feed->enable_cache(false);    
        $feed->init();
        $feed->handle_content_type(); 
        
        if ($feed->error()){
            $errors[1] = "Can't parse feed";
        } else {
            $feed_id = $blogger->addFeed($params);
            $add_success = true;
        }
    }
}

if (isset($_POST['update_feed']) && isset($_POST['feed_id'])){
    $params = $_POST;
    $errors = $blogger->validateFeed($params, true);
    if (!count($errors)){
        $feed = new SimplePie();
        $feed->enable_order_by_date(false); 
        $feed->set_feed_url($_POST['url']);
        $feed->set_item_limit(1);
        $feed->set_stupidly_fast(true);
        $feed->enable_cache(false);    
        $feed->init();
        $feed->handle_content_type(); 
        
        if ($feed->error()){
            $errors[1] = "Can't parse feed";
        } else {
            $blogger->updateFeed($params, $params['feed_id']);
            $upd_success = true;
        }
    }
}

if (isset($feed_id) && $feed_id){
    $params = $blogger->getFeed($feed_id);
}

?>

<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                <a href="index.php?menu=plugins">Plugins</a>
            </li>
            <li>
                Blogger
            </li>
            <li>
                Manage feeds
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <?php if (isset($add_success) && $add_success){ ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            Feed successfully added
        </div>
        <?php } ?>
        
        <?php if (isset($upd_success) && $upd_success){ ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            Feed successfully updated
        </div>
        <?php } ?>
    
        <form action="index.php" method="post" class="form-horizontal well" onsubmit="$('#tags').val($('#array_tag_handler').tagHandler('getSerializedTags'));">
            <fieldset>
                <p class="f_legend">Add new feed</p>
                
                <div class="control-group<?php if (isset($errors[1])){ print(" error"); } ?>">
                    <label class="control-label">RSS Feed URL</label>
                    <div class="controls">
                        <input type="text" name="url" id="url" value="<?php if (isset($params['url'])) print($params['url']); ?>" class="span8" />
                        <?php if (isset($errors[1])){ ?>
                            <span class="help-inline"><?php print($errors[1]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Crawl frequency</label>
                    <div class="controls">
                        <input type="text" name="frequency" id="frequency" value="<?php if (isset($params['frequency'])){ print($params['frequency']); } else { print("60"); } ?>" class="span1" />
                        <?php if (isset($errors[2])){ ?>
                            <span class="help-inline"><?php print($errors[2]); ?></span>
                        <?php } else { ?>
                            <span class="help-inline">Minutes</span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[3])){ print(" error"); } ?>">
                    <label class="control-label">Language</label>
                    <div class="controls">
                        <select name="language">
                            <?php 
                                foreach($global_languages as $lang_code => $lang_name){
                                    print("<option value=\"{$lang_code}\"");
                                    if (isset($params['language']) && $params['language'] == $lang_code){
                                        print(" selected=\"selected\"");
                                    }
                                    print(">{$lang_name}</option>");
                                }
                            ?>
                        </select>
                        <?php if (isset($errors[3])){ ?>
                            <span class="help-inline"><?php print($errors[3]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="plugin" />
                        <input type="hidden" name="plugin" value="blogger" />
                        <input type="hidden" name="plugin_menu" value="blogger_feeds" />
                        <?php if (isset($feed_id)){ ?>
                            <input type="hidden" name="feed_id" value="<?php print($feed_id); ?>" />
                            <input type="submit" name="update_feed" value="Update feed" class="btn btn-primary" />
                        <?php } else { ?>
                            <input type="submit" name="add_feed" value="Add feed" class="btn btn-primary" />
                        <?php } ?>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Existing feeds</h3>
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="*">Feed URL</th>
                    <th width="10%">Frequency</th>
                    <th width="10%">Last crawl</th>
                    <th width="1">&nbsp;</th>
                    <th width="1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $feeds = $blogger->getFeeds();
                    if (!count($feeds)){
                ?>
                    
                    <tr>
                        <td colspan="5">No feeds added yet</td>
                    </tr>
                
                <?php 
                    } else {
                        foreach($feeds as $feed_id => $feed_data){
                            
                            print(" <tr>
                                        <td><a href=\"{$feed_data['url']}\" target=\"_blank\">{$feed_data['url']}</a></td>
                                        <td>{$feed_data['frequency']}</td>
                                        <td>{$feed_data['last_checked']}</td>
                                        <td><a href=\"index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_feeds&delete=$feed_id\" class=\"btn btn-danger\">Delete</a></td>
                                        <td><a href=\"index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_feeds&feed_id=$feed_id\" class=\"btn\">Edit</a></td>
                                    </tr>");
                            
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>