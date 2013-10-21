<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (isset($delete) && $delete){
    $misc->deleteLink($delete);
}

if (isset($approve) && $approve){
    $link_data = $movie->getLink($approve);
    if ($link_data){
        $movie_data = $movie->getByImdb($link_data['imdb_id']);
        if ($movie_data){
            // existing movie
            $movie_id = $movie_data['id'];
        } else {
            // new movie
            require_once("../includes/imdb.class.php");
            $imdb = new IMDB();
            
            $imdb_data = $imdb->getById($link_data['imdb_id']);
            $movie_data = array();
            
            if (count($imdb_data)){
                $movie_data['imdb_id'] = $link_data['imdb_id'];
                
                if (isset($imdb_data['title']) && $imdb_data['title']){
                    $movie_data['title'] = array();
                    foreach($global_languages as $lang_code => $lang_name){
                        $movie_data['title'][$lang_code] = $imdb_data['title'];
                    }
                }
                
                if (isset($imdb_data['summary']) && $imdb_data['summary']){
                    $movie_data['description'] = array();
                    foreach($global_languages as $lang_code => $lang_name){
                        $movie_data['description'][$lang_code] = $imdb_data['summary'];
                    }
                }
                
                if (isset($imdb_data['rating']) && $imdb_data['rating']){
                    $movie_data['imdb_rating'] = $imdb_data['rating'];
                }
                
                if (isset($imdb_data['stars']) && $imdb_data['stars'] && count($imdb_data['stars'])){
                    $movie_data['stars'] = $imdb_data['stars'];
                } else {
                    $movie_data['stars'] = array();
                }
                
                if (isset($imdb_data['director'])){
                    $movie_data['director'] = $imdb_data['director'];
                } else {
                    $movie_data['director'] = "";
                }
                
                if (isset($imdb_data['year'])){
                    $movie_data['year'] = $imdb_data['year'];
                } else {
                    $movie_data['year'] = 0;
                }
                
                if (isset($imdb_data['genres']) && is_array($imdb_data['genres']) && count($imdb_data['genres'])){
                    if (isset($global_languages['en'])){
                        $categories = $movie->getCategories("en");
                    } else {
                        $categories = $movie->getCategories($default_language);
                    }
                    
                    $movie_data['categories'] = array();
                    if (count($categories)){
                        foreach($categories as $category_id => $val){
                            foreach($imdb_data['genres'] as $key => $genre){
                                if (preg_replace("/[^a-z0-9]/","",strtolower($genre))==preg_replace("/[^a-z0-9]/","",strtolower($val['name']))){
                                    $movie_data['categories'][] = $category_id;
                                }
                            }
                        }
                    }
                }
                
                if (isset($imdb_data['image'])){
                    $curl = new Curl();
                    $image_data = $curl->get($imdb_data['image']);
                    if ($image_data && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                        $image_name = "movie_".md5($imdb_data['title'].$link_data['imdb_id']);
                        
                        $handle = fopen("../thumbs/$image_name.jpg","w+");
                        fwrite($handle,$image_data);
                        fclose($handle);
                        
                        $movie_data['thumb'] = $image_name.".jpg";
                    } else {
                        $movie_data['thumb'] = "";
                    }
                }
            }
            
            $errors = $movie->validate($movie_data, false, true);
            if (count($errors)){
                print_r($errors);
                $movie_id = 0;
                $error = "There was a problem adding this movie. Please add it manually";
            } else {
                $movie_id = $movie->save($movie_data);
                if (isset($movie_data['categories']) && is_array($movie_data['categories']) && count($movie_data['categories'])){
                    $movie->saveCategories($movie_id,$movie_data['categories']);
                }
            }
        }
        
        if ($movie_id){
            $embed_code = $misc->buildEmbed($link_data['link']);
            $embed_link = $link_data['link'];
            $embed_lang = $link_data['language'];
            $weight = $misc->getWeight($embed_link);
            
            $movie->saveEmbed($movie_id, $embed_code, $embed_lang, $weight, $embed_link);
            $misc->approveLink($approve); 
        }  
    }    
}

$links = $movie->getLinks(null,$default_language);
 
?>

<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                <a href="index.php?menu=movies_manage">Movies</a>
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
                      <th width="10%">User</th>
                      <th width="*">Add / Edit movie</th>
                      <th width="50%">Link</th>
                      <th width="*">Language</th>
                      <th width="1">Delete</th>
                      <th width="1">Approve</th>
                 </tr>
             </thead>
         
             <tbody>
                 <?php 
                     if (!count($links)){
                         print("<tr><td colspan=\"6\">No submitted links found</td></tr>");
                     } else {
                         foreach($links as $link_id => $link_data){
                             
                             if ($link_data['status']==1){
                                 $row_class=" style=\"background-color: #DFF0D8 !important;\"";
                                 $approve_button = "Approved";
                             }  else {
                                 $row_class = "";
                                 $approve_button = "<a href=\"index.php?menu=movie_links&approve={$link_id}\" class=\"btn\">Approve</a>";
                             }
                             
                             if ($link_data['movie_id'] && $link_data['movie_title']){
                                 $movie_link = "<a href=\"index.php?menu=movies_new&movie_id={$link_data['movie_id']}\">{$link_data['movie_title']} ({$link_data['imdb_id']})</a>";
                             } else {
                                 $movie_link = "<a href=\"index.php?menu=movies_new&imdb_id={$link_data['imdb_id']}\">{$link_data['imdb_id']}</a>";
                             }
                             
                             
                             
                             print("    <tr{$row_class}>
                                         <td{$row_class}><a href=\"index.php?menu=users_email&user_list={$link_data['username']}\">{$link_data['username']}</a></td>
                                         <td{$row_class}>{$movie_link}</td>
                                         <td{$row_class}><input type=\"text\" class=\"span12\" value=\"{$link_data['link']}\" /></td>
                                         <td{$row_class}>
                                             {$link_data['language']}
                                         </td>
                                         <td{$row_class}>
                                             <a href=\"index.php?menu=movie_links&delete={$link_id}\" class=\"btn\">Delete</a>
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