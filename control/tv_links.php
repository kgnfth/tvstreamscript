<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (isset($delete) && $delete){
    $misc->deleteLink($delete);
}

if (isset($approve) && $approve){
    $link_data = $show->getLink($approve);
    if ($link_data && isset($link_data['show_id']) && $link_data['show_id']){
        $episode_data = $show->getEpisode($link_data['show_id'], $link_data['season'], $link_data['episode']);
        if ($episode_data){
            // existing episode
            $episode_id = $episode_data['id'];
        } else {
            // new episode
            require_once("../includes/sidereel.class.php");
            $sidereel = new Sidereel();
            
            $episode_data = array();
            $episode_data['show_id'] = $link_data['show_id'];
            $episode_data['season'] = $link_data['season'];
            $episode_data['episode'] = $link_data['episode'];
            
            if (isset($global_languages['en'])){
                $show_data = $show->getShow($link_data['show_id'], false, "en");
            } else {
                $show_data = $show->getShow($link_data['show_id'], false, $default_language);
            }
            
            // title and description
            $sidereel_title = $sidereel->sidereelURL($show_data['title'],$show_data['sidereel_url']);
            $sidereel_link = "http://www.sidereel.com/".$sidereel_title."/season-".$link_data['season']."/episode-".$link_data['episode'];

            $episode_details = $sidereel->getEpisodeDetails($sidereel_link);
            if (isset($episode_details['title']) && $episode_details['title']){
                if (substr_count(strtolower($episode_details['title']),"season")==0 && substr_count(strtolower($episode_details['title']),"episode")==0){
                    $episode_details['title'] = "Season ".$link_data['season'].", Episode ".$link_data['episode']." - ".$episode_details['title'];
                }
                $episode_data['title'] = $episode_details['title'];
            } else {
                $episode_data['title'] = "Season ".$link_data['season'].", Episode ".$link_data['episode'];
            }
            
            if (isset($episode_details['description']) && $episode_details['description']){
                $episode_data['description'] = $episode_details['description'];
            } else {
                $episode_data['description'] = "No description";
            }
            
            // thumbnail
            $episode_data['thumbnail'] = $sidereel->getThumbnail($show_data,$link_data['season'],$link_data['episode'],$basepath);
            $episode_data['thumbnail'] = trim($episode_data['thumbnail']);
            
            $errors = $show->validateEpisode($episode_data, true);
            
            if (count($errors)){
                $episode_id = 0;
                $error = "There was a problem adding the episode. Please add it manually";
            } else {
                $episode_id = $show->saveEpisode($episode_data);
            }
        }
        
        if ($episode_id){
            $embed_code = $misc->buildEmbed($link_data['link']);
            $embed_link = $link_data['link'];
            $embed_lang = $link_data['language'];
            $weight = $misc->getWeight($embed_link);
            $show->addEmbed($episode_id,$embed_code,$embed_lang,$embed_link,$weight);
            $misc->approveLink($approve);
        }           
    }
}

if (isset($global_languages['en'])){
    $links = $show->getLinks(null,"en");
} else {
    $links = $show->getLinks(null, $default_language);
}
 
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
                Submitted links
            </li>
        </ul>
    </div>
</nav>


<div class="row-fluid">
    <div class="span12">
        <?php 
            if (isset($error) && $error){
        ?>
        
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert">×</a>
            <?php print($error); ?>
        </div>
        
        <?php 
            }
        ?>
    
        <table class="table table-striped table-bordered">
             <thead>
                 <tr>
                      <th width="*%">User</th>
                      <th width="*">Add / Edit Show</th>
                      <th width="1">Season</th>
                      <th width="1">Episode</th>
                      <th width="50%">Link</th>
                      <th width="*">Language</th>
                      <th width="1">Delete</th>
                      <th width="1">Approve</th>
                 </tr>
             </thead>
         
             <tbody>
                 <?php 
                     if (!count($links)){
                         print("<tr><td colspan=\"8\">No submitted links found</td></tr>");
                     } else {
                         foreach($links as $link_id => $link_data){
                             

                             if ($link_data['show_id'] && $link_data['show_title']){
                                 $movie_link = "<a href=\"index.php?menu=episodes&show_id={$link_data['show_id']}&season={$link_data['season']}&episode={$link_data['episode']}\">{$link_data['show_title']} ({$link_data['imdb_id']})</a>";
                                 $approve_button = "<a href=\"index.php?menu=tv_links&approve={$link_id}\" class=\"btn btn-success\">Approve</a>";
                             } else {
                                 $movie_link = "<a href=\"index.php?menu=shows_new&imdb_id={$link_data['imdb_id']}\">{$link_data['imdb_id']}</a>";
                                 $approve_button = "<a href=\"javascript:void(0);\" class=\"btn btn-danger\" onclick=\"alert('Please add the TV show first');\">Approve</a>";
                             }
                             
                             if ($link_data['status']==1){
                                 $row_class=" style=\"background-color: #DFF0D8 !important;\"";
                                 $approve_button = "Approved";
                             }  else {
                                 $row_class = "";
                             }
                             
                             
                             print("    <tr{$row_class}>
                                         <td{$row_class}><a href=\"index.php?menu=users_email&user_list={$link_data['username']}\">{$link_data['username']}</a></td>
                                         <td{$row_class}>{$movie_link}</td>
                                         <td{$row_class}>{$link_data['season']}</td>
                                         <td{$row_class}>{$link_data['episode']}</td>
                                         <td{$row_class}><input type=\"text\" class=\"span12\" value=\"{$link_data['link']}\" /></td>
                                         <td{$row_class}>
                                             {$link_data['language']}
                                         </td>
                                         <td{$row_class}>
                                             <a href=\"index.php?menu=tv_links&delete={$link_id}\" class=\"btn\">Delete</a>
                                         </td>
                                         <td{$row_class}>
                                             $approve_button
                                         </td>
                                     </tr>");
                         }
                     }
                 
                 ?>
             </tbody>
         </table>
    </div>
</div>