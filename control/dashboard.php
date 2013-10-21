<?php 
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}
?>
<div class="row-fluid">
    <div class="span12">
        <?php 
            if ($update_version > $current_version){
        ?>
        
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert">×</a>
            Your script is out of date. Version <strong><?php print($update_version); ?></strong> is now available. <a href="index.php?menu=update">Click here</a> to upgrade now
        </div>
        
        <?php 
            }
        ?>
        <div class="row-fluid">
        
            <h3 class="heading">Quick stats</h3>
        
        </div>
        
        <div class="row-fluid" style="margin-top: 0px">
            <div class="span2">
                <img alt="" src="img/gCons/screen.png" style="width:32px; height: 32px;">&nbsp; Number of TV shows: <strong><a href="index.php?menu=shows_manage"><?php print($show->getShowCount()); ?></a></strong>
            </div>
            
            <div class="span2">
                <img alt="" src="img/gCons/screen.png">&nbsp; Number of episodes: <strong><a href="index.php?menu=episodes"><?php print($show->getEpisodeCount()); ?></a></strong>
            </div>
            
            <div class="span2">
                <img alt="" src="img/gCons/fire.png">&nbsp; Broken episodes: <a href="index.php?menu=tv_broken"><strong><?php print($show->getBrokenCount()); ?></strong></a>
            </div>
            
            <div class="span2">
                <img alt="" src="img/gCons/pie-chart.png">&nbsp; Episode ratings: <strong><?php print($show->getRatingCount()); ?></strong>
            </div>
            
            <div class="span2">
                <img alt="" src="img/gCons/processing-02.png">&nbsp; Pending episode links: <a href="index.php?menu=tv_links"><strong><?php print(count($show->getLinks(0))); ?></strong></a>
            </div>
            
            <div class="span2">
                <img alt="" src="img/gCons/agent.png">&nbsp; Number of users: <a href="index.php?menu=users"><strong><?php print($user->getUserCount()); ?></strong></a>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span2">
                <img alt="" src="img/gCons/screen.png">&nbsp; Number of movies: <a href="index.php?menu=movies_manage"><strong><?php print($movie->getMovieCount()); ?></strong></a>
            </div>
            
            <div class="span2">
                <img alt="" src="img/gCons/processing-02.png">&nbsp; Pending movie links: <a href="index.php?menu=movie_links"><strong><?php print(count($movie->getLinks(0))); ?></strong></a>
            </div>

            <div class="span2">
                <img alt="" src="img/gCons/fire.png">&nbsp; Broken movies: <a href="index.php?menu=movie_broken"><strong><?php print($movie->getBrokenCount()); ?></strong></a>
            </div>
            
            <div class="span2">
                <img alt="" src="img/gCons/pie-chart.png">&nbsp; Movie ratings: <strong><?php print($movie->getRatingCount()); ?></strong>
            </div>
            
            <div class="span2">
                <img alt="" src="img/gCons/satellite.png">&nbsp; Pending requests: <a href="index.php?menu=users_requests"><strong><?php print($request->getPendingCount()); ?></strong></a>
            </div>
        </div>
    </div>
</div>

 <div class="row-fluid">
    <div class="span6">
        <h3 class="heading">Visitors by Country <small>last 30 days</small></h3>
        <div id="fl_2" style="height:200px;width:80%;margin:50px auto 0"></div>
    </div>
    <div class="span6">
        <div class="heading clearfix">
            <h3 class="pull-left">Daily visitors <small>last 30 days</small></h3>
        </div>
        <div id="fl_1" style="height:270px;width:100%;margin:15px auto 0"></div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="heading clearfix">
            <h3 class="pull-left">Top referring sites <small>last 30 days</small></h3>
        </div>
        
        <div id="fl_3">
        
        </div>

        
    </div>
    <div class="span6">
        <div class="heading clearfix">
            <h3 class="pull-left">Latest users</h3>
            <a href="index.php?menu=users"><span class="pull-right label label-info ttip_t" style="cursor:pointer">Manage users</span></a>
        </div>
        <?php 
            $latest_users = $user->getAllUsers(null,0,10);
            if (count($latest_users)){
        ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="optional">ID</th>
                    <th class="essential persist" style="width:60px">Avatar</th>
                    <th class="optional">Username</th>
                    <th class="optional">Email</th>
                </tr>
            </thead>
            <tbody>
        <?php 
                foreach($latest_users as $key => $val){
                    print("    <tr>
                                <td>{$key}</td>
                                <td>
                                    <div class='thumbnail'>
                                        <img src='{$baseurl}/thumbs/users/{$val['avatar']}' style='width:50px; height:50px' />
                                    </div>
                                </td>
                                <td>{$val['username']}</td>
                                <td><a href=\"index.php?menu=users_email&user_list={$val['username']}\">{$val['email']}</a></td>
                            </tr>");
                }
        ?>
            </tbody>
        </table>
        <?php 
            } else {
                print("<center><br />No users found<br /><br />");
            }
        ?>
        
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="heading clearfix">
            <h3 class="pull-left">Most popular shows <small>by views</small></h3>
            <a href="index.php?menu=shows_manage"><span class="pull-right label label-info ttip_t" style="cursor:pointer">Manage shows</span></a>
        </div>

        <div id="shows_small_grid" class="wmk_grid">
            <?php 
            $top_shows = $show->getPopularShows($default_language,10);
            if (count($top_shows)){
                print("<ul>");
                foreach($top_shows as $show_id => $val){
            ?>
    
                <li class="thumbnail">
                    <a class="ttip_t" title="<?php print($val['showtitle']); ?>" href="index.php?menu=edit_episodes&showid=<?php print($show_id); ?>">
                        <img alt="" src="<?php print($baseurl); ?>/thumbs/<?php print($val['thumbnail']); ?>" style="width:120px; height:170px;">
                    </a>
                    <p>
                        <span><?php print(number_format($val['views'],0)); ?> views</span>
                    </p>
                </li>
    
            <?php 
                }
                print("</ul>");
            }
            ?>
        </div>
    </div>
    <div class="span6" id="user-list">
        <div class="heading clearfix">
            <h3 class="pull-left">Most popular movies <small>by views</small></h3>
            <a href="index.php?menu=movies_manage"><span class="pull-right label label-info ttip_t" style="cursor:pointer">Manage movies</span></a>
        </div>
        <div id="movies_small_grid" class="wmk_grid">
            <?php 
            $top_movies = $movie->getCounts(1,$default_language,10);
            if (count($top_movies)){
                print("<ul>");
                foreach($top_movies as $movie_id => $val){
            ?>
    
                <li class="thumbnail">
                    <a class="ttip_t" title="<?php print($val['title']); ?>" href="index.php?menu=movies_new&movie_id=<?php print($movie_id); ?>">
                        <img alt="" src="<?php print($baseurl); ?>/thumbs/<?php print($val['thumb']); ?>" style="width:120px; height:170px;">
                    </a>
                    <p>
                        <span><?php print(number_format($val['views'],0)); ?> views</span>
                    </p>
                </li>
    
            <?php 
                }
                print("</ul>");
            }
            ?>
        </div>
    </div>
</div>