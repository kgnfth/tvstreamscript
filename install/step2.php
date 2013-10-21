<ul id="simple_wizard-titles" class="stepy-titles clearfix">
	<li id="simple_wizard-title-0">
		<div>mySQL details</div>
		<span>Enter your database info</span>
		<span class="stepNb">1</span>
	</li>
	
	<li id="simple_wizard-title-1" class="current-step">
		<div>Site info</div>
		<span>Paths, urls, etc.</span>
		<span class="stepNb">2</span>
	</li>
	
	<li id="simple_wizard-title-3">
		<div>Admin user</div>
		<span>Your first user</span>
		<span class="stepNb">3</span>
	</li>
	
	<li id="simple_wizard-title-4">
		<div>All done</div>
		<span>Enjoy your site</span>
		<span class="stepNb">4</span>
	</li>
</ul>
<br />								
<form method="post" class="stepy-wizzard form-horizontal" id="simple_wizard">
	<fieldset title="" class="step" id="simple_wizard-step-0">
		<legend class="hide">Enter your site details</legend>
		
		
		<div class="formSep control-group<?php if (isset($step2_errors[1])){ print(" error"); }?>">
			<label class="control-label" for="site_title">Site title:</label>
			<div class="controls">
				<input type="text" id="mysql_user" class="span6" name="site_title" <?php if (isset($_POST['site_title']) && $_POST['site_title']){ print("value=\"{$_POST['site_title']}\""); } elseif (isset($_SESSION['site_title'])){ print("value=\"{$_SESSION['site_title']}\""); } ?>>
				<?php if (isset($step2_errors[1])){ ?>
					<span class="help-inline"><?php print($step2_errors[1]); ?></span>
				<?php } ?>
			</div>
		</div>
		
		<div class="formSep control-group<?php if (isset($step2_errors[2])){ print(" error"); }?>">
			<label class="control-label" for="site_url">Site url:</label>
			<div class="controls">
				<input type="text" id="site_url" class="span6" name="site_url" <?php if (isset($_POST['site_url']) && $_POST['site_url']){ print("value=\"{$_POST['site_url']}\""); } elseif (isset($_SESSION['site_url'])){ print("value=\"{$_SESSION['site_url']}\""); } ?>>
				<?php if (isset($step2_errors[2])){ ?>
					<span class="help-inline"><?php print($step2_errors[2]); ?></span>
				<?php } ?>
			</div>
		</div>
		
		<div class="formSep control-group<?php if (isset($step2_errors[3])){ print(" error"); }?>">
			<label class="control-label" for="site_path">Root path:</label>
			<div class="controls">
				<input type="text" id="site_path" class="span6" name="site_path" <?php if (isset($_POST['site_path']) && $_POST['site_path']){ print("value=\"{$_POST['site_path']}\""); } elseif (isset($_SESSION['site_path'])){ print("value=\"{$_SESSION['site_path']}\""); } ?>>
				<?php if (isset($step2_errors[3])){ ?>
					<span class="help-inline"><?php print($step2_errors[3]); ?></span>
				<?php } ?>
			</div>
		</div>
		

		<p class="simple_wizard-buttons" id="simple_wizard-buttons-0">
			<input type="hidden" name="menu" value="install" />
			<input type="hidden" name="step" value="2" />
			<a id="simple_wizard-back-1" href="index.php?menu=install&step=1" class="btn button-back">&laquo; Back</a>
			<input type="submit" name="process" class="btn btn-inverse button-next" value="Next &raquo;" <?php if (isset($errors) && count($errors)){ print("disabled='disabled'"); } ?> />
		</p>
	</fieldset>
</form>