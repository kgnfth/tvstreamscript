<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (isset($addcategory) && isset($category)){
    
    $errors = $movie->validateCategory($category);
    
    if (!count($errors)){
        $movie->addCategory($category);
        $success = 1;
    }

}

$categories = $movie->getCategories($default_language);

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
                Manage categories
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        
        <?php
            if (isset($success) && $success){
        ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Category added
            </div>    
        <?php } ?>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>
                <?php foreach($global_languages as $lang_code => $lang_name) {?>
                    <div class="control-group<?php if (isset($errors[$lang_code])){ print(" error"); } ?>">
                        <label class="control-label"><?php print($lang_name); ?> title:</label>
                        <div class="controls">
                            <input type="text" name="category[<?php print($lang_code); ?>]" class="span8" />
                            <?php if (isset($errors[$lang_code])){ ?>
                                <span class="help-inline"><?php print($errors[$lang_code]); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                <?php }?>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="movie_categories" />
                        <input type="submit" name="addcategory" value="Add category" class="btn btn-primary"/>
                    </div>
                </div>    
                
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <h3 class="heading">Existing categories</h3>
        </div>
        
        <div class="row-fluid" style="margin-top: 0px">        
        <?php
            if (count($categories)){
                $counter = 0;
                foreach($categories as $key=>$val){
                    $counter++;
                    print("    <span class='span2 well' id='tag$key'>
                                <a href='javascript:void(0);' onclick='deleteMovietag($key)' title='Delete'><img src='img/gCons/fire.png' style='border:0px;' /></a> {$val['name']}
                            </span>");
                    
                    if ($counter%6==0){
                        print(" </div>
                                <div class='row-fluid'>");
                    }
                }
                
            } else {
                print("<span class='span12'>No categories added yet</span>");
            }
        ?>
        </div>
    </div>
</div>