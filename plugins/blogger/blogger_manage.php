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

if (isset($_REQUEST['delete_post_id'])){
    $blogger->deletePost($_REQUEST['delete_post_id']);
}

if (!isset($page) || !$page || !is_numeric($page)){
    $page = 1;
}

$total = $blogger->getPostCount();
$posts = $blogger->getPosts($page);

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
                Manage posts
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <div class="pagination pull-right nomargin" style="margin-top:0px;">
            <?php
                $pagination = $misc->getAdminPagination($total, $page, 50, "index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_manage&page=","active");
                print($pagination);
            ?>
        </div>
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="1">Thumb</th>
                    <th width="*">Title</th>
                    <th width="10%">Created</th>
                    <th width="1">&nbsp;</th>
                    <th width="1">&nbsp;</th>
                </tr>
            </thead>
         
            <tbody>
                <?php 
                    if (count($posts)){
                        foreach($posts as $post_id => $post_data){
                            if ($post_data['thumbnail']){
                                $thumbnail = "<a href=\"$baseurl/thumbs/{$post_data['thumbnail']}\"><img src=\"$baseurl/thumbs/{$post_data['thumbnail']}\" style=\"width:60px\" /></a>";
                            } else {
                                $thumbnail = "&nbsp;";
                            }
                            
                            print(" <tr id=\"row{$post_id}\">
                                        <td>
                                            <div class=\"thumbnail\">
                                                $thumbnail
                                            </div>
                                        </td>
                                        <td><a href=\"$baseurl/blog/{$post_data['perma']}\" target=\"_blank\">{$post_data['title']}</a></td>
                                        <td>{$post_data['created']}</td>
                                        <td><a href=\"index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_manage&delete_post_id={$post_id}\" class=\"btn\">Delete</a></td>
                                        <td><a href=\"index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_write&post_id={$post_id}\" class=\"btn\">Edit</a></td>                                 
                                    </tr>");
                        }
                    } else {
                        print("<tr><td colspan=\"5\">No posts found. <a href=\"index.php?menu=plugin&plugin=blogger&plugin_menu=blogger_write\">Click here to add one</a></td></tr>");
                    }
                ?>
            </tbody>
        </table>
        
    </div>
</div>