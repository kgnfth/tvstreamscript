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
                <a href="index.php?menu=shows_manage">TV shows</a>
            </li>
            <li>
                Submitters
            </li>
            <li>
                Sidereel
            </li>
        </ul>
    </div>
</nav>
<?php
  
$sr = $settings->getSetting("sidereel");
$decaptcher = $settings->getSetting("decaptcher");
  
$shows = $show->getShows(null,"en");
$shows = $misc->aasort($shows,"title");

if (!isset($decaptcher->username) || !$decaptcher->username){

?>

<div class="alert alert-info">
      <button type="button" class="close" data-dismiss="alert">×</button>
    If you add your DeCaptcher account informations then the SideReel submitter will be able to submit your episodes without your input. DeCaptcher is a paid captcha breaking API. <a href="index.php?menu=settings_accounts">Click here</a> to add your account
</div>    

<?php
} 
?>

<div class="alert alert-info">
      <button type="button" class="close" data-dismiss="alert">×</button>
    It takes up to 1-2 hours for SideReel to process your links so please be patient
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">                    
            <h3>Submit episodes to Sidereel</h3>
        </div>        

<?php
      if (!isset($sr->username) || !$sr->username){
?>


<div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">×</button>
    There is no valid Sidereel username / password in the database. Please <a href="index.php?menu=settings_accounts">Click here</a> to add your Sidereel account details
</div>    

<?php 
      } else {
?>

        <form action="index.php" method="post" class="well">
            <table width="95%" align="center" cellpadding="2" cellspacing="1">
                <tr class="nostripe">
                    <td width="40%">Please select the TV show you would like to promote:</td>
                    <td width="60%" align="left">
                        <select name="tvshow" id="tvshow" style="width:70%; float:left; margin-right: 5px;">
                            <?php
                                if (count($shows)){
                                    foreach($shows as $id=>$val){
                                        $title = $val['title'];
                                        print("<option value='$id' "); if ($id==@$tvshow) print(" selected='selected'"); print(">".stripslashes($title)."</option>\n");
                                    }
                                }
                            ?>
                        </select>
                        <input type="hidden" name="menu" value="submitter_sidereel" />
                        <input type="submit" value="Start submitting" class="btn btn-primary" style="margin-top:0px" name="getepisodes" />
                    </td>
                </tr>
                <tr class="nostripe">
                    <td width="40%"><strong>OR</strong></td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="nostripe">
                    <td width="40%" align="left">Select the date you added the episode:</td>
                    <td width="60%" align="left">
                        <select name="period" id="period" style="width:70%; float:left; margin-right: 5px;">
                            <option value="1" <?php if (@$period==1) print("selected='selected'"); ?>>Today</option>
                            <option value="2" <?php if (@$period==2) print("selected='selected'"); ?>>Yesterday</option>
                            <option value="3" <?php if (@$period==3) print("selected='selected'"); ?>>This week</option>
                            <option value="4" <?php if (@$period==4) print("selected='selected'"); ?>>This month</option>
                            <option value="6" <?php if (@$period==6) print("selected='selected'"); ?>>Last month</option>
                            <option value="7" <?php if (@$period==7) print("selected='selected'"); ?>>Two months ago</option>
                            <option value="5" <?php if (@$period==5) print("selected='selected'"); ?>>All time</option>
                        </select>
                        <input type="hidden" name="menu" value="submitter_sidereel" />
                        <input type="submit" value="Start submitting" class="btn btn-primary" style="margin-top:0px" name="getperiod" />
                    </td>
                </tr>
                <tr>
                    <td width="40%" align="left">&nbsp;</td>
                    <td width="40%" align="left">
                        <input type="checkbox" name="hidesubmitted" checked="checked" /> Hide submitted episodes
                    </td>
                </tr>
            </table>
        </form>

<?php } ?>
    </div>
</div>

<?php
       if (isset($getepisodes) || isset($getperiod)){
     
        if (isset($getepisodes)){
            $episodes = $show->getEpisodes($tvshow,null,"en",array("ENG","ENGSUB"));
         } else {
            $episodes = $show->getByPeriod($period,'en',array("ENG","ENGSUB"));
            $dates = $show->getPeriod($period);
         }
     
         if (count($episodes)==0){
?>

<div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">×</button>
    No episodes found for this show / period
</div>    


<?php
} else {

    $submits = $show->getAllSubmits(2);
?>

<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">                    
            <h3 class="pull-left">Select the episodes you would like to promote <small><?php if (isset($dates)){ print("({$dates['from']} - {$dates['to']})"); }?></small></h3>
            <?php if (isset($getepisodes)){ ?>
                <a href="index.php?menu=edit_episodes&showid=<?php print($tvshow); ?>"><span class="pull-right label label-info ttip_t" style="cursor:pointer">Edit episodes</span></a>
            <?php } ?>
        </div>    

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>
                        <?php if (isset($decaptcher->username) && $decaptcher->username){ ?> 
                            <input type="checkbox" class="check-all" checked="checked" /> 
                        <?php } else { ?>
                            &nbsp;
                        <?php } ?>
                    </th>
                    <th width="20%">Status</th>
                    <th width="35%">Episode title</th>
                    <th width="40%">Link</th>
                </tr>
            </thead>
            <tbody>
<?php
    foreach($episodes as $id=>$val){
        extract($val);
        if (array_key_exists($id,$submits)){
            $o1 = "<strong>SUBMITTED</strong>";
            $o2 = "";
            $o3 = "<a href='{$submits[$id]['link']}' target='_blank'>{$submits[$id]['link']}</a>";
        } else {
            $o1 = "&nbsp;";
            $o2 = "checked='checked'";
            $o3 = "&nbsp;";
        }
        
        $episodetitle = "Season $season, episode $episode";
        
        if (isset($decaptcher->username) && ($decaptcher->username)){
            if ($o2 || !isset($hidesubmitted) || !$hidesubmitted){
                print("    <tr>
                            <td align='center'><input type='checkbox' class='selected_episode' name='selected[]' $o2 value='$id' /></td>
                            <td id='status_$id'>$o1</td>
                            <td>".stripslashes($title)." - ".stripslashes($episodetitle)."</td>
                            <td id='link_$id'>$o3</td>
                        </tr>");
            }
        } else {
            if ($o2 || !isset($hidesubmitted) || !$hidesubmitted){
                print("    <tr>
                            <td align='center'><input type='button' value='Submit' class='btn' id='submit_button_$id' onclick='manualSidereel($id);' /></td>
                            <td id='submit$id'>$o1</td>
                            <td>".stripslashes($title)." - ".stripslashes($episodetitle)."</td>
                            <td id='link$id'>$o3</td>
                        </tr>");
            }
        }
    } 
    if (isset($decaptcher->username) && ($decaptcher->username)){
?>
                    <tr>
                        <td colspan="4">
                            <input type="button" id="submit_button" value="Submit selected to SideReel" onclick="submitSidereelInit();" class="btn btn-primary" />
                        </td>
                    </tr>

<?php 
    } 
?>
            </tbody>
        </table>
    </div>
</div>

<?php
    }
}
?>

<!-- hide elements (for later use) -->
<div class="hide">
    <!-- confirmation box -->
    <div id="sidereel_overlay" class="cbox_content span5" style="height: 150px; width: 500px; margin: 0px;">
        <h3 class="heading">Please enter the captcha value</h3>
        <div class="tac">
            <div id="captcha"></div>
        </div>
    </div>
</div>
<!-- 
<div id="sidereel_overlay" style="display:none">
    <a href='javascript:void(0)' onclick="$('#backgroundPopup').hide(); $('#sidereel_overlay').hide();" style="float:right;margin-right: 10px; margin-bottom: 10px;">Close</a>
    <br style="clear:both;" />
    <div id="captcha"></div>
</div>
 -->
<div id="backgroundPopup"></div>
