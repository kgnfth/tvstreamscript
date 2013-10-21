<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (!isset($p) || !$p){
    $p = 1;
}

$total = $movie->getBrokenCount();
$brokens = $movie->getBroken($p,$default_language);

?>

<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                <a href="index.php?menu=movies_manage">Movies</a>
            </li>
            <li>
                Broken movie reports
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <div class="pagination pull-right nomargin" style="margin-top:0px;">
            <?php
                $pagination = $misc->getAdminPagination($total, $p, 100, "index.php?menu=movie_broken&p=");
                if ($pagination!='1'){
                    print($pagination);
                }
            ?>
        </div>
        
        <table class="table table-striped table-bordered">
             <thead>
                 <tr>
                      <th width="35%">Title</th>
                      <th width="35%">Problem</th>
                      <th width="10%">User / IP</th>
                      <th width="10%">Date</th>
                      <th width="5%">Edit</th>
                      <th width="5%">Delete</th>
                 </tr>
             </thead>
         
             <tbody>
                  <?php
                    if (count($brokens)){
                          foreach($brokens as $id => $broken){
                            extract($broken);
                            
                              if (isset($user['user_id'])){
                                $user_link = "<a href='index.php?menu=users_email&user_list={$user['username']}'>{$user['username']}</a>";
                            } else {
                                $user_link = $ip;
                            }
                            
                            print("    <tr id='row$id'>
                                        <td><a href='$baseurl".$url."' target='_blank'>$title</a></td>
                                        <td>$problem</td>
                                        <td>$user_link</td>
                                        <td>$date</td>
                                        <td><a href='index.php?menu=movies_new&movie_id=$movieid' class='btn'>Edit</a></td>
                                        <td><a href='javascript:void(0);' onclick='deleteBrokenMovie($id);' class='btn'>Delete</a></td>
                                       </tr>");
                          }
                    } else {
                        print("<tr><td colspan='7' align='center'>No broken movie reports found</td></tr>");
                    }
                 ?>
             </tbody>
        </table>
    </div>
</div>