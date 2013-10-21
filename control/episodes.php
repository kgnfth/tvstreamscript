<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

$shows = $show->getShows(null,$default_language);
$shows = $misc->aasort($shows,"title");

if (isset($save_episode)){
    
    $errors = $show->validateEpisode($_POST);
    
    if (!count($errors)){
        
        $data = array();
        if (isset($_POST['title'])){
            $data['title'] = $_POST['title'];
        } else {
            $data['title'] = "";
        }
        
        if (isset($_POST['description'])){
            $data['description'] = $_POST['description'];
        } else {
            $data['description'] = "";
        }
        
        if (isset($_POST['thumbnail'])){
            $data['thumbnail'] = $_POST['thumbnail'];
        } else {
            $data['thumbnail'] = "";
        }
        
        $data['season'] = $_POST['season'];
        $data['episode'] = $_POST['episode'];
        $data['show_id'] = $_POST['show_id'];
        
        $check = $show->getEpisode($_POST['show_id'], $_POST['season'], $_POST['episode']);
        if ($check && is_array($check)){
            // episode already exists
            $episode_id = $check['episodeid'];
            $show->updateEpisode($episode_id,$data);
            $show->deleteAllEmbeds($episode_id);
            
            $update = 1;
        } else {
            $episode_id = $show->saveEpisode($data);
        }
        
        if ($episode_id){
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
                    
                    $show->addEmbed($episode_id,$embed_code,$embed_lang,$embed_link,$weight);
                    
                    $show->setEpisodeDate($episode_id,$data['show_id']);
                }
            }
            
            $success = 1;
            
            if (isset($thumbnail)) unset($thumbnail);
            if (isset($embeds)) unset($embeds);
            if (isset($embed_enabled)) unset($embed_enabled);
            if (isset($title)) unset($title);
            if (isset($description)) unset($description);
            if (isset($languages)) unset($languages);
            
            unset($save_episode);
            
            if (!isset($update) || !$update){
                $episode += 1;
            }
        }
    }
}

if (isset($season)){
    $season = (int) $season;
}

if (isset($episode)){
    $episode = (int) $episode;
}

if (isset($show_id)){
    $show_id = (int) $show_id;
}

if (!isset($season) || !$season) $season = 1;
if (!isset($episode) || !$episode) $episode = 1;


// getting existing data
if (isset($show_id)){
    $show_id = (int) $show_id;
    
    $episode_data = $show->getEpisode($show_id, $season, $episode);
    
    if ($episode_data && is_array($episode_data)){
        
        $title = $episode_data['title'];
        $description = $episode_data['description'];
        $thumbnail = $episode_data['thumbnail'];
        
        $episode_embeds = $show->getEpisodeEmbeds($episode_data['episodeid']);
        if (count($episode_embeds)){
            
            $embed_enabled = array();
            $embeds = array();
            $links = array();
            $languages = array();
            
            $counter = 1;
            foreach($episode_embeds as $embed_id => $val){
                $embed_enabled[$counter] = 1;
                $embeds[$counter] = $val['embed'];
                $links[$counter] = $val['link'];
                $languages[$counter] = $val['lang'];
                
                $counter++;
            }
        }
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
                <a href="index.php?menu=shows_manage">TV shows</a>
            </li>
            <li>
                Add new episode
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
                    Episode update successfully. You can check the episode list for this show by <a href='index.php?menu=edit_episodes&showid=<?php print($show_id); ?>'>clicking here</a>
                <?php } else { ?>
                    New episode has been added. You can check the episode list for this show by <a href='index.php?menu=edit_episodes&showid=<?php print($show_id); ?>'>clicking here</a>
                <?php } ?>
            </div>
        <?php } ?> 

         <form action="index.php" method="post" class="form-horizontal well">
             <fieldset>
                <div class="control-group<?php if (isset($errors[1])) print(" error"); ?>">
                    <label class="control-label">TV show</label>
                    <div class="controls">
                         <select name="show_id" id='show_id' onchange="reloadEpisode();" class="span3" >
                             <option value='0'>Select a show</option>
                              <?php
                                if (count($shows)){
                                    foreach($shows as $id => $val){
                                        $show_title = stripslashes($val['title']);
                                        print("<option value='$id' "); if (isset($show_id) && $show_id==$id) print(" selected='selected'"); print(">$show_title</option>\n");
                                    }
                                }
                              ?>
                         </select>
                         <?php if (isset($show_id)){ ?>
                         <span class="help-inline">
                             <a href="index.php?menu=edit_episodes&showid=<?php print($show_id); ?>">Episodes list</a>
                         </span>
                         <?php } ?>
                         
                        <?php if (isset($errors[1])){ ?>
                            <span class="help-inline"><?php print($errors[1]); ?></span>
                        <?php } ?>    
                    </div>
                </div>
                    
                <div class="control-group">
                    <label class="control-label">Custom thumbnail</label>
                    <div class="controls">
                        <div class="span3">
                            <div class="fileupload-new thumbnail" style="max-width:200px;margin-bottom:8px;">
                                <img src="<?php if (isset($thumbnail) && $thumbnail){ print($baseurl."/thumbs/".$thumbnail); } else { print("http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"); } ?>" alt="" id="episode_thumbnail" style="max-width: 200px; max-height: 150px;" />
                            </div>
                            <input type="hidden" id="thumbnail_hidden" name="thumbnail" value="<?php if (isset($thumbnail)) print($thumbnail); ?>" />
                            <input type='button' value='Upload' class="btn" id="upload_button" />
                            <input type='button' value='Grab' class="btn" id="grab_button" onclick="getEpisodeThumbnail($('#show_id').val(),$('#season').val(),$('#episode').val())"/>
                            
                        </div>
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[2])) print(" error"); ?>">
                    <label class="control-label">Season</label>
                    <div class="controls">
                         <input type="button" value="&laquo; Prev" class="btn" onclick="prevSeason();" <?php if ($season==1){ print('disabled="disabled"'); } ?> />
                         <input type="text" id="season" size="2" style="width:30px;" name="season" value="<?php if (isset($season) && $season) print($season); ?>" />
                         <input type="button" value="Next &raquo;" class="btn" onclick="nextSeason();" />
                         <input type="button" value="Reload" class="btn" onclick="reloadEpisode();" />
                        <?php if (isset($errors[2])){ ?>
                            <span class="help-inline"><?php print($errors[2]); ?></span>
                        <?php } ?>    
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[3])) print(" error"); ?>">
                    <label class="control-label">Episode</label>
                    <div class="controls">
                         <input type="button" value="&laquo; Prev" class="btn" onclick="prevEpisode();" <?php if ($episode==1){ print('disabled="disabled"'); } ?> />
                         <input type="text" id="episode" size="2" style="width:30px;" name="episode" value="<?php if (isset($episode) && $episode) print($episode); ?>" />
                         <input type="button" value="Next &raquo;" class="btn" onclick="nextEpisode();" />
                         <input type="button" value="Reload" class="btn" onclick="reloadEpisode();" />
                        <?php if (isset($errors[3])){ ?>
                            <span class="help-inline"><?php print($errors[3]); ?></span>
                        <?php } ?>    
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Episode title</label>
                    <div class="controls">
                        <div class="row-fluid">
                            <div class="span10">
                                 <input type="text" id="title" class="span12" name="title" value="<?php if (isset($title)) print($title); ?>" />
                             </div>
                             <div class="span2">
                                 <input type="button" class="btn" value="Grab title"  onclick="getEpisodeDescription(jQuery('#show_id').val(),jQuery('#season').val(),jQuery('#episode').val(),jQuery(this));" style="width:125px; text-align:left"/>
                             </div>
                         </div>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Description</label>
                    <div class="controls">
                        <div class="row-fluid">
                            <div class="span10">
                                 <textarea name="description" id="description" class="span12" rows="5"><?php if (isset($description)) print(stripslashes($description)); ?></textarea>
                             </div>
                             <div class="span2">
                                 <input type="button" class="btn" value="Grab description" onclick="getEpisodeDescription(jQuery('#show_id').val(),jQuery('#season').val(),jQuery('#episode').val(),jQuery(this));" style="width:125px; text-align:left"/>
                             </div>
                    </div>
                </div>
                
                
                <div id="embed_list" style="padding-top: 8px;">
                    <?php if (isset($errors[4])){ ?>
                        <div class="control-group error">
                            <label class="control-label">&nbsp;</label>
                            <div class="controls">
                                <span class="help-inline"><br /><?php print($errors[4]); ?></span>
                            </div>
                        </div>
                    <?php } ?>
                    <?php 
                        $language_options = $misc->getLanguageOptions();
                    
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
                                    if ($file!='.' && $file!='..' && stripos($file, "grabber_", 0) === 0){
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
                                        print("<input type=\"button\" class=\"btn\" value=\"$grabber_name\" onclick=\"grabEmbeds('$file',jQuery(this));\" /> ");
                                    }
                                }
                            }
                            
                            if (!$foundone){
                                print("<input type='button' class='btn' disabled value='No installed grabber found' />");
                            }
                        ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                         <input type="hidden" name="menu" value="episodes" />
                         <input type="hidden" name="from" value="admin" />
                         <input type="submit" name="save_episode" value="Save episode" class="btn btn-primary" />
                    </div>
                </div>

            </fieldset>
         </form>
    </div>
</div>


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
            You already have some embed codes added to this episode. Do you want to replace them with the grabbed ones or just add to them? <br /><br />
            
            <input type="button" class="btn pull-right" value="Replace" id="replace-button" />
            <input type="button" class="btn pull-right" value="Add" id="add-button" style="margin-right: 8px;"/>
        </div>    
    </div>
</div>