<?php
if (!defined("IN_SCRIPT")){
    print("<script> window.location = 'index.php'; </script>");
    exit();
}

require_once($basepath."/plugins/blogger/includes/blogger.class.php");

$blogger_license_key = $settings->getSetting("blogger_license_key");
if (empty($blogger_license_key)){
    $blogger_license_key = '';
}

$blogger = new Blogger($blogger_license_key);

if (!$blogger->valid){
    print("<script> window.location = 'index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_config'; </script>");
    exit();
}

if (isset($_POST['add_post'])){
    $params = $_POST;
    $errors = $blogger->validatePost($params);
    if (!count($errors)){
        $post_id = $blogger->addPost($params);
        $add_success = true;
    }
}

if (isset($_POST['update_post']) && isset($_POST['post_id'])){
    $params = $_POST;
    $errors = $blogger->validatePost($params);
    if (!count($errors)){
        $blogger->updatePost($_POST['post_id'],$params);
        $upd_success = true;
    }
}

if (isset($_REQUEST['post_id'])){
    $post_id = $_REQUEST['post_id'];
    if (!isset($_POST['update_post'])){
        $params = $blogger->getPost($post_id);
        if (!$params){
            unset($post_id);
        } else {
            if (count($params['tags'])){
                $tags = array();
                foreach($params['tags'] as $key => $val){
                    $tags[] = $val['tag'];
                }
                $params['tags'] = implode(",",$tags);
            }
        }
    }
}

?>
<link rel="stylesheet" href="<?php print($baseurl); ?>/control/lib/tag_handler/css/jquery.taghandler.css" />
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
                Write post
            </li>
        </ul>
    </div>
</nav>
<div class="row-fluid">
    <div class="span12">
        <?php if (isset($add_success) && $add_success){ ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            Your blog post was published successfully. <a href="index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_manage">Click here</a> to manage your posts
        </div>
        <?php } ?>
        
        <?php if (isset($upd_success) && $upd_success){ ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            Your blog post was updated successfully. <a href="index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_manage">Click here</a> to manage your posts
        </div>
        <?php } ?>
    
        <form action="index.php" method="post" class="form-horizontal well" onsubmit="$('#tags').val($('#array_tag_handler').tagHandler('getSerializedTags'));">
            <fieldset>
                <p class="f_legend">New blog post</p>
                
                <!--  Thumbnail -->
                <div class="control-group<?php if (isset($errors[5])){ print(" error"); } ?>">
                    <label class="control-label">Post thumbnail</label>
                    <div class="controls">
                        <div class="span2">
                            <input type="hidden" name="thumbnail" id="thumbnail_hidden" value="<?php if (isset($params['thumbnail']) && $params['thumbnail']) print($params['thumbnail']); ?>" />
                            <div class="fileupload-new thumbnail" style="width:170px;">
                                <img src="<?php if (isset($params['thumbnail']) && $params['thumbnail']){ print($baseurl."/thumbs/".$params['thumbnail']); } else { print("http://www.placehold.it/170x170/EFEFEF/AAAAAA&amp;text=no+image"); } ?>" alt="" id="post_thumbnail" style="max-width: 170px; max-height: 170px;" />
                            </div>
                            
                            <div>
                                <input type="button" class="btn" value="Upload thumbnail" id="uploader" style="width:180px;"/>
                            </div>                      
                        </div>  
                        <?php if (isset($errors[5])){ ?>
                            <span class="help-inline"><?php print($errors[5]); ?></span>
                        <?php } ?>                      
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[1])){ print(" error"); } ?>">
                    <label class="control-label">Title</label>
                    <div class="controls">
                        <input type="text" name="title" id="title" value="<?php if (isset($params['title'])) print($params['title']); ?>" class="span12" />
                        <?php if (isset($errors[1])){ ?>
                            <span class="help-inline"><?php print($errors[1]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Content</label>
                    <div class="controls">
                        <textarea name="content" id="wysiwg_full" cols="30" rows="10"><?php if (isset($params['content'])) print($params['content']); ?></textarea>
                        <?php if (isset($errors[2])){ ?>
                            <span class="help-inline"><?php print($errors[2]); ?></span>
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
                
                <div class="control-group<?php if (isset($errors[4])){ print(" error"); } ?>">
                    <label class="control-label">Tags</label>
                    <div class="controls">
                        <input type="hidden" name="tags" id="tags" <?php if (isset($params['tags'])) print("value=\"{$params['tags']}\""); ?> />                        
                        <ul id="array_tag_handler"></ul>
                        <?php if (isset($errors[4])){ ?>
                            <span class="help-inline"><?php print($errors[4]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="plugin" />
                        <input type="hidden" name="plugin" value="blogger" />
                        <input type="hidden" name="plugin_menu" value="blogger_write" />
                        <?php if (isset($post_id)){ ?>
                            <input type="hidden" name="post_id" value="<?php print($post_id); ?>" />
                            <input type="submit" name="update_post" value="Update blog post" class="btn btn-primary" />
                        <?php } else { ?>
                            <input type="submit" name="add_post" value="Publish blog post" class="btn btn-primary" />
                        <?php } ?>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<script src="<?php print($baseurl); ?>/control/lib/ajaxupload/ajaxupload.js"></script>
<script src="<?php print($baseurl); ?>/plugins/blogger/js/scripts_blogger_write.js"></script>