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
                Configuration
            </li>
            <li>
                Text widgets / Ads
            </li>
        </ul>
    </div>
</nav>

<?php 
    
if (isset($add_widget) && $add_widget){
    $errors = $settings->validateWidget($_POST);
    if (!count($errors)){
        $settings->addWidget($_POST);
        $success = true;
        unset($widget_reference);
        unset($widget_content);
        unset($widget_logged);
    }
}

?>

<?php if (isset($success) && $success){ ?>
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert">×</a>
        New widget added successfully
    </div>    
<?php } ?>    

<div class="row-fluid">
    <div class="span12">
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>
                <p class="f_legend">Add new Text widget</p>
                
                <div class="control-group<?php if (isset($errors[1])){ print(" error"); } ?>">
                    <label class="control-label">
                        Reference Key
                        <a href="#" class="pop_over" title="Reference key" data-content="In your template you can reference this widget by using {$widget.reference_key}"><i class="icon-question-sign"></i></a>
                    </label>
                    <div class="controls">
                        <input type="text" value="<?php if (!isset($widget_id) && isset($widget_reference)){ print($widget_reference); } ?>" name="widget_reference" class="span11"/>
                        <?php if (isset($errors[1])){ ?>
                            <span class="help-inline" style="margin-top: 3px;">
                                <?php print($errors[1]); ?>
                            </span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Widget content</label>
                    <div class="controls">

                        <textarea name="widget_content" class="span11" rows="5"><?php if (!isset($widget_id) && isset($widget_content)) print($widget_content); ?></textarea>
                        <?php if (isset($errors[2])){ ?>
                            <span class="help-inline" style="margin-top: 3px;"><?php print($errors[2]); ?></span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Show to logged users?</label>
                    <div class="controls">
                        <input type="checkbox" class="switch" name="widget_logged" <?php if (!isset($widget_id) && isset($widget_logged) && $widget_logged) print('checked="checked"'); ?>/>            
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="settings_widgets" />
                        <input type="submit" class="btn btn-primary" name="add_widget" value="Add this widget"/>            
                    </div>
                </div>
                
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
    
        <h3 class="heading">Existing widgets</h3>
        
        <?php 
            if (isset($widget_delete) && $widget_delete && isset($widget_id) && $widget_id){
                if (!defined("DEMO") || !DEMO){
                    $settings->deleteWidget($widget_id);
                }
            }
            
            if (isset($widget_update) && $widget_update && isset($widget_id) && $widget_id){
                if (!defined("DEMO") || !DEMO){
                    $update_errors = $settings->validateWidget($_POST,$widget_id);
                    if (!count($update_errors)){
                        $settings->updateWidget($_POST,$widget_id);
                        $update_success = true;
                    } 
                }
            }
        
            $widgets = $settings->getWidgets();
        ?>
        
        <?php 
            if (isset($update_errors) && count($update_errors)){
        ?>
        
            <div class="alert alert-error">
                <a class="close" data-dismiss="alert">×</a>
                <?php 
                    print(implode("<br />",$update_errors));
                ?>
            </div>    
        
        <?php 
            }
        ?>
        
        <?php if (isset($update_success) && $update_success){ ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Widget updated successfully
            </div>    
        <?php } ?>    
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="1">Reference</th>
                    <th width="*">Content</th>
                    <th width="1">Logged</th>
                    <th width="1">&nbsp;</th>
                    <th width="1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (!count($widgets)){
                        print("<tr><td colspan=\"5\">No widgets added yet</td></tr>");
                    } else {
                        foreach($widgets as $reference => $widget_data){
                            
                            if (defined("DEMO") && DEMO){
                                $widget_data['content'] = "HIDDEN IN DEMO";
                            }
                            
                            print("    <form action=\"index.php\" method=\"post\">
                                    <input type=\"hidden\" name=\"menu\" value=\"settings_widgets\" />
                                    <input type=\"hidden\" name=\"widget_id\" value=\"{$widget_data['id']}\" />
                                    <tr id=\"row_{$widget_data['id']}\">
                                        <td><input name=\"widget_reference\" type=\"text\" class=\"input-medium\" value=\"{$reference}\" /></td>
                                        <td><textarea name=\"widget_content\" class=\"span12\" rows=\"5\" />{$widget_data['content']}</textarea></td>
                                        <td><input type=\"checkbox\" name=\"widget_logged\" class=\"switch\" "); 
                            if ($widget_data['logged']){
                                print("checked=\"checked\"");
                            }
                            print("        /></td>
                                        <td><input type=\"submit\" name=\"widget_delete\" class=\"btn\" value=\"Delete\" /></td>
                                        <td><input type=\"submit\" name=\"widget_update\" class=\"btn btn-primary\" value=\"Update\" /></td>
                                    </tr>
                                    </form>");
                        }    
                    }                
                ?>
            </tbody>
        </table>
    </div>
</div>