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
                Manage plugins
            </li>
        </ul>
    </div>
</nav>
<?php

$allplugins = $plugins->getAllPlugins();

if (isset($deactivate) && $deactivate){
    $thisplugin = 0;
    foreach($allplugins as $k => $v){
        if ($v['dirname']==$deactivate){
            $thisplugin = $allplugins[$k];
            break;
        }
    }
    
    if ($thisplugin){
?>


<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">                    
            <h3>Removing <?php print($thisplugin['name']); ?>...</h3>
        </div> <!-- End .content-box-header -->
                
    <div class="content-box-content">
        <?php
            if ($thisplugin['uninstall_url']){
                if (file_exists("../plugins/{$thisplugin['dirname']}/{$thisplugin['uninstall_url']}")){
                    include("../plugins/{$thisplugin['dirname']}/{$thisplugin['uninstall_url']}");
                }
            }
            
            $plugins->deletePlugin($thisplugin);            
        ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            Plugin removed successfully
        </div>
    </div>
</div>

<?php 
        $plugins->deletePlugin($thisplugin);    
    }
}

if (isset($activate) && $activate){
    $thisplugin = 0;
    foreach($allplugins as $k => $v){
        if ($v['dirname']==$activate){
            $thisplugin = $allplugins[$k];
            break;
        }
    }
    
    if ($thisplugin){
?>



<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">                    
            <h3>Installing <?php print($thisplugin['name']); ?>...</h3>
        </div> <!-- End .content-box-header -->
                
    <div class="content-box-content">
        <?php
            if ($thisplugin['install_url']){
                if (file_exists("../plugins/{$thisplugin['dirname']}/{$thisplugin['install_url']}")){
                    include("../plugins/{$thisplugin['dirname']}/{$thisplugin['install_url']}");
                }
            }
            
            $plugins->deletePlugin($thisplugin);
            $plugins->activatePlugin($thisplugin);            
        ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            Plugin installation was successful
        </div>
    </div>
</div>

<?php
    }
}


$activeplugins = $plugins->getInstalledPlugins();
?>

<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">    
            <h3>Available plugins <small>Click "Activate" next to each plugin to enable them</small></h3>
        </div>

                
        <?php
            if (count($allplugins)==0){
                print("No available plugins found");
            } else {
                print("<table class=\"table table-striped table-bordered\">");
                $foundone = 0;
                foreach($allplugins as $key => $plugin){
                    if (!array_key_exists($plugin['dirname'],$activeplugins)){
                        $foundone = 1;
                        print("    <tr>
                                    <td>
                                        <strong>{$plugin['name']}</strong>");
                        if ($plugin['author'] && $plugin['author_url']){
                            print(" (by <a href='{$plugin['author_url']}' target='_blank'>{$plugin['author']}</a>)");
                        } elseif ($plugin['author']){
                            print(" (by {$plugin['author']})");
                        }
                        print("        </td>
                                    <td>{$plugin['description']}</td>
                                    <td>
                                        <a href=\"index.php?menu=plugins&activate={$plugin['dirname']}\" class=\"btn\">Activate plugin</a>
                                    </td>
                                </tr>");
                    }
                }
                print("</table>");
                
                if (!$foundone){
                    print("No available plugins found");
                }
            }
        ?>
    </div>    
</div>


<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">                    
            <h3>Installed plugins</h3>
        </div>
        
        <?php 
            if (!count($activeplugins)){
                print("No active plugins found");
            } else {
                print("<table class=\"table table-striped table-bordered\">");
                foreach($activeplugins as $key => $plugin){
                        $foundone = 1;
                        print("    <tr>
                                    <td>
                                        <strong>{$plugin['name']}</strong>");
                        if ($plugin['author'] && $plugin['author_url']){
                            print(" (by <a href='{$plugin['author_url']}' target='_blank'>{$plugin['author']}</a>)");
                        } elseif ($plugin['author']){
                            print(" (by {$plugin['author']})");
                        }                        
                        print("        </td>
                                    <td>{$plugin['description']}</td>
                                    <td>
                                        <a href=\"index.php?menu=plugins&deactivate={$plugin['dirname']}\" class=\"btn\">Deactivate plugin</a>
                                    </td>
                                </tr>");
                }
                print("</table>");
            }
        
        ?>
    </div>
    
</div>