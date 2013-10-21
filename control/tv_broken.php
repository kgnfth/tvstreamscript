<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (!isset($page) || !$page || !is_numeric($page)){
    $page = 1;
}

$total = $show->getBrokenCount();
$brokens = $show->getBroken($page,$default_language);

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
                Broken episode reports
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <div class="pagination pull-right nomargin" style="margin-top:0px;">
            <?php
                $pagination = $misc->getAdminPagination($total, $page, 50, "index.php?menu=tv_broken&page=","active");
                print($pagination);
            ?>
        </div>

        <table class="table table-striped table-bordered">
             <thead>
                 <tr>
                      <th width="40%">Title</th>
                      <th width="30%">Problem</th>
                      <th width="10%">User / IP</th>
                      <th width="10%">Date</th>
                      <th width="5%">&nbsp;</th>
                      <th width="5%">&nbsp;</th>
                 </tr>
             </thead>
         
             <tbody>
              <?php
            if (count($brokens)){
                  foreach($brokens as $id => $broken){
                      
                    extract($broken);
                    $title = $showtitle." - S".str_pad($season,2,"0",STR_PAD_LEFT)."E".str_pad($episode,2,"0",STR_PAD_LEFT);
                    $title = stripslashes($title);
                    if (strlen($title)>=40){
                        $print_title = substr($title,0,40)."...";
                    } else {
                        $print_title = $title;
                    }
                    
                    if (isset($user['user_id'])){
                        $user_link = "<a href='index.php?menu=users_email&user_list={$user['username']}'>{$user['username']}</a>";
                    } else {
                        $user_link = $ip;
                    }
                    
                    print("    <tr id='row$id'>
                                <td><a href='$baseurl".$url."' target='_blank' title='$title'>$print_title</a></td>
                                <td>$problem</td>
                                <td>$user_link</td>
                                <td>$date</td>
                                <td><a href='index.php?menu=episodes&show_id=$showid&season=$season&episode=$episode' class='btn'>Edit</a><br /></td>
                                <td><a href='javascript:void(0);' onclick='deleteBrokenEpisode($id);' class='btn'>Delete</a><br /></td>
                               </tr>");
                  }
            } else {
                print("<tr><td colspan='7' align='center'>No broken episode reports found</td></tr>");
            }
              ?>
             </tbody>
        </table>
    </div>
</div>