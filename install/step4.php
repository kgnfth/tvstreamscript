<ul id="simple_wizard-titles" class="stepy-titles clearfix">
	<li id="simple_wizard-title-0">
		<div>mySQL details</div>
		<span>Enter your database info</span>
		<span class="stepNb">1</span>
	</li>
	
	<li id="simple_wizard-title-1">
		<div>Site info</div>
		<span>Paths, urls, etc.</span>
		<span class="stepNb">2</span>
	</li>
	
	<li id="simple_wizard-title-3" class="current-step">
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
		<legend class="hide">Admin user</legend>
		
		<div class="formSep control-group<?php if (isset($step4_errors[1])){ print(" error"); }?>">
			<label class="control-label" for="admin_user">Admin username:</label>
			<div class="controls">
				<input type="text" id="admin_user" class="span6" name="admin_user" <?php if (isset($_POST['admin_user']) && $_POST['admin_user']){ print("value=\"{$_POST['admin_user']}\""); } elseif (isset($_SESSION['admin_user'])){ print("value=\"{$_SESSION['admin_user']}\""); } ?>>
				<?php if (isset($step4_errors[1])){ ?>
					<span class="help-inline"><?php print($step4_errors[1]); ?></span>
				<?php } ?>
			</div>
		</div>
		
		<div class="formSep control-group<?php if (isset($step4_errors[2])){ print(" error"); }?>">
			<label class="control-label" for="admin_pass1">Admin password:</label>
			<div class="controls">
				<input type="password" id="admin_pass1" class="span6" name="admin_pass1" <?php if (isset($_POST['admin_pass1']) && $_POST['admin_pass1']){ print("value=\"{$_POST['admin_pass1']}\""); } ?>>
				<?php if (isset($step4_errors[2])){ ?>
					<span class="help-inline"><?php print($step4_errors[2]); ?></span>
				<?php } ?>
			</div>
		</div>
		
		<div class="formSep control-group<?php if (isset($step4_errors[3])){ print(" error"); }?>">
			<label class="control-label" for="admin_pass2">Password confirmation:</label>
			<div class="controls">
				<input type="password" id="admin_pass2" class="span6" name="admin_pass2" <?php if (isset($_POST['admin_pass2']) && $_POST['admin_pass2']){ print("value=\"{$_POST['admin_pass2']}\""); } ?>>
				<?php if (isset($step4_errors[3])){ ?>
					<span class="help-inline"><?php print($step4_errors[3]); ?></span>
				<?php } ?>
			</div>
		</div>


		<p class="simple_wizard-buttons" id="simple_wizard-buttons-0">
			<input type="hidden" name="menu" value="install" />
			<input type="hidden" name="step" value="4" />
			<a id="simple_wizard-back-1" href="index.php?menu=install&step=2" class="btn button-back">&laquo; Back</a>
			<input type="submit" name="process" class="btn btn-inverse button-next" value="Next &raquo;" <?php if (isset($errors) && count($errors)){ print("disabled='disabled'"); } ?> />
		</p>
	</fieldset>
</form>