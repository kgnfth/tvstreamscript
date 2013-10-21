<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (!isset($page) || !$page || !is_numeric($page)){
    $page = 1;
}

$total = $show->getEpisodeCount();
$counts = $show->getCounts($page,$default_language);
  
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
                Popular episodes
            </li>
        </ul>
    </div>
</nav>



<div class="row-fluid">
    <div class="span12">
        <div class="pagination pull-right nomargin" style="margin-top:0px;">
            <?php
                $pagination = $misc->getAdminPagination($total, $page, 100, "index.php?menu=tv_popular&page=","active");
                print($pagination);
            ?>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Episode</th>
                    <th>Season</th>
                    <th>Views</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (count($counts)){
                        foreach($counts as $id => $ep){
                            extract($ep);
                            $title = "<a href='index.php?menu=edit_episodes&showid=$showid'>$showtitle</a> - ".$episodetitle;
                            $title = stripslashes($title);
                            print("    <tr>
                                        <td>$title</td>
                                        <td>$episode</td>
                                        <td>$season</td>
                                        <td>$views</td>
                                        <td><a href='index.php?menu=episodes&show_id=$showid&season=$season&episode=$episode' class='btn'>Edit</a><br /></td>
                                    </tr>");
                        }
                    } else {
                        print("<tr><td colspan='5' align='center'>No episodes found</td></tr>");
                    }
                ?>
            </tbody>
        </table>
    
    </div>
</div>