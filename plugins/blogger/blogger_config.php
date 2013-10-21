<?php
if (!defined("IN_SCRIPT")){
    print("<script> window.location = 'index.php'; </script>");
    exit();
}

require_once($basepath."/plugins/blogger/includes/blogger.class.php");


if (isset($_POST['blogger_update']) && $_POST['blogger_update']){
    
    if (isset($_POST['blogger_license_key']) && $_POST['blogger_license_key']){
        $blogger = new Blogger($_POST['blogger_license_key']);
        if ($blogger->valid){
        	$settings->addSetting("blogger_license_key",$_POST['blogger_license_key']);
        } else {
        	$errors[1] = "Invalid Blogger plugin license key";
        }
    }
}

$blogger_license_key = $settings->getSetting("blogger_license_key");
if (empty($blogger_license_key)){
    $blogger_license_key = 'http://tvstreamscript.tk';
} else {
	
	$test_blogger = new Blogger($blogger_license_key);
	if (!$test_blogger->valid){
		
		$blogger_license_key = '';
	}
	
	unset($test_blogger);
}

?>

<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                <a href="index.php?menu=plugins">Plugins</a>
            </li>
            <li>
                Blogger
            </li>
            <li>
                Configuration
            </li>
        </ul>
    </div>
</nav>


<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Blogger plugin settings</h3>
        <?php if (!$blogger_license_key || empty($blogger_license_key)){ ?>
            <div class="alert alert-error">
                You haven't entered your Blogger plugin license key yet. The plugin won't work until you configure it properly
            </div>
        <?php } ?>
    
        <form action="index.php" method="post" class="form-horizontal well">
            <input type="hidden" name="menu" value="plugin" />
            <input type="hidden" name="plugin" value="blogger" />
            <input type="hidden" name="plugin_menu" value="blogger_config" />
            
            <fieldset>
                <div class="control-group<?php if (isset($errors[1])){ print(" error"); } ?>">
                    <label class="control-label span3">License key</label>
                    <div class="controls span9">
                    	<?php if (defined("DEMO") && DEMO){ $blogger_license_key = "HIDDEN IN DEMO"; } ?>
                        <input type="text" name="blogger_license_key" id="blogger_license_key" value="<?php if (isset($blogger_license_key) && $blogger_license_key){ print($blogger_license_key); } elseif (isset($_POST['blogger_license_key'])) print($_POST['blogger_license_key']); ?>" class="span12" />
                        <?php if (isset($errors[1])){ ?>
                            <span class="help-inline"><?php print($errors[1]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label span3">&nbsp;</label>
                    <div class="controls span9">
                        <input type="submit" class="btn btn-primary" value="Update configuration" name="blogger_update" />
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Set up your cronjob</h3>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <p>The blogger plugin is able to fetch new post from the RSS feeds you add through the interface. In order to do this you have to add the following line to your cronjobs:
            <br /><br /><br />
            <fieldset>
                <div class="control-group">
                    <label class="control-label span3">Cronjob command:</label>
                    <div class="controls span9">
                        <input type="text" value="*/5 * * * *     cd <?php print($basepath); ?>/plugins/blogger/ && php grabber.php" class="span12" />
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>