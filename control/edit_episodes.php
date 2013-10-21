<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (!isset($showid) || !$showid || !is_numeric($showid)){
    print("<script> window.location = 'index.php'; </script>");
    exit();
}

$show_data = $show->getShow($showid,false,$default_language);
if (!$show_data){
    print("<script> window.location = 'index.php'; </script>");
    exit();
}

$episodes = $show->getEpisodes($showid);
?>

<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                <a href="index.php?menu=shows_manage">TV shows</a>
            </li>
            <li>
                <a href="index.php?menu=episodes&show_id=<?php print($show_data['id']); ?>"><?php print($show_data['title']); ?></a>
            </li>
            <li>
                Edit episodes
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
    
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="optional">ID</th>
                    <th class="optional" style="width:60px">Thumbnail</th>
                    <th class="optional">Title</th>
                    <th class="optional">Description</th>
                    <th class="optional">Episode code</th>
                    <th class="optional">&nbsp;</th>
                    <th class="optional">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!count($episodes)){
                    print("<tr><td colspan='7' align='center'>No episodes yet. <a href='index.php?menu=episodes&show_id=$showid'>Click here to add one</a></td></tr>");
                } else {
                    foreach($episodes as $id => $val){
                        extract($val);
                        
                        $episode_code = "S".str_pad($season,2,"0",STR_PAD_LEFT)."E".str_pad($episode,2,"0",STR_PAD_LEFT);
                        
                        print("    <tr id=\"row{$id}\">
                                    <td>{$id}</td>
                                    <td>
                                        <div class=\"thumbnail\">
                                            <a href=\"$baseurl/thumbs/{$thumbnail}\"><img src=\"$baseurl/thumbs/{$thumbnail}\" style=\"width:60px\" /></a>
                                        </div>
                                    </td>
                                    <td>{$episodetitle}</td>
                                    <td>{$description}</td>
                                    <td>{$episode_code}</td>
                                    <td><a href=\"index.php?menu=episodes&show_id=$showid&season=$season&episode=$episode\">Edit</a></td>
                                    <td><a href=\"javascript:void(0);\" onclick=\"deleteEpisode($id);\">Delete</a></td>                                    
                                </tr>");
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>