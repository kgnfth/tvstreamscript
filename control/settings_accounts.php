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
                Manage accounts
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span6">
        <h3 class="heading">Google Analytics</h3>
        
        <?php 
            if (isset($remove_google) && $remove_google){
                if (!defined("DEMO") || !DEMO){
                    $settings->deleteSetting("analytics");
                }
            }
        
            if (isset($save_google) && $save_google && isset($_POST['google'])){
                if (!defined("DEMO") || !DEMO){
                    $ga_errors = array();
                    
                    $google = $_POST['google'];
                    
                    if (!isset($google['username']) || !$google['username']){
                        $ga_errors[1] = "Please enter your analytics username";
                    }
                    
                    if (!isset($google['password']) || !$google['password']){
                        $ga_errors[2] = "Please enter your analytics password";
                    }
                    
                    if (!isset($google['profile']) || !$google['profile']){
                        $ga_errors[3] = "Please enter your analytics Profile ID";
                    } elseif (!is_numeric($google['profile'])){
                        $ga_errors[3] = "Profile ID must be numeric";
                    }
                    
                    if (!isset($google['tracking']) || !$google['tracking']){
                        $ga_errors[4] = "Please enter your analytics Tracking ID";
                    }
                    
                    if (!count($ga_errors)){
                        require_once("../includes/gapi.class.php");
                        
                        $ga = new gapi($google['username'],$google['password']);
                        $check = $ga->authenticateUser($google['username'],$google['password']);
                        if ($check){
                            try{
                                $res = $ga->requestReportData($google['profile'],array('country'),array('pageviews','visits'),array("-visits"),null,date("Y-m-d"),date("Y-m-d"));
                                $settings->addSetting("analytics",json_encode($google));
                                $ga_success = true;
                            } catch(Exception $e){
                                $ga_errors[3] = "Invalid Profile ID";        
                            }
                        } else {
                            $ga_errors[1] = "Can't authenticate";
                        }
                    }
                
                }
            } else {
                $google = $settings->getSetting("analytics",true);
                if (defined("DEMO") && DEMO && $google && !empty($google)){
                    foreach($google as $key => $val){
                        $google[$key] = "HIDDEN IN DEMO";
                    }
                }
            }
 
            if (isset($ga_success) && $ga_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Google Analytics details are saved successfully
            </div>
        
        <?php 
            }
        ?>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>            
                <div class="control-group<?php if (isset($ga_errors[1])){ print(" error"); } ?>">
                    <label class="control-label">Username</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($google['username'])){ print($google['username']); } ?>" name="google[username]" class="span12" />
                        <?php if (isset($ga_errors[1])){ ?>
                            <span class="help-inline" style="margin-top: 3px;"><?php print($ga_errors[1]); ?></span>
                        <?php } ?>                    
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($ga_errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        <input type="password" value="<?php if (isset($google['password'])){ print($google['password']); } ?>" name="google[password]" class="span12" />
                        <?php if (isset($ga_errors[2])){ ?>
                            <span class="help-inline" style="margin-top: 3px;"><?php print($ga_errors[2]); ?></span>
                        <?php } ?>                    
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($ga_errors[3])){ print(" error"); } ?>">
                    <label class="control-label">Profile ID</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($google['profile'])){ print($google['profile']); } ?>" name="google[profile]" class="span12" />
                        <?php if (isset($ga_errors[3])){ ?>
                            <span class="help-inline" style="margin-top: 3px;"><?php print($ga_errors[3]); ?></span>
                        <?php } else {?>
                            <span class="help-inline" style="margin-top: 3px;">You can find this under "Profile Settings" in Google Analytics</span> 
                        <?php } ?>                    
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($ga_errors[4])){ print(" error"); } ?>">
                    <label class="control-label">Tracking ID</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($google['tracking'])){ print($google['tracking']); } ?>" name="google[tracking]" class="span12" />
                        <?php if (isset($ga_errors[4])){ ?>
                            <span class="help-inline" style="margin-top: 3px;"><?php print($ga_errors[4]); ?></span>
                        <?php } else {?>
                            <span class="help-inline" style="margin-top: 3px;">Also known as property id eg. UA-XXXXX-X</span> 
                        <?php } ?>                    
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="settings_accounts" />
                        <input type="submit" name="save_google" class="btn btn-primary" value="Save" />
                        <input type="submit" name="remove_google" class="btn" value="Remove Account" />            
                    </div>
                </div>
            </fieldset>
        </form>
        
    </div>
    <div class="span6">
        <div class="heading clearfix">
            <h3 class="pull-left">
                Decaptcher
            </h3>
        </div>
        
        <?php 
            if (isset($remove_decaptcher) && $remove_decaptcher){
                if (!defined("DEMO") || !DEMO){
                    $settings->deleteSetting("decaptcher");
                }
            }
        
            if (isset($save_decaptcher) && $save_decaptcher && isset($_POST['decaptcher'])){
                if (!defined("DEMO") || !DEMO){
                    $decaptcher = $_POST['decaptcher'];    
                    
                    $dc_errors = array();
                    
                    if (!isset($decaptcher['url']) || !$decaptcher['url']){
                        $dc_errors[1] = "Please enter the DeCaptcher API url";    
                    } else {
                        if (substr_count($decaptcher['url'],"http://")){
                            $url_parts = parse_url($decaptcher['url']);
                            if (isset($url_parts['host'])){
                                $decaptcher['url'] = $url_parts['host'];
                            } else {
                                $dc_errors[1] = "Invalid API url provided";
                            }
                        }
                    }
                    
                    if (!isset($decaptcher['username']) || !$decaptcher['username']){
                        $dc_errors[2] = "Please enter the DeCaptcher API username";
                    }
                    
                    if (!isset($decaptcher['password']) || !$decaptcher['password']){
                        $dc_errors[3] = "Please enter the DeCaptcher API password";
                    }
                    
                    if (isset($decaptcher['port']) && $decaptcher['port'] && !is_numeric($decaptcher['port'])){
                        $dc_errors[4] = "Port number must be numeric";
                    }
                    
                    if (!count($dc_errors)){
                        
                        if (isset($decaptcher['port']) && $decaptcher['port']){
                            require_once("../includes/ccproto_client.php");
                            $ccp = new ccproto();
                            $ccp->init();
                                    
                            if( @$ccp->login($decaptcher['url'], $decaptcher['port'], $decaptcher['username'], $decaptcher['password'] ) >= 0 ) {
                                $settings->addSetting("decaptcher",json_encode($decaptcher));
                                $dc_success = true;                    
                            } else {
                                $dc_errors[1] = "Can't connect to the API with these details";
                            }
                        } else {
                            $settings->addSetting("decaptcher",json_encode($decaptcher));
                            $dc_success = true;                            
                        }
                    }
                }
            } else {
                $decaptcher = $settings->getSetting("decaptcher",true);
                if (defined("DEMO") && DEMO && $decaptcher && !empty($decaptcher)){
                    foreach($decaptcher as $key => $val){
                        $decaptcher[$key] = "HIDDEN IN DEMO";
                    }
                }    
            }            
        ?>
        
        <?php 
            if (isset($dc_success) && $dc_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                DeCaptcher details are saved successfully
            </div>
        
        <?php 
            }
        ?>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>                
                <div class="control-group<?php if (isset($dc_errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Username</label>
                    <div class="controls">
                        <span class="span1">&nbsp;</span>
                        <input type="text" value="<?php if (isset($decaptcher['username'])){ print($decaptcher['username']); } ?>" name="decaptcher[username]" class="span11" />
                        <?php if (isset($dc_errors[2])){ ?>
                            <span class="help-inline" style="margin-left:25px; margin-top: 3px;"><?php print($dc_errors[2]); ?></span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($dc_errors[3])){ print(" error"); } ?>">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        <span class="span1">&nbsp;</span>
                        <input type="password" value="<?php if (isset($decaptcher['password'])){ print($decaptcher['password']); } ?>" name="decaptcher[password]" class="span11" />
                        <?php if (isset($dc_errors[3])){ ?>
                            <span class="help-inline" style="margin-left:25px; margin-top: 3px;"><?php print($dc_errors[3]); ?></span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($dc_errors[1])){ print(" error"); } ?>">
                    <label class="control-label">API Url</label>
                    <div class="controls">
                        <span class="span1" style="padding-top:6px;">
                            <a href="#" class="pop_over" title="Decaptcher API url" data-content="You can find this in your Decaptcher interface. You can use either the POST or the Normal API url"><i class="icon-question-sign"></i></a>
                        </span>
                        <input type="text" value="<?php if (isset($decaptcher['url'])){ print($decaptcher['url']); } ?>" name="decaptcher[url]" class="span11"/>
                        <?php if (isset($dc_errors[1])){ ?>
                            <span class="help-inline" style="margin-left:25px; margin-top: 3px;"><?php print($dc_errors[1]); ?></span>
                        <?php } ?>              
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($dc_errors[4])){ print(" error"); } ?>">
                    <label class="control-label">Port number</label>
                    <div class="controls">
                        <span class="span1" style="padding-top:6px;">
                            <a href="#" class="pop_over" title="Decaptcher port number" data-content="After paying for Decaptcher you can find this on your interface"><i class="icon-question-sign"></i></a>
                        </span>
                        <input type="port" value="<?php if (isset($decaptcher['port'])) { print($decaptcher['port']); } ?>" name="decaptcher[port]" class="span3" />
                        <?php if (isset($dc_errors[4])){ ?>
                            <span class="help-inline" style="margin-left:10px; margin-top: 3px;"><?php print($dc_errors[4]); ?></span>
                        <?php } else { ?>
                            <span class="help-inline" style="margin-left:10px; margin-top: 3px;">Only needed for the normal API</span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <span class="span1">&nbsp;</span>
                        <input type="hidden" name="menu" value="settings_accounts" />
                        <input type="submit" name="save_decaptcher" class="btn btn-primary" value="Save" />
                        <input type="submit" name="remove_decaptcher" class="btn" value="Remove Account" />            
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <h3 class="heading">Admin users</h3>
        
        <?php 
            if (!defined("DEMO") || !DEMO){
                if (isset($save_admin) && $save_admin){
                    if (isset($edit_admin) && $edit_admin){
                        $admin_errors = $user->validateAdmin($_POST['admin'],$edit_admin);
                    } else {
                        $admin_errors = $user->validateAdmin($_POST['admin']);
                    }
                    if (!count($admin_errors)){
                        if (isset($edit_admin) && $edit_admin){
                            $user->updateAdmin($_POST['admin'],$edit_admin);
                            $admin_update_success = true;
                        } else {
                            $user->addAdmin($_POST['admin']);
                            $admin_success = true;
                        }    
    
                        $admin = array();
                        unset($edit_admin);
                    }
                } elseif (isset($edit_admin) && $edit_admin){
                    $admin = $user->getAdmin($edit_admin);
                    $admin['password'] = '';
                }
            
                if (isset($remove_admin) && $remove_admin){
                    $user->removeAdmin($remove_admin);
                }
            }
        ?>
        
        <?php 
            if (isset($admin_success) && $admin_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                New admin user added successfully
            </div>
        
        <?php 
            }
        ?>
        
        <?php 
            if (isset($admin_update_success) && $admin_update_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Admin user updated successfully
            </div>
        
        <?php 
            }
        ?>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>            
                <div class="control-group<?php if (isset($admin_errors[1])){ print(" error"); } ?>">
                    <label class="control-label">Username</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($admin['username'])){ print($admin['username']); } ?>" name="admin[username]" class="span12" />
                        <?php if (isset($admin_errors[1])){ ?>
                            <span class="help-inline"><?php print($admin_errors[1]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($admin_errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        <input type="password" value="<?php if (isset($admin['password'])){ print($admin['password']); } ?>" name="admin[password]" class="span12" />
                        <?php if (isset($admin_errors[2])){ ?>
                            <span class="help-inline"><?php print($admin_errors[2]); ?></span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($admin_errors[3])){ print(" error"); } ?>">
                    <label class="control-label">Confirm password</label>
                    <div class="controls">
                        <input type="password" value="<?php if (isset($admin['password2'])){ print($admin['password2']); } ?>" name="admin[password2]" class="span12" />
                        <?php if (isset($admin_errors[3])){ ?>
                            <span class="help-inline"><?php print($admin_errors[3]); ?></span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="settings_accounts" />
                        <?php if (isset($edit_admin) && $edit_admin){ ?>
                            <input type="hidden" name="edit_admin" value="<?php print($edit_admin); ?>" />
                            <input type="submit" name="save_admin" class="btn btn-primary" value="Update this admin user" />
                        <?php } else { ?>
                            <input type="submit" name="save_admin" class="btn btn-primary" value="Add new admin user" />
                        <?php } ?>        
                    </div>
                </div>
            </fieldset>
        </form>
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="*">Username</th>
                    <th width="1">&nbsp;</th>
                    <th width="1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $admin_users = $user->getAdminUsers();
                    if (count($admin_users)){
                        foreach($admin_users as $admin_id => $admin_data){
                            print("    <tr>
                                        <td>{$admin_data['username']}</td>
                                        <td><a href=\"index.php?menu=settings_accounts&remove_admin={$admin_id}\" class=\"btn\">Delete</a></td>
                                        <td><a href=\"index.php?menu=settings_accounts&edit_admin={$admin_id}\" class=\"btn\">Edit</a></td>
                                    </tr>");
                        }
                    }                
                ?>
            </tbody>
        </table>
        
    </div>
    <div class="span6">
        <h3 class="heading">Email settings</h3>
        
        <?php 
            if (isset($save_email) && $save_email && isset($_POST['email'])){
                if (!defined("DEMO") || !DEMO){
                    $email_errors = array();
                    
                    $email = $_POST['email'];
                    
                    if (!isset($email['sender_name']) || !$email['sender_name']){
                        $email_errors[1] = "Please enter the sender's name";
                    }
                    
                    if (!isset($email['sender_email']) || !$email['sender_email']){
                        $email_errors[2] = "Please enter the sender's email address";
                    }
                    
                    if (isset($email['smtp']) && $email['smtp']){
                        // smtp details validation
                        if (!isset($email['smtp_host']) || !$email['smtp_host']){
                            $email_errors[3] = "Please enter the SMTP's host url";
                        }
                                    
                        if (!isset($email['smtp_port']) || !$email['smtp_port']){
                            $email_errors[4] = "Please enter the SMTP's port number";
                        } elseif (!is_numeric($email['smtp_port'])){
                            $email_errors[4] = "SMTP port must be numeric";
                        }
                        
                        if (!isset($email['smtp_user']) || !$email['smtp_user']){
                            $email_errors[5] = "Please enter the SMTP's user name";
                        }
                        
                        if (!isset($email['smtp_password']) || !$email['smtp_password']){
                            $email_errors[6] = "Please enter the SMTP's password";
                        }
                        
                        if (!isset($email['smtp_security']) || !$email['smtp_security']){
                            $email_errors[7] = "Please indicate if this is a secure SMTP server";
                        }
                    }
                    
                    if (!count($email_errors)){
                        $settings->addSetting("email_settings",json_encode($email));
                        $email_success = true;
                    }
                }
            } else {
                $email = $settings->getSetting("email_settings",true);
                if (defined("DEMO") && DEMO && $email && !empty($email)){
                    foreach($email as $key => $val){
                        $email[$key] = "HIDDEN IN DEMO";
                    }
                }
            }
        
        ?>
        
        <?php 
            if (isset($email_success) && $email_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Email settings are saved successfully
            </div>
        
        <?php 
            }
        ?>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>            
                <div class="control-group<?php if (isset($email_errors[1])){ print(" error"); } ?>">
                    <label class="control-label">Sender name</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($email['sender_name'])){ print($email['sender_name']); } ?>" name="email[sender_name]" class="span12" />
                        <?php if (isset($email_errors[1])){ ?>
                            <span class="help-inline"><?php print($email_errors[1]); ?></span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($email_errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Sender email</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($email['sender_email'])){ print($email['sender_email']); } ?>" name="email[sender_email]" class="span12" />
                        <?php if (isset($email_errors[2])){ ?>
                            <span class="help-inline"><?php print($email_errors[2]); ?></span>
                        <?php } ?>                    
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Use SMTP</label>
                    <div class="controls">
                        <input type="checkbox" id="is_smtp" <?php if (isset($email['smtp']) && $email['smtp']){ print("checked"); } ?> name="email[smtp]" />                
                    </div>
                </div>
                
                <div id="smtp_details" <?php if (!isset($email['smtp']) || !$email['smtp']){ print("style='display:none'"); } ?>>
                    <div class="control-group<?php if (isset($email_errors[3])){ print(" error"); } ?>">
                        <label class="control-label">SMTP host</label>
                        <div class="controls">
                            <input type="text" value="<?php if (isset($email['smtp_host'])){ print($email['smtp_host']); } ?>" name="email[smtp_host]" class="span12" />
                            <?php if (isset($email_errors[3])){ ?>
                                <span class="help-inline"><?php print($email_errors[3]); ?></span>
                            <?php } ?>                    
                        </div>
                    </div>
                    
                    <div class="control-group<?php if (isset($email_errors[4])){ print(" error"); } ?>">
                        <label class="control-label">SMTP port</label>
                        <div class="controls">
                            <input type="text" value="<?php if (isset($email['smtp_port'])){ print($email['smtp_port']); } ?>" name="email[smtp_port]" class="span4" />
                            <?php if (isset($email_errors[4])){ ?>
                                <span class="help-inline"><?php print($email_errors[4]); ?></span>
                            <?php } ?>                    
                        </div>
                    </div>
                    
                    <div class="control-group<?php if (isset($email_errors[5])){ print(" error"); } ?>">
                        <label class="control-label">SMTP user</label>
                        <div class="controls">
                            <input type="text" value="<?php if (isset($email['smtp_user'])){ print($email['smtp_user']); } ?>" name="email[smtp_user]" class="span12" />
                            <?php if (isset($email_errors[5])){ ?>
                                <span class="help-inline"><?php print($email_errors[5]); ?></span>
                            <?php } ?>                    
                        </div>
                    </div>
                    
                    <div class="control-group<?php if (isset($email_errors[6])){ print(" error"); } ?>">
                        <label class="control-label">SMTP password</label>
                        <div class="controls">
                            <input type="password" value="<?php if (isset($email['smtp_password'])){ print($email['smtp_password']); } ?>" name="email[smtp_password]" class="span12" />
                            <?php if (isset($email_errors[6])){ ?>
                                <span class="help-inline"><?php print($email_errors[6]); ?></span>
                            <?php } ?>                    
                        </div>
                    </div>
                    
                    <div class="control-group<?php if (isset($email_errors[7])){ print(" error"); } ?>">
                        <label class="control-label">SMTP security</label>
                        <div class="controls">
                            <select name="email[smtp_security]">
                                <option value="normal">Normal</option>
                                <option value="ssl" <?php if (isset($email['smtp_security']) && $email['smtp_security']=="ssl"){ print("selected='selected'"); } ?>>SSL</option>
                            </select>                
                            <?php if (isset($email_errors[7])){ ?>
                                <span class="help-inline"><?php print($email_errors[7]); ?></span>
                            <?php } ?>    
                        </div>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="settings_accounts" />
                        <input type="submit" name="save_email" class="btn btn-primary" value="Save" />        
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <h3 class="heading">SideReel</h3>
        
        <?php 
            if (isset($remove_sidereel) && $remove_sidereel){
                if (!defined("DEMO") || !DEMO){
                    $settings->deleteSetting("sidereel");
                }
            }
            
            if (isset($save_sidereel) && $save_sidereel && isset($_POST['sidereel'])){
                if (!defined("DEMO") || !DEMO){
                    $sidereel_errors = array();
                    $sidereel = $_POST['sidereel'];
                    
                    if (!isset($sidereel['username']) || !$sidereel['username']){
                        $sidereel_errors[1] = "Please enter your sidereel username";
                    }
                    
                    if (!isset($sidereel['password']) || !$sidereel['password']){
                        $sidereel_errors[1] = "Please enter your sidereel password";
                    }
                    
                    if (!count($sidereel_errors)){
                        require_once("../includes/sidereel.class.php");
                        $sr = new Sidereel();
                        
                        if ($sr->checkLogged($sidereel['username']) || $sr->login($sidereel['username'],$sidereel['password'])){
                            $settings->addSetting("sidereel",json_encode($sidereel));
                            $sidereel_success = true;
                        } else {
                            $sidereel_errors[1] = "Can't login with these details";
                        }
                    }
                }
            } else {
                $sidereel = $settings->getSetting("sidereel", true);
                if (defined("DEMO") && DEMO && $sidereel && !empty($sidereel)){
                    foreach($sidereel as $key => $val){
                        $sidereel[$key] = "HIDDEN IN DEMO";
                    }
                }
            }
        
            
        ?>
        
        <?php 
            if (isset($sidereel_success) && $sidereel_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Sidereel settings are saved successfully
            </div>
        
        <?php 
            }
        ?>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>            
                <div class="control-group<?php if (isset($sidereel_errors[1])){ print(" error"); } ?>">
                    <label class="control-label">Username</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($sidereel['username'])){ print($sidereel['username']); } ?>" name="sidereel[username]" class="span12" />
                        <?php if (isset($sidereel_errors[1])){ ?>
                            <span class="help-inline"><?php print($sidereel_errors[1]); ?></span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($sidereel_errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        <input type="password" value="<?php if (isset($sidereel['password'])){ print($sidereel['password']); } ?>" name="sidereel[password]" class="span12" />
                        <?php if (isset($sidereel_errors[2])){ ?>
                            <span class="help-inline"><?php print($sidereel_errors[2]); ?></span>
                        <?php } ?>                    
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="settings_accounts" />
                        <input type="submit" name="save_sidereel" class="btn btn-primary" value="Save" />
                        <input type="submit" name="remove_sidereel" class="btn" value="Remove Account" />            
                    </div>
                </div>
            </fieldset>
        </form>    
    </div>
    
    <div class="span6">
        <h3 class="heading">Facebook</h3>
        
        <?php 
            if (isset($remove_facebook) && $remove_facebook){
                if (!defined("DEMO") || !DEMO){
                    $settings->deleteSetting("facebook");
                }
            }
        
            if (isset($save_facebook) && $save_facebook && isset($_POST['facebook'])){
                if (!defined("DEMO") || !DEMO){
                    $facebook_errors = array();
                    
                    $facebook = $_POST['facebook'];
                    if (!isset($facebook['app_id']) || !$facebook['app_id']){
                        $facebook_errors[1] = "Please enter your Facebook App ID";
                    }
                    
                    if (!isset($facebook['app_secret']) || !$facebook['app_secret']){
                        $facebook_errors[2] = "Please enter your Facebook App Secret";
                    }
                    
                    if (!count($facebook_errors)){
                        $facebook_test = new Facebook(array(
                              'appId'  => $facebook['app_id'],
                              'secret' => $facebook['app_secret']
                        ));
                        
                        try{
                            $test_result = $facebook_test->api(array(     'method' => 'fql.query',
                                                                         'query' => 'SELECT url FROM url_like WHERE user_id="1" AND url="http://sorozatom.com"' ));
                        } catch(FacebookApiException $e) { 
                            $facebook_errors[1] = "Can't validate facebook credentials";
                        }
                        
                        if (!count($facebook_errors)){
                            $settings->addSetting("facebook",json_encode($facebook));
                            $facebook_success = true;
                        }
                        
                    }
                }
            } else {
                $facebook = $settings->getSetting("facebook",true);
                if (defined("DEMO") && DEMO && $facebook && !empty($facebook)){
                    foreach($facebook as $key => $val){
                        $facebook[$key] = "HIDDEN IN DEMO";
                    }
                }
            }
        
        ?>
        
        <?php 
            if (isset($facebook_success) && $facebook_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Facebook settings are saved successfully
            </div>
        
        <?php 
            }
        ?>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>            
                <div class="control-group<?php if (isset($facebook_errors[1])){ print(" error"); } ?>">
                    <label class="control-label">Facebook App ID</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($facebook['app_id'])){ print($facebook['app_id']); } ?>" name="facebook[app_id]" class="span12" />
                        <?php if (isset($facebook_errors[1])){ ?>
                            <span class="help-inline"><?php print($facebook_errors[1]); ?></span>
                        <?php } ?>                    
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($facebook_errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Facebook App Secret</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($facebook['app_secret'])){ print($facebook['app_secret']); } ?>" name="facebook[app_secret]" class="span12" />
                        <?php if (isset($facebook_errors[2])){ ?>
                            <span class="help-inline"><?php print($facebook_errors[2]); ?></span>
                        <?php } ?>                    
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="settings_accounts" />
                        <input type="submit" name="save_facebook" class="btn btn-primary" value="Save" />
                        <input type="submit" name="remove_facebook" class="btn" value="Remove Account" />            
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <h3 class="heading">TV-links.eu</h3>
        
        <?php
            if (isset($remove_tvlinks) && $remove_tvlinks){
                if (!defined("DEMO") || !DEMO){
                    $settings->deleteSetting("tvlinks");
                }
            }
            
            if (isset($save_tvlinks) && $save_tvlinks && isset($_POST['tvlinks'])){
                if (!defined("DEMO") || !DEMO){
                    $tvlinks = $_POST['tvlinks'];
                    
                    $tv_errors = array();
                    
                    if (!isset($tvlinks['username']) || !$tvlinks['username']){
                        $tv_errors[1] = "Please enter your TV-links username";
                    }
                    
                    if (!isset($tvlinks['password']) || !$tvlinks['password']){
                        $tv_errors[1] = "Please enter your TV-links password";
                    }
                    
                    if (!count($tv_errors)){
                        require_once("../includes/tvlinks.class.php");
                        require_once("../includes/curl.php");
                        
                        $curl_test = new Curl();
                        $tvlinks_test = new TVlinks($curl_test);
                        if ($tvlinks_test->login($tvlinks['username'],$tvlinks['password'])){
                            
                            $settings->addSetting("tvlinks",json_encode($tvlinks));
                            $tv_success = true;
                            
                        } else {
                            $tv_errors[1] = "Can't login with these details";
                        }
                    }
                }
            } else {
                $tvlinks = $settings->getSetting("tvlinks",true);
                if (defined("DEMO") && DEMO && $tvlinks && !empty($tvlinks)){
                    foreach($tvlinks as $key => $val){
                        $tvlinks[$key] = "HIDDEN IN DEMO";
                    }
                }    
            }
            
        ?>
        
        <?php 
            if (isset($tv_success) && $tv_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                TV-links.eu settings are saved successfully
            </div>
        
        <?php 
            }
        ?>
        
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>            
                <div class="control-group<?php if (isset($tv_errors[1])){ print(" error"); } ?>">
                    <label class="control-label">Username</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($tvlinks['username'])){ print($tvlinks['username']); } ?>" name="tvlinks[username]" class="span12" />
                        <?php if (isset($tv_errors[1])){ ?>
                            <span class="help-inline"><?php print($tv_errors[1]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group<?php if (isset($tv_errors[2])){ print(" error"); } ?>">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        <input type="password" value="<?php if (isset($tvlinks['password'])){ print($tvlinks['password']); } ?>" name="tvlinks[password]" class="span12" />
                        <?php if (isset($tv_errors[2])){ ?>
                            <span class="help-inline"><?php print($tv_errors[2]); ?></span>
                        <?php } ?>                
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="settings_accounts" />
                        <input type="submit" name="save_tvlinks" class="btn btn-primary" value="Save" />
                        <input type="submit" name="remove_tvlinks" class="btn" value="Remove Account" />            
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="span6">
        <h3 class="heading">Adf.ly account</h3>
        
        <?php 
            if (isset($save_adfly) && $save_adfly && isset($_POST['adfly'])){
                if (!defined("DEMO") || !DEMO){
                    $adfly_errors = array();
                    
                    if (!isset($adfly['id']) || !$adfly['id']){
                        $adfly_errors[1] = "Please enter your Adf.ly ID";                    
                    } elseif (!is_numeric($adfly['id'])){
                        $adfly_errors[1] = "Adf.ly ID must be numeric";
                    }
                    
                    if (!count($adfly_errors)){
                        $settings->addSetting("adfly",json_encode($adfly));
                        $adfly_success = true;
                    }
                }
            } else {
                $adfly = $settings->getSetting("adfly", true);
                if (defined("DEMO") && DEMO && $adfly && !empty($adfly)){
                    foreach($adfly as $key => $val){
                        $adfly[$key] = "HIDDEN IN DEMO";
                    }
                }
            }

            if (isset($adfly_success) && $adfly_success){
        ?>
        
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Adf.ly settings are saved successfully
            </div>
        
        <?php 
            }
        ?>
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>            
                <div class="control-group<?php if (isset($adfly_errors[1])){ print(" error"); } ?>">
                    <label class="control-label">Adf.ly ID</label>
                    <div class="controls">
                        <input type="text" value="<?php if (isset($adfly['id'])){ print($adfly['id']); } ?>" name="adfly[id]" class="span12" />
                        <?php if (isset($adfly_errors[1])){ ?>
                            <span class="help-inline"><?php print($adfly_errors[1]); ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="settings_accounts" />
                        <input type="submit" name="save_adfly" class="btn btn-primary" value="Save" />        
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>