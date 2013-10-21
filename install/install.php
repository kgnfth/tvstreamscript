<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">TVstreamScript installation - Step 1</h3>
		<br /><br />
		<div class="row-fluid">
			<div class="span1"></div>
				<div class="span10">
					<?php 
						if (isset($_GET['step'])){
							$step = $_GET['step'];
						} elseif (isset($_POST['step'])){
							$step = $_POST['step'];
						} else {
							$step = 1;
						}
						
						if (!in_array($step,array(1,2,3,4,5))){
							$step = 1;
						}
						
						if (isset($_POST['process'])){
							
							if ($step == 1){
								// checking mysql
								$step1_errors = array();
								if (!isset($_POST['mysql_user']) || !$_POST['mysql_user']){
									$step1_errors[1] = "Please enter your mysql username";
								}
								
								if (!isset($_POST['mysql_pass']) || !$_POST['mysql_pass']){
									$step1_errors[2] = "Please enter your mysql password";
								}
								
								if (!isset($_POST['mysql_name']) || !$_POST['mysql_name']){
									$step1_errors[3] = "Please enter your mysql database name";
								}
								
								if (!isset($_POST['mysql_host']) || !$_POST['mysql_host']){
									$step1_errors[4] = "Please enter your mysql database host";
								}
								
								if (!count($step1_errors)){
									$conn = @mysql_connect($_POST['mysql_host'],$_POST['mysql_user'],$_POST['mysql_pass']);
									if ($conn){
										mysql_select_db($_POST['mysql_name'],$conn) or $step1_errors[3]="Can't select database";
										if (!count($step1_errors)){
											$_SESSION['mysql_host'] = $_POST['mysql_host'];
											$_SESSION['mysql_user'] = $_POST['mysql_user'];
											$_SESSION['mysql_pass'] = $_POST['mysql_pass'];
											$_SESSION['mysql_name'] = $_POST['mysql_name'];
											
											$step = 2;
										} 
									} else {
										$step1_errors[4] = "Can't connect to database";
									}
								}
								
							} elseif ($step == 2){
								$step2_errors = array();
								
								if (!isset($_POST['site_title']) || !$_POST['site_title']){
									$step2_errors[1] = "Please enter your site's title";
								}
								
								if (!isset($_POST['site_url']) || !$_POST['site_url']){
									$step2_errors[2] = "Please enter your site's url";
								}
								
								if (!isset($_POST['site_path']) || !$_POST['site_path']){
									$step2_errors[3] = "Please enter your site's root path";
								}
								
								if (!count($step2_errors)){
									$_SESSION['site_title'] = $_POST['site_title'];
									$_SESSION['site_url'] = $_POST['site_url'];
									$_SESSION['site_path'] = $_POST['site_path'];
									
									$step = 4;
								}
							} elseif ($step == 3){
								$step3_errors = array();
								
								if (!isset($_POST['license_key']) || !$_POST['license_key']){
									$step3_errors[1] = "Please enter your license key";
								} else {
                                    $_SESSION['license_key'] = $_POST['license_key'];
								}
							} elseif ($step == 4){
								$step4_errors = array();
								
								if (!isset($_POST['admin_user']) || !$_POST['admin_user']){
									$step4_errors[1] = "Please enter the admin username";
								} else {
									preg_match("/[^a-zA-Z0-9_]/i",$_POST['admin_user'],$matches);
									if (count($matches)){
										$step4_errors[1] = "Admin username contains invalid characters";
									} elseif (strlen($_POST['admin_user'])<5){
										$step4_errors[1] = "Admin username must be at least 5 characters long";
									}
								}
								
								if (!isset($_POST['admin_pass1']) || !$_POST['admin_pass1']){
									$step4_errors[2] = "Please enter the admin password";
								} elseif (strlen($_POST['admin_pass1'])<5){
									$step4_errors[2] = "Admin password must be at least 5 characters long";
								}
								
								if (!isset($_POST['admin_pass2']) || !$_POST['admin_pass2']){
									$step4_errors[3] = "Please confirm the admin password";
								}
								
								if (isset($_POST['admin_pass1']) && isset($_POST['admin_pass2']) && $_POST['admin_pass1']!=$_POST['admin_pass2']){
									$step4_errors[3] = "Password confirmation doesn't match";
								}
								
								if (!count($step4_errors)){
									$_SESSION['admin_pass'] = $_POST['admin_pass1'];
									$_SESSION['admin_user'] = $_POST['admin_user'];
									$step = 5;
								}
							}
							
							
							
						} 
						
						if ($step == 1){
							// checking write access
							
							$dir = "../cachefiles";
							$perms = @base_convert(fileperms($dir), 10, 8);
							$errors = array();
							if ((substr_count($perms,"777")==0) && ($perms!=666)){
								$errors[1] = "Please make sure the <strong>/cachefiles</strong> directory is writeable";
							}
							
							$dir = "../thumbs";
							$perms = @base_convert(fileperms($dir), 10, 8);
							$errors = array();
							if ((substr_count($perms,"777")==0) && ($perms!=666)){
								$errors[1] = "Please make sure the <strong>/thumbs</strong> directory is writeable";
							}
							
							$dir = "../vars.php";
							$perms = @base_convert(fileperms($dir), 10, 8);
							$errors = array();
							if (substr_count($perms,"777")==0 && substr_count($perms,"666")==0){
								$errors[1] = "Please make sure the <strong>/vars.php</strong> is writeable";
							}
						}
						
						if ($step == 2){
							// checking if variables exists
							if (!isset($_SESSION['mysql_host']) || !isset($_SESSION['mysql_user']) || !isset($_SESSION['mysql_pass']) || !isset($_SESSION['mysql_name'])){
								$step = 1;
							} else {
								if (isset($_SERVER['DOCUMENT_ROOT'])){
									$_SESSION['site_path'] = $_SERVER['DOCUMENT_ROOT'];
								} elseif (isset($_SERVER['SCRIPT_FILENAME'])){
									$_SESSION['site_path'] = str_replace("/install/index.php","",$_SERVER['SCRIPT_FILENAME']);
								}	
								
								if (isset($_SERVER['HTTP_HOST'])){
									$_SESSION['site_url'] = "http://".$_SERVER['HTTP_HOST'];
									if (isset($_SERVER['SCRIPT_NAME'])){
										$_SESSION['site_url'] .= str_replace("/install/index.php","",$_SERVER['SCRIPT_NAME']);
									}
								}
							}
						}
						
						if ($step == 3){
							
							if (!isset($_SESSION['mysql_host']) || !isset($_SESSION['mysql_user']) || !isset($_SESSION['mysql_pass']) || !isset($_SESSION['mysql_name'])){
								$step = 1;
							} elseif  (!isset($_SESSION['site_url']) || !isset($_SESSION['site_title']) || !isset($_SESSION['site_path'])){
								$step = 2;
							}
						}
						
						if ($step == 4){
							if (!isset($_SESSION['mysql_host']) || !isset($_SESSION['mysql_user']) || !isset($_SESSION['mysql_pass']) || !isset($_SESSION['mysql_name'])){
								$step = 1;
							} elseif  (!isset($_SESSION['site_url']) || !isset($_SESSION['site_title']) || !isset($_SESSION['site_path'])){
								$step = 2;
							} 
						}
						
						if ($step == 5){
							if (!isset($_SESSION['mysql_host']) || !isset($_SESSION['mysql_user']) || !isset($_SESSION['mysql_pass']) || !isset($_SESSION['mysql_name'])){
								$step = 1;
							} elseif  (!isset($_SESSION['site_url']) || !isset($_SESSION['site_title']) || !isset($_SESSION['site_path'])){
								$step = 2;
							} elseif  (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_pass'])){
								$step = 4;
							}
						}
						
						require_once("step".$step.".php");
					
					?>			
				</div>
			</div>
		</div>
</div>