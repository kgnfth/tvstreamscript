<?php 
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}
?>
<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                Pages
            </li>
            <li>
                Manage pages
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <?php 
            if (isset($delete_page) && $delete_page && is_numeric($delete_page)){
                $page->delete($delete_page);
            }
        
            if (isset($save_page) && $save_page){
                if (!isset($page_id) || !is_numeric($page_id)){
                    $page_id = 0;
                }
                
                $errors = $page->validate($_POST,$page_id);
                if (!count($errors)){
                    $perma = $misc->makePerma($title[$default_language]);
                    $errors = $page->validatePerma($perma,$page_id);
                    if (!count($errors)){
                        $_POST['permalink'] = $perma;
                        $page->save($_POST,$page_id);
                        
                        if ($page_id){
                            $update_success = true;
                        } else {
                            $success = true;
                        }
                    }
                }
            } else if (isset($page_id) && $page_id){
                $page_data = $page->getPage($page_id);
                if (count($page_data)){
                    extract($page_data);
                } else {
                    $page_id = 0;
                }
            }
            
            $pages = $page->getPages($default_language);
        ?>
        
        <?php if (isset($update_success) && $update_success){ ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Page updated successfully
            </div>    
        <?php } ?>    
        
        <?php if (isset($success) && $success){ ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Page added successfully
            </div>    
        <?php } ?>    
    
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>
                <?php if (isset($page_id) && $page_id){ ?>
                    <p class="f_legend">Edit page</p>
                <?php } else { ?>
                    <p class="f_legend">Add new page</p>
                <?php } ?>
                
                <!--  Titles -->
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                <div class="control-group<?php if (isset($errors[1][$lang_code])){ print(" error"); } ?>">
                    <label class="control-label"><?php print($lang_name); ?> title</label>
                    <div class="controls">
                        <input type="text" name="title[<?php print($lang_code); ?>]" id="title[<?php print($lang_code); ?>]" value="<?php if (isset($title[$lang_code])) print(stripslashes($title[$lang_code])); ?>" class="span12" />
                        <?php if (isset($errors[1][$lang_code])){ ?>
                            <span class="help-inline"><?php print($errors[1][$lang_code]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <?php 
                    }
                ?>
                
                <!--  Content -->
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                <div class="control-group<?php if (isset($errors[2][$lang_code])){ print(" error"); } ?>">
                    <label class="control-label"><?php print($lang_name); ?> content</label>
                    <div class="controls">
                        <textarea name="content[<?php print($lang_code); ?>]" id="content[<?php print($lang_code); ?>]" class="span12" rows="10"><?php if (isset($content[$lang_code])) print(stripslashes($content[$lang_code])); ?></textarea>
                        <?php if (isset($errors[2][$lang_code])){ ?>
                            <span class="help-inline"><?php print($errors[2][$lang_code]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <?php 
                    }
                ?>
                
                <div class="control-group<?php if (isset($errors[3])){ print(" error"); } ?>">
                    <label class="control-label">Parent page</label>
                    <div class="controls">
                        <select name="parent_id">
                            <option value="0">-- Top --</option>
                            <?php 
                                if (count($pages)){
                                    foreach($pages as $p_id => $page_data){
                                        if ($page_data['parent_id'] == 0 && (!isset($page_id) || $page_id!=$p_id)){
                                            print("<option value='{$p_id}'");
                                            if (isset($parent_id) && $p_id == $parent_id) print(" selected='selected'");
                                            print(">{$page_data['title']}</option>");
                                        }
                                    }
                                }
                            ?>
                        </select>
                        <?php if (isset($errors[3])){ ?>
                            <span class="help-inline"><?php print($errors[3]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Visible in menu</label>
                    <div class="controls">
                        <input type="checkbox" <?php if (isset($visible) && $visible) print("checked='checked'"); ?> name="visible" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="pages_manage" />
                        <?php if (isset($page_id) && $page_id){ ?>
                            <input type="hidden" name="page_id" value="<?php print($page_id); ?>" />
                            <input type="submit" name="save_page" value="Update page" class="btn btn-primary" />
                        <?php } else { ?>
                            <input type="submit" name="save_page" value="Save page" class="btn btn-primary" />
                        <?php } ?>
                    </div>
                </div>
            </fieldset>
        </form> 
    </div>
</div>


<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Existing pages</h3>
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Parent</th>
                    <th>Visible</th>
                    <th width="1">&nbsp;</th>
                    <th width="1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (count($pages)){
                        foreach($pages as $page_id => $page_data){
                            
                            if ($page_data['parent_id'] && isset($pages[$page_data['parent_id']])){
                                $parent = "<a href=\"$baseurl/pages/{$pages[$page_data['parent_id']]['permalink']}\" target=\"_blank\">{$pages[$page_data['parent_id']]['title']}</a>";
                            } else {
                                $parent = "None";
                            }
                            
                            if ($page_data['visible']){
                                $visible = "Yes";
                            } else {
                                $visible = "No";
                            }
                            
                            print("    <tr>
                                        <td><a href=\"$baseurl/pages/{$page_data['permalink']}\" target=\"_blank\">{$page_data['title']}</a></td>
                                        <td>$parent</td>
                                        <td>$visible</td>
                                        <td><a href=\"index.php?menu=pages_manage&delete_page=$page_id\" class=\"btn\">Delete</a></td>
                                        <td><a href=\"index.php?menu=pages_manage&page_id=$page_id\" class=\"btn\">Edit</a></td>
                                    </tr>");
                        }
                    } else {
                        print("<tr><td colspan='5'>No pages added yet</td></tr>");
                    }
                
                ?>
            </tbody>
        </table>     
    </div>
</div>