<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

$categories = $show->getCategories($default_language);
$showtags = array();

if (isset($updshow) && isset($showid) && $showid){
    $errors = $show->validate($_POST,$showid);
    if (!count($errors)){
        
        $updsuccess = 1;
        $updid = $showid;
        $show->update($_POST,$showid);
        if (!isset($show_categories) || !is_array($show_categories) || !$show_categories){
            $show_categories = array();
        }
        
        $show->saveCategories($showid,$show_categories);
        
        foreach($_POST as $key => $val){
            if ($key!='menu'){
                unset($$key);
            }
        }
                
    }   
}

if (isset($showid) && $showid && (!isset($updshow) || !$updshow)){
    $edit_show = $show->getShow($showid);
    if ($edit_show){
        extract($edit_show[$showid]);
        if (isset($meta) && is_array($meta) && count($meta)){
            extract($meta);
        }
        $show_categories = $show->getShowCategories($showid);
        
    }
}

if (isset($addshow)){
    
    $errors = $show->validate($_POST);
    
    if (!count($errors)){
        $success = 1;
        $showid = $show->save($_POST);
        if (isset($show_categories) && $show_categories && is_array($show_categories) && count($show_categories)){
            $show->saveCategories($showid,$show_categories);
        }
        
    }

    if (isset($success) && $success){
        foreach($_POST as $key => $val){
            if ($key!='menu'){
                unset($$key);
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
                Add new show
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
            <?php if (isset($success) && $success){ ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                New TV show has been added. You can add episodes by <a href='index.php?menu=episodes&show_id=<?php print($showid); ?>'>clicking here</a>
            </div>
        <?php 
                    $showid = 0;    
                } 
        ?>

        <?php if (isset($updsuccess) && $updsuccess){ ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                TV show has been updated. You can add episodes by <a href='index.php?menu=episodes&show_id=<?php print($updid); ?>'>clicking here</a>
            </div>    
        <?php } ?>    

        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>
                <p class="f_legend">Add new show</p>
                
                <!--  Thumbnail -->
                <div class="control-group<?php if (isset($errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Default thumbnail</label>
                    <div class="controls">
                        <div class="span2">
                            <input type="hidden" name="thumbnail" id="thumbnail_hidden" value="<?php if (isset($thumbnail) && $thumbnail) print($thumbnail); ?>" />
                            <div class="fileupload-new thumbnail" style="max-width:120px;">
                                <img src="<?php if (isset($thumbnail) && $thumbnail){ print($baseurl."/thumbs/".$thumbnail); } else { print("http://www.placehold.it/120x170/EFEFEF/AAAAAA&amp;text=no+image"); } ?>" alt="" id="show_thumbnail" style="max-width: 120px; max-height: 170px;" />
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
                <div class="control-group<?php if (isset($errors[6])){ print(" error"); } ?>">
                    <label class="control-label">IMDB id</label>
                    <div class="controls">
                        <input type="text" name="imdb_id" id="imdb_id" value="<?php if (isset($imdb_id)) print(stripslashes($imdb_id)); ?>" class="span5" />
                        <?php if (isset($errors[6])){ ?>
                            <span class="help-inline"><?php print($errors[6]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <!--  IMDB rating -->
                <div class="control-group<?php if (isset($errors[7])){ print(" error"); } ?>">
                    <label class="control-label">IMDB rating</label>
                    <div class="controls">
                        <input type="text" name="imdb_rating" id="imdb_rating" value="<?php if (isset($imdb_rating)) print(stripslashes($imdb_rating)); ?>" class="span1" />
                        <?php if (isset($errors[7])){ ?>
                            <span class="help-inline"><?php print($errors[7]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[8])){ print(" error"); } ?>">
                    <label class="control-label">Year started</label>
                    <div class="controls">
                        <input type="text" name="year_started" id="year_started" value="<?php if (isset($year_started)) print(stripslashes($year_started)); ?>" class="span2" />
                        <?php if (isset($errors[8])){ ?>
                            <span class="help-inline"><?php print($errors[8]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Stars</label>
                    <div class="controls" id="star_controls">

                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Creators</label>
                    <div class="controls" id="creator_controls">

                    </div>
                </div>
                
                <!--  Sidereel URL -->
                <div class="control-group<?php if (isset($errors[4])){ print(" error"); } ?>">
                    <label class="control-label">Sidereel URL</label>
                    <div class="controls">
                        <input type="text" name="sidereel_url" id="sidereel_url" value="<?php if (isset($sidereel_url)) print(stripslashes($sidereel_url)); ?>" class="span5" />
                        <input type="button" class="btn" onclick="getSidereelURL();" value="Try to grab" />

                        <?php if (isset($errors[4])){ ?>
                            <span class="help-inline"><?php print($errors[4]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <!--  Featured -->
                <div class="control-group">
                    <label class="control-label">Featured?</label>
                    <div class="controls">
                        <label class="checkbox inline">
                            <input type="checkbox" <?php if (isset($featured) && $featured) print("checked='checked'"); ?> name="featured" />
                        </label>
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
                                            <input type=\"checkbox\" class=\"category_checkbox\" name=\"show_categories[]\" value=\"$key\" ");
                                
                                if (isset($show_categories) && in_array($key,$show_categories)) print(" checked=\"checked\" ");
                                
                                print("        /> {$val['name']}
                                        </label>");
                                $counter++;
                            }
                        ?>
                    </div>
                </div>
                <?php } ?>
                
                <!--  Submit -->
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <br /><br />
                        <input type="hidden" name="menu" value="shows_new" />
                        <?php if (isset($showid) && $showid){ ?>
                            <input type="hidden" name="showid" value="<?php print($showid); ?>" />
                            <input type="submit" class="btn btn-primary" name="updshow" value="Update this show" />
                            <input type="button" class="btn" id="getshow" onclick="getShow()" value="Grab details" />    
                        <?php } else { ?>
                            <input type="submit" class="btn btn-primary"  name="addshow" value="Add this show" />
                            <input type="button" class="btn" id="getshow" onclick="getShow()" value="Grab details" />
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
                print("addShowStar('".$star."');");
            }    
        }
        
        print("</script>");
    } else { 
?>
    <script>
        addShowStar();
    </script>
<?php } ?>

<?php 
    if (isset($creators) && count($creators)){
        print("<script>");
        
        foreach($creators as $key => $creator){
            if ($creator){
                print("addShowCreator('".$creator."');");
            }    
        }
        
        print("</script>");
    } else { 
?>
    <script>
        addShowCreator();
    </script>
<?php } ?>