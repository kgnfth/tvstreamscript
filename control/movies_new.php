<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

$categories = $movie->getCategories($default_language);
$language_options = $misc->getLanguageOptions();

if (isset($_POST['add_movie'])){
    
    if (isset($_POST['movie_id']) && $_POST['movie_id']){
        // update
        $errors = $movie->validate($_POST,true);
        if (!count($errors)){
            $movie->update($_POST['movie_id'],$_POST);
            if (!isset($movie_categories) || !$movie_categories || !is_array($movie_categories)){
                $movie_categories = array();
            }
            $movie->saveCategories($_POST['movie_id'],$movie_categories);
            $movie->deleteAllEmbeds($_POST['movie_id']);
            
            foreach($_POST['embed_enabled'] as $key => $val){
                if (isset($_POST['embeds'][$key]) && $_POST['embeds'][$key]){
                    $embed_code = $_POST['embeds'][$key];
                    if (isset($_POST['links'][$key]) && $_POST['links'][$key]){
                        $embed_link = $_POST['links'][$key];
                    } else {
                        $embed_link = $misc->buildLink($embed_code);
                        if (!$embed_link){
                            $embed_link = "";
                        }
                    }
                    
                    if (isset($_POST['languages'][$key]) && $_POST['languages'][$key]){
                        $embed_lang = $_POST['languages'][$key];
                    } else {
                        $embed_lang = "ENG";
                    }
                    
                    $weight = $misc->getWeight($embed_link);
                    
                    $movie->saveEmbed($_POST['movie_id'],$embed_code,$embed_lang,$weight,$embed_link);                    
                }
            }
            
            $movie->setDate($_POST['movie_id']);
            
            $update = 1;
            $success = 1;
            
        }
    } else {
        // new movie
        $errors = $movie->validate($_POST,false);
        if (!count($errors)){
            $new_movie_id = $movie->save($_POST);
            if (isset($movie_categories) && is_array($movie_categories) && count($movie_categories)){
                $movie->saveCategories($new_movie_id,$movie_categories);
            }
            
            foreach($_POST['embed_enabled'] as $key => $val){
                if (isset($_POST['embeds'][$key]) && $_POST['embeds'][$key]){
                    $embed_code = $_POST['embeds'][$key];
                    if (isset($_POST['links'][$key]) && $_POST['links'][$key]){
                        $embed_link = $_POST['links'][$key];
                    } else {
                        $embed_link = $misc->buildLink($embed_code);
                        if (!$embed_link){
                            $embed_link = "";
                        }
                    }
                    
                    if (isset($_POST['languages'][$key]) && $_POST['languages'][$key]){
                        $embed_lang = $_POST['languages'][$key];
                    } else {
                        $embed_lang = "ENG";
                    }
                    
                    $weight = $misc->getWeight($embed_link);
                    
                    $movie->saveEmbed($new_movie_id,$embed_code,$embed_lang,$weight,$embed_link);                    
                }
            }
            
            $movie->setDate($new_movie_id);

            $success = 1;
            
            if (isset($thumb)) unset($thumb);
            if (isset($embeds)) unset($embeds);
            if (isset($embed_enabled)) unset($embed_enabled);
            if (isset($title)) unset($title);
            if (isset($description)) unset($description);
            if (isset($languages)) unset($languages);
            if (isset($imdb_id)) unset($imdb_id);
        }
    }    
}

if ((!isset($errors) || !count($errors)) && isset($movie_id) && $movie_id){
    $mov = $movie->getMovie($movie_id);
    if (!empty($mov)){
        extract($mov);
        
        if (is_array($mov['meta'])){
            extract($mov['meta']);
        }
        
        $movie_categories = $movie->getMovieCategories($movie_id);
        $movie_embeds = $movie->getEmbeds($movie_id);
        if (count($movie_embeds)){        
            $embed_enabled = array();
            $embeds = array();
            $links = array();
            $languages = array();
            
            $counter = 1;
            foreach($movie_embeds as $embed_id => $val){
                $embed_enabled[$counter] = 1;
                $embeds[$counter] = $val['embed'];
                $links[$counter] = $val['link'];
                $languages[$counter] = $val['lang'];
                
                $counter++;
            }
        }        
    } else {
        unset($movie_id);
    }
}

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
                Add new movie
            </li>
        </ul>
    </div>
</nav>


<div class="row-fluid">
    <div class="span12">

        <?php if (isset($success) && $success){ ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                <?php if (isset($update) && $update){ ?>
                    Movie update successfully. Manage all movies by <a href='index.php?menu=movies_manage'>clicking here</a>
                <?php } else { ?>
                    New movie added successfully. Manage all movies by <a href='index.php?menu=movies_manage'>clicking here</a>
                <?php } ?>
            </div>
        <?php } ?> 


        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>
                <p class="f_legend">Add new movie</p>
                
                <!--  Thumbnail -->
                <div class="control-group<?php if (isset($errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Thumbnail</label>
                    <div class="controls">
                        <div class="span2">
                            <input type="hidden" name="thumb" id="thumbnail_hidden" value="<?php if (isset($thumb) && $thumb) print($thumb); ?>" />
                            <div class="fileupload-new thumbnail" style="max-width:120px;">
                                <img src="<?php if (isset($thumb) && $thumb){ print($baseurl."/thumbs/".$thumb); } else { print("http://www.placehold.it/120x170/EFEFEF/AAAAAA&amp;text=no+image"); } ?>" alt="" id="movie_thumbnail" style="max-width: 120px; max-height: 170px;" />
                            </div>
                            
                            <div>
                                <input type="button" class="btn" value="Upload thumbnail" id="uploader" style="width:130px;"/>
                            </div>                        
                        </div>    
                        <?php if (isset($errors[2])){ ?>
                            <span class="help-inline"><?php print($errors[2]); ?></span>
                        <?php } ?>                        
                    </div>
                </div>
                
                <!--  Titles -->
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                <div class="control-group<?php if (isset($errors[1][$lang_code])){ print(" error"); } ?>">
                    <label class="control-label"><?php print($lang_name); ?> title</label>
                    <div class="controls">
                        <input type="text" name="title[<?php print($lang_code); ?>]" id="title[<?php print($lang_code); ?>]" value="<?php if (isset($title[$lang_code])) print(stripslashes($title[$lang_code])); ?>" class="span8" />
                        <?php if (isset($errors[1][$lang_code])){ ?>
                            <span class="help-inline"><?php print($errors[1][$lang_code]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <?php 
                    }
                ?>    
                
                <!--  Descriptions -->
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                <div class="control-group<?php if (isset($errors[3][$lang_code])){ print(" error"); } ?>">
                    <label class="control-label"><?php print($lang_name); ?> description</label>
                    <div class="controls">
                        <textarea name="description[<?php print($lang_code); ?>]" id="description[<?php print($lang_code); ?>]" class="span8" rows="5"><?php if (isset($description[$lang_code])) print(stripslashes($description[$lang_code])); ?></textarea>
                        <?php if ($lang_code!='en'){ ?>
                            <!-- <input type="button" value="Get translation" class="btn" onclick="translateDescription('#description\\[en\\]','#description\\[<?php print($lang_code); ?>\\]','<?php print($lang_code); ?>');" /> -->
                        <?php } ?>
                        <?php if (isset($errors[3][$lang_code])){ ?>
                            <span class="help-inline"><?php print($errors[3][$lang_code]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <?php 
                    }
                ?>    
                
                <!--  IMDB id -->
                <div class="control-group<?php if (isset($errors[4])){ print(" error"); } ?>">
                    <label class="control-label">IMDB id</label>
                    <div class="controls">
                        <input type="text" name="imdb_id" id="imdb_id" value="<?php if (isset($imdb_id)) print(stripslashes($imdb_id)); ?>" class="span5" />
                        <?php if (isset($errors[4])){ ?>
                            <span class="help-inline"><?php print($errors[4]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <!-- Extras -->
                <div class="control-group<?php if (isset($errors[6])){ print(" error"); } ?>">
                    <label class="control-label">IMDB rating</label>
                    <div class="controls">
                        <input type="text" name="imdb_rating" id="imdb_rating" value="<?php if (isset($imdb_rating)) print(stripslashes($imdb_rating)); ?>" class="span1" />
                        <?php if (isset($errors[6])){ ?>
                            <span class="help-inline"><?php print($errors[6]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[7])){ print(" error"); } ?>">
                    <label class="control-label">Year of release</label>
                    <div class="controls">
                        <input type="text" name="year" id="year" value="<?php if (isset($year)) print(stripslashes($year)); ?>" class="span1" />
                        <?php if (isset($errors[7])){ ?>
                            <span class="help-inline"><?php print($errors[7]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Director</label>
                    <div class="controls">
                        <input type="text" name="director" id="director" value="<?php if (isset($director)) print(stripslashes($director)); ?>" class="span5" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Stars</label>
                    <div class="controls" id="star_controls">

                    </div>
                </div>
                
                
                <!--  Categories -->
                <?php if (count($categories)){ ?>
                <div class="control-group">
                    <label class="control-label">Categories</label>
                    <div class="controls">
                        <?php
                            $counter = 0;
                            foreach($categories as $key=>$val){                                
                                print("    <label class=\"checkbox inline span2\">
                                            <input type=\"checkbox\" class=\"category_checkbox\" name=\"movie_categories[]\" value=\"$key\" ");
                                
                                if (isset($movie_categories) && in_array($key,$movie_categories)) print(" checked=\"checked\" ");
                                
                                print("        /> {$val['name']}
                                        </label>");
                                $counter++;
                            }
                        ?>
                    </div>
                </div>
                <?php } ?>
                
                <div id="embed_list" style="padding-top: 8px;">
                    <?php if (isset($errors[5])){ ?>
                        <div class="control-group error">
                            <label class="control-label">&nbsp;</label>
                            <div class="controls">
                                <span class="help-inline"><br /><?php print($errors[5]); ?></span>
                            </div>
                        </div>
                    <?php } ?>
                    <?php 
                    
                        if (isset($embed_enabled) && $embed_enabled && is_array($embed_enabled) && count($embed_enabled)){
                            $counter = 0;
                            foreach($embed_enabled as $key => $val){
                                $counter++;
                    ?>
                    <div class="control-group embedgroup" id="embedgroup_<?php print($counter); ?>">
                        <label class="control-label"><input type="checkbox" name="embed_enabled[<?php print($counter); ?>]" id="embed_enabled_<?php print($counter); ?>"  checked="checked" style="margin:0px" />&nbsp;&nbsp;Embed code #<?php print($counter); ?></label>
                        <div class="controls">
                            <div class="span12">
                                 <div class="row-fluid">
    
                                    <div class="span10">
                                         <input type="text" name="links[<?php print($counter); ?>]" id="links_<?php print($counter); ?>" class="span12" value="<?php if (isset($links[$key]) && $links[$key]) print($links[$key]); ?>" placeholder="enter embed link" style="margin-bottom:8px;" />
                                         <textarea name="embeds[<?php print($counter); ?>]" id="embeds_<?php print($counter); ?>" class="span12" rows="10" placeholder="enter embed code" style="margin-bottom:8px;"><?php if (isset($embed)) print(stripslashes($embed)); ?><?php if (isset($embeds[$key]) && $embeds[$key]) print($embeds[$key]); ?></textarea>
                                         <select name="languages[<?php print($counter); ?>]" id="languages_<?php print($counter); ?>">
                                             <?php 
                                                 
                                                 foreach($language_options as $lang_key => $code){
                                                     print("<option value\"{$code}\"");
                                                     if (isset($languages[$key]) && $languages[$key]==$code) print(" selected=\"selected\"");
                                                     print(">{$code}</option>\n");
                                                 }
                                             ?>
                                         </select>
                                     </div>
                                     <div class="span2">
                                         <input type="button" class="btn" value="Make embed code" onclick="makeEmbed(<?php print($counter); ?>);" style="margin-bottom:8px; width:125px; text-align:left"/>
                                         <input type="button" class="btn" value="Make link" onclick="makeLink(<?php print($counter); ?>);" style="margin-bottom:8px;width:125px; text-align:left"/>
                                         <input type="button" class="btn" value="Preview" onclick="previewEmbed(<?php print($counter); ?>);" style="margin-bottom:8px;width:125px; text-align:left"/>
                                         <input type="button" class="btn" value="Remove" onclick="removeEmbed(<?php print($counter); ?>);" style="margin-bottom:8px;width:125px; text-align:left"/>
                                         <input type="button" class="btn" value="Add more" onclick="addMoreEmbed();" style="margin-bottom:8px;width:125px; text-align:left"/>
                                     </div>
                                 
                                 </div>
                             </div>
                        </div>
                    </div>
                    
                    <?php 
                            }
                        } else { 
                    ?>
                    <div class="control-group embedgroup" id="embedgroup_1">
                        <label class="control-label"><input type="checkbox" name="embed_enabled[1]" id="embed_enabled_1" checked="checked" style="margin:0px" />&nbsp;&nbsp;Embed code #1</label>
                        <div class="controls">
                            <div class="span12">
                                 <div class="row-fluid">
    
                                    <div class="span10">
                                         <input type="text" name="links[1]" id="links_1" class="span12" placeholder="enter embed link" style="margin-bottom:8px;" />
                                         <textarea name="embeds[1]" id="embeds_1" class="span12" rows="10" placeholder="enter embed code" style="margin-bottom:8px;"><?php if (isset($embed)) print(stripslashes($embed)); ?></textarea>
                                         <select name="languages[1]" id="languages_1">
                                             <?php 
                                                 foreach($language_options as $lang_key => $code){
                                                     print("<option value\"{$code}\">{$code}</option>\n");
                                                 }
                                             ?>
                                         </select>
                                     </div>
                                     <div class="span2">
                                         <input type="button" class="btn" value="Make embed code" onclick="makeEmbed(1);" style="margin-bottom:8px; width:125px; text-align:left"/>
                                         <input type="button" class="btn" value="Make link" onclick="makeLink(1);"style="margin-bottom:8px;width:125px; text-align:left"/>
                                         <input type="button" class="btn" value="Preview" onclick="previewEmbed(1);" style="margin-bottom:8px;width:125px; text-align:left"/>
                                         <input type="button" class="btn" value="Remove" onclick="removeEmbed(1);" style="margin-bottom:8px;width:125px; text-align:left"/>
                                         <input type="button" class="btn" value="Add more" onclick="addMoreEmbed();" style="margin-bottom:8px;width:125px; text-align:left"/>
                                     </div>
                                 
                                 </div>
                             </div>
                        </div>
                    </div>
                    <?php 
                        }
                    ?>
                </div>
                
                <div class="control-group" >
                    <label class="control-label">Grabbers</label>
                    <div class="controls">
                        <?php 
                            $foundone = false;

                            if ($dh = opendir("./ajax/")) {
                                $grabbers = array();
                                $grabber_priority = array();
                                while (($file = readdir($dh)) !== false) {
                                    if ($file!='.' && $file!='..' && stripos($file, "movie_grabber_", 0) === 0){
                                        $f = fopen("./ajax/".$file,"r");
                                        $grabber_name = fgets($f);
                                        $grabber_name = fgets($f);
                                        fclose($f);
                                        
                                        $grabber_name = trim(str_replace(array("/*","*/"),array("",""),$grabber_name));
                                        
                                        $tmp = explode("|",$grabber_name);
                                        if (isset($tmp[1])){
                                            $priority = $tmp[1];
                                        } else {
                                            $priority = 0;
                                        }
                                        
                                        $grabber_name = $tmp[0];
                                        $grabbers[$grabber_name] = $file;
                                        $grabber_priority[$grabber_name] = $priority;
                                        
                                        $foundone = true;
                                    }
                                }
                                
                                if ($foundone){
                                    arsort($grabber_priority);
                                    foreach($grabber_priority as $grabber_name => $priority){
                                        $file = $grabbers[$grabber_name];
                                        print("<input type=\"button\" class=\"btn\" value=\"$grabber_name\" onclick=\"grabMovieEmbeds('$file',jQuery(this));\" /> ");
                                    }
                                }
                            }
                            
                            if (!$foundone){
                                print("<input type='button' class='btn' disabled value='No installed grabber found' />");
                            }
                        ?>
                    </div>
                </div>
                
                
                <!--  Submit -->
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <br /><br />
                        <input type="hidden" name="menu" value="movies_new" />
                        <input type="hidden" name="from" value="admin" />
                        
                        <?php if (isset($movie_id) && $movie_id){ ?>
                            <input type="hidden" name="movie_id" value="<?php print($movie_id); ?>" />
                            <input type="submit" name="add_movie" class="btn btn-primary" value="Update this movie" />
                            <input type="button" class="btn" id="get_movie" onclick="getMovie()" value="Grab details" />    
                        <?php } else { ?>
                            <input type="submit" name="add_movie" class="btn btn-primary" value="Add this movie" />
                            <input type="button" class="btn" id="get_movie" onclick="getMovie()" value="Grab details" />    
                        <?php } ?>
                    </div>
                </div>
                
            </fieldset>
        </form>
    </div>
</div>

<?php 
    if (isset($stars) && count($stars)){
        print("<script>");
        
        foreach($stars as $key => $star){
            if ($star){
                print("addMovieStar('".$star."');");
            }    
        }
        
        print("</script>");
    } else { 
?>
    <script>
        addMovieStar();
    </script>
<?php } ?>


<div id="embed_block" class="hide">
    <div class="control-group embedgroup"  id="embedgroup_[embed_counter]">
        <label class="control-label"><input type="checkbox" name="embed_enabled[[embed_counter]]" id="embed_enabled_[embed_counter]" checked="checked" style="margin:0px" />&nbsp;&nbsp;Embed code #[embed_counter]</label>
        <div class="controls">
            <div class="span12">
                 <div class="row-fluid">

                    <div class="span10">
                         <input type="text" name="links[[embed_counter]]" id="links_[embed_counter]" class="span12" placeholder="enter embed link" style="margin-bottom:8px;" />
                         <textarea name="embeds[[embed_counter]]" id="embeds_[embed_counter]" class="span12" rows="10" placeholder="enter embed code" style="margin-bottom:8px;"><?php if (isset($embed)) print(stripslashes($embed)); ?></textarea>
                         <select name="languages[[embed_counter]]" id="languages_[embed_counter]">
                             <?php 
                                 $language_options = $misc->getLanguageOptions();
                                 foreach($language_options as $lang_key => $code){
                                     print("<option value\"{$code}\">{$code}</option>\n");
                                 }
                             ?>
                         </select>
                     </div>
                     <div class="span2">
                         <input type="button" class="btn" value="Make embed code" onclick="makeEmbed([embed_counter]);" style="margin-bottom:8px; width:125px; text-align:left"/>
                         <input type="button" class="btn" value="Make link" onclick="makeLink([embed_counter]);" style="margin-bottom:8px;width:125px; text-align:left"/>
                         <input type="button" class="btn" value="Preview"  onclick="previewEmbed([embed_counter]);" style="margin-bottom:8px;width:125px; text-align:left"/>
                         <input type="button" class="btn" value="Remove"  onclick="removeEmbed([embed_counter]);" style="margin-bottom:8px;width:125px; text-align:left"/>
                         <input type="button" class="btn" value="Add more" onclick="addMoreEmbed();" style="margin-bottom:8px;width:125px; text-align:left"/>
                     </div>
                 
                 </div>
             </div>
        </div>
    </div>
</div>

<div class="modal hide" id="embed_preview" style="width:720px;">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>Embed preview</h3>
    </div>
    <div class="modal-body">
        <div class="span12" id="preview_body" style="width:620px; overflow: hidden;">
        
        </div>
    </div>
</div>

<div class="modal hide" id="confirm-modal" style="width:720px;">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>Do you want to replace the existing embed codes?</h3>
    </div>
    <div class="modal-body">
        <div class="span12" id="replace-dialog" style="width:620px; overflow: hidden;">
            You already have some embed codes added to this movie. Do you want to replace them with the grabbed ones or just add to them? <br /><br />
            
            <input type="button" class="btn pull-right" value="Replace" id="replace-button" />
            <input type="button" class="btn pull-right" value="Add" id="add-button" style="margin-right: 8px;"/>
        </div>    
    </div>
</div>