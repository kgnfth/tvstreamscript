<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (!isset($p) || !$p){
    $p = 1;
}

$total = $movie->getMovieCount();
$counts = $movie->getCounts($p,$default_language);

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
                Popular movies
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <div class="pagination pull-right nomargin" style="margin-top:0px;">
            <?php
                $pagination = $misc->getAdminPagination($total, $p, 100, "index.php?menu=movie_popular&p=");
                print($pagination);
            ?>
        </div>
        
        <table class="table table-striped table-bordered">
             <thead>
                 <tr>
                      <th width="75%">Title</th>
                      <th width="20%">Views</th>
                      <th width="5%">Edit</th>
                 </tr>
             </thead>
             <tbody>
              <?php
                if (count($counts)){
                      foreach($counts as $id => $mov){
                        extract($mov);
                        print("    <tr>
                                    <td>$title</td>
                                    <td>$views</td>
                                    <td><a href='index.php?menu=movies_new&movie_id=$id' class='btn'>Edit</a></td>
                                   </tr>");
                      }
                } else {
                    print("<tr><td colspan='5' align='center'>No movies found</td></tr>");
                }
              ?>
             </tbody>
        </table>    
    </div>
</div>