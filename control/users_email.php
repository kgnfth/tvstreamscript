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
                <a href="index.php?menu=users">Users</a>
            </li>
            <li>
                Email users
            </li>
        </ul>
    </div>
</nav>

<?php 

$email_settings = $settings->getSetting("email_settings", true);

if (!$email_settings){

?>

<div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">×</button>
    You haven't configured your email settings yet. <a href="index.php?menu=settings_accounts">Click here</a> to add your details
</div>    


<?php 

} else {
    
?>

    <div class="row-fluid">
        <div class="span12">
        
        <?php 
            
            $users = $user->getAllUsers();
            
            if (isset($send_email) && $send_email){
                $errors = array();
                
                if (!isset($_POST['user_list']) || !$_POST['user_list']){
                    $errors[1] = "Please select at least one user to send email to";
                }
                
                if (!isset($_POST['email_subject']) || !$_POST['email_subject']){
                    $errors[2] = "Please enter the subject of the email";
                }
                
                if (!isset($_POST['email_body']) || !$_POST['email_body']){
                    $errors[3] = "Please enter the body of the email";
                }
                
                if (!count($errors)){
                    $user_names = explode(",",$_POST['user_list']);
                    $foundone = false;
                    
                    foreach($user_names as $key => $username){
                        if ($username){
                            $mail_user = $user->getByUsername($username);
                            
                            if ($mail_user){
                                
                                if (file_exists($basepath."/language/".$mail_user['language']."/email_template.html")){
                                    $mailcontent = file_get_contents($basepath."/language/".$mail_user['language']."/email_template.html");
                                } elseif (file_exists($basepath."/language/".$default_language."/email_template.html")){
                                    $mailcontent = file_get_contents($basepath."/language/".$default_language."/email_template.html");
                                } else {
                                    $mailcontent = "#mailcontent#";    
                                }
                                
                                $mailcontent = str_replace("#mailcontent#",nl2br($email_body),$mailcontent);
                                $mailcontent = str_replace("#sitename#",$sitename,$mailcontent);
                                $mailcontent = str_replace("#baseurl#",$baseurl,$mailcontent);
                                
                                if (isset($email_settings['smtp']) && $email_settings['smtp']){
                                    // phpmailer
                                    $mail = new PHPMailer();
                                    
                                    $mail->IsSMTP(); 
                                    $mail->SMTPDebug  = 1;
                                    $mail->SMTPAuth   = true;
                                    if ($email_settings['smtp_security']=="ssl"){
                                        $mail->SMTPSecure = "ssl";
                                    }
                                    
                                    $mail->Host       = $email_settings['smtp_host'];
                                    $mail->Port       = $email_settings['smtp_port'];
                                    $mail->Username   = $email_settings['smtp_user'];
                                    $mail->Password   = $email_settings['smtp_password'];
    
                                    $mail->Charset = "UTF-8";
                                    $mail->SetFrom($email_settings['sender_email'], $email_settings['sender_name']);        
                                    $mail->IsHTML(true);
                                    
                                    $mail->Subject    = utf8_decode($email_subject);
                                    $mail->Body = $mailcontent;
                                    
                                    $mail->AddAddress($mail_user['email']);
                                    $mail->Send();
                                } else {
                                    // mail
                                    
                                    $headers = "Content-type: text/html\nFrom: {$email_settings['sender_name']} <{$email_settings['sender_email']}>";
                                    @mail($mail_user['email'],$email_subject,$mailcontent,$headers);
                                }
                                
                                $foundone = true;
                                $success = 1;
                            }
                        }
                    }
                    
                    if (!$foundone){
                        $errors[1] = "Please select at least one user to send email to";
                    }
                }
            }
            
        ?>
    
    
        <?php if (isset($success)){ ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Email sent
            </div>
        <?php } ?>    
        
            <form action="index.php" method="post" class="form-horizontal well">
                <fieldset>
                    <div class="control-group<?php if (isset($errors[1])){ print(" error"); } ?>">
                        <label class="control-label">User</label>
                        <div class="controls">
                            <div class="tagHandler">
                                <ul id="array_tag_handler"></ul>
                                <input type="hidden" id="user_list" name="user_list" <?php if (isset($user_list)) print("value='$user_list'"); ?>/>
                            </div>
                            <?php if (isset($errors[1])){ ?>
                                <span class="help-inline"><?php print($errors[1]); ?></span>
                            <?php } ?>
                            
                        </div>
                    </div>
                    
                    <div class="control-group<?php if (isset($errors[2])){ print(" error"); } ?>">
                        <label class="control-label">Message subject</label>
                        <div class="controls">
                            <input class="span12" type="text" name="email_subject" <?php if (isset($email_subject) && $email_subject) print("value='$email_subject'"); ?> />
                            <?php if (isset($errors[2])){ ?>
                                <span class="help-inline"><?php print($errors[2]); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="control-group<?php if (isset($errors[3])){ print(" error"); } ?>">
                        <label class="control-label">Message body</label>
                        <div class="controls">
                            <textarea class="span12" name="email_body" rows="10"><?php if (isset($email_body) && $email_body) print($email_body); ?></textarea>
                            <?php if (isset($errors[3])){ ?>
                                <span class="help-inline"><?php print($errors[3]); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">&nbsp;</label>
                        <div class="controls">
                            <input type="hidden" name="menu" value="users_email" />
                            <?php if (!defined("DEMO") || !DEMO) { ?>
                            <input type="submit" name="send_email" value="Email user" class="btn btn-primary" />
                            <?php } else { ?>
                            <input type="button" name="send_email" value="DISABLED IN DEMO" disabled="disabled" class="btn btn-primary" />
                            <?php } ?>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

<?php 
}
?>
