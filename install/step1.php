<ul id="simple_wizard-titles" class="stepy-titles clearfix">
	<li id="simple_wizard-title-0" class="current-step">
		<div>mySQL details</div>
		<span>Enter your database info</span>
		<span class="stepNb">1</span>
	</li>
	
	<li id="simple_wizard-title-1">
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
<form method="post" class="stepy-wizzard form-horizontal" id="simple_wizard" >
	<fieldset title="" class="step" id="simple_wizard-step-0">
		<legend class="hide">Enter your database info</legend>
		
		<?php 
			if (isset($errors) && count($errors)){
		?>
		<div class="alert alert-error">
		  	<button type="button" class="close" data-dismiss="alert">×</button>
		  	<?php 
		  		print(implode("<br />",$errors));
		  	?>
		</div>
		<?php 
			} else {
		?>
		
		<div class="alert">
		  	<button type="button" class="close" data-dismiss="alert">x</button>
		  	You are about to perform a fresh installation. Please keep in mind the install process will remove all data from the database.
		</div>
		
		<?php 	
			}		
		?>
		
		<div class="formSep control-group<?php if (isset($step1_errors[1])){ print(" error"); }?>">
			<label class="control-label" for="mysql_user">mySQL username:</label>
			<div class="controls">
				<input type="text" id="mysql_user" class="span6" name="mysql_user" <?php if (isset($_POST['mysql_user']) && $_POST['mysql_user']){ print("value=\"{$_POST['mysql_user']}\""); } elseif (isset($_SESSION['mysql_user'])){ print("value=\"{$_SESSION['mysql_user']}\""); } ?>>
				<?php if (isset($step1_errors[1])){ ?>
					<span class="help-inline"><?php print($step1_errors[1]); ?></span>
				<?php } ?>
			</div>
		</div>
		
		<div class="formSep control-group<?php if (isset($step1_errors[2])){ print(" error"); }?>">
			<label class="control-label" for="mysql_pass">mySQL password:</label>
			<div class="controls">
				<input type="text" id="mysql_pass" class="span6" name="mysql_pass" <?php if (isset($_POST['mysql_pass']) && $_POST['mysql_pass']){ print("value=\"{$_POST['mysql_pass']}\""); } elseif (isset($_SESSION['mysql_pass'])){ print("value=\"{$_SESSION['mysql_pass']}\""); } ?>>
				<?php if (isset($step1_errors[2])){ ?>
					<span class="help-inline"><?php print($step1_errors[2]); ?></span>
				<?php } ?>
			</div>
		</div>
		
		<div class="formSep control-group<?php if (isset($step1_errors[3])){ print(" error"); }?>">
			<label class="control-label" for="mysql_name">Database name:</label>
			<div class="controls">
				<input type="text" id="mysql_name" class="span6" name="mysql_name" <?php if (isset($_POST['mysql_name']) && $_POST['mysql_name']){ print("value=\"{$_POST['mysql_name']}\""); } elseif (isset($_SESSION['mysql_name'])){ print("value=\"{$_SESSION['mysql_name']}\""); } ?>>
				<?php if (isset($step1_errors[3])){ ?>
					<span class="help-inline"><?php print($step1_errors[3]); ?></span>
				<?php } ?>
			</div>
		</div>
		
		<div class="formSep control-group<?php if (isset($step1_errors[4])){ print(" error"); }?>">
			<label class="control-label" for="mysql_host">Database host:</label>
			<div class="controls">
				<input type="text" id="mysql_host" class="span6" name="mysql_host" <?php if (isset($_POST['mysql_host']) && $_POST['mysql_host']){ print("value=\"{$_POST['mysql_host']}\""); } elseif (isset($_SESSION['mysql_host'])){ print("value=\"{$_SESSION['mysql_host']}\""); } ?>>
				<?php if (isset($step1_errors[4])){ ?>
					<span class="help-inline"><?php print($step1_errors[4]); ?></span>
				<?php } ?>
			</div>
		</div>

		<p class="simple_wizard-buttons" id="simple_wizard-buttons-0">
			<input type="hidden" name="menu" value="install" />
			<input type="hidden" name="step" value="1" />
			<input type="submit" name="process" class="btn btn-inverse button-next" value="Next &raquo;" <?php if (isset($errors) && count($errors)){ print("disabled='disabled'"); } ?> />
		</p>
	</fieldset>
</form>