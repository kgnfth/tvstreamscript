<?php 
session_start();
require_once("../vars.php");
require_once("../includes/settings.class.php");

$settings = new Settings();

$captchas = $settings->getSetting("captchas");
        
if (is_array($captchas) && empty($captchas)){
	$captchas = 0;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Gebo Admin Panel - Login Page</title>
    
        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
        <!-- theme color-->
            <link rel="stylesheet" href="css/blue.css" />
        <!-- tooltip -->    
			<link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <!-- main styles -->
            <link rel="stylesheet" href="css/style.css" />
    
        <!-- Favicons and the like (avoid using transparent .png) -->
            <link rel="shortcut icon" href="favicon.ico" />
            <link rel="apple-touch-icon-precomposed" href="icon.png" />
    
        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
    
        <!--[if lte IE 8]>
            <script src="js/ie/html5.js"></script>
			<script src="js/ie/respond.min.js"></script>
        <![endif]-->
		
    </head>
    <body class="login_page" style="max-width:100%;">
		
		<div class="login_box">
			
			<form action="index.php" method="post" id="login_form">
				<div class="top_b">Sign in to TVstreamScript admin</div>    
				<div class="cnt_b">
					<?php 
						if (isset($_REQUEST['login_error'])){
					?>
					
					<div class="formRow">
						<span style="color: #aa0000">
							<?php print($_REQUEST['login_error']); ?><br /><br />
						</span>
					</div>
					
					<?php 
						}
					?>
				
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span><input type="text" id="username" name="adminuser"  placeholder="Username" value="" />
						</div>
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span><input type="password" id="password" name="adminpass" placeholder="Password" value="" />
						</div>
					</div>
					<?php 
						if ($captchas){
					?>
					
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-eye-open"></i></span><input type="text" id="captcha" name="captcha" placeholder="Enter the word below" value="" />
							<br />
							<img src="<?php print($baseurl); ?>/includes/captcha/captcha.php?<?php print(rand(0,1000)); ?>" style="border: 1px solid #e2e2e2; margin-right: 5px;  width: 217px;  margin-top: 5px;" class="pull-right" />
							<div class="clearfix"></div>
						</div>
					</div>
					
					<?php 
						}
					?>
					
				</div>
				<div class="btm_b clearfix">
					<?php 
						if (isset($_GET['menu'])){
					?>
						<input type="hidden" name="menu" value="<?php print($_GET['menu']);?>" />
					<?php 
						}
					?>
					<button class="btn btn-inverse pull-right" type="submit" name="dologin">Sign In</button>
				</div>  
			</form>
		</div>
        
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.actual.min.js"></script>
        <script src="lib/validation/jquery.validate.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){
                
				//* boxes animation
				form_wrapper = $('.login_box');
                $('.linkform a,.link_reg a').on('click',function(e){
					var target	= $(this).attr('href'),
						target_height = $(target).actual('height');
					$(form_wrapper).css({
						'height'		: form_wrapper.height()
					});	
					$(form_wrapper.find('form:visible')).fadeOut(400,function(){
						form_wrapper.stop().animate({
                            height	: target_height
                        },500,function(){
                            $(target).fadeIn(400);
                            $('.links_btm .linkform').toggle();
							$(form_wrapper).css({
								'height'		: ''
							});	
                        });
					});
					e.preventDefault();
				});
				
				//* validation
				$('#login_form').validate({
					onkeyup: false,
					errorClass: 'error',
					validClass: 'valid',
					rules: {
						username: { required: true, minlength: 3 },
						password: { required: true, minlength: 3 }
					},
					highlight: function(element) {
						$(element).closest('div').addClass("f_error");
					},
					unhighlight: function(element) {
						$(element).closest('div').removeClass("f_error");
					},
					errorPlacement: function(error, element) {
						$(element).closest('div').append(error);
					}
				});
            });
        </script>
    </body>
</html>
